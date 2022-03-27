<?php
/*-------------------------------------------------------------------------------------------------
@Module: moviezone_view.php
This server-side module provides all required functionality to format and display movie in html

@Author: Shaswat Shah
@Modified by: 
@Date: 01/04/2021
--------------------------------------------------------------------------------------------------*/

class MovieZoneView
{
	/*Class contructor: performs any initialization
	*/
	public function __construct()
	{
	}

	/*Class destructor: performs any deinitialiation
	*/
	public function __destruct()
	{
	}

	/*Creates left navigation panel
	*/
	public function leftNavPanel()
	{
		print file_get_contents('html/leftnav.html');
	}

	/*Creates top navigation panel director
	*/
	public function topNavPanelDirector($director)
	{
		print "
		<div style='color: #0e5968; float:left;'>
		";
		print "
		<div>
			<div class='topnav'>
			<label for='director'><b>Search by Director:</b></label><br>
			<select name='director' id='id_director' onchange='movieFilterDirector();'>
				<option value='all'>Select all</option>
		";
		//------------------
		foreach ($director as $director) {
			print "<option value='" . $director['director_id'] . "'>" . $director['director_name'] . "</option>";
		}
		print "
			</select>
			</div>
		";
		print "
			</select>
			</div>
		</div>
		";
	}
	/*Creates top navigation panel
	*/
	public function topNavPanelActor($actor)
	{
		print "
		<div style='color: #0e5968; float:left;'>
		";
		print "
			<div class='topnav'>
			<label for='actor'><b>Search by Actor:</b></label><br>
			<select name='actor' id='id_actor' onchange='movieFilterActor();'>
				<option value='all'>Select all</option>			
		";
		//------------------
		foreach ($actor as $actor) {
			print "<option value='" . $actor['actor_id'] . "'>" . $actor['actor_name'] . "</option>";
		}
		print "
			</select>
			</div>
		</div>
		";
	}
	/*Creates top navigation panel
	*/
	public function topNavPanelGenre($genre)
	{
		print "
		<div style='color: #0e5968; float:left;'>
		";
		print "
			<div class='topnav'>
			<label for='genre'><b>Seach by Genre:</b></label><br>
			<select name='genre' id='id_genre' onchange='movieFilterGenre();'>
				<option value='all'>Select all</option>
		";
		//------------------
		foreach ($genre as $genre) {
			print "<option value='" . $genre['genre_id'] . "'>" . $genre['genre_name'] . "</option>";
		}
		print "
			</select>
			</div>
		</div>
		";
	}
	/*Creates top navigation panel
	*/
	public function topNavPanelClassification()
	{
		$classification = array('G', 'M', 'MA', 'PG', 'R');
		print "
		<div style='color: #0e5968; float:left;'>
		
		";
		print "
			<div class='topnav'>
			<label for='classification'><b>Search by Classification:</b></label><br>
			<select name='classification' id='id_classification' onchange='movieFilterClassification();'>
				<option value='all'>Select all</option>
		";
		//------------------
		foreach ($classification as $cls) {
			print "<option value='" . $cls . "'>" . $cls . "</option>";
		}
		print "
			</select>
			</div>
		</div>
		";
	}
	/*Creates top navigation panel
	*/
	public function topNavPanel($director, $actor, $genre, $studio, $title)
	{
		$classification = array('G', 'M', 'MA', 'PG', 'R');
		print "
		<div style='color: #0e5968; float:left;'>
		<div class='topnav'>
				<label for='classification'><b>Search by Title:</b></label><br>
				<select name='title' id='title' onchange='movieSelectTitle();'>
				<option value='all'>Select all</option>
		";
		//------------------
		foreach ($title as $title) {
			print "<option value='" . $title['movie_id'] . "'>" . $title['title'] . "</option>";
		}

		print "
		</select>
		<div>
			<div class='topnav'>
			<label for='director'><b>Search by Director:</b></label><br>
			<select name='director' id='id_director' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>
		";
		//------------------
		foreach ($director as $director) {
			print "<option value='" . $director['director_id'] . "'>" . $director['director_name'] . "</option>";
		}
		print "
			</select>
			</div>
			<div class='topnav'>
			<label for='actor'><b>Search by Actor:</b></label><br>
			<select name='actor' id='id_actor' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>			
		";
		//------------------
		foreach ($actor as $actor) {
			print "<option value='" . $actor['actor_id'] . "'>" . $actor['actor_name'] . "</option>";
		}
		print "
			</select>
			</div>
			<div class='topnav'>
			<label for='genre'><b>Seach by Genre:</b></label><br>
			<select name='genre' id='id_genre' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>
		";
		//------------------
		foreach ($genre as $genre) {
			print "<option value='" . $genre['genre_id'] . "'>" . $genre['genre_name'] . "</option>";
		}
		print "
			</select>
			</div>
			<div class='topnav'>
			<label for='studio'><b>Search by Studio:</b></label><br>
			<select name='studio' id='id_studio' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>
		";
		//------------------
		foreach ($studio as $studio) {
			print "<option value='" . $studio['studio_id'] . "'>" . $studio['studio_name'] . "</option>";
		}
		print "
			</select>
			</div>
			<div class='topnav'>
			<label for='classification'><b>Search by Classification:</b></label><br>
			<select name='classification' id='id_classification' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>
		";
		//------------------
		foreach ($classification as $cls) {
			print "<option value='" . $cls . "'>" . $cls . "</option>";
		}
		print "
			</select>
			</div>
		</div>
		";
	}

