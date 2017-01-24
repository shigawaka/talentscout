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
// Route::get('register/verify/{confirmationCode}', [
//     'as' => 'confirmation_path',
//     'uses' => 'RegistrationController@confirmGroup'
// ]);
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
Route::get('/sendinvitation', 'ChikkaController@send');
//about with policy
Route::get('/terms', function () {
    return view('terms');
});
//group to prevent links being visited by guest users
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
//endorse
Route::get('/connection/{id}', 'HomeController@showConnection');
Route::get('/removeEndorsement/{id}', 'HomeController@removeEndorsement');
//invitation
Route::get('/invitation/{id}', 'HomeController@showInvitations');
Route::get('/invitation/accept/{id}', 'HomeController@acceptInvitation');
Route::get('/invitation/decline/{id}', 'HomeController@declinePostInvitation');
Route::get('/groupinvitation/accept/{id}', 'HomeController@acceptGroupInvitation');
//schedule
Route::get('/schedule/{id}', 'HomeController@showSchedule');
Route::post('/addschedule/{id}', 'HomeController@addSchedule');
Route::post('/deleteschedule', 'HomeController@deleteSchedule');
//rate scout
Route::post('/ratescout/{id}/{postid}', 'HomeController@rateScout');
//add group member
Route::get('/addmembers/', 'HomeController@addMember');
Route::get('/savemember/{id}', 'HomeController@saveMember');
//remove/leave member/group 
Route::get('/removeMember/{id}', 'HomeController@removeMember');
Route::get('/leaveGroup/{id}', 'HomeController@leaveGroup');
//join group
Route::get('/joinGroup/{id}', 'HomeController@joinGroup');
//add talent
Route::post('/addtalent/{id}', 'HomeController@addTalent');
//homepage
Route::get('/homescout', 'HomeController@show');
Route::get('/hometalent', 'HomeController@show');
//about
Route::get('/about', function () {
    return view('about');
});
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
Route::get('/deletecomment/{id}', 'HomeController@deleteComment');
//proposal
Route::post('/addProposal', 'HomeController@addProposal');
Route::post('/editProposal', 'HomeController@editProposal');

//portfolio
Route::get('/portfolio/{id}', 'HomeController@showPortfolio');
Route::post('/addPortfolio', 'HomeController@addPortfolio');
//notification
Route::get('/readNotifications', 'HomeController@readAllNotifications');

//paypal
Route::post('/payment', array(
    'as' => 'payment',
    'uses' => 'PaypalController@postPayment',
));
Route::get('/paymentprocess', function () {
    return view('payment');
});
// this is after make the payment, PayPal redirect back to your site
Route::get('/payment/status', array(
    'as' => 'payment.status',
    'uses' => 'PaypalController@getPaymentStatus',
));
//admin
Route::get('/featured', 'HomeController@showFeatured');
Route::get('/removefeaturedprofile/{id}', 'HomeController@removeFeatureProfile');
Route::get('/approvePayment/{id}', 'HomeController@approvePayment');
Route::get('/deletePost/{id}', 'HomeController@deletePost');
Route::get('/removefeaturedfeedback/{id}', 'HomeController@removeFeatureFeedback');
Route::get('/searchUserFeaturedProfile/', 'HomeController@searchUserFeaturedProfile');
Route::post('/addFeaturedProfile', 'HomeController@addFeaturedProfile');
Route::post('/addFeaturedFeedback', 'HomeController@addFeaturedFeedback');

}); //end group route

Route::filter('auth', function()
{
    if (Auth::guest()) return Redirect::to('/login');
});