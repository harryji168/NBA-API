<!-- Search box -->
<form action="index.php" method="get">
    <div class="d-flex align-items-center">           
            <label for="searchBox" class="form-label mb-0 me-2">Search:</label>
            <?php $searchValue = isset($_GET['search']) ? $_GET['search'] : '';
                echo '<input type="text" id="searchBox" name="search" value="'.htmlspecialchars($searchValue).'"
                    class="form-control me-2" placeholder="Search..." aria-label="Search box">';
            if (!empty($_GET['search'])) {
                echo "<a href='?' class='btn btn-primary ms-2' title='Clear filter'><i class='fas fa-times'></i></a>";
            } else {
                echo "<button type='submit' class='btn btn-primary' class='btn btn-primary ms-2'
                    title='Search'><i class='fas fa-search'></i></button>";
            }
            ?> 
    </div>
</form>