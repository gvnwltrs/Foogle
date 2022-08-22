<?php
include("config.php"); 
include("classes/SiteResultsProvider.php"); 
include("classes/ImageResultsProvider.php"); 

if(isset($_GET["term"]) && $_GET["term"] != null) {
    $term = $_GET["term"];
}
else {
    exit("You must enter a search term"); 
}

$type = isset($_GET["type"]) ? $_GET["type"] : "sites"; 
$page = isset($_GET["page"]) ? $_GET["page"] : 1; 
?>

<!DOCTYPE html>
<head>
    <title>Welcome to Foogle</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" integrity="sha512-nNlU0WK2QfKsuEmdcTwkeh+lhGs6uyOxuUs+n+0oXSYDok5qy0EI0lt01ZynHq6+p/tbgpZ7P+yUb+r71wqdXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>

    <div class="wrapper">

        <div class="header">

            <div class="headerContent">

                <div class="logoContainer">
                    <a href="index.php">
                        <img src="assets/images/foogle-logo1.png">
                    </a>
                </div>

                <div class="searchContainer">

                    <form action="search.php" method="GET">
                        <div class="searchBarContainer"> 
                            <input type="hidden" name="type" value="<?php echo $type;?>">
                            <input class="searchBox" type="text" name="term" value="<?php echo $term;?>">
                            <button class="searchButton">
                                <img src="assets/images/icons/search.png">
                            </button>
                        </div>
                    </form>

                </div>

            </div>

            <div class="tabsContainer"> 
                <ul class="tabsList">
                    <li class="<?php echo $type == 'sites' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=sites"; ?>'>Sites</a>
                    </li>
                    <li class="<?php echo $type == 'images' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=images"; ?>'>Images</a>
                    </li>
                </ul>
            </div>

        </div>




        <div class="mainResultsSection">
            <?php
            global $page;

            if($type == "sites") {
                $resultsProvider = new SiteResultsProvider($connection); 
                $pageLimit = 20; 
            }
            else {
                $resultsProvider = new ImageResultsProvider($connection); 
                $pageLimit = 30; 
            }
           

            $numResults = $resultsProvider->getNumResults($term); 

            echo "<p class='resultsCount'>$numResults results found</p>"; 

            echo $resultsProvider->getResultsHtml($page, $pageLimit, $term);
            ?>
        </div>

        <div class="paginationContainer">

            <div class="pageButtons">


                <div class="pageNumberContainer">
                    <img src="assets/images/pageStart.png">
                </div>


                <?php 
        
                // concept: just "sliding" the current page back to a lower bound to start iterating from within the pages to show range
                // if it's less than say 10, the starting point will always be 1 to the end point 
                $pagesToShow = 10;
                $numPages = ceil($numResults / $pageLimit);  
                $pagesLeft = min($pagesToShow, $numPages); 

                $currentPage = $page - floor($pagesToShow / 2); 

                if($currentPage < 1) {
                    $currentPage = 1; 
                }

                if($currentPage + $pagesLeft > $numPages + 1) {
                    $currentPage = $numPages + 1 - $pagesLeft; 
                }

                while($pagesLeft != 0 && $currentPage <= $numPages) {
                    if($currentPage == $page) {
                        echo "<div class='pageNumberContainer'>
                                <img src='assets/images/pageSelected.png'>
                                <span class='pageNumber'>$currentPage</span>
                            </div>"; 
                    }
                    else {
                        echo "<div class='pageNumberContainer'>
                                <a href='search.php?term=$term&type=$type&page=$currentPage'>
                                    <img src='assets/images/page.png'>
                                    <span class='pageNumber'>$currentPage</span>
                                </a>
                            </div>"; 
                    }

                    if($numPages == 1) {
                        echo "<div class='pageNumberContainer'>
                                    <img src='assets/images/page.png'>
                            </div>"; 
                    }

                    $currentPage++;
                    $pagesLeft--; 
                }
                
                ?> 


                <div class="pageNumberContainer">
                    <img src="assets/images/pageEnd.png">
                </div>

            </div>

        </div>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>