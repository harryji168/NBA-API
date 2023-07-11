<?php session_start();?>
<!DOCTYPE html>
<html> 
<head> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO improvements -->
    <title>NBA Games - Data Processing</title>
    <meta name="description" content="Get the latest updates and statistics on NBA games.">
    <meta name="keywords" content="NBA, Basketball, NBA Games, Data Processing, NBA Statistics">
    <!-- End of SEO improvements -->

    <title>Data Processing</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style> 
        /* Add CSS styles here */
        #loading {
            position: absolute; /* Absolute to the closest relative parent */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto; /* Auto margins will center the spinner */
            width: 50px;
            height: 50px;
            text-align: center;
        }
        #form-container {
            position: relative; /* Relative to itself */ 
        }
        .hidden {
            display: none;
        }
        .logo-in-center {
            position: absolute;
            top: 20%;
            left: 20%;
            width: 60%;
            height: 60%;
            object-fit: cover;
            transition: all 2s ease-in-out;            
            pointer-events: none;
            z-index: 9999; 
        }

        .logo-in-corner {
            position: fixed;
            bottom: 10px;
            right: 10px;
            width: 150px;
            height: auto;
            opacity: 0.3;
            pointer-events: none;
            z-index: -1; 
        }
    </style>
</head> 
<body> 
    <h1 id="data_processing">Data Processing</h1> 
    <div id="results">
        <div class="spinner-border text-primary" role="status" id="loading">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="container">
        <h1 class="my-4"><a href="?"><img id="logo" src="image/nba.png" class="logo-in-corner"
            width=150 alt="NBA Logo">NBA Games</a></h1> 

        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <?php
                    require_once __DIR__ . '/../resources/views/forms/EntriesPerPageForm.php';
                if (!empty($_GET['date'])) {
                    echo "&nbsp;Date: ".$_GET['date'];
                    echo "&nbsp;<a href='?' class='btn btn-primary btn-sm ms-2' title='Clear filter'>
                    <i class='fas fa-times'></i></a>";
                }
                ?>
            </div>
            <?php require_once __DIR__ . '/../resources/views/forms/SearchForm.php';?>
        </div>`

        <div id="form-container" class="hidden">
            <!-- NBA Game form will go here -->
        </div>
    </div>
    </div>
    <footer style="background-color: #f8f9fa; padding: 20px; width: 100%; text-align: center;">
        <p style="margin-bottom: 0;">NBA API Requester Project - a database solution showcasing a PHP API client 
        that retrieves NBA games data from the RapidAPI platform using object-oriented programming principles. 
        Explore our project structure, follow the development and access the live project at 
        <a href="http://nba.harryji.com">nba.harryji.com</a>.</p>
        <p style="margin-bottom: 0;">For detailed insights and usage, visit our comprehensive 
        <a href="https://harryji168.github.io/NBA-API/v2">documentation</a>. We welcome your suggestions 
        and issues on our <a href="https://github.com/harryji168/NBA-API">GitHub</a> repository.</p>
        <p style="margin-bottom: 0;">&copy; <?php echo date("Y"); ?> NBA API Requester Project. All rights reserved.</p>
    </footer>

    <script src="js/jquery.js"></script>
    <script>
        // Check if the animation has already been played in this session
        if (!sessionStorage.getItem('animationPlayed')) {
            var logoElement = document.getElementById('logo');
            logoElement.classList.remove('logo-in-corner'); // Remove the original class
            logoElement.classList.add('logo-in-center'); // Add the new class
        }
        function loadPage(params) {
            // Set default values for missing parameters
            params = $.extend({
                'gamesPerPage': 'default',
                'orderby': 'default',
                'page': 'default',
                'date': 'default',
                'search': 'default'
            }, params);

            $.ajax({
                url: 'ajax-handler.php',
                type: 'GET',
                data: params,
                beforeSend: function() {
                    $('#data_processing').removeClass('hidden');
                    $('#loading').removeClass('hidden');
                    $('#form-container').addClass('hidden');                    
                    $('body').css('opacity', '0.3'); 
                },
                success: function(data) {
                    $('#data_processing').addClass('hidden');
                    $('#loading').addClass('hidden');
                    $('#form-container').removeClass('hidden').html(data);
                    $('body').css('opacity', '1');
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
        $(document).ready(function() { 
            // PHP variables set here to be passed into loadPage()
            var params = {
                'gamesPerPage': 
                    '<?php echo isset($_SESSION['gamesPerPage']) ? $_SESSION['gamesPerPage'] : 'default'; ?>',
                'orderby': '<?php echo isset($_SESSION['orderby']) ? $_SESSION['orderby'] : 'default'; ?>',
                'page': '<?php echo isset($_GET['page']) ? $_GET['page'] : 'default'; ?>',
                'date': '<?php echo isset($_GET['date']) ? $_GET['date'] : 'default'; ?>',
                'search': '<?php echo isset($_GET['search']) ? $_GET['search'] : 'default'; ?>',
            };

            // Call the loadPage() function with initial parameters here
            loadPage(params);

            // Check if the animation has already been played in this session
            if (!sessionStorage.getItem('animationPlayed')) {
                  // Immediately add 'logo-in-center' to the element with id 'logo'
                //document.getElementById('img-in-corner').classList.add('logo-in-center');

                setTimeout(function() { 
                    document.getElementById('logo').classList.add('logo-in-corner');
                }, 1000);

                // Set the flag in session storage
                sessionStorage.setItem('animationPlayed', 'true');
            }
        });
    </script>
</body> 
</html>