	/*Displays error message
	*/
	public function showError($error)
	{
		print "<h2>Error: $error</h2>";
	}

	/*Displays an array of movie
	*/
	public function showmovie($movie_array)
	{
		if (!empty($movie_array)) {
			foreach ($movie_array as $movie) {
				$this->printMovieInHtml($movie);
			}
		}
	}

	/*Format a movie into html
	*/
	private function printmovieInHtml($movie)
	{
		//print_r($movie);

		if (empty($movie['thumbpath'])) {
			$photo = _MOVIE_PHOTO_FOLDER_ . "default.jpg";
		} else {
			$photo = _MOVIE_PHOTO_FOLDER_ . $movie['thumbpath'];
		}
		$studio = $movie['studio'];
		$plot = $movie['plot'];
		$director = $movie['director'];
		$actors = array();
		if ($movie['star1']) array_push($actors, $movie['star1']);
		if ($movie['star2']) array_push($actors, $movie['star2']);
		if ($movie['star3']) array_push($actors, $movie['star3']);
		if ($movie['costar1']) array_push($actors, $movie['costar1']);
		if ($movie['costar2']) array_push($actors, $movie['costar2']);
		if ($movie['costar3']) array_push($actors, $movie['costar3']);
		$year = $movie['year'];
		$tagline = $movie['tagline'];
		$genre = $movie['genre'];
		$title = $movie['title'];

		$classification = $movie['classification'];
		$rental_period = $movie['rental_period'];
		$DVD_rental_price = $movie['DVD_rental_price'];
		$DVD_purchase_price = $movie['DVD_purchase_price'];
		$numDVD = $movie['numDVD'];
		$numDVDout = $movie['numDVDout'];
		$BluRay_rental_price = $movie['BluRay_rental_price'];
		$BluRay_purchase_price = $movie['BluRay_purchase_price'];
		$numBluRay = $movie['numBluRay'];
		$numBluRayOut = $movie['numBluRayOut'];
		$DVDavai = $numDVD - $numDVDout;
		$BluRayavai = $numBluRay - $numBluRayOut;
		print "
		<div class='movie_card'>	
			<div class='title'>$title</div>
			
			
			<div class='content'>
				<img src= '$photo' alt='car photo' class='photo'>
				<p class='plot'>$plot</p>
				<span class='topic'>Year:</span> $year<br>
				<span class='topic'>Genre:</span> $genre<br>
				<span class='topic'>Classification:</span> $classification<br>
				<span class='topic'>Studio:</span> $studio<br>
				<span class='topic'>Director:</span> $director<br>
				<span class='topic'>Starring:</span>
		";
		foreach ($actors as $idx => $actor) {
			if ($idx == 0) {
				print "$actor";
			} else
				print ", $actor";
		}
		print "
				 <br>
				<span class='topic'>Tagline:</span> $tagline<br>
				<br>
				<span class='topic'>Rental:</span>DVD -\$$DVD_rental_price BluRay -\$$BluRay_rental_price<br>
				<span class='topic'>Purchase:</span>DVD -\$$DVD_purchase_price BluRay -\$$BluRay_purchase_price<br>
				<span class='topic'>Availability:</span>DVD -$DVDavai BluRay -$BluRayavai <br>
				<span class='topic'>$rental_period</span>
			</div>
		</div>
		";
	}
}
// <span class='topic'>DVD Rental Price:</span> $DVD_rental_price <br>
// <span class='topic'>DVD Purchase Price:</span> $DVD_purchase_price<br>
// <span class='topic'>Number of DVD:</span> $numDVD<br>
// <span class='topic'>Number of DVD Out:</span> $numDVDout <br>
// <span class='topic'>BluRay Rental Price:</span> $BluRay_rental_price<br>
// <span class='topic'>BluRay Purchase Price:</span> $BluRay_purchase_price<br> 
// <span class='topic'>Number of BluRay:</span> $numBluRay<br>
// <span class='topic'>Number of  BluRay Out:</span> $numBluRayOut<br>
