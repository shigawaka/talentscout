<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/home', function () {
	if (Auth::check()) {
		if(Session::get('roleID') == 0){
		return Redirect::to('/homescout');
		}
		else {
		return Redirect::to('/hometalent');
		}
	}
	else {
    return view('home');
	}
});
Route::get('/', function () {
    if (Auth::check()) {
		if(Session::get('roleID') == 0){
		return Redirect::to('/homescout');
		}
		else {
		return Redirect::to('/hometalent');
		}
	}
	else {
    return view('home');
	}
});

Route::get('/scoutregister', function () {
    return view('scout.register');
});

Route::get('/talentregister', function () {
    return view('talent.register');
});
Route::get('/forgotpassword', function () {
    return view('forgotpassword');
});
//email activation
Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'RegistrationController@confirm'
]);
Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'RegistrationController@confirmGroup'
]);
//reset password
Route::post('/resetpassword', 'RegistrationController@resetpassword');
Route::get('reset/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'RegistrationController@resetpasswordCode'
]);
//login
Route::get('/login', function () {
	if (Auth::check()) {
		if(Session::get('roleID') == 0){
		return Redirect::to('/homescout');
		}
		else {
		return Redirect::to('/hometalent');
		}
	}
	else {
    return view('login');
	}
});
Route::post('/login', 'HomeController@doLogin');
//logout
Route::get('/logout', 'Auth\AuthController@getLogout');

//Register
Route::post('/talentregister', 'RegistrationController@store');
Route::post('/talentregisterGroup', 'RegistrationController@storeGroup');
Route::post('/scoutregister', 'RegistrationController@storeScout');
//group
Route::group(array('before' => 'auth'), function()
{	

//posts
Route::get('/post/{id}', 'ScoutController@showPost');
Route::get('/post', 'ScoutController@show');
Route::post('/post', 'ScoutController@sortPost');
Route::post('/addpost', 'ScoutController@addPost');
Route::post('/editpost', 'ScoutController@editpost');
Route::post('/closepost/{id}', 'ScoutController@closePost');
Route::post('/sortpost', 'ScoutController@sortPost');
//hire
Route::get('/hire/{id}', 'ScoutController@hire');
//profile
Route::get('/profile/invite/{id}', 'ScoutController@inviteTalent');
Route::get('/profile/{id}', 'HomeController@showProfile');
Route::post('/profile/edit/{id}', 'HomeController@editProfile');
//invitation
Route::get('/invitation/{id}', 'HomeController@showInvitations');
Route::get('/invitation/accept/{id}', 'HomeController@acceptInvitation');
//rate scout
Route::post('/ratescout/{id}/{postid}', 'HomeController@rateScout');
//add talent
Route::post('/addtalent/{id}', 'HomeController@addTalent');
//homepage
Route::get('/homescout', 'HomeController@show');
Route::get('/hometalent', 'HomeController@show');
//email
Route::get('/send', 'EmailController@send');

//search
Route::post('/search', 'HomeController@searchTalent');
Route::get('/search', function () {
    return view('scout.searchtalent');
});
Route::post('/searchscout', 'HomeController@searchScout');
Route::get('/searchscout', function () {
    return view('talent.searchscout');
});
Route::get('/view', function () {
    return view('scout.viewpost');
});
//comment
Route::post('/addComment', 'HomeController@addComment');
//proposal
Route::post('/addProposal', 'HomeController@addProposal');
Route::post('/editProposal', 'HomeController@editProposal');
});

Route::filter('auth', function()
{
    if (Auth::guest()) return Redirect::to('/login');
});