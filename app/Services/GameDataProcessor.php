<?php

/**
 * The NbaSeasonsService class fetches NBA seasons data from an external API.
 *
 * NbaSeasonsService retrieves data about NBA seasons from an external API. It leverages the NbaApiRequester
 * to make the actual HTTP requests. The class uses environment variables to set the API host, API key, and API
 * endpoint for seasons. The data fetched is cached in a local file and is preferentially retrieved from
 * this cache (if available) to save on API requests.
 *
 * @category   NBADataRetrieval
 * @package    NBA_API
 * @author     Harry Ji <jiharry@hotmail.com>
 * @license    https://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       https://github.com/harryji168/NBA-API
 */

 namespace App\Services;

 use PDO;
 
 /**
  * Class GameDataProcessor
  * Processes game data and updates the database as required.
  */
class GameDataProcessor
{
    protected $conn;

    /**
     * GameDataProcessor constructor.
    * @param $conn Connection to the database.
    */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * This method fetches game data from the database.
     * It combines data from the games, teams, and arenas tables.
     * The returned games are sorted by the game_id in descending order (most recent first).
     *
     * @return array An array containing data for each game.
     */
    public function getGameData()
    {
        $stmt = $this->conn->prepare("SELECT games.id, games.game_id, games.home_team_id, 
        games.visitors_team_id, games.date_start, games.status, games.home_team_points, 
        games.visitors_team_points,homeTeam.name AS home_team_name, homeTeam.logo AS home_team_logo,
        visitorTeam.name AS visitors_team_name, visitorTeam.logo AS visitors_team_logo,
        arenas.name AS arena_name, arenas.city AS arena_city, arenas.state AS arena_state
            FROM games
            JOIN teams AS homeTeam ON games.home_team_id = homeTeam.id
            JOIN teams AS visitorTeam ON games.visitors_team_id = visitorTeam.id
            JOIN arenas ON games.arena_id = arenas.id order by games.game_id desc
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves all games for a specific season.
    * @param $season The season to retrieve games for.
    * @return int The number of games for the specified season.
    */
    public function getGamesBySeason($season)
    {
        // Prepare and execute the query
        $stmt = $this->conn->prepare("SELECT * FROM games WHERE season = ?");
        $stmt->execute([$season]);
        // Fetch all games matching the season and return the count
        $games = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return count($games);
    }

    /**
     * Processes team data and inserts or updates in the database as necessary.
    * @param $teamData Data for the team to process.
    * @param $teamType The type of team (home/visitor).
    * @return string The ID of the processed team.
    */
    public function processTeamData($teamData, $teamType)
    {
        // Check if the team already exists in the database
        $stmt = $this->conn->prepare("SELECT id FROM teams WHERE name = ?");
        $stmt->execute([$teamData['name']]);
        $team_id = $stmt->fetchColumn();

        // If the team doesn't exist, insert it
        if (!$team_id) {
            $stmt = $this->conn->prepare("INSERT INTO teams (name, logo) VALUES (?, ?)");
            $stmt->execute([$teamData['name'], $teamData['logo']]);
            $team_id = $this->conn->lastInsertId();
        }

        // Return the team id
        return $team_id;
    }

    /**
     * Processes arena data and inserts or updates in the database as necessary.
    * @param $arenaData Data for the arena to process.
    * @return string The ID of the processed arena.
    */
    public function processArenaData($arenaData)
    {
        // Check if the arena already exists in the database
        $stmt = $this->conn->prepare("SELECT id FROM arenas WHERE name = ?");
        $stmt->execute([$arenaData['name']]);
        $arena_id = $stmt->fetchColumn();

        // If the arena doesn't exist, insert it
        if (!$arena_id) {
            $stmt = $this->conn->prepare("INSERT INTO arenas (name, city, state) VALUES (?, ?, ?)");
            $stmt->execute([$arenaData['name'], $arenaData['city'], $arenaData['state']]);
            $arena_id = $this->conn->lastInsertId();
        }

        // Return the arena id
        return $arena_id;
    }

    /**
     * Processes game data and inserts or updates in the database as necessary.
    * @param $gameData Data for the game to process.
    * @param $homeTeamId The ID of the home team.
    * @param $visitorsTeamId The ID of the visitors team.
    * @param $arenaId The ID of the arena.
    * @param $season The season of the game.
    * @return string The ID of the processed game.
    */
    public function processGameData($gameData, $homeTeamId, $visitorsTeamId, $arenaId, $season)
    {
        // Check if the game already exists in the database
        $stmt = $this->conn->prepare("SELECT id FROM games WHERE game_id = ?");
        $stmt->execute([$gameData['id']]);
        $game_id = $stmt->fetchColumn();

        // If the game doesn't exist, insert it
        if (!$game_id) {
            $dt = new \DateTime($gameData['date']['start']);
            $date_start = $dt->format('Y-m-d H:i:s');
            
            $stmt = $this->conn->prepare("
                INSERT INTO games (game_id, season, date_start, home_team_id, visitors_team_id, 
                visitors_team_points, home_team_points, arena_id, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $gameData['id'],
                $season,
                $date_start,
                $homeTeamId,
                $visitorsTeamId,
                $gameData['scores']['visitors']['points'],
                $gameData['scores']['home']['points'],
                $arenaId,
                $gameData['status']['long']
            ]);

            $game_id = $this->conn->lastInsertId();
        }

        // Return the game id
        return $game_id;
    }

    /**
     * Processes line score data and inserts or updates in the database as necessary.
    * @param $gameId The ID of the game.
    * @param $teamId The ID of the team.
    * @param $lineScoreData The line score data to process.
    */
    public function processLineScores($gameId, $teamId, $lineScoreData)
    {
        foreach ($lineScoreData as $quarter => $points) {
            $quarter += 1;

            // Check if the linescore already exists in the database
            $stmt = $this->conn->prepare("SELECT * FROM linescores WHERE game_id = ? AND team_id = ? AND quarter = ?");
            $stmt->execute([$gameId, $teamId, $quarter]);
            $record = $stmt->fetch();

            // If the linescore doesn't exist, insert it
            if (!$record) {
                $stmt = $this->conn->prepare("INSERT INTO linescores (game_id, team_id, quarter, points)
                                                 VALUES (?, ?, ?, ?)");
                $stmt->execute([$gameId, $teamId, $quarter, $points]);
            }
        }
    }

    /**
     * This method fetches the line scores for a given game and team from the database.
     * The line scores are returned in ascending order of quarters.
     *
     * @param int $gameId The id of the game.
     * @param int $teamId The id of the team.
     * @return array An array containing line scores data for each quarter.
     */
    public function getLineScores($gameId, $teamId)
    {
        $stmt = $this->conn->prepare("
            SELECT quarter, points 
            FROM linescores 
            WHERE game_id = ? AND team_id = ?
            ORDER BY quarter ASC
        ");
        $stmt->execute([$gameId, $teamId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
