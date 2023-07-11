<?php
/**
 * This file generates the HTML for displaying a table of NBA game data.
 *
 * The data used to populate this table is filtered according to parameters provided
 * by the user (team name, game status, and game date). The table includes various
 * details about each game, such as ID, date, teams, status, scores, and arena.
 *
 * It also includes a map modal for displaying the location of each game, and a set
 * of forms for controlling the display (pagination, number of entries to show, etc.).
 *
 * PHP version 8.1
 *
 * @category   NBAGamesTable
 * @package    NBA_API
 * @subpackage View
 * @author     Harry Ji <jiharry@hotmail.com>
 * @license    https://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       https://github.com/harryji168/NBA-API
 * @since      File available since Release 1.0.0
 */

require_once __DIR__ . '/../../vendor/autoload.php';

// Import necessary classes
use Config\Database;
use App\Helpers\DataFilters;
use App\Helpers\Dates;
use App\Helpers\Storage;
use App\Providers\AppServiceProvider;
use App\Services\GameDataProcessor;
use App\Services\NbaGamesService;

// Initialize AppServiceProvider
$serviceProvider = new AppServiceProvider();
$serviceProvider->boot();

if ($_ENV['USE_CACHE'] !== 'true') {
    // Use NbaGamesService to fetch game data
    $gamesService = new NbaGamesService();
    $games = $gamesService->fetchGameData();

    // Use the usort function to sort the array in descending order by 'id'
    usort($games, function ($a, $b) {
        return $b['id'] <=> $a['id'];
    });
}

$conn = (new Database())->getConnection();
$processor = new GameDataProcessor($conn);
$games = $processor->getGameData();


$gamesPerPage = $_SESSION['gamesPerPage'];
$orderby = (isset($_SESSION['orderby']) && $_SESSION['orderby'] !== 'default')
                ? filter_input(INPUT_GET, 'orderby', FILTER_VALIDATE_INT) : 'id';
$page = (isset($_GET['page']) && $_GET['page'] !== 'default')
                ? filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) : 1;
$search = (isset($_GET['search']) && $_GET['search'] !== 'default')
                ? filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;
