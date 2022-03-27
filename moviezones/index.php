 <?php
	/*-------------------------------------------------------------------------------------------------
@Module: index.php
This server-side module provides main UI for the application (admin part)

@Author: Shaswat Shah
@Modified by: 
@Date: 01/04/2021
-------------------------------------------------------------------------------------------------*/
	require_once('moviezone_main.php');
	?>

 <html>

 <head>
 	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<link rel="stylesheet" type="text/css" href="css/moviezone.css">
 	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">

 	<script defer src="js/ajax.js"></script>
 	<script defer src="js/moviezone.js"></script>
 	<title>Movie Zone</title>
 </head>

 <body>
 	<div id="id_container">
 		<header class="header">
 			<h1>MOVIE ZONE</h1>
 		</header>
 		<!-- left navigation area -->
 		<div id="id_left">
 			<!-- load the navigation panel by embedding php code -->
 			<?php $controller->loadLeftNavPanel() ?>
 		</div>
 		<!-- right area -->
 		<div id="id_right">
 			<!-- top navigation area -->
 			<div id="id_topnav">
 				<!-- the top navigation panel is loaded on demand using Ajax (see js code) -->
 			</div>
 			<div id="id_content"></div>
 		</div>
 		<!-- footer area -->
 		<footer class="footer">Copyright &copy; 2021 Shaswat Shah
 			<p>Credits: <a href="http://www.imdb.com/">Internet Movie Database(IMDb)</a> For Images and Data</p>
 		</footer>
 	</div>
 </body>

 </html>
