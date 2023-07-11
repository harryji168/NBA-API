 <!-- Pagination -->
 <?php
    // Finally, output navigation links
    $numPages = ceil($totalGames / $gamesPerPage);

    echo "<ul class='pagination'>";

    // First page link
 if ($page > 1) {
     echo "<li class='page-item'><a class='page-link' href='javascript:void(0)'
        onclick='loadPage({page: 1})'>First</a></li>";
 } else {
     echo "<li class='page-item disabled'><span class='page-link'>First</span></li>";
 }

    // Previous page link
 if ($page > 1) {
     $prevPage = $page - 1;
     echo "<li class='page-item'><a class='page-link' href='javascript:void(0)'
        onclick='loadPage({page: $prevPage})'>Previous</a></li>";
 } else {
     echo "<li class='page-item disabled'><span class='page-link'>Previous</span></li>";
 }

    // Calculate the starting page
    $start = max(1, min($page - 3, $numPages - 5));

    // Calculate the ending page
    $end = min($numPages, max($page + 3, 6));

    // Page number links
 for ($i = $start; $i <= $end; $i++) {
     if ($i != $page) {
         echo "<li class='page-item'><a class='page-link' href='javascript:void(0)'
            onclick='loadPage({page: $i})'>$i</a></li>";
     } else {
         echo "<li class='page-item active'><span class='page-link'>$i</span></li>";
     }
 }

    // Next and Last links
 if ($page < $numPages) {
     // Next page link
     $nextPage = $page + 1;
     echo "<li class='page-item'><a class='page-link' href='javascript:void(0)'
        onclick='loadPage({page: $nextPage})'>Next</a></li>";
    
     // Last page link
     echo "<li class='page-item'><a class='page-link' href='javascript:void(0)'
        onclick='loadPage({page: $numPages})'>Last</a></li>";
 } else {
     echo "<li class='page-item disabled'><span class='page-link'>Next</span></li>";
     echo "<li class='page-item disabled'><span class='page-link'>Last</span></li>";
 }
    

    echo "</ul>";
    ?>