$date = (isset($_GET['date']) && $_GET['date'] !== 'default')
                ? Dates::sanitizeDate(filter_input(INPUT_GET, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : null;

$start = ($page - 1) * $gamesPerPage;

if ($games !== null) {
    // Filter games
    $games = DataFilters::getFilteredGames($games, $search, $date);
    // Get the total count before slicing the array
    $totalGames = count($games);
    // If games is not null, slice the array based on the page number
    $games = array_slice($games, $start, $gamesPerPage);
}
// Generate the table
ob_start(); // Start output buffering
?>
<style>
    .winner {
        font-weight: bold;
    }

    .loser {
        font-weight: normal;
    }
    .score, .pscore {
        font-size: 1.2em;
        text-align: center;
        display: flex;
        align-items: center; 
        justify-content: center; 
    }
    .pscore {
        font-size: 0.8em;
    }
    .sortable {
        position: relative;
    }
    .sortable .fas {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
    }
    .sortable .fas.fa-sort {
        color: lightgrey;
    }
    .team-logo-winner {
        width: 38px;
        height: auto; 
    }
    .team-logo-loser {
        width: 30px;
        height: auto; 
        opacity: 0.5;
    }
    .row-even {
        background-color: #ffffff;
    }
    .row-odd {
        background-color: #F5F5F5;
    }
    .row-even,
    .row-odd {
        transition: background-color 0.3s ease;
    }
    .row-even:hover,
    .row-odd:hover {
        background-color: #e0f7fa;
    }    
</style>
<?php if (isset($error)) : ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php elseif ($games) : ?> 
    <table class="table table-bordered table-sm">
    <thead class="thead-light">
        <tr>
            <th class="sortable">ID <i class="fas fa-sort-down"></i></th>
            <th class="sortable">Date <i class="fas fa-sort"></th>         
            <th class="sortable">Teams <i class="fas fa-sort"></th>
            <th class="sortable">Status <i class="fas fa-sort"></th>
            <th class="sortable">1<i class="fas fa-sort"></th>
            <th class="sortable">2<i class="fas fa-sort"></th>
            <th class="sortable">3<i class="fas fa-sort"></th>
            <th class="sortable">4<i class="fas fa-sort"></th>
            <th class="sortable">Score <i class="fas fa-sort"></th>
            <th class="sortable">Arena <i class="fas fa-sort"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $row_counter = 0;
        foreach ($games as $game) :
            $row_class = ($row_counter % 2 == 0) ? 'row-even' : 'row-odd';
            $row_counter++;
            $visitors_lineScores = $processor->getLineScores($game['id'], $game['visitors_team_id']);
            $home_lineScores = $processor->getLineScores($game['id'], $game['home_team_id']);
            ?>
            <tr class="<?= $row_class; ?>">
                <td rowspan=2 class="align-middle"><?= $game['game_id']; ?></td>
                <td rowspan=2 class="align-middle">
                    <a href="?date=<?= Dates::sanitizeDate($game['date_start']); ?>">
                        <?= $date = Dates::sanitizeDateTime($game['date_start']); ?>
                    </a>
                </td>              
                <td>
                    <span class="team-name <?= $game['visitors_team_points'] > $game['home_team_points']
                        ? 'winner' : 'loser' ?>">
                        <a href="?search=<?= urlencode($game['visitors_team_name']); ?>">
                            <img src="<?= $game['visitors_team_logo']; ?>" alt="<?= $game['home_team_name']; ?>" 
                                class="<?= $game['visitors_team_points'] > $game['home_team_points']
                                ? 'team-logo-winner' : 'team-logo-loser' ?>" />
                            <?= $game['visitors_team_name']; ?>
                        </a>
                    </span>
                </td>
                <td rowspan=2 class="align-middle"><?= $game['status']; ?></td>
                <td>
                    <span class="pscore <?= $visitors_lineScores[0]['points'] > $home_lineScores[0]['points']
                        ? 'winner' : 'loser' ?>">
                        <?= $visitors_lineScores[0]['points'];?>
                    </span>
                </td>
                <td>
                    <span class="pscore <?= $visitors_lineScores[1]['points'] > $home_lineScores[1]['points']
                        ? 'winner' : 'loser' ?>">
                        <?= $visitors_lineScores[1]['points']; ?>
                    </span>
                </td>
                <td>
                    <span class="pscore <?= $visitors_lineScores[2]['points'] > $home_lineScores[2]['points']
                        ? 'winner' : 'loser' ?>">
                        <?= $visitors_lineScores[2]['points']; ?>
                    </span>
                </td>
                <td>
                    <span class="pscore <?= $visitors_lineScores[3]['points'] > $home_lineScores[3]['points']
                        ? 'winner' : 'loser' ?>">
                        <?= $visitors_lineScores[3]['points']; ?>
                    </span>
                </td>
                <td>
                    <span class="score <?= $game['visitors_team_points'] > $game['home_team_points']
                        ? 'winner' : 'loser' ?>">
                        <?= $game['visitors_team_points']; ?>
                    </span>
                </td>
                <td rowspan=2 class="align-middle">
                    <a href="https://www.google.com/maps/search/?api=1&query=
                        <?= urlencode($game['arena_name'].','.$game['arena_city'].','.$game['arena_state']) ?>"
                        target="_blank">
                        <?= $game['arena_name'].'<br>'.$game['arena_city'].','.$game['arena_state']; ?>
                        <i class="fas fa-map" style="color: #006400;"></i>
                    </a>        
                </td>
            </tr>
            <tr class="<?= $row_class; ?>">
                <td>
                    <span class="team-name <?= $game['home_team_points'] > $game['visitors_team_points']
                        ? 'winner' : 'loser' ?>">
                        <a href="?search=<?= urlencode($game['home_team_name']); ?>">
                            <img src="<?= $game['home_team_logo']; ?>" alt="<?= $game['home_team_name']; ?>"
                            class="<?= $game['home_team_points'] > $game['visitors_team_points']
                                ? 'team-logo-winner' : 'team-logo-loser' ?>" />
                            <?= $game['home_team_name']; ?>
                        </a>
                    </span>
                </td>
                <td>
                    <span class="pscore <?= $home_lineScores[0]['points'] > $visitors_lineScores[0]['points']
                        ? 'winner' : 'loser' ?>">
                        <?= $home_lineScores[0]['points']; ?>
                    </span>
                </td>
                <td>
                    <span class="pscore <?= $home_lineScores[1]['points'] > $visitors_lineScores[1]['points']
                        ? 'winner' : 'loser' ?>">
                        <?= $home_lineScores[1]['points']; ?>
                    </span>
                </td>
                <td>
                    <span class="pscore <?= $home_lineScores[2]['points'] > $visitors_lineScores[2]['points']
                        ? 'winner' : 'loser' ?>">
                        <?= $home_lineScores[2]['points']; ?>
                    </span>
                </td> 
                <td>
                    <span class="pscore <?= $home_lineScores[3]['points'] > $visitors_lineScores[3]['points']
                        ? 'winner' : 'loser' ?>">
                        <?= $home_lineScores[3]['points']; ?>
                    </span>
                </td> 
                <td>
                    <span class="score <?= $game['home_team_points'] > $game['visitors_team_points']
                        ? 'winner' : 'loser' ?>">
                        <?= $game['home_team_points']; ?>
                    </span>
                </td> 
            </tr>  
        <?php endforeach; ?>
    </tbody>
    <thead class="thead-light">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Teams</th>
            <th>Status</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>Score</th>
            <th>Arena</th>
        </tr>
    </thead>
    </table>
    <div class="d-flex justify-content-between">
        <div>
            <?php require_once 'forms/ShowEntriesForm.php';?>
        </div>
        <div>
            <?php require_once 'forms/PaginationForm.php';?>
        </div>
    </div> 
    <script>
    $(document).ready(function() {
        $("tr:has(td[rowspan=2])").hover(
            function() {
                $(this).css("background-color", "#e0f7fa");
                $(this).next("tr").css("background-color", "#e0f7fa");
            }, function() {
                $(this).css("background-color", "");
                $(this).next("tr").css("background-color", "");
            }
        );
    });
    </script>

<?php else : ?>
    <div class="alert alert-info">No games found. <a href="/">Back to Home</a> </div>
<?php endif;

// Get the generated table and save it to the cache file
$tableHTML = ob_get_clean(); // Fetch the content and stop output buffering
$filepath = Storage::path('cache/'.$cacheFileName);
file_put_contents($filepath, $tableHTML);

// Output the generated table
echo $tableHTML;
?>