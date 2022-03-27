<?php
/*This is a good practice to define all constants which may be used at different places
*/

define('DB_CONNECTION_STRING', "mysql:host=localhost;dbname=sshah23_moviezone_db");
define('DB_USER', "sshah23");
define('DB_PASS', "23522260");

define('MSG_ERR_CONNECTION', "Open connection to the database first");

//maximum number of random movie will be shown
define('MAX_RANDOM_movie', 9);
//the folder where movie photos are stored
define('_MOVIE_PHOTO_FOLDER_', "photos/");

//request command messages for client-server communication using AJAX
define('CMD_REQUEST', 'request'); //the key to access submitted command via POST or GET
define('CMD_SHOW_TOP_NAV', 'cmd_show_top_nav'); //create and show top navigation panel
define('CMD_SHOW_TOP_ACTOR', 'cmd_show_top_actor'); //create and show top navigation panel
define('CMD_SHOW_TOP_DIRECTOR', 'cmd_show_top_director'); //create and show top navigation panel
define('CMD_SHOW_TOP_GENRE', 'cmd_show_top_genre'); //create and show top navigation panel
define('CMD_SHOW_TOP_CLASSIFICATION', 'cmd_show_top_classification'); //create and show top navigation panel
define('CMD_MOVIE_SELECT_RANDOM', 'cmd_movie_select_random');
define('CMD_MOVIE_SELECT_ALL', 'cmd_movie_select_all');
define('CMD_MOVIE_FILTER', 'cmd_movie_filter'); //filter movie by submitted parameters
define('CMD_MOVIE_NEW_RELEASE', 'cmd_movie_new_release');
define('CMD_MOVIE_SELECT_TITLE', 'cmd_movie_select_title');

//define error messages
define('errSuccess', 'SUCCESS'); //no error, command is successfully executed
define('errAdminRequired', "Login as admin to perform this task");

require_once('moviezone_dba.php');
require_once('moviezone_model.php');
require_once('moviezone_view.php');
require_once('moviezone_controller.php');
