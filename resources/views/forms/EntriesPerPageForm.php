<?php
if (isset($_GET['gamesPerPage'])) {
    $_SESSION['gamesPerPage'] = $_GET['gamesPerPage'];
} else {
    if (!isset($_SESSION['gamesPerPage'])) {
        $_SESSION['gamesPerPage'] = 10;
    }
}?>
<form id="entriesForm" action="?page=1" method="GET" class="d-flex align-items-center">
    <label for="entriesPerPage" class="form-label mb-0 me-2">Show</label>&nbsp;
    <select id="entriesPerPage" name="gamesPerPage" onchange="this.form.submit()"
        class="form-select me-2" aria-label="Entries per page">
        <option value="5" <?php echo ($_SESSION['gamesPerPage'] == 5) ? 'selected' : ''; ?>>5</option>    
        <option value="10" <?php echo ($_SESSION['gamesPerPage'] == 10) ? 'selected' : ''; ?>>10</option>
        <option value="20" <?php echo ($_SESSION['gamesPerPage'] == 20) ? 'selected' : ''; ?>>20</option>
        <option value="50" <?php echo ($_SESSION['gamesPerPage'] == 50) ? 'selected' : ''; ?>>50</option>
        <option value="100" <?php echo ($_SESSION['gamesPerPage'] == 100) ? 'selected' : ''; ?>>100</option>
    </select>&nbsp;
    <label for="entriesPerPage" class="form-label mb-0 me-2">entries</label>
</form>