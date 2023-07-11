<!-- Showing entries -->
<?php
$endEntry = min($start + $gamesPerPage, $totalGames);
echo "Showing ".($start + 1)." to $endEntry of $totalGames entries";
?>
