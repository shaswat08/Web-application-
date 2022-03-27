/*Use onload event to load the page with random cars
*/
window.addEventListener("load", function(){
    makeAjaxGetRequest('moviezone_main.php', 'cmd_movie_select_random', null, updateContent);
	//show the top navigation panel
	document.getElementById('id_topnav').style.display = "none";
});

/*Handles onchange event to filter the car database
*/
function movieFilterActor() {
	var actor = document.getElementById('id_actor').value;
	let params='';
	if (actor != 'all')
		params += '&actor=' + actor;
	makeAjaxGetRequest('moviezone_main.php', 'cmd_movie_filter', params, updateContent);
}
function movieFilterDirector() {
	var director = document.getElementById('id_director').value;
	let params='';
	if (director != 'all')
	params += '&director=' + director;
	makeAjaxGetRequest('moviezone_main.php', 'cmd_movie_filter', params, updateContent);
}
function movieFilterGenre() {
	var genre = document.getElementById('id_genre').value;
	let params='';
	if (genre != 'all')
		params += '&genre=' + genre;
	makeAjaxGetRequest('moviezone_main.php', 'cmd_movie_filter', params, updateContent);
}
function movieFilterClassification() {
	var classification = document.getElementById('id_classification').value;
	let params='';
	if (classification != 'all')
	params += '&classification=' + classification;
	makeAjaxGetRequest('moviezone_main.php', 'cmd_movie_filter', params, updateContent);
}
function movieFilterChanged() {
	var director = document.getElementById('id_director').value;
	var actor = document.getElementById('id_actor').value;
	var genre = document.getElementById('id_genre').value;
	var studio = document.getElementById('id_studio').value;
	var classification = document.getElementById('id_classification').value;

	var params = '';
	if (director != 'all')
		params += '&director=' + director;
	if (actor != 'all')
		params += '&actor=' + actor;
	if (genre != 'all')
		params += '&genre=' + genre;
	if (studio != 'all')
		params += '&studio=' + studio;
	if (classification != 'all')
		params += '&classification=' + classification;
	makeAjaxGetRequest('moviezone_main.php', 'cmd_movie_filter', params, updateContent);
}

function movieSelectTitle() {
	var title = document.getElementById('title').value;
	var params = '';
	if (title != 'all')
		params += '&title=' + title;
	makeAjaxGetRequest('moviezone_main.php', 'cmd_movie_select_title', params, updateContent);
}
/*Handles show all movies onlick event to show all movies
*/
function movieShowAllClick() {	
	makeAjaxGetRequest('moviezone_main.php','cmd_movie_select_all', null, updateContent);
	//hide the top navigation panel
	document.getElementById('id_topnav').style.display = "none";
}

/*Handles filter movies onclick event to filter movies
*/
function movieSearchByActor() {
	//load the navigation panel on demand
	var x = document.getElementById("id_topnav");
	makeAjaxGetRequest('moviezone_main.php', 'cmd_show_top_actor', null, updateTopNav);
	document.getElementById('id_content').innerHTML = "";

}
function movieSearchByDirector() {
	//load the navigation panel on demand
	var x = document.getElementById("id_topnav");
	makeAjaxGetRequest('moviezone_main.php', 'cmd_show_top_director', null, updateTopNav);
	document.getElementById('id_content').innerHTML = "";

 
}
function movieSearchByGenre() {
	//load the navigation panel on demand
	var x = document.getElementById("id_topnav");
	makeAjaxGetRequest('moviezone_main.php', 'cmd_show_top_genre', null, updateTopNav);
	document.getElementById('id_content').innerHTML = "";

 
}
function movieSearchByClassification() {
	//load the navigation panel on demand
	var x = document.getElementById("id_topnav");
	makeAjaxGetRequest('moviezone_main.php', 'cmd_show_top_classification', null, updateTopNav);
	document.getElementById('id_content').innerHTML = "";


}
/*Handles filter movies onclick event to filter movies
*/
function movieFilterClick() {
	//load the navigation panel on demand
	var x = document.getElementById("id_topnav");
//   if (x.style.display === "none") {
	makeAjaxGetRequest('moviezone_main.php', 'cmd_show_top_nav', null, updateTopNav);
//   } else {
//     x.style.display = "none";
//   }
	
}
/*Handles filter movies onclick event to show latest release
 */
function movieNewRelease(){
	makeAjaxGetRequest('moviezone_main.php', 'cmd_movie_new_release', null, updateContent);
	document.getElementById('id_topnav').style.display = "none";
}

/*Updates the content area if success
*/
function updateContent(data) {
	document.getElementById('id_content').innerHTML = data;
}
/*Updates the top navigation panel
*/
function updateTopNav (data) {
	var topnav = document.getElementById('id_topnav');
	topnav.innerHTML = data;
	topnav.style.display = "inherit";
}
