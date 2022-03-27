<?php
/*-------------------------------------------------------------------------------------------------
@Module: moviezone_controller.php
This server-side module provides all required functionality to format and display movie in html

@Author: Shaswat Shah
@Date: 01/04/2021
--------------------------------------------------------------------------------------------------*/
require_once('moviezone_config.php');

class MovieZoneController
{
	private $model;
	private $view;

	/*Class contructor
	*/
	public function __construct($model, $view)
	{
		$this->model = $model;
		$this->view = $view;
	}
	/*Class destructor
	*/
	public function __destruct()
	{
		$this->model = null;
		$this->view = null;
	}
	/*Loads left navigation panel*/
	public function loadLeftNavPanel()
	{
		$this->view->leftNavPanel();
	}

	/*Loads top navigation panel*/
	public function loadTopNavPanelBy($top)
	{
		switch ($top) {
			case 'actor':
				$actor = $this->model->selectAllactor();
				$this->view->topNavPanelActor($actor);
				break;
			case 'director':
				$director = $this->model->selectAlldirector();
				$this->view->topNavPanelDirector($director);
				break;
			case 'genre':
				$genre = $this->model->selectAllgenre();
				$this->view->topNavPanelGenre($genre);
				break;
			case 'classification':
				$this->view->topNavPanelClassification();
				break;

			default:
				$title = $this->model->selectAllTitle();
				$director = $this->model->selectAlldirector();
				$actor = $this->model->selectAllactor();
				$genre = $this->model->selectAllgenre();
				$studio = $this->model->selectAllstudio();
				if (($director != null) && ($actor != null) && ($genre != null) && ($studio != null) && ($title != null)) {
					$this->view->topNavPanel($director, $actor, $genre, $studio, $title);
				} else {
					$error = $this->model->getError();
					if (!empty($error))
						$this->view->showError($error);
				}
				break;
		}
	}
	/*Processes user requests and call the corresponding functions
	  The request and data are submitted via POST or GET methods
	*/
	/*Loads top navigation panel*/
	public function loadTopNavPanel()
	{
		$title = $this->model->selectAllTitle();
		$director = $this->model->selectAlldirector();
		$actor = $this->model->selectAllactor();
		$genre = $this->model->selectAllgenre();
		$studio = $this->model->selectAllstudio();
		if (($director != null) && ($actor != null) && ($genre != null) && ($studio != null) && ($title != null)) {
			$this->view->topNavPanel($director, $actor, $genre, $studio, $title);
		} else {
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);
		}
	}
	/*Processes user requests and call the corresponding functions
	  The request and data are submitted via POST or GET methods
	*/
	public function processRequest($request)
	{
		$top = '';
		switch ($request) {
			case CMD_SHOW_TOP_NAV:
				$this->loadTopNavPanel();
				break;
			case CMD_SHOW_TOP_ACTOR:
				$top = 'actor';
				$this->loadTopNavPanelBy($top);
				break;
			case CMD_SHOW_TOP_DIRECTOR:
				$top = 'director';
				$this->loadTopNavPanelBy($top);
				break;
			case CMD_SHOW_TOP_GENRE:
				$top = 'genre';
				$this->loadTopNavPanelBy($top);
				break;
			case CMD_SHOW_TOP_CLASSIFICATION:
				$top = 'classification';
				$this->loadTopNavPanelBy($top);
				break;
			case CMD_MOVIE_SELECT_ALL:
				$this->handleSelectAllMovieRequest();
				break;
			case CMD_MOVIE_SELECT_TITLE:
				$this->handleSelectTitleMovieRequest();
				break;
			case CMD_MOVIE_SELECT_RANDOM:
				$this->handleSelectRandomMovieRequest();
				break;
			case CMD_MOVIE_FILTER:
				$this->handleFilterMovieRequest();
				break;
			case CMD_MOVIE_NEW_RELEASE:
				$this->handleMovieNewRelease();
			default:
				$this->handleSelectRandomMovieRequest();
				break;
		}
	}
	/*Handles select all movie request
	*/
	private function handleSelectAllMovieRequest()
	{
		$movie = $this->model->selectAllmovie();
		if ($movie != null) {
			$this->view->showmovie($movie);
		} else {
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);
		}
	}
	/*Handles select movie by title
	*/
	private function handleSelectTitleMovieRequest()
	{
		if (!empty($_REQUEST['title']))
			$movie = $this->model->selectByTitle($_REQUEST['title']);
		else $movie = $this->model->selectAllmovie();
		if ($movie != null) {
			$this->view->showmovie($movie);
		} else {
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);
		}
	}
	/*Handles select random movie request
	*/
	private function handleSelectRandomMovieRequest()
	{
		$movie = $this->model->selectRandomMovie(MAX_RANDOM_movie);
		if ($movie != null) {
			$this->view->showmovie($movie);
		} else {
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);
		}
	}
	private function handleMovieNewRelease()
	{
		$movie = $this->model->selectNewRelease(MAX_RANDOM_movie);
		if ($movie != null) {
			$this->view->showmovie($movie);
		} else {
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);
		}
	}
	/*Handles filter movie request
	*/
	private function handleFilterMovieRequest()
	{
		$condition = array();
		if (!empty($_REQUEST['director']))
			$condition['director_id'] = $_REQUEST['director']; //submitted is director id and not director name
		if (!empty($_REQUEST['actor']))
			$condition['actor_id'] = $_REQUEST['actor']; //submitted is actor id and not actor name
		if (!empty($_REQUEST['genre']))
			$condition['genre_id'] = $_REQUEST['genre']; //submitted is genre id and not genre name
		if (!empty($_REQUEST['studio']))
			$condition['studio_id'] = $_REQUEST['studio']; //submitted is studio id and not studio name
		if (!empty($_REQUEST['classification']))
			$condition['classification'] = $_REQUEST['classification'];
		//call the dbAdapter function
		$movie = $this->model->filtermovie($condition);
		if ($movie != null) {
			$this->view->showmovie($movie);
		} else {
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);
		}
	}
}
