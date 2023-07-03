
# NBA API Requester Project

This project serves as a demonstration of constructing a straightforward PHP API client using object-oriented programming principles. The client is designed to retrieve NBA games data from the RapidAPI platform. 

## The live project can be accessed at: [nba.harryji.com.](https://nba.harryji.com)

![Screenshot from 2023-07-03 13-22-22](https://github.com/harryji168/NBA-API/assets/21187699/9be074a1-f202-4ef2-9946-2695273afb42)

## Project Structure
The structure of the project adheres to the Laravel file hierarchy. 
```
NBA_API_project/
├── app/
│   ├── Helpers/
│   │   ├── DataFilters.php
│   │   ├── Dates.php
│   │   ├── Storage.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   ├── Services/
│   │   ├── NbaApiRequester.php
│   │   ├── NbaGamesService.php
│   │   ├── NbaSeasonsService.php
├── public/
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   ├── font-awesome.css
│   ├── js/
│   │   ├── jquery.js
│   ├── image/
│   │   ├── nba.png
│   ├── webfonts/
│   │   ├── fa-brands-400.eot
│   ├── ajax-handler.php
│   ├── index.php
├── resources/
│   ├── views/
│   │   ├── nba-game-table.php
│   │   ├── forms/
│   │   │   ├── ShowEntriesForm.php
│   │   │   ├── SearchForm.php
│   │   │   ├── PaginationForm.php
│   │   │   ├── EntriesPerPageForm.php
├── storage/
│   ├── cache/
│   │   ├── cache_6edb04f1190a0af6e485a4068df76856.html
│   ├── json/
│   │   ├── seasons.json
│   ├── logs/
│   │   ├── nba_api.log
├── tests/
│   ├── unit/
│   │   ├── Helpers/
│   │   │   ├── DataFiltersTest.php
│   │   │   ├── DatesTest.php
│   │   │   ├── StorageTest.php
│   │   ├── Providers/
│   │   │   ├── AppServiceProviderTest.php
│   │   ├── Services/
│   │   │   ├── NbaApiRequesterTest.php
│   │   │   ├── NbaGamesServiceTest.php
│   │   │   ├── NbaSeasonsServiceTest.php
├── vendor/
├── docker-compose.yml
├── Dockerfile
├── composer.json
├── nginx.conf
├── README.md
├── .env.sample
└── .env (to be created by user)
```

## Setup

### Docker Compose Setup

1. Install Docker and Docker Compose on your system.
2. Clone this repository to your local system.
3. Navigate to the project root directory.
4. Run `docker-compose up -d` to build the images and start the containers.

## Usage

### Docker Compose Usage

The services can be accessed on the following ports on your host machine:

- PHP service: `9000`
- Nginx service: `8061` (HTTP), `44361` (HTTPS)
- MySQL service: `33639`

To execute commands inside a Docker container, you can use `docker exec`. For example, to execute PHPUnit tests, you can use:

```bash
docker exec -it dcindesign ./vendor/bin/phpunit tests/unit/Services/NbaApiRequesterTest.php
```

## Running Tests

### Docker Tests

From within the Docker container, run `./vendor/bin/phpunit tests/unit/Services/NbaApiRequesterTest.php` to execute the unit tests.

## License

This project is licensed under the terms of the MIT license.
 
