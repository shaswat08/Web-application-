<?php
/*dbAdapter: this module acts as the database abstraction layer for the application
@Author: Shaswat Shah
@Modify by:
@Version: 01/04/2021
*/

/*Connection paramaters
*/
require_once('moviezone_config.php');

/* DBAdpater class performs all required CRUD functions for the application
*/
class DBAdaper
{
	/*local variables
	*/
	private $dbConnectionString;
	private $dbUser;
	private $dbPassword;
	private $dbConn; //holds connection object
	private $stmt;
	private $dbError; //holds last error message

	/* The class constructor
	*/
	public function __construct($dbConnectionString, $dbUser, $dbPassword)
	{
		$this->dbConnectionString = $dbConnectionString;
		$this->dbUser = $dbUser;
		$this->dbPassword = $dbPassword;
	}
	/*Opens connection to the database
	*/
	public function dbOpen()
	{
		try {
			$this->dbConn = new PDO($this->dbConnectionString, $this->dbUser, $this->dbPassword);
			// set the PDO error mode to exception
			$this->dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->dbError = null;
			// echo "DB Connected successfully";
		} catch (PDOException $e) {
			$this->dbError = $e->getMessage();
			$this->dbConn = null;
			// echo "Connection failed: " . $this->dbError;
		}
	}
	/*Closes connection to the database
	*/
	public function dbClose()
	{
		//in PDO assigning null to the connection object closes the connection
		$this->dbConn = null;
	}
	/*Return last database error
	*/
	public function lastError()
	{
		return $this->dbError;
	}
	/*Creates required tables in the database if not already created
	  @return: TRUE if successful and FALSE otherwise
	*/
	public function dbCreate()
	{
		$result = null;
		if ($this->dbConn != null) {
			try {
				//table actor
				$sql = "CREATE TABLE IF NOT EXISTS `actor` (
                    `actor_id` int(10) NOT NULL AUTO_INCREMENT,
                    `actor_name` char(128) DEFAULT NULL
						) ENGINE=MyISAM DEFAULT CHARSET=latin1";
				$result = $this->dbConn->exec($sql);
				//table movie
				$sql = "CREATE TABLE IF NOT EXISTS `movie` (
                    `movie_id` int(10) NOT NULL AUTO_INCREMENT,
                    `title` varchar(45) NOT NULL,
                    `tagline` varchar(128) NOT NULL,
                    `plot` varchar(256) NOT NULL,
                    `thumbpath` varchar(40) NOT NULL,
                    `director_id` int(10) NOT NULL,
                    `studio_id` int(10) NOT NULL,
                    `genre_id` int(10) NOT NULL,
                    `classification` varchar(128) NOT NULL,
                    `rental_period` varchar(128) NOT NULL,
                    `year` int(4) NOT NULL,
                    `DVD_rental_price` decimal(4,2) NOT NULL DEFAULT '0.00',
                    `DVD_purchase_price` decimal(4,2) NOT NULL DEFAULT '0.00',
                    `numDVD` int(3) NOT NULL DEFAULT '0',
                    `numDVDout` int(3) NOT NULL DEFAULT '0',
                    `BluRay_rental_price` decimal(4,2) NOT NULL DEFAULT '0.00',
                    `BluRay_purchase_price` decimal(4,2) NOT NULL DEFAULT '0.00',
                    `numBluRay` int(3) NOT NULL DEFAULT '0',
                    `numBluRayOut` int(3) NOT NULL DEFAULT '0'
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";
				$result = $this->dbConn->exec($sql);
				//table director
				$sql = "CREATE TABLE IF NOT EXISTS `director` (
                    `director_id` int(10) NOT NULL AUTO_INCREMENT,
                    `director_name` char(128) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1";
				$result = $this->dbConn->exec($sql);
				//table genre
				$sql = "CREATE TABLE IF NOT EXISTS `genre` (
                   `genre_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                   `genre_name` char(128) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1";
				$result = $this->dbConn->exec($sql);
				//create view to simplify the movie selection
				$sql = "
					CREATE OR REPLACE VIEW movie_detail_view AS
					SELECT 
						movie.*, 
						director.name as director,  
						actor.name as actor,
						genre.name as genre 
					FROM
						movie, director, actor, genre
					WHERE
						director.director_id = movie.director_id AND
						actor.actor_id = movie.actor_id AND
						genre.genre_id = movie.genre_id
						";
				$result = $this->dbConn->exec($sql);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*------------------------------------------------------------------------------------------- 
                              DATABASE MANIPULATION FUNCTIONS
	-------------------------------------------------------------------------------------------*/

	/*Helper function:
	Build SQL AND conditional clause from the array of condition paramaters
	*/
	protected function sqlBuildConditionalClause($params, $condition)
	{
		$clause = "";
		$and = false; //so we know when to add AND in the sql statement	
		if ($params != null) {
			foreach ($params as $key => $value) {
				$op = '='; //comparison operator
				if ($key == 'studio')
					$op = '<=';
				if (!empty($value)) {
					if ($and) {
						$clause = $clause . " $condition $key $op '$value'";
					} else {
						//the first AND condition
						$clause = "WHERE $key $op '$value'";
						$and = true;
					}
				}
			}
		}

		return $clause;
	}

	protected function sqlBuildConditionalClause2($params, $condition)
	{
		$clause = "";
		$and = false; //so we know when to add AND in the sql statement	
		if ($params != null) {
			foreach ($params as $key => $value) {
				$op = '='; //comparison operator
				if ($key == 'director_id') {
					$key = "director";
					$smt = $this->dbConn->prepare(
						"SELECT director_name from director where director_id='$value'"
					);
					//Execute the query
					$smt->execute();
					$result = $smt->fetchAll(PDO::FETCH_ASSOC);
					$value = $result[0]['director_name'];
					// var_dump($result);
					// exit();
				}
				if ($key == 'actor_id') {
					$key = "actor";
					$smt = $this->dbConn->prepare(
						"SELECT actor_name from actor where actor_id='$value'"
					);
					//Execute the query
					$smt->execute();
					$result = $smt->fetchAll(PDO::FETCH_ASSOC);
					$value = $result[0]['actor_name'];
					// var_dump($result);
					// exit();
				}
				if ($key == 'genre_id') {
					$key = "genre";
					$smt = $this->dbConn->prepare(
						"SELECT genre_name from genre where genre_id='$value'"
					);
					//Execute the query
					$smt->execute();
					$result = $smt->fetchAll(PDO::FETCH_ASSOC);
					$value = $result[0]['genre_name'];
					// var_dump($result);
					// exit();
				}
				if ($key == 'studio_id') {
					$key = "studio";
					$smt = $this->dbConn->prepare(
						"SELECT studio_name from studio where studio_id='$value'"
					);
					//Execute the query
					$smt->execute();
					$result = $smt->fetchAll(PDO::FETCH_ASSOC);
					$value = $result[0]['studio_name'];
					// var_dump($result);
					// exit();
				}
				if (!empty($value)) {
					if ($and) {
						if ($key == 'actor') {
							$clause = $clause . "AND star1 $op '$value' or star2 $op '$value' or star3 $op '$value' or costar1 $op '$value' or costar2 $op '$value' or costar3 $op '$value'";
						} else
							$clause = $clause . " $condition $key $op '$value'";
					} else {
						//the first AND condition
						if ($key == 'actor') {
							$clause = "WHERE star1 $op '$value' or star2 $op '$value' or star3 $op '$value' or costar1 $op '$value' or costar2 $op '$value' or costar3 $op '$value'";
						} else
							$clause = "WHERE $key $op '$value'";
						$and = true;
					}
				}
			}
		}

		return $clause;
	}

	/*Select all existing movie from the movie table
	@return: an array of matched movie
	*/
	public function movieSelectAll()
	{
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare(
					'SELECT * FROM movie_detail_view'
				);
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Select all existing movie from the movie table
	@return: an array of matched movie
	*/
	public function titleSelectAll()
	{
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare(
					'SELECT movie_id, title FROM movie_detail_view'
				);
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	public function movieSelectTitle($title)
	{
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare(
					"SELECT * FROM movie_detail_view WHERE movie_id='$title'"
				);
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Select ramdom movie from the movie table
	@param: $max - the maximum number of movie will be selected
	@return: an array of matched movie (default 1 movie)
	*/
	public function movieSelectRandom($max = 10)
	{
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare(
					"SELECT *
					FROM movie_detail_view 
					ORDER BY RAND() 
					LIMIT $max"
				);
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	public function movieSelectNewRelease($max = 10)
	{
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare(
					"SELECT *
					FROM movie_detail_view 
					ORDER BY year desc 
					LIMIT $max"
				);
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Select an existing movie from the movie table
	@param $condition: is an associative array of movie's details you want to match
	@return: an array of matched movie
	*/
	public function movieFilter($condition)
	{
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$sql = 'SELECT * FROM movie_detail_view '
					. $this->sqlBuildConditionalClause2($condition, 'AND');
				// var_dump($sql);
				// exit();
				$smt = $this->dbConn->prepare($sql);
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}


	/*Select all existing states from the genre table
	@return: an array of genre with column name as the keys;
	*/
	public function genreSelectAll()
	{
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare('SELECT * FROM genre');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}


	/*Select all existing director from the  director table
	@return: an array of director with column name as the keys;
	*/
	public function directorSelectAll()
	{
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare('SELECT * FROM director');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}


	/*Select all existing body type from the bodytypes table
	@return: an array of body type with column name as the keys;
	*/
	public function actorSelectAll()
	{
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare('SELECT * FROM actor');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}
	public function studioSelectAll()
	{
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare(
					'SELECT * FROM studio'
				);
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}
	/*Select all movide by classification
	@return: an array of matched movie
	*/
	public function classificationSelectAll($cls)
	{
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare(
					'SELECT * FROM movie_detail_view WHERE classification=?'
				);
				//Execute the query
				$smt->execute($cls);
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}
}


/*---------------------------------------------------------------------------------------------- 
                                         TEST FUNCTIONS
 ----------------------------------------------------------------------------------------------*/

//Your task: implement the test function to test each function in this dbAdapter


/*Tests database functions
*/
function testDBA()
{
	$dbAdapter = new DBAdaper(DB_CONNECTION_STRING, DB_USER, DB_PASS);

	$movie = array(
		'photo' => '1250744226twilight.jpg',
		'studio_id`' => 1,
		'movie_id' => 1,
		'director_id' => 1,
		'actor_id' => 1,
		'tagline' => 'When you can live forever what do you live for?',
		'year' => 2007,
		'genre_id' => 2,
		'title' => "Twilight"
	);

	$dbAdapter->dbOpen();
	$dbAdapter->dbCreate();

	//	$result = $dbAdapter->movieSelectRandom(200);	
	//	$result = $dbAdapter->movieSelectAll();	
	//	$result = $dbAdapter->movieFilter($movie);	

	//	$result = $dbAdapter->genreSelectAll();	
	//	$result = $dbAdapter->directorSelectAll();	
	//	$result = $dbAdapter->actorSelectAll();	

	// if ($result != null)		
	// 	print_r($result);
	// else
	// 	echo $dbAdapter->lastError();
	$dbAdapter->dbClose();
}

//execute the test
// testDBA();
