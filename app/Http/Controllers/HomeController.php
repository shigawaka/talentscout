<?php

namespace App\Http\Controllers;

use Request;
use Redirect;
use Validator;
use Calendar;
use Response;
use DB;
use File;


use App\Scout;
use App\Featured;
use App\User;
use App\Talent;
use App\Post;
use App\Rating;
use App\Comment;
use App\Proposal;
use App\Invitation;
use App\EventModel;
use App\Group;
use App\Endorse;
use App\Notification;
use App\Paypalpayment;
use App\Portfolio;
use App\Subscription;
use App\Groupmember;
use App\TalentDetail;
use App\ScoutDetail;
use App\VaultCreditCard;

use Hash;
use Auth;
use Session;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }
    public function showHome(){
        if (Auth::check()) {
         if(Session::get('first_login') == 1){
            Session::flash('message', 'You need to setup your profile!');
            return Redirect::to('http://localhost:8000/profile'.'/'.Session::get('id'));
         }
         else {
            if(Session::get('roleID') == 0){
            return Redirect::to('/homescout');
            }
            else {
            return Redirect::to('/hometalent');
            }
         }
            
    }
    else {
        $testi = Rating::where('testimonial_score', '=', 5)
                       ->where('testimonial_comment', '!=', '')
                       ->groupBy('user_id')
                       ->get();
        $testimonialarr = array();
        $i=0;
        if(count($testi) !== 0){
            foreach ($testi as $key => $value) {
                $user = User::find($value['user_id']);
                if($user !== null){
                    $testimonialarr[$i]['firstname'] = $user['firstname'];
                    $testimonialarr[$i]['lastname'] = $user['lastname'];
                    $testimonialarr[$i]['picture'] = $user['profile_image'];
                    $testimonialarr[$i]['score'] = $value['testimonial_score'];
                    $testimonialarr[$i]['comment'] = $value['testimonial_comment'];
                    $i++;
                }
            }
        }
        $profilearray = array();
        $i = 0;
        $profile = Featured::where('isProfile','=',1)->get();
        $slideshow = Featured::where('isFeedback', '=', 1)->get();
        foreach ($profile as $key => $value) {
            $userdetail = User::find($value['profile_id']);
            $checkexpiration = Carbon::parse($value['end_date']);
            if(!Carbon::now()->gte($checkexpiration)){
            $profilearray[$i]['id'] = $value['profile_id'];
            $profilearray[$i]['profile_image'] = $userdetail['profile_image'];
            $profilearray[$i]['firstname'] = $userdetail['firstname'];
            $profilearray[$i]['lastname'] = $userdetail['lastname'];
            $profilearray[$i]['profile_description'] = $userdetail['profile_description'];
            $profilearray[$i]['start_date'] = $value['start_date'];
            $profilearray[$i]['end_date'] = $value['end_date'];
            $i++;
            }
            else {
                //remove profile from db
                $rempro = Featured::where('profile_id', '=', $value['profile_id']);
                $rempro->delete();
            }
        }
        return view('home')
                ->with('profilearray', $profilearray)
                ->with('slideshow', $slideshow)
                ->with('testimonialarr', $testimonialarr);
        }
    }
    public function showHomedash(){
        if (Auth::check()) {
        if(Session::get('first_login') == 1){
            Session::flash('message', 'You need to setup your profile!');
            return Redirect::to('http://localhost:8000/profile'.'/'.Session::get('id'));
         }
         else {
            if(Session::get('roleID') == 0){
            return Redirect::to('/homescout');
            }
            else {
            return Redirect::to('/hometalent');
            }
         }
    }
    else {
        $testi = Rating::where('testimonial_score', '=', 5)
                       ->where('testimonial_comment', '!=', '')
                       ->groupBy('user_id')
                       ->get();
        $testimonialarr = array();
        $i=0;
        if(count($testi) !== 0){
            foreach ($testi as $key => $value) {
                $user = User::find($value['user_id']);
                if($user !== null){
                    $testimonialarr[$i]['firstname'] = $user['firstname'];
                    $testimonialarr[$i]['lastname'] = $user['lastname'];
                    $testimonialarr[$i]['picture'] = $user['profile_image'];
                    $testimonialarr[$i]['score'] = $value['testimonial_score'];
                    $testimonialarr[$i]['comment'] = $value['testimonial_comment'];
                    $i++;
                }
            }
        }
        $profile = Featured::where('isProfile','=',1)->get();
        $slideshow = Featured::where('isFeedback', '=', 1)->get();
        $profilearray = array();
        $i = 0;
        foreach ($profile as $key => $value) {
            $userdetail = User::find($value['profile_id']);
            $checkexpiration = Carbon::parse($value['end_date']);
            if(!Carbon::now()->gte($checkexpiration)){
            $profilearray[$i]['id'] = $value['profile_id'];
            $profilearray[$i]['profile_image'] = $userdetail['profile_image'];
            $profilearray[$i]['firstname'] = $userdetail['firstname'];
            $profilearray[$i]['lastname'] = $userdetail['lastname'];
            $profilearray[$i]['profile_description'] = $userdetail['profile_description'];
            $profilearray[$i]['start_date'] = $value['start_date'];
            $profilearray[$i]['end_date'] = $value['end_date'];
            $i++;
            }
            else {
                //remove from db
                $rempro = Featured::where('profile_id', '=', $value['profile_id']);
                $rempro->delete();
            }
        }
        return view('home')
            ->with('profilearray', $profilearray)
            ->with('slideshow', $slideshow)
            ->with('testimonialarr', $testimonialarr);
    }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show()
    {
        if(Session::get('roleID') == 1 || Session::get('roleID') == 2){
            $posts = Post::where('status', '=', 0)->orderBy('date_posted', 'desc')->get();
            $succ = Post::where('status', 1)->get();
            $slideshow = Featured::where('isFeedback', '=', 1)->get();
            $unreadNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 0)
                                            ->limit(6)
                                            ->get();
            $readNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 1)
                                            ->limit(6)
                                            ->get();
            // dd(count($posts));
            if(count($posts) < 0 ){
                $posts = null;
            }
            else {
                $postarr = array();
                $i = 0;
                //retrieve posts which matches the users talent criteria
                foreach ($posts as $key => $value) {
                    foreach (json_decode($value['tags'],true) as $tal) {
                       $temp = TalentDetail::where('talent_id', '=', Session::get('id'))
                                           ->where('talent', '=', $tal)
                                           ->get();
                        if(count($temp) > 0){
                            // dd(empty($postarr));
                            if(!empty($postarr)){
                                for ($j=0; $j < count($postarr); $j++) { 
                                    
                                    //check post if it has same id
                                    // $checkduplicate = array_search($value['id'], $postarr[$j]);
                                    if($value['id'] !== $postarr[$j]['id']){
                                        $postarr[$i]['id'] = $value['id'];
                                        $postarr[$i]['scout_id'] = $value['scout_id'];
                                        $postarr[$i]['scout_id'] = $value['scout_id'];
                                        $postarr[$i]['title'] = $value['title'];
                                        $postarr[$i]['description'] = $value['description'];
                                        $postarr[$i]['file'] = $value['file'];
                                        $postarr[$i]['description'] = $value['description'];
                                        $postarr[$i]['tags'] = $value['tags'];
                                        $postarr[$i]['age'] = $value['age'];
                                        $postarr[$i]['gender'] = $value['gender'];
                                        $postarr[$i]['budget'] = $value['budget'];
                                        $postarr[$i]['rate'] = $value['rate'];
                                        $postarr[$i]['group'] = $value['group'];
                                        $postarr[$i]['date_posted'] = $value['date_posted'];
                                        $postarr[$i]['status'] = $value['status'];
                                        $postarr[$i]['hire_id'] = $value['hire_id'];
                                        $postarr[$i]['start_date'] = $value['start_date'];
                                        $postarr[$i]['end_date'] = $value['end_date'];
                                        $i++;
                                    }
                                    //end
                                }
                            }
                            else {
                            $postarr[$i]['id'] = $value['id'];
                            $postarr[$i]['scout_id'] = $value['scout_id'];
                            $postarr[$i]['title'] = $value['title'];
                            $postarr[$i]['description'] = $value['description'];
                            $postarr[$i]['file'] = $value['file'];
                            $postarr[$i]['description'] = $value['description'];
                            $postarr[$i]['tags'] = $value['tags'];
                            $postarr[$i]['age'] = $value['age'];
                            $postarr[$i]['gender'] = $value['gender'];
                            $postarr[$i]['budget'] = $value['budget'];
                            $postarr[$i]['rate'] = $value['rate'];
                            $postarr[$i]['group'] = $value['group'];
                            $postarr[$i]['date_posted'] = $value['date_posted'];
                            $postarr[$i]['status'] = $value['status'];
                            $postarr[$i]['hire_id'] = $value['hire_id'];
                            $postarr[$i]['start_date'] = $value['start_date'];
                            $postarr[$i]['end_date'] = $value['end_date'];
                            $i++;
                            }
                        }
                    }
                }
                //end
            }
            $testi = Rating::where('testimonial_score', '=', 5)
                       ->where('testimonial_comment', '!=', '')
                       ->groupBy('user_id')
                       ->get();
                $testimonialarr = array();
                $i=0;
                if(count($testi) !== 0){
                    foreach ($testi as $key => $value) {
                        $user = User::find($value['user_id']);
                        if($user !== null){
                            $testimonialarr[$i]['firstname'] = $user['firstname'];
                            $testimonialarr[$i]['lastname'] = $user['lastname'];
                            $testimonialarr[$i]['picture'] = $user['profile_image'];
                            $testimonialarr[$i]['score'] = $value['testimonial_score'];
                            $testimonialarr[$i]['comment'] = $value['testimonial_comment'];
                            $i++;
                        }
                    }
                }
                $otherpost = Post::where('status', '=', 0)->get();
                $op = array();
                $i = 0;
                    // for ($j=0; $j < count($otherpost); $j++) { 
                    //     $find = array_search($otherpost[$j]['id'], array_column($postarr, 'id'));
                    //     if($find == false && array_search($postarr[$j]['id'], array_column($otherpost, 'id')) == false){
                    //         $op[$i]['id'] = $value['id'];
                    //         $op[$i]['scout_id'] = $value['scout_id'];
                    //         $op[$i]['title'] = $value['title'];
                    //         $op[$i]['description'] = $value['description'];
                    //         $op[$i]['file'] = $value['file'];
                    //         $op[$i]['description'] = $value['description'];
                    //         $op[$i]['tags'] = $value['tags'];
                    //         $op[$i]['age'] = $value['age'];
                    //         $op[$i]['gender'] = $value['gender'];
                    //         $op[$i]['budget'] = $value['budget'];
                    //         $op[$i]['rate'] = $value['rate'];
                    //         $op[$i]['group'] = $value['group'];
                    //         $op[$i]['date_posted'] = $value['date_posted'];
                    //         $op[$i]['status'] = $value['status'];
                    //         $op[$i]['hire_id'] = $value['hire_id'];
                    //         $op[$i]['start_date'] = $value['start_date'];
                    //         $op[$i]['end_date'] = $value['end_date'];
                    //         $i++;
                    //     }
                    // }
                foreach ($otherpost as $key => $value) {
                    $find = array_search($value['id'], array_column($postarr, 'id'));
                    if(count($op) == 0){
                        if($find == false){
                            $op[$i]['id'] = $value['id'];
                            $op[$i]['scout_id'] = $value['scout_id'];
                            $op[$i]['title'] = $value['title'];
                            $op[$i]['description'] = $value['description'];
                            $op[$i]['file'] = $value['file'];
                            $op[$i]['description'] = $value['description'];
                            $op[$i]['tags'] = $value['tags'];
                            $op[$i]['age'] = $value['age'];
                            $op[$i]['gender'] = $value['gender'];
                            $op[$i]['budget'] = $value['budget'];
                            $op[$i]['rate'] = $value['rate'];
                            $op[$i]['group'] = $value['group'];
                            $op[$i]['date_posted'] = $value['date_posted'];
                            $op[$i]['status'] = $value['status'];
                            $op[$i]['hire_id'] = $value['hire_id'];
                            $op[$i]['start_date'] = $value['start_date'];
                            $op[$i]['end_date'] = $value['end_date'];
                            $i++;
                        }
                    }
                    else {
                        dd('ok');
                        if($find == false && array_search($value['id'], array_column($op, 'id')) == false){
                            $op[$i]['id'] = $value['id'];
                            $op[$i]['scout_id'] = $value['scout_id'];
                            $op[$i]['title'] = $value['title'];
                            $op[$i]['description'] = $value['description'];
                            $op[$i]['file'] = $value['file'];
                            $op[$i]['description'] = $value['description'];
                            $op[$i]['tags'] = $value['tags'];
                            $op[$i]['age'] = $value['age'];
                            $op[$i]['gender'] = $value['gender'];
                            $op[$i]['budget'] = $value['budget'];
                            $op[$i]['rate'] = $value['rate'];
                            $op[$i]['group'] = $value['group'];
                            $op[$i]['date_posted'] = $value['date_posted'];
                            $op[$i]['status'] = $value['status'];
                            $op[$i]['hire_id'] = $value['hire_id'];
                            $op[$i]['start_date'] = $value['start_date'];
                            $op[$i]['end_date'] = $value['end_date'];
                            $i++;
                        }
                    }
                }
                dd($op);
            return view('talent.home')
                ->with('posts',$postarr)
                ->with('succ',$succ)
                ->with('otherpost', $otherpost)
                ->with('testimonialarr', $testimonialarr)
                ->with('unreadNotifications', $unreadNotifications)
                ->with('readNotifications', $readNotifications)
                ->with('slideshow', $slideshow);
        }
        elseif(Session::get('roleID') == 3) {
            $posts = Post::where('status', '=', 0)->orderBy('date_posted', 'desc')->get();
            $succ = Post::where('status', 1)->get();
            return view('admin.home')
                ->with('posts',$posts)
                ->with('succ',$succ);
        }
        else {
            $recomprofile = array();
            $counter = 0;
        $posts = Post::where('scout_id', Auth::user()->id)->orderBy('status', 'asc')->get();
        foreach ($posts as $key => $value) {
            foreach (json_decode($value['tags'],true) as $tal) {
                $temp = DB::table('users')
                            ->join('talent', function ($join){
                            $join->on('users.id', '=', 'talent.id');
                                 // dd($join);
                            })
                            ->join('talent_details', function ($join) use ($tal) {
                            $join->on('talent_details.talent_id', '=', 'users.id')
                                 ->where('talent_details.talent', '=', $tal);
                            })
                            ->get();
                            if(!empty($temp)){
                                foreach ($temp as $key) {
                                    if(!empty($recomprofile)){
                                        for ($i=0; $i < count($recomprofile); $i++) { 
                                            if($key->talent_id !== $recomprofile[$i]['id']){
                                                if($key->roleID == 2)
                                                    {
                                                        $gp = Group::find($key->talent_id);
                                                        $recomprofile[$counter]['id'] = $key->talent_id;
                                                        $recomprofile[$counter]['firstname'] = $gp['groupname'];
                                                        $recomprofile[$counter]['lastname'] = '';
                                                        $recomprofile[$counter]['gender'] = 'group';
                                                        $recomprofile[$counter]['profile_image'] = $key->profile_image;
                                                        $recomprofile[$counter]['profile_description'] = $key->profile_description;
                                                        $recomprofile[$counter]['score'] = $key->score;
                                                        $counter++;
                                                    }
                                                    else {
                                                        $recomprofile[$counter]['id'] = $key->talent_id;
                                                        $recomprofile[$counter]['firstname'] = $key->firstname;
                                                        $recomprofile[$counter]['lastname'] = $key->lastname;
                                                        $recomprofile[$counter]['gender'] = $key->gender;
                                                        $recomprofile[$counter]['age'] = $key->age;
                                                        $recomprofile[$counter]['profile_image'] = $key->profile_image;
                                                        $recomprofile[$counter]['profile_description'] = $key->profile_description;
                                                        $recomprofile[$counter]['score'] = $key->score;
                                                        $counter++;
                                                    }
                                            }
                                        }
                                    }
                                    else {
                                                    if($key->roleID == 2)
                                                    {
                                                        $gp = Group::find($key->talent_id);
                                                        $recomprofile[$counter]['id'] = $key->talent_id;
                                                        $recomprofile[$counter]['firstname'] = $gp['groupname'];
                                                        $recomprofile[$counter]['lastname'] = '';
                                                        $recomprofile[$counter]['gender'] = 'group';
                                                        $recomprofile[$counter]['profile_image'] = $key->profile_image;
                                                        $recomprofile[$counter]['profile_description'] = $key->profile_description;
                                                        $recomprofile[$counter]['score'] = $key->score;
                                                        $counter++;
                                                    }
                                                    else {
                                                        $recomprofile[$counter]['id'] = $key->talent_id;
                                                        $recomprofile[$counter]['firstname'] = $key->firstname;
                                                        $recomprofile[$counter]['lastname'] = $key->lastname;
                                                        $recomprofile[$counter]['gender'] = $key->gender;
                                                        $recomprofile[$counter]['age'] = $key->age;
                                                        $recomprofile[$counter]['profile_image'] = $key->profile_image;
                                                        $recomprofile[$counter]['profile_description'] = $key->profile_description;
                                                        $recomprofile[$counter]['score'] = $key->score;
                                                        $counter++;
                                                    }
                                    }
                                }
                            }
            }
        }
        $succ = Post::where('status', 1)->get();
        $profile = Featured::where('isProfile','=',1)->get();
        $profilearray = array();
        $unreadNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 0)
                                            ->limit(6)
                                            ->get();

        $readNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 1)
                                            ->limit(6)
                                            ->get();
        $i = 0;
        foreach ($profile as $key => $value) {
            $userdetail = User::find($value['profile_id']);
            $checkexpiration = Carbon::parse($value['end_date']);
            if(!Carbon::now()->gte($checkexpiration)){
            $profilearray[$i]['id'] = $value['profile_id'];
            $profilearray[$i]['profile_image'] = $userdetail['profile_image'];
            $profilearray[$i]['firstname'] = $userdetail['firstname'];
            $profilearray[$i]['lastname'] = $userdetail['lastname'];
            $profilearray[$i]['profile_description'] = $userdetail['profile_description'];
            $profilearray[$i]['start_date'] = $value['start_date'];
            $profilearray[$i]['end_date'] = $value['end_date'];
            $i++;
            }
            else {
                //remove from db
            }
        }
        $testi = Rating::where('testimonial_score', '=', 5)
                       ->where('testimonial_comment', '!=', '')
                       ->groupBy('user_id')
                       ->get();
                $testimonialarr = array();
                $i=0;
                if(count($testi) !== 0){
                    foreach ($testi as $key => $value) {
                        $user = User::find($value['user_id']);
                        if($user !== null){
                            $testimonialarr[$i]['firstname'] = $user['firstname'];
                            $testimonialarr[$i]['lastname'] = $user['lastname'];
                            $testimonialarr[$i]['picture'] = $user['profile_image'];
                            $testimonialarr[$i]['score'] = $value['testimonial_score'];
                            $testimonialarr[$i]['comment'] = $value['testimonial_comment'];
                            $i++;
                        }
                    }
                }
        // dd($recomprofile);
        return view('scout.home')
                ->with('posts',$posts)
                ->with('succ',$succ)
                ->with('recomprofile', $recomprofile)
                ->with('testimonialarr', $testimonialarr)
                ->with('unreadNotifications', $unreadNotifications)
                ->with('readNotifications', $readNotifications)
                ->with('profilearray', $profilearray);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
    public function doLogin()
    {
        // validate the info, create rules for the inputs
$rules = array(
    'username'    => 'required', // make sure the email is an actual email
    'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
);

// run the validation rules on the inputs from the form
$validator = Validator::make(Request::all(), $rules);
// if the validator fails, redirect back to the form
if ($validator->fails()) {
    return Redirect::to('/login')
        ->withErrors($validator) // send back all errors to the login form
        ->withInput(Request::except('password')); // send back the input (not the password) so that we can repopulate the form
} else {

    // create our user data for the authentication
    $data = Request::all();
    $userdata = array(
        'username'     => $data['username'],
        'password'  => $data['password'],
        'confirmed' => 1
    );
    // dd($userdata);
    // attempt to do the login
    if (Auth::attempt($userdata)) {

        // validation successful!
        // redirect them to the secure section or whatever
        // return Redirect::to('secure');
        // for now we'll just echo success (even though echoing in a controller is bad)
        $creds = User::where('id', '=', Auth::user()->id)->get();
        foreach ($creds as $cred) {
        Session::set('id', $cred['id']);
        Session::set('roleID', $cred['roleID']);
        if($cred['roleID'] == '2') {
                $groupcred = Group::where('id', '=', $cred['id'])->first();
                Session::set('groupname', $groupcred['groupname']);
                Session::set('birthday', $cred['birthday']);
                Session::set('contactno', $cred['contactno']);
                Session::set('address', $cred['address']);
                Session::set('emailaddress', $cred['emailaddress']);
                Session::set('groupname', $cred['groupname']);
                Session::set('username', $cred['username']);
                Session::set('profile_image', $cred['profile_image']);
                Session::set('profile_description', $cred['profile_description']);
                Session::set('first_login', $cred['first_login']);
                $card = VaultCreditCard::where('user_id', '=', $cred['id'])->first();
                if(!empty($card)){
                    Session::set('cc', 'activated');
                }
                else {
                    Session::set('cc', 'null');
                }
            }
        else {
        Session::set('firstname', $cred['firstname']);
        Session::set('lastname', $cred['lastname']);
        Session::set('birthday', $cred['birthday']);
        Session::set('contactno', $cred['contactno']);
        Session::set('address', $cred['address']);
        Session::set('emailaddress', $cred['emailaddress']);
        Session::set('groupname', $cred['groupname']);
        Session::set('username', $cred['username']);
        Session::set('profile_image', $cred['profile_image']);
        Session::set('profile_description', $cred['profile_description']);
        Session::set('first_login', $cred['first_login']);
        $card = VaultCreditCard::where('user_id', '=', $cred['id'])->first();
                if(!empty($card)){
                    Session::set('cc', 'activated');
                }
                else {
                    Session::set('cc', 'null');
                }
            }
        }
        $tf = Talent::find(Auth::user()->id);
        if(!empty($tf)){
        Session::set('talentfee', $tf['fee']);
        }
        if($cred['roleID'] == 1 || $cred['roleID'] == 2){
            //check if first time login
            if($cred['first_login'] == 1){
                Session::flash('message', 'You need to setup your profile! Setup the following: Talents, Talent fee, Address, link your card!');
                return Redirect::to('http://localhost:8000/profile'.'/'.$cred['id']);
            }
            else {

            return Redirect::to('http://localhost:8000/hometalent');
            }
        }
        elseif($cred['roleID'] == 3) {
            //check if first time login
            if($cred['first_login'] == 1){
                Session::flash('message', 'You need to setup your profile! Setup the following: Talents, Talent fee, Address, link your card!');
                return Redirect::to('http://localhost:8000/profile'.'/'.$cred['id']);
            }
            else {
            return Redirect::to('http://localhost:8000/hometalent');
            }
        }
        else {
        return Redirect::to('http://localhost:8000/homescout');
        }
            } else {        

        // validation not successful, send back to form 
        Session::flash('message', 'Error login credentials or account is still not activated. Check your email.');
        return Redirect::back()->withInput();

            }
        }
    }
    public function doLogout(){
        Auth::logout(); // log the user out of our application
        Session::flush(); //flush existing session
        return Redirect::to('/home'); // redirect the user to the login screen
    }
    public function readAllNotifications(){
        $read = Notification::where('user_id', '=', Auth::user()->id)
                            ->where('is_read','=', '0')
                            ->get();
        foreach ($read as $key => $value) {
            $value->is_read = 1;
            $value->save();
        }
        return Redirect::back();
    }
    public function addComment(){
        $data = Request::all();
        $rules = array(
            'comment' => 'required',
        );

        $message = array(
            'comment.required' => 'Please write something in the comment!',
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()) {
            if(Comment::where('body', $data['comment'])->first()) {
                return back()->withInput();
            } else {
                $detail = new Comment;
                $detail->id =null;
                $detail->user_id = Auth::user()->id;
                $detail->post_id = Session::get('post_id');
                $detail->body =$data['comment'];
                $detail->date_posted = new Carbon();
                $detail->save();

                $notification = new Notification;
                $notification->id = null;
                $getUserID = Post::find(Session::get('post_id'));
                $notification->user_id = $getUserID['scout_id'];
                $notification->subject = 'comment';
                $notification->body = Session::get('username').' has commented on your post. Titled: '.$getUserID['title'];
                $notification->object_id = Session::get('post_id');
                $notification->is_read = 0;
                $notification->sent_at = new Carbon();
                $notification->save();

                return Redirect::back();
            }
        } else {
            return Redirect::back()->withInput()->withErrors($validation);
        }
    }
    public function deleteComment($id){
        Comment::where('id', '=', $id)->delete();
                Session::flash('message', 'Comment successfully deleted!');
                return Redirect::back();
    }
    public function addProposal(){
        $data = Request::all();
        dd($data);
        $rules = array(
            'body' => 'required',
            'rate' => 'required',
        );

        $message = array(
            'body.required' => 'Please write something in the comment!',
            'rate.required' => 'Please write something in the comment!',
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()) {
            if(Proposal::where('user_id','=',Auth::user()->id)->where('post_id','=', Session::get('post_id'))->first()) {
                return back()->withInput();
            } else {
                $detail = new Proposal;
                $detail->id =null;
                $detail->user_id = Auth::user()->id;
                $detail->post_id = Session::get('post_id');
                if( Request::hasFile('file') ) {
                        $destinationPath = public_path().'/files/';
                        $file = Request::file('file');
                        $filename = str_random(10).".".$file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);
                        $detail->file = $filename;
                    }
                $detail->body =$data['body'];
                $detail->proposed_rate = $data['rate'];
                $detail->save();
                
                return Redirect::back();
            }
        } else {
            return Redirect::back()->withInput()->withErrors($validation);
        }
    }
    public function editProposal(){
         $data = Request::all();
         $new_proposal = Proposal::find(Session::get('proposal_id'));
         $new_proposal->body = $data['body'];
         $new_proposal->proposed_rate = $data['rate'];
         $new_proposal->save();
         Session::flash('message', 'Successfully Edited!');
         return Redirect::back();
    }
    public function showProfile($user_id){
            
            $findUser = User::find($user_id);
            $role = $findUser['roleID'];
            if($role == 1 ){
                $user = User::find($user_id);
                $user['contactno'] = preg_replace('/^63/','0',$user['contactno']);
                //finding group of the user
                $grouparray = array();
                $groups = json_decode($user['group'], true);
                for ($i=0; $i < count($groups); $i++) { 
                    $searchgroup = Group::find($groups[$i]);
                    $searchusergroup = User::find($groups[$i]);
                    $grouparray[$i]['id'] = $searchgroup['id'];
                    $grouparray[$i]['groupname'] = $searchgroup['groupname'];
                    $grouparray[$i]['profile_image'] = $searchusergroup['profile_image'];
                }
                //end finding group
                $birthday = date("F d,Y", strtotime($user['birthday']));
                $user['birthday'] = $birthday;
                $rating = Rating::where('user_id' , '=', $user_id)->get();
                $tal = Talent::find($user_id);
                $finalscore = 0;
                $finaldemerit = 0;
                if(!empty($rating)){
                    foreach ($rating as $key => $value) {
                        $finalscore += $value['score'];
                        $finaldemerit += $value['demerit'];
                    }
                }
                if(!empty($tal)) {

                    if($tal['score'] !== $finalscore){
                        $tal->score = $finalscore;
                    }
                    if($tal['demerit'] !== $finaldemerit){
                        $tal->demerit = $finaldemerit;
                    }
                    $tal->save();
                }
                //finding talents of  user
                $td = TalentDetail::where('talent_id', '=', $user_id)->get();
                return view('talent.profile')
                        ->with('rating', $rating)
                        ->with('user', $user)
                        ->with('fee', $tal)
                        ->with('td', $td)
                        ->with('grouparray', $grouparray)
                        ->with('talent', json_decode($tal['talents'], true));
            }
            elseif ($role == 2) {
                $user = User::find($user_id);
                
                $user['contactno'] = preg_replace('/^63/','0',$user['contactno']);
                $groupdetails = Group::find($user_id);
                $birthday = date("F d,Y", strtotime($user['birthday']));
                $user['birthday'] = $birthday;
                //finding group of the user
                $grouparray = array();
                $naccgrouparray = array();
                $groups = json_decode($groupdetails['member'], true);
                for ($i = 0; $i < count($groups); $i++) { 
                    $searchusergroup = User::find($groups[$i]);
                    $grouparray[$i]['id'] = $searchusergroup['id'];
                    $grouparray[$i]['fullname'] = $searchusergroup['firstname'].' '.$searchusergroup['lastname'];
                    $grouparray[$i]['profile_image'] = $searchusergroup['profile_image'];
                }
                //end finding group
                //start finding NACC group member
                $searchnacc = Groupmember::where('group_id', '=', $user_id)->get();
                $i = 0;
                foreach ($searchnacc as $key => $value) {
                    $naccgrouparray[$i]['id'] = $value['id'];
                    $naccgrouparray[$i]['fullname'] = $value['member'];
                    $naccgrouparray[$i]['profile_image'] = 'avatar.png';
                    $i++;
                }
                //end finding nacc group member
                //check if talent sent a request to join in the group
                $checkinvitation = Invitation::where('inviter_id', '=', Session::get('id'))
                                            ->where('talent_id', '=', $user_id)
                                            ->get();
                $request = null;
                if(count($checkinvitation) >= 1){
                    $request = true;
                }
                else{
                    $request = false;
                }
                //end check sent request
                $rating = Rating::where('user_id' , '=', $user_id)->get();
                $tal = Talent::find($user_id);
                $finalscore = 0;
                $finaldemerit = 0;
                if(!empty($rating)){
                    foreach ($rating as $key => $value) {
                        $finalscore += $value['score'];
                        $finaldemerit += $value['demerit'];
                    }
                }
                if(!empty($tal)) {

                    if($tal['score'] !== $finalscore){
                        $tal->score = $finalscore;
                    }
                    if($tal['demerit'] !== $finaldemerit){
                        $tal->demerit = $finaldemerit;
                    }
                    $tal->save();
                }
                $td = TalentDetail::where('talent_id', '=', $user_id)->get();
                return view('talent.profilegroup')
                        ->with('rating', $rating)
                        ->with('user', $user)
                        ->with('td', $td)
                        ->with('groupdetails', $groupdetails)
                        ->with('grouparray', $grouparray)
                        ->with('naccgrouparray', $naccgrouparray)
                        ->with('fee', $tal)
                        ->with('talent', json_decode($tal['talents'], true));
            }
            else {
            $user = User::find($user_id);
                $birthday = date("F d,Y", strtotime($user['birthday']));
                $user['birthday'] = $birthday;
                $rating = Scout::find($user_id);
                $getRate = Rating::where('user_id' , '=', $user_id)->get();
                $finalscore = 0;
                $finaldemerit = 0;
                if(!empty($getRate)){
                    foreach ($getRate as $key => $value) {
                        $finalscore += $value['score'];
                        $finaldemerit += $value['demerit'];
                    }
                }
                if($rating['score'] !== $finalscore){
                    $rating->score = $finalscore;
                }
                if($rating['demerit'] !== $finaldemerit){
                    $rating->demerit = $finaldemerit;
                }
                $rating->save();
                // dd($rating);
                $td = ScoutDetail::where('scout_id', '=', $user_id)->get();
                return view('scout.profile')
                        ->with('rating', $rating)
                        ->with('td', $td)
                        ->with('user', $user);
            }
    }
    public function editProfile($user_id){
        $data = Request::except('_token');
        $new_profile = User::find($user_id);
        $old_profile = User::find($user_id);
        if($new_profile['roleID'] == '2'){
                $rules = array(
                    'lastname' => 'regex:/^[\pL\s\-]+$/u',
                    'firstname' => 'regex:/^[\pL\s\-]+$/u',
                    'birthday' => 'required',
                    'contact' => 'numeric|regex:/(09)[0-9]{9}/',
                    'emailaddress' => 'unique:users',
                    'username' => 'unique:users|min:5',
                    'password' => 'alphaNum|min:6',
                    'image' => 'max:1500|mimes:png,jpeg,jpg',
                );
        }
        else {

        $rules = array(
            'lastname' => 'regex:/^[\pL\s\-]+$/u',
            'firstname' => 'regex:/^[\pL\s\-]+$/u',
            'birthday' => 'before:2011-01-01',
            'contact' => 'numeric|regex:/(09)[0-9]{9}/',
            'emailaddress' => 'unique:users',
            'address' => 'required',
            'username' => 'unique:users|min:5',
            'password' => 'alphaNum|min:6',
            'image' => 'max:1500|mimes:png,jpeg,jpg',
        );
        }

        $message = array(
            'lastname.regex' => 'Letters only',
            'firstname.regex' => 'Letters only',
            'birthday.required' => 'Required',
            'contact.required' => 'Required',
            'contact.numeric' => 'Numbers only',
            'email.required' => 'Required',
            'username.required' => 'Required',
            'password.required' => 'Required',
            'birthday.before' => 'You must be 5 years older and above!',
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()) {
            //if initial address is empty save it to session for future use
            $new_profile->address = $data['address'];
            Session::set('address', $data['address']);
            $new_profile->contactno = $data['contactno'];
            //if new username is not empty and not the old username then save it else use the old one
            if($new_profile['username'] !== $data['username'] &&  !empty($data['username'])){
            $new_profile->username = $data['username'];
            }
            else {
                $old_profile->username = $old_profile['username'];
            }
            //if new emailaddress is not empty and not the old emailaddress then save it else use the old one
            if($new_profile['emailaddress'] !== $data['emailaddress'] &&  !empty($data['emailaddress'])) {
            $new_profile->emailaddress = $data['emailaddress'];
            }
            else {
                $old_profile->username = $old_profile['emailaddress'];
            }
            if($data['description'] !== null) {
                $new_profile->profile_description = $data['description'];
            }
            $new_profile->birthday = date("Y-m-d", strtotime($data['birthday']));
            if( Request::hasFile('image') ) {
                        $destinationPath = public_path().'/files/';
                        $file = Request::file('image');
                        $filename = str_random(10).".".$file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);
                        $new_profile->profile_image = $filename;
                    }
            if(!empty($data['password'])) {
            $new_profile->password = Hash::make($data['password']);
            }
            $new_profile->save();
            //determine if scout then the fee field is empty
                if (Session::get('roleID') !== 0) {
                    
                    $new_fee = Talent::find($user_id);
                    if(!empty($new_fee)){
                    $new_fee->fee = $data['fee'];
                    Session::set('talentfee', $data['fee']);
                    $new_fee->save();
                    }
                    else {
                        $new_fee = new Talent;
                        $new_fee->id = $user_id;
                        $new_fee->fee = $data['fee'];
                        $new_fee->save();
                    }
                }
            Session::flash('message', 'Successfully edited profile!');
            return Redirect::back();
        
            
        }
        else {
            return Redirect::back()->withInput()->withErrors($validation);
        }
            
    }
    public function addTalent($user_id){
        $data = Request::except('_token');

        $rules = array(
            //reserve
        );

        $message = array(
            //reserve
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()) {
            for ($i=0; $i < count($data['category']); $i++) { 
            $td = TalentDetail::where('talent_id', '=', $user_id)
                                ->where('category', '=', $data['category'][$i])
                                ->where('talent', '=', $data['talent'][$i])
                                ->get();
                if(count($td) !== 0){
                    Session::flash('duplicate', 'Duplicate Entry!');
                    return Redirect::back();
                }
            }
               for ($i=0; $i < count($data['category']); $i++) { 
                    if($data['category'][$i] !== 'Select Category' || $data['talent'][$i] !== 'Select talent'){
                        //every loop checking for db for duplicate entry entered by the user
                        $td = TalentDetail::where('talent_id', '=', $user_id)
                                ->where('category', '=', $data['category'][$i])
                                ->where('talent', '=', $data['talent'][$i])
                                ->get();
                            if(count($td) == 0){
                                $newtd = new TalentDetail;
                            $newtd->id = null;
                            $newtd->talent_id = $user_id;
                            $newtd->category = $data['category'][$i];
                            $newtd->talent = $data['talent'][$i];
                            $newtd->save();
                            }
                    }
                }
                            Session::flash('message', 'Successfully added talent!');
                            return Redirect::back();
        } else {
            return Redirect::back()->withInput()->withErrors($validation);
        }
    }
    public function addScoutTalent($user_id){
        $data = Request::except('_token');

        $rules = array(
            //reserve
        );

        $message = array(
            //reserve
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()) {
            for ($i=0; $i < count($data['category']); $i++) { 
            $td = ScoutDetail::where('talent_id', '=', $user_id)
                                ->where('category', '=', $data['category'][$i])
                                ->where('talent', '=', $data['talent'][$i])
                                ->get();
                if(count($td) !== 0){
                    Session::flash('duplicate', 'Duplicate Entry!');
                    return Redirect::back();
                }
            }
               for ($i=0; $i < count($data['category']); $i++) { 
                    if($data['category'][$i] !== 'Select Category' || $data['talent'][$i] !== 'Select talent'){
                        //every loop checking for db for duplicate entry entered by the user
                        $td = ScoutDetail::where('talent_id', '=', $user_id)
                                ->where('category', '=', $data['category'][$i])
                                ->where('talent', '=', $data['talent'][$i])
                                ->get();
                            if(count($td) == 0){
                                $newtd = new ScoutDetail;
                            $newtd->id = null;
                            $newtd->talent_id = $user_id;
                            $newtd->category = $data['category'][$i];
                            $newtd->talent = $data['talent'][$i];
                            $newtd->save();
                            }
                    }
                }
                            Session::flash('message', 'Successfully added talent!');
                            return Redirect::back();
        } else {
            return Redirect::back()->withInput()->withErrors($validation);
        }
    }
    public function removeTalent(){
        $data = Request::all();
        TalentDetail::find($data['tal_cal'])->delete();
        return $data;
    }
    public function showInvitations($user_id) {
        $hired = 0;
                $user = User::find($user_id);
                $birthday = date("F d,Y", strtotime($user['birthday']));
                $user['birthday'] = $birthday;
                $rating = Rating::find($user_id);
                $tal = Talent::find($user_id);
                $invitation = Invitation::where('talent_id', '=', $user_id)->get();
                //group invitation
                $groupinvitation = array();
                $counter = 0;
                foreach ($invitation as $k => $v) {
                    if($v['post_id'] == null){
                        $groupdetails = Group::where('id', '=', $v['inviter_id'])->first();
                        $groupdetails2 = User::where('id', '=', $v['inviter_id'])->first();
                        $groupinvitation[$counter]['id'] = $groupdetails['id'];
                        $groupinvitation[$counter]['groupname'] = $groupdetails['groupname'];
                        $groupinvitation[$counter]['picture'] = $groupdetails2['profile_image'];
                        $groupinvitation[$counter]['description'] = $groupdetails2['profile_description'];
                        $counter++;
                    }
                }
                //end group invitation
                $counter = 0;
                $temparr = array();
                $userdetails = array();
                //post invitation
                foreach ($invitation as $key => $val) {
                    $postDetails = Post::where('id', '=', $val['post_id'])->get();
                    foreach ($postDetails as $key => $value) {
                        $temparr[$counter]['id'] = $value['id'];
                        $temparr[$counter]['scout_id'] = $value['scout_id'];
                        $temparr[$counter]['title'] = $value['title'];
                        $temparr[$counter]['description'] = $value['description'];
                        $temparr[$counter]['file'] = $value['file'];
                        $temparr[$counter]['tags'] = $value['tags'];
                        $temparr[$counter]['budget'] = $value['budget'];
                        $temparr[$counter]['rate'] = $value['rate'];
                        $temparr[$counter]['date_posted'] = $value['date_posted'];
                        $temparr[$counter]['status'] = $value['status'];
                        $temparr[$counter]['hire_id'] = $value['hire_id'];
                        $temparr[$counter]['start_date'] = $value['start_date'];
                        $temparr[$counter]['end_date'] = $value['end_date'];
                        $temparr[$counter]['start_date'] = $value['start_date'];
                        $findscout = User::find($value['scout_id']);
                        $temparr[$counter]['scout_id'] = $findscout['id'];
                        $temparr[$counter]['roleID'] = $findscout['roleID'];
                        $temparr[$counter]['firstname'] = $findscout['firstname'];
                        $temparr[$counter]['lastname'] = $findscout['lastname'];
                        $temparr[$counter]['profile_image'] = $findscout['profile_image'];
                        if(!empty($value['hire_id'])){
                            $hired = in_array($user_id, json_decode($value['hire_id']));
                            if($hired){
                                $hired = $user_id;
                            }
                            else {
                                $hired = 0;
                            }
                        }
                        $counter++;
                    }
                }
                // end post invitation;
                return view('talent.invitation')
                        ->with('rating', $rating)
                        ->with('user', $user)
                        ->with('fee', $tal)
                        ->with('hired', $hired)
                        ->with('group', $groupinvitation)
                        ->with('postDetails', $temparr)
                        ->with('talent', json_decode($tal['talents'], true));
            
    }
    public function showSchedule($user_id) {
            if($user_id != Session::get('id')){
                return Redirect::to('home');
            }
            else
                $user = User::find($user_id);
                $events = [];

                $event = EventModel::where('user_id', '=', $user_id)->get();
                foreach ($event as $eve) {
                    if($eve->isAllDay == 0) {
                        $eve->isAllDay = true;
                    } 
                    else {
                        $eve->isAllDay = false;
                    }
                  $events[] = \Calendar::event(
                  $eve->title, //event title
                  $eve->isAllDay, //full day event?
                  $eve->start_date, //start time (you can also use Carbon instead of DateTime)
                  $eve->end_date, //end time (you can also use Carbon instead of DateTime)
                  $eve->id //optionally, you can specify an event ID
                  );
                }

                // $eloquentEvent = EventModel::first(); //EventModel implements MaddHatter\LaravelFullcalendar\Event

                $calendar = \Calendar::addEvents($events) //add an array with addEvents
                    ->setOptions([ //set fullcalendar options
                        'firstDay' => 1
                    ])->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)
                        'eventClick' => 'function(event) { 
                            id= event.id; 
                            $("#dialog").dialog({
                                  resizable: false,
                                  draggable: false,
                                  height:200,
                                  width:500,
                                  modal: true,
                                  title: event.title,
                                  buttons: {
                                             CLOSE: function() {
                                                 $("#dialog").dialog( "close" );
                                             },
                                             "YES": function() {

                                                $.ajax({
                                                            method: "POST", // Type of response and matches what we said in the route
                                                            url: "/deleteschedule", // This is the url we gave in the route
                                                            data: {id : id}, // a JSON object to send back
                                                            success: function(response){ // What to do if we succeed
                                                                console.log(response);
                                                                alert("Successfully Deleted Event!");
                                                                //location.reload();
                                                            },
                                                            error: function(response){
                                                                console.log(response);
                                                            }
                                                        });
                                                
                                             }
                                           }

                            });


                        }',
                        // "dayClick" => "function() { 
                        //      $('#modal1').openModal();
                        // }",


                        
                    ]); 
                return view('schedule', compact('calendar'))
                        ->with('user', $user);
            
    }
    public function deleteSchedule(Request $request){
        $eventID = Request::all('id');
        $eventDetail = EventModel::find($eventID)->first();

        //find out if it's not an event made by the current User by finding post_id not equal to 0.
        if ($eventDetail['post_id'] !== 0) {
            $post = Post::find($eventDetail['post_id']);
            $today = Carbon::now();
            $days = $today->diffInDays(Carbon::parse($post['start_date']));
            
            $findHireID = json_decode($post['hire_id'],true);
            //if the user is not a scout then no need to notify the talents and just notify the scout.
            if(Session::get('roleID') !== 0){
                $position = array_search($eventDetail['user_id'], $findHireID);
                if($position !== false) {
                    //if start date of the event starts in 3 or 
                        if($days <= 3 && $today->eq(Carbon::parse($post['start_date']))){
                            PaypalController::penalizeTalent($post['scout_id']);
                            $notification = new Notification;
                            $notification->id = null;
                            $notification->user_id = $post['scout_id'];
                            $notification->subject = 'invitation';
                            $notification->body = 'Hello '.Session::get('username').'! This is to inform that you recently violated the Terms and Policy of the site: Cancelling the event 3 days prior to the start date of event. You have been penalized.';
                            $notification->object_id = $post['id'];
                            $notification->is_read = 0;
                            $notification->sent_at = new Carbon();
                            $notification->save();
                        }

                        $notification = new Notification;
                        $notification->id = null;
                        $notification->user_id = $post['scout_id'];
                        $notification->subject = 'invitation';
                        $notification->body = Session::get('username').' has cancelled his participation on event entitled: '.$post['title'];
                        $notification->object_id = $post['id'];
                        $notification->is_read = 0;
                        $notification->sent_at = new Carbon();
                        $notification->save();



                        unset($findHireID[$position]);
                        $post->hire_id = json_encode($findHireID);
                        $post->save();

                }
            }
            //if the user is a scout then notify 
            else {

            }
            
        }
        //it is customized or not connected to any post/events thus safe to delete without any penalties.
        else {
            $eventDetail->delete();
            Session::flash('message', 'Successfully deleted event!');
        return Redirect::to('/schedule/'.Session::get('id'));
        }
        
    }
    public function addSchedule(Request $request, $user_id){
        $data = Request::all();
        if($data['allday'] == 'False'){
             $start_date = Carbon::parse($data['start_date'].$data['start_time']);
             $end_date = Carbon::parse($data['end_date'].$data['end_time']);
        }
        else{
             $start_date = Carbon::parse($data['start_date']);
             $end_date = Carbon::parse($data['end_date']);
        }
       

        $rules = array(
            'title' => 'required|regex:/^[\pL\s\-]+$/u',
            'start_date' => 'required|date', //before:tomorrow
            'end_date' => 'required|date', //before:tomorrow
            'end_time' => 'after:start_time',
        );

        $message = array(
            'start_date.before' => 'Invalid date input!',
            'end_date.before' => 'Invalid date input!',
            'end_time.after' => 'Invalid end time input!',
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()) {
            if(EventModel::where('start_date', $start_date)->first()) {
                return back()->withInput();
            } else {
                $detail = new EventModel;

                $detail->id =null;
                $detail->user_id =$user_id;
                if($data['allday'] == 'False'){
                    $data['allday'] = 0;
                }
                else {
                    $data['allday'] = 1;
                }
                $detail->isAllDay =$data['allday'];
                $detail->title =$data['title'];
                $detail->post_id = 0;
                $detail->start_date =$start_date->format('Y-m-d G:i:s');
                $detail->end_date =$end_date->format('Y-m-d G:i:s');
                $detail->save();

                Session::flash('message', 'Successfully added Event!');
                return Redirect::back();
            }
        } else {
            return Redirect::back()->withInput()->withErrors($validation);
        }
    }
    public function acceptInvitation($post_id) {
        $invited = Invitation::where('post_id', '=', $post_id)
                              ->where('talent_id', '=', Session::get('id'))->first();
        $postDetails = Post::find($post_id);
            $postStartdate = Carbon::parse($postDetails['start_date']);
            $postEnddate = Carbon::parse($postDetails['end_date']);
        $schedule = EventModel::where('user_id','=', Session::get('id'))->get();
        foreach ($schedule as $key => $value) {
            $schedStartdate = Carbon::parse($value['start_date']);
            $schedEnddate = Carbon::parse($value['end_date']);
            $today = Carbon::now();
            //check if the start date is not greater than today's date
            if($today->gt($postStartdate)) {
                Session::flash('message', 'Invalid start date! Event is already starting or done!');
                return Redirect::back();
            }
            else {
                if(!$postStartdate->gte($schedEnddate)){
                    Session::flash('message', 'You are not available in that event day!');
                    return Redirect::back();
                }
                elseif($postStartdate->eq($schedStartdate)){ //check same day
                    if($value['isAllDay'] == '0'){
                    Session::flash('message', 'You are not available in that event day!');
                    return Redirect::back();
                    }
                }
            }
                
        }
        if(!empty($invited)){
            $invited->status = 1;
            $invited->save();
            $newSchedule = new EventModel;
            $newSchedule->id = null;
            $newSchedule->user_id = Session::get('id');
            $newSchedule->title = $postDetails['title'];
            $newSchedule->isAllDay = 0;
            $newSchedule->start_date = $postStartdate;
            $newSchedule->end_date = $postEnddate;
            $newSchedule->post_id = $post_id;
            $newSchedule->save();

            $hire = Post::find($post_id);
            if(empty($hire['hire_id'])){
                $hire->hire_id = json_encode(explode(',', Session::get('id')));
            }
            else {
                $getHire = json_decode($hire['hire_id'], true);
                // dd(implode(',',$getHire));
                array_push($getHire, Session::get('id'));
                // $getHire[] = json_encode(explode(',', $talent_id[0]));
                // $test = json_encode(explode(',', $getHire));
                // dd(implode(',',$getHire));
                $hire->hire_id = json_encode($getHire);
            }
            // $hire->hire_id = json_encode(explode(',', $talent_id[0]));
            $hire->save();

            $notification = new Notification;
                    $notification->id = null;
                    $notification->user_id = $postDetails['scout_id'];
                    $notification->subject = 'comment';
                    $notification->body = Session::get('username').' has accepted your invitation on event entitled: '.$postDetails['title'];
                    $notification->object_id = $postDetails['id'];
                    $notification->is_read = 0;
                    $notification->sent_at = new Carbon();
                    $notification->save();
            return Redirect::back();
            
        }
    }
    public function declinePostInvitation($post_id){
        $data = Invitation::where('post_id', '=', $post_id)->delete();
        return Redirect::back();
    }
    public function acceptGroupInvitation($id){
        $group = Group::where('id', '=', $id)->first();
        $user = User::where('id', '=', Session::get('id'))->first();
        if(empty($group['member']) || empty($user['group'])){
            $member = json_encode(explode(',', Session::get('id')));
            $group->member = $member;
            $user->group = json_encode(explode(',', $id));
        }
        else {
            $member = json_decode($group['member'], true);
            array_push($member, Session::get('id'));
            $group->member = json_encode($member);
            $groupmember = json_decode($user['group'], true);
            array_push($groupmember, $id);
            $user->group = json_encode($groupmember);
        }
        $group->save();
        $user->save();
        $invitation = Invitation::where('inviter_id', '=', $id)->delete();
        Session::flash('message', 'Successfully joined group!');
        return Redirect::back();
    }
    public function showSearchTalent(){
        $unreadNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 0)
                                            ->limit(6)
                                            ->get();
            $readNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 1)
                                            ->limit(6)
                                            ->get();
        $arr = array();
        return view('scout.searchtalent')
                ->with('result', $arr)
                ->with('unreadNotifications', $unreadNotifications)
                ->with('readNotifications', $readNotifications);
    }
    public function searchTalent(){
        $data = Request::except('_token');
        $unreadNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 0)
                                            ->limit(6)
                                            ->get();
            $readNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 1)
                                            ->limit(6)
                                            ->get();
        $keyword = strtolower($data['search']);
        //categorize talents
        // $guitar = array('acoustic', 'guitar', 'wood guitar','acoustic guitar');
        // $bass = array('bass', 'acoustic bass',);
        // $dancing = array('dancing', 'dance', 'hip-hop', 'modern dance', 'dance troupe', 'dancers', 'dancer');
        // $drum = array('drum', 'drummer', 'drummer band', 'marching band');
        // $vocalist = array('singing', 'singer', 'songer', 'sing', 'vocalist', 'choir', 'vocal');

        $searchUser = User::where('roleID', '=', '1')
                          ->orWhere('roleID', '=', '2')
                          ->orWhere('roleID','=','0')->get();
        $arr = array();
        $counter = 0;
        if(!empty($data['search']) && !empty($data['fee']) && !empty($data['category'])) {
            foreach ($searchUser as $key => $value) {
            if($value['id'] !== Session::get('id')){
                if($value['roleID'] == '2'){
                    $group = Group::find($value['id']);
                    if(stripos(strtolower($group['groupname']), strtolower($keyword)) !== false){
                    $fee = Talent::where('id', '=', $value['id'])->where('fee', '<=', $data['fee'])->first();
                    // dd($fee);
                    if(!empty($fee)){
                        $arr[$counter]['fee'] = $fee['fee'];
                      }
                    else {
                        $arr[$counter]['fee'] = 'Unavailable';
                         }
                    $arr[$counter]['id'] = $value['id'];
                    $arr[$counter]['groupname'] = $group['groupname'];
                    $arr[$counter]['profile_image'] = $value['profile_image'];
                    $arr[$counter]['profile_description'] = $value['profile_description'];
                    $rank = Rating::where('user_id', '=', $value['id'])->first();
                    if(!empty($rank)){
                        $arr[$counter]['rank'] = $rank['score'];
                      }
                    else {
                        $arr[$counter]['rank'] = 0;
                      }
                    $counter++;
                    }
                }
                else {
                    if(stripos(strtolower($value['firstname']), strtolower($keyword)) !== false || stripos(strtolower($value['lastname']), strtolower($keyword)) !== false){
                    $fee = Talent::where('id', '=', $value['id'])
                                ->where('fee', '<=', $data['fee'])
                                ->first();
                    if(!empty($fee)){
                        $arr[$counter]['fee'] = $fee['fee'];
                      }
                    else {
                        $arr[$counter]['fee'] = 'Unavailable';
                         }
                    $td = TalentDetail::where('talent_id', '=', $fee['id'])
                                     ->where('category', '=', $data['category'])
                                     ->get();
                    if(count($td) !== 0){
                        $arr[$counter]['id'] = $value['id'];
                        $arr[$counter]['firstname'] = $value['firstname'];
                        $arr[$counter]['lastname'] = $value['lastname'];
                        $arr[$counter]['profile_image'] = $value['profile_image'];
                        $arr[$counter]['profile_description'] = $value['profile_description'];
                        $rank = Rating::where('user_id', '=', $value['id'])->first();
                        if(!empty($rank)){
                            $arr[$counter]['rank'] = $rank['score'];
                          }
                        else {
                            $arr[$counter]['rank'] = 0;
                          }
                        $counter++;
                    }
                    
                    }
                }
            }
          }
        }
        elseif(!empty($data['search']) && !empty($data['fee'])) {
            foreach ($searchUser as $key => $value) {
                 if($value['id'] !== Session::get('id')){
                    if($value['roleID'] == '2'){
                        $group = Group::find($value['id']);
                        if(stripos(strtolower($group['groupname']), strtolower($keyword)) !== false){
                        $fee = Talent::where('id', '=', $value['id'])->where('fee', '<=', $data['fee'])->first();
                        if(!empty($fee)){
                            $arr[$counter]['fee'] = $fee['fee'];
                        }
                        else {
                            $arr[$counter]['fee'] = 'Unavailable';
                        }
                        $arr[$counter]['id'] = $value['id'];
                        $arr[$counter]['groupname'] = $group['groupname'];
                        $arr[$counter]['profile_image'] = $value['profile_image'];
                        $arr[$counter]['profile_description'] = $value['profile_description'];
                        $rank = Rating::where('user_id', '=', $value['id'])->first();
                        if(!empty($rank)){
                            $arr[$counter]['rank'] = $rank['score'];
                        }
                        else {
                            $arr[$counter]['rank'] = 0;
                        }
                        $counter++;
                        }
                    }
                    else {
                        if(stripos(strtolower($value['firstname']), strtolower($keyword)) !== false || stripos(strtolower($value['lastname']), strtolower($keyword)) !== false){
                        $fee = Talent::where('id', '=', $value['id'])->where('fee', '<=', $data['fee'])->first();
                        if(!empty($fee)){
                            $arr[$counter]['fee'] = $fee['fee'];
                        }
                        else {
                            $arr[$counter]['fee'] = 'Unavailable';
                        }
                        $arr[$counter]['id'] = $value['id'];
                        $arr[$counter]['firstname'] = $value['firstname'];
                        $arr[$counter]['lastname'] = $value['lastname'];
                        $arr[$counter]['profile_image'] = $value['profile_image'];
                        $arr[$counter]['profile_description'] = $value['profile_description'];
                        $rank = Rating::where('user_id', '=', $value['id'])->first();
                        if(!empty($rank)){
                            $arr[$counter]['rank'] = $rank['score'];
                        }
                        else {
                            $arr[$counter]['rank'] = 0;
                        }
                        $counter++;
                        }
                    }
                }
          }
        }
        elseif (!empty($data['search'])) {
        foreach ($searchUser as $key => $value) {
            if($value['id'] !== Session::get('id')){
                if($value['roleID'] == '2'){
                    $group = Group::find($value['id']);
                    if(stripos(strtolower($group['groupname']), strtolower($keyword)) !== false){
                    $arr[$counter]['id'] = $value['id'];
                    $arr[$counter]['groupname'] = $group['groupname'];
                    $arr[$counter]['profile_image'] = $value['profile_image'];
                    $arr[$counter]['profile_description'] = $value['profile_description'];
                    $rank = Rating::where('user_id', '=', $value['id'])->first();
                    if(!empty($rank)){
                        $arr[$counter]['rank'] = $rank['score'];
                    }
                    else {
                        $arr[$counter]['rank'] = 0;
                    }
                    $counter++;
                    // dd($arr[0]['groupname']);
                    }
                }
                else {
                    if(stripos(strtolower($value['firstname']), strtolower($keyword)) !== false || stripos(strtolower($value['lastname']), strtolower($keyword)) !== false){
                    $arr[$counter]['id'] = $value['id'];
                    $arr[$counter]['firstname'] = $value['firstname'];
                    $arr[$counter]['lastname'] = $value['lastname'];
                    $arr[$counter]['profile_image'] = $value['profile_image'];
                    $arr[$counter]['profile_description'] = $value['profile_description'];
                    $rank = Rating::where('user_id', '=', $value['id'])->first();
                    if(!empty($rank)){
                        $arr[$counter]['rank'] = $rank['score'];
                    }
                    else {
                        $arr[$counter]['rank'] = 0;
                    }
                    $counter++;
                    }
                }
            }
          }
        }
        elseif(empty($data['search']) && !empty($data['fee'])) {
            foreach ($searchUser as $key => $value) {
            $fee = Talent::where('id', '=', $value['id'])
                         ->where('fee', '<=', $data['fee'])->first();
            if(!empty($fee)){
                $arr[$counter]['fee'] = $fee['fee'];
                $arr[$counter]['id'] = $value['id'];
                $arr[$counter]['firstname'] = $value['firstname'];
                $arr[$counter]['lastname'] = $value['lastname'];
                $arr[$counter]['profile_image'] = $value['profile_image'];
                $arr[$counter]['profile_description'] = $value['profile_description'];
            }
            else {
                $arr[$counter]['fee'] = 'Unavailable';
                $arr[$counter]['id'] = $value['id'];
                $arr[$counter]['firstname'] = $value['firstname'];
                $arr[$counter]['lastname'] = $value['lastname'];
                $arr[$counter]['profile_image'] = $value['profile_image'];
                $arr[$counter]['profile_description'] = $value['profile_description'];
            }
            $rank = Rating::where('user_id', '=', $value['id'])->first();
            if(!empty($rank)){
                $arr[$counter]['rank'] = $rank['score'];
            }
            else {
                $arr[$counter]['rank'] = 0;
            }
            $counter++;
          }
        }
        elseif($data['category'] !== 'Select Category'){
            foreach ($searchUser as $key => $value) {
            $fee = TalentDetail::where('talent_id', '=', $value['id'])
                         ->where('category', '=', $data['category'])
                         ->get();
            if(count($fee) !== 0){
                if($value['roleID'] == 2){
                    $gro = Group::find($value['id']);
                    $arr[$counter]['id'] = $value['id'];
                    $arr[$counter]['firstname'] = $gro['groupname'];
                    $arr[$counter]['lastname'] = '';
                    $arr[$counter]['profile_image'] = $value['profile_image'];
                    $arr[$counter]['profile_description'] = $value['profile_description'];
                    $rank = Rating::where('user_id', '=', $value['id'])->first();
                    if(!empty($rank)){
                        $arr[$counter]['rank'] = $rank['score'];
                    }
                    else {
                        $arr[$counter]['rank'] = 0;
                    }
                    $counter++;
                }
                else {
                    $arr[$counter]['id'] = $value['id'];
                    $arr[$counter]['firstname'] = $value['firstname'];
                    $arr[$counter]['lastname'] = $value['lastname'];
                    $arr[$counter]['profile_image'] = $value['profile_image'];
                    $arr[$counter]['profile_description'] = $value['profile_description'];
                    $rank = Rating::where('user_id', '=', $value['id'])->first();
                    if(!empty($rank)){
                        $arr[$counter]['rank'] = $rank['score'];
                    }
                    else {
                        $arr[$counter]['rank'] = 0;
                    }
                    $counter++;
                }
            }
            
          }
        }
        // dd($arr);
        arsort($arr);
        $endorsed = Endorse::where('endorser_id', '=', Session::get('id'))->get();
        $endorsedarr = array();
        $i = 0;
        if(count($endorsed)  !== 0){
            foreach ($endorsed as $key => $value) {
                $endorsedarr[$i] = $value['endorsed_id'];
                $i++;
            }
        }
        // dd($endorsedarr);
        
        return view('scout.searchtalent')
                ->with('result', $arr)
                ->with('endorsedarr', $endorsedarr)
                ->with('unreadNotifications', $unreadNotifications)
                ->with('readNotifications', $readNotifications);
    }
    public function showSearchScout(){
        $unreadNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 0)
                                            ->limit(6)
                                            ->get();
            $readNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 1)
                                            ->limit(6)
                                            ->get();
        $arr = array();
        return view('talent.searchscout')
                ->with('result', $arr)
                ->with('unreadNotifications', $unreadNotifications)
                ->with('readNotifications', $readNotifications);
    }
    public function searchScout(){
        $data = Request::except('_token');
        $keyword = strtolower($data['search']);
        $searchUser = User::where('roleID', '=', '1')
                          ->orWhere('roleID', '=', '2')
                          ->orWhere('roleID','=','0')->get();
        $searchPost = Post::all();
        $arr = array();
        $counter = 0;
        $unreadNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 0)
                                            ->limit(6)
                                            ->get();
            $readNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 1)
                                            ->limit(6)
                                            ->get();
        // if(!empty($data['search']) && $data['category'] !== null && $data['category'] === 'post'){
            
        // }
        if(!empty($data['search']) && $data['category'] !== 'Select Category') {
            foreach ($searchUser as $key => $value) {
                if($value['id'] !== Session::get('id')){
                if($value['roleID'] == '2'){
                    $group = Group::find($value['id']);
                    if(stripos(strtolower($group['groupname']), strtolower($keyword)) !== false){
                    $td = TalentDetail::where('talent_id', '=', $group['id'])
                                      ->where('category', '=', $data['category'])
                                      ->get();
                    if(count($td) !== 0){
                         $arr[$counter]['id'] = $value['id'];
                        $arr[$counter]['groupname'] = $group['groupname'];
                        $arr[$counter]['profile_image'] = $value['profile_image'];
                        $arr[$counter]['profile_description'] = $value['profile_description'];
                        $rank = Rating::where('user_id', '=', $value['id'])->first();
                        if(!empty($rank)){
                            $arr[$counter]['rank'] = $rank['score'];
                          }
                        else {
                            $arr[$counter]['rank'] = 0;
                          }
                        $counter++;
                        }
                    }
                   
                }
                else {
                    if(stripos(strtolower($value['firstname']), strtolower($keyword)) !== false || stripos(strtolower($value['lastname']), strtolower($keyword)) !== false){
                    $td = TalentDetail::where('talent_id', '=', $value['id'])
                                      ->where('category', '=', $data['category'])
                                      ->get();
                    if(count($td) !== 0){
                         $arr[$counter]['id'] = $value['id'];
                        $arr[$counter]['groupname'] = $group['groupname'];
                        $arr[$counter]['profile_image'] = $value['profile_image'];
                        $arr[$counter]['profile_description'] = $value['profile_description'];
                        $rank = Rating::where('user_id', '=', $value['id'])->first();
                        if(!empty($rank)){
                            $arr[$counter]['rank'] = $rank['score'];
                          }
                        else {
                            $arr[$counter]['rank'] = 0;
                          }
                        $counter++;
                        }
                    }
            }
          }
        }
    }
        // elseif(!empty($data['search']) && !empty($data['category'])) {
        //     foreach ($searchUser as $key => $value) {
        //     if(stripos(strtolower($value['firstname']), strtolower($keyword)) !== false || stripos(strtolower($value['lastname']), strtolower($keyword)) !== false){
        //     $fee = Talent::where('id', '=', $value['id'])->where('fee', '<=', $data['fee'])->first();
        //     if(!empty($fee)){
        //         $arr[$counter]['fee'] = $fee['fee'];
        //     }
        //     else {
        //         $arr[$counter]['fee'] = 'Unavailable';
        //     }
        //     $arr[$counter]['id'] = $value['id'];
        //     $arr[$counter]['firstname'] = $value['firstname'];
        //     $arr[$counter]['lastname'] = $value['lastname'];
        //     $arr[$counter]['profile_image'] = $value['profile_image'];
        //     $arr[$counter]['profile_description'] = $value['profile_description'];
        //     $rank = Rating::where('user_id', '=', $value['id'])->first();
        //     if(!empty($rank)){
        //         $arr[$counter]['rank'] = $rank['score'];
        //     }
        //     else {
        //         $arr[$counter]['rank'] = 0;
        //     }
        //     $counter++;
        //     }
        //   }
        // }
        elseif (!empty($data['search'])) {
        foreach ($searchUser as $key => $value) {
            if($value['id'] !== Session::get('id')){
                if($value['roleID'] == '2'){
                    $group = Group::find($value['id']);
                    if(stripos(strtolower($group['groupname']), strtolower($keyword)) !== false){
                    $arr[$counter]['id'] = $value['id'];
                    $arr[$counter]['groupname'] = $group['groupname'];
                    $arr[$counter]['profile_image'] = $value['profile_image'];
                    $arr[$counter]['profile_description'] = $value['profile_description'];
                    $rank = Rating::where('user_id', '=', $value['id'])->first();
                    if(!empty($rank)){
                        $arr[$counter]['rank'] = $rank['score'];
                    }
                    else {
                        $arr[$counter]['rank'] = 0;
                    }
                    $counter++;
                    }
                }
                else {
                    if(stripos(strtolower($value['firstname']), strtolower($keyword)) !== false || stripos(strtolower($value['lastname']), strtolower($keyword)) !== false){
                    $arr[$counter]['id'] = $value['id'];
                    $arr[$counter]['firstname'] = $value['firstname'];
                    $arr[$counter]['lastname'] = $value['lastname'];
                    $arr[$counter]['profile_image'] = $value['profile_image'];
                    $arr[$counter]['profile_description'] = $value['profile_description'];
                    $rank = Rating::where('user_id', '=', $value['id'])->first();
                    if(!empty($rank)){
                        $arr[$counter]['rank'] = $rank['score'];
                    }
                    else {
                        $arr[$counter]['rank'] = 0;
                    }
                    $counter++;
                    }
                }
            }
          }
        }
        // elseif(empty($data['search']) && !empty($data['category'])) {
        //     foreach ($searchUser as $key => $value) {
        //     $fee = Talent::where('id', '=', $value['id'])
        //                  ->where('fee', '<=', $data['fee'])->first();
        //     if(!empty($fee)){
        //         $arr[$counter]['fee'] = $fee['fee'];
        //         $arr[$counter]['id'] = $value['id'];
        //         $arr[$counter]['firstname'] = $value['firstname'];
        //         $arr[$counter]['lastname'] = $value['lastname'];
        //         $arr[$counter]['profile_image'] = $value['profile_image'];
        //         $arr[$counter]['profile_description'] = $value['profile_description'];
        //     }
        //     else {
        //         $arr[$counter]['fee'] = 'Unavailable';
        //         $arr[$counter]['id'] = $value['id'];
        //         $arr[$counter]['firstname'] = $value['firstname'];
        //         $arr[$counter]['lastname'] = $value['lastname'];
        //         $arr[$counter]['profile_image'] = $value['profile_image'];
        //         $arr[$counter]['profile_description'] = $value['profile_description'];
        //     }
        //     $rank = Rating::where('user_id', '=', $value['id'])->first();
        //     if(!empty($rank)){
        //         $arr[$counter]['rank'] = $rank['score'];
        //     }
        //     else {
        //         $arr[$counter]['rank'] = 0;
        //     }
        //     $counter++;
        //   }
        // }
        arsort($arr);
        // dd($arr);
        return view('talent.searchscout')
                ->with('result', $arr)
                ->with('unreadNotifications', $unreadNotifications)
                ->with('readNotifications', $readNotifications);
    }

    public function addMember(){
        $term = Request::get('q');
    
        $results = array();
        $queries = User::where('firstname', 'like', '%'.$term.'%')
        ->where('roleID', '=', 1)
            ->orWhere('lastname', 'like', '%'.$term.'%')
            ->take(5)->get();
        foreach ($queries as $query)
        {
            $invitation = Invitation::where('talent_id', '=', $query->id)
                                    ->where('inviter_id', '=', Session::get('id'))
                                    ->first();
            if(empty($invitation)){
                $checkgroup = Group::find(Session::get('id'));
                $checkgroupmember = json_decode($checkgroup['member'], true);
                for ($i=0; $i < count($checkgroupmember); $i++) { 
                    if($checkgroupmember[$i] == $query->id){
                        $results[] = [ 'id' => $query->id, 'value' => $query->firstname.' '.$query->lastname, 'picture' => $query->profile_image, 'invited' => 'Talent is already a member!' ];
                    }
                    else {
                        $results[] = [ 'id' => $query->id, 'value' => $query->firstname.' '.$query->lastname, 'picture' => $query->profile_image, 'invited' => 'Already invited' ];
                    }
                }
            }
            else {
                $checkgroup = Group::find(Session::get('id'));
                $checkgroupmember = json_decode($checkgroup['member'], true);
                for ($i=0; $i < count($checkgroupmember); $i++) { 
                    if($checkgroupmember[$i] == $query->id){
                        $results[] = [ 'id' => $query->id, 'value' => $query->firstname.' '.$query->lastname, 'picture' => $query->profile_image, 'invited' => 'Talent is already a member!' ];
                    }
                    else {
                        $results[] = [ 'id' => $query->id, 'value' => $query->firstname.' '.$query->lastname, 'picture' => $query->profile_image ];
                    }
                }
            
            
            }
        }
        if(count($results))
            return Response::json($results);
        else
            $results[] = [ 'id' => '', 'value' => 'No Result Found' ];
            return Response::json($results);
    }
    public function revealTalent(){
        $data = Request::all();
        $results = array();
        $entertainment = array('Clown', 'Magician', 'Trickster');
        $singing = array('Legit', 'Traditional Musical Theatre', 'Contemporary Musical Theatre', 'Modern Pop', 'Pop/Rock');
        $dancing = array('Ballet','Belly Dance','Break dance','Hip-hop','Line Dance', 'RnB', 'Salsa', 'Samba', 'Sarabande','Tap Dance','Traditional Dancing','Yongko Dance');
        if($data['tal_cal'] == "Select Category"){
            $results[0] = ['value' => 'Select talent'];
        }
        else {
            if($data['tal_cal'] == 'Dancing'){
                for ($i=0; $i < count($dancing); $i++) { 
                $results[$i] = ['value' => $dancing[$i]];
                }
            }
            elseif($data['tal_cal'] == 'Singing'){
                for ($i=0; $i < count($singing); $i++) { 
                $results[$i] = ['value' => $singing[$i]];
                }
            }
            elseif($data['tal_cal'] == 'Entertainment'){
                    for ($i=0; $i < count($entertainment); $i++) { 
                    $results[$i] = ['value' => $entertainment[$i]];
                    }
            }
            
        }
        return Response::json($results);
    }
    public function revealCategory(){
        $data = Request::all();
        $results = array();
        $cat = array('Select Category','Singing', 'Dancing', 'Entertainment');

        for ($i=0; $i < count($cat); $i++) { 
            $results[$i] = ['value' => $cat[$i]];
        }
        return Response::json($results);
    }
    public function searchUserFeaturedProfile() {
        $term = Request::get('q');
        
        $results = array();
        $queries = User::where('firstname', 'like', '%'.$term.'%')
        ->where('roleID', '=', 1)
            ->orWhere('lastname', 'like', '%'.$term.'%')
            ->take(5)->get();
        foreach ($queries as $query)
        {
            
            $results[] = [ 'id' => $query->id, 'value' => $query->firstname.' '.$query->lastname, 'picture' => $query->profile_image ];
            
        }
        if(count($results))
            return Response::json($results);
        else
            $results[] = [ 'id' => '', 'value' => 'No Result Found' ];
            return Response::json($results);
    }
    public function addNACCmember($name){
        $fullname = str_replace('_', ' ', $name);
        $mem = new Groupmember;
        $mem->id = null;
        $mem->group_id = Session::get('id');
        $mem->member = $fullname;
        $mem->save();
        Session::flash('message', 'Member added successfully!');
        return Redirect::back();
    }
    public function showPortfolio($id){
        $user = User::find($id);
        //retrieve portfolio in db
        $portfolio = Portfolio::where('user_id','=', $id)->get();
        foreach ($portfolio as $key => $value) {
            $value['file'] = json_decode($value['file']);
        }
        // dd($portfolio);
        //retrieve past experience in post table (if hired)
        $posts = Post::all();
        $hiredposts = array();
        $counter = 0;
        foreach ($posts as $key => $value) {
            $hireid = json_decode($value['hire_id']);
            if($hireid !== null){

                if(array_search($id, $hireid) !== false){
                    $findRating = Rating::where('post_id', '=', $value['id'])->first();
                    if($findRating !== null){
                        // dd($value['id']);
                    $checkpost = Rating::where('post_id', '=', $value['id'])->with('post')->get();
                    // ^ forgot what to do with this variable :|
                    }
                    $average_score = ceil($findRating['attitude'] + $findRating['performance'] + $findRating['punctuality'])/3;
                    // dd(json_decode($value['file']));
                    $hiredposts[$counter]['id'] = $value['id'];
                    $hiredposts[$counter]['event_name'] = $value['title'];
                    $hiredposts[$counter]['description'] = $value['description'];
                    $hiredposts[$counter]['file'] = $value['file'];
                    $hiredposts[$counter]['event_date'] = $value['start_date'];
                    $hiredposts[$counter]['score'] = (int)$average_score;
                    $hiredposts[$counter]['comment'] = $findRating['comment'];
                    $counter++;
                }
            }
        }

        
        return view('portfolio')
               ->with('user', $user)
               ->with('hiredposts', $hiredposts)
               ->with('portfolio', $portfolio);
    }
    public function addPortfolio(){
        $data = Request::all();
        $rules = array(
            // 'files' => 'max:200000|mimes:jpg,jpeg,png,bmp,mp4,ogg,mkv,avi',
        );
        

        $message = array(
            'files.max' => 'Sorry! Maximum upload limit is 200mb!',
            'files.mimes' => 'File format is not acceptable!',
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()){
            $detail = new Portfolio;
            $detail->id = null;
            $detail->user_id = Session::get('id');
            $detail->talent = $data['talent'];
            $detail->category = $data['category'];
            $detail->event_date = date("Y-m-d", strtotime($data['event_date']));
            $detail->description = $data['description'];
            $temparr = array();
            if( Request::hasFile('files') ) {
                        $destinationPath = public_path().'/files/';
                        // $file = Request::file('file');
                        for ($i=0; $i < count($data['files']); $i++) { 
                        $filename = str_random(10).".".$data['files'][$i]->getClientOriginalExtension();
                        $data['files'][$i]->move($destinationPath, $filename);
                        array_push($temparr, $filename);
                        }
                        $detail->file = json_encode($temparr);
                        // dd($detail->file);
                    }
            $detail->save();
            Session::flash('message', 'Added portfolio!');
            return Redirect::back();
        }
        else
        {
            return Redirect::back()->withInput()->withErrors($validation);
        }
    }
    public function removeMember($id){
        $group = Group::find(Session::get('id'));
        $member = json_decode($group['member'], true);
        foreach($member as $key => $value) {
               if($value == $id) {
                unset($member[$key]);
               }
            }
        if(empty($member)){
            $member = null;
        }
        else {
            $member = json_encode($member);
        }
        $group->member = $member;
        $group->save();
        //user leaves the group
        $user = User::find($id);
        $member = json_decode($user['group'], true);
        foreach($member as $key => $value) {
               if($value == Session::get('id')) {
                unset($member[$key]);
               }
            }
        if(empty($member)){
            $member = null;
        }
        else {
            $member = json_encode($member);
        }
        $user->group = $member;
        $user->save();
        Session::flash('message', 'Member removed!');
        return Redirect::back();
    }
    public function removeNACCmember($id){
        $mem = Groupmember::find($id);
        if($mem !== null){
            $mem->delete();
             Session::flash('message', 'Member removed!');
             return Redirect::back();
        }
    }
    public function leaveGroup($id){
        $user = User::find($id);
        $member = json_decode($user['group'], true);
            foreach($member as $key => $value) {
                   if($value == Session::get('id')) {
                    unset($member[$key]);
                   }
                }
            if(empty($member)){
                $member = null;
            }
            else {
                $member = json_encode($member);
            }
            $user->group = $member;
            $user->save();
        //remove the user from group

        $group = Group::find($id);
        $member = json_decode($group['member'], true);
        foreach($member as $key => $value) {
               if($value == $id) {
                unset($member[$key]);
               }
            }
        if(empty($member)){
            $member = null;
        }
        else {
            $member = json_encode($member);
        }
        $group->member = $member;
        $group->save();
        Session::flash('message', 'You successfully left the group!');
        return Redirect::back();
    }
    public function saveMember($user_id){
            $sendinvitation =  new Invitation;
            $sendinvitation->id = null;
            $sendinvitation->post_id = null;
            $sendinvitation->talent_id = $user_id;
            $sendinvitation->inviter_id = Session::get('id');
            $sendinvitation->status = 0;
            $sendinvitation->save();

             $notification = new Notification;
                $notification->id = null;
                $notification->user_id = $user_id;
                $notification->subject = 'invitation';
                $notification->body = Session::get('username').' has invited you to a group';
                $notification->object_id = Session::get('id');
                $notification->is_read = 0;
                $notification->sent_at = new Carbon();
                $notification->save();

            Session::flash('message', 'User successfully invited!');
            return Redirect::back();
    }
    public function joinGroup($group_id){
        $sendinvitation =  new Invitation;
            $sendinvitation->id = null;
            $sendinvitation->post_id = null;
            $sendinvitation->talent_id = $group_id;
            $sendinvitation->inviter_id = Session::get('id');
            $sendinvitation->status = 0;
            $sendinvitation->save();

             $notification = new Notification;
                $notification->id = null;
                $notification->user_id = $group_id;
                $notification->subject = 'invitation';
                $notification->body = Session::get('username').' has request to join in your group!';
                $notification->object_id = Session::get('id');
                $notification->is_read = 0;
                $notification->sent_at = new Carbon();
                $notification->save();

            Session::flash('message', 'You successfully sent a request to join this group!');
            return Redirect::back();
    }
    public function showFeatured(){
    $profile = Featured::where('isProfile','=',1)->get();
    $profilearray = array();
    $i = 0;
    foreach ($profile as $key => $value) {
        // dd($value);
        $userdetail = User::find($value['profile_id']);
        $profilearray[$i]['id'] = $value['id'];
        $profilearray[$i]['profile_image'] = $userdetail['profile_image'];
        $profilearray[$i]['firstname'] = $userdetail['firstname'];
        $profilearray[$i]['lastname'] = $userdetail['lastname'];
        $profilearray[$i]['start_date'] = $value['start_date'];
        $profilearray[$i]['end_date'] = $value['end_date'];
        $i++;
    }
    $feedbacks = Featured::where('isFeedback','=',1)->get();
    // dd($feedbacks);
    $payments = Paypalpayment::where('state', '=', 'pending')->get();
    // dd($payments);
    $subscription = Subscription::all();
    return view('admin.adminpanel')
            ->with('profile',$profile)
            ->with('feedbacks', $feedbacks)
            ->with('subscription', $subscription)
            ->with('payments', $payments)
            ->with('profilearray',$profilearray);
    }
    public function approvePayment($id){
        $data = Paypalpayment::find($id);
        $data->state = 'approved';
        $data->save();
        $start_date = Featured::where('profile_id', '=', $data['user_id'])->first();

        $start = Carbon::parse($start_date['start_date'])->format('F d, Y');
        //notifications
        $notification = new Notification;
                $notification->id = null;
                $notification->user_id = $data['user_id'];
                $notification->subject = 'comment';
                $notification->body = 'Your profile is now featured in the Homepage! For '.$data['duration'].' week! Starting '.$start.'';
                $notification->object_id = null;
                $notification->is_read = 0;
                $notification->sent_at = new Carbon();
                $notification->save();
            Session::flash('message', 'Successfully approved!');
            return Redirect::back();
    }
    public function addFeaturedProfile(){
        $data = Request::all();
        // dd($data);
        $start_date = Carbon::parse($data['start_date']);
        $end_date = $start_date->addWeeks($data['duration']);
        $checkstartdate = Carbon::now()->gt($start_date);
        if($checkstartdate){
            return Redirect::back()->withInput()->withErrors('Error input!');
        }
        else {
            $details = new Featured;
            $details->id = null;
            $details->isProfile = 1;
            $details->isFeedback = 0;
            $details->image = null;
            $details->profile_id = $data['invisible'];
            $details->start_date = $data['start_date'];
            $details->end_date = $end_date;
            $details->save();
            Session::flash('message', 'Successfully added!');
            return Redirect::back();
        }
    }
    public function showPaymentprocess(){
        $subscription = Subscription::all();
        return view('payment')
               ->with('subscription', $subscription);
    }
    public function addSubscription(){
        $data = Request::all();
        $sub = new Subscription;
        $sub->id = null;
        $sub->price = $data['price'];
        $sub->description = $data['description'];
        if( Request::hasFile('file') ) {
                        $destinationPath = public_path().'/images/';
                        $file = $data['file'];
                        $filename = str_random(10).".".$file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);
                        $sub->file = $filename;
                    }
        $sub->save();
        Session::flash('message','Successfully added!');
        return Redirect::back();
    }
    public function editSubscription(){
        $data = Request::all();
        $sub = Subscription::find($data['hiddenid']);
        if($sub !== null){
            $sub->price = $data['price'];
            $sub->description = $data['description'];
            if( Request::hasFile('file') ) {
                        $destinationPath = public_path().'/images/';
                        $file = $data['file'];
                        $filename = str_random(10).".".$file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);
                        $sub->file = $filename;
                    }
            $sub->save();
            Session::flash('message','Successfully edited!');
            return Redirect::back();
        }
        Session::flash('message','Something went wrong!');
        return Redirect::back();
    }
    public function deleteSubscription($id){
        $sub = Subscription::find($id);
        \File::Delete(public_path().'/images/'.$sub['file']);
        $sub->delete();
        Session::flash('message','Successfully deleted!');
        return Redirect::back();
    }
    public function addFeaturedFeedback(){
        $detail = new Featured;
        $detail->id = null;
        $detail->isProfile = 0;
        $detail->isFeedback = 1;
        if( Request::hasFile('file') ) {
                        $destinationPath = public_path().'/files/';
                        $file = Request::file('file');
                        $filename = str_random(10).".".$file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);
                        $detail->image = $filename;
        }
        $detail->profile_id = null;
        $details->start_date = null;
        $details->end_date = null;
        $detail->save();
        Session::flash('message', 'Successfully added!');
        return Redirect::back();
    }
    public function removeFeaturedProfile($id){
        Featured::find($id)->delete();
         Session::flash('message', 'Successfully deleted!');
         return Redirect::back();
    }
    public function deletePost($post_id){
        $post = Post::find($post_id);
        $scout_id = $post['scout_id'];
        Post::find($post_id)->delete();
        //notify the post
        $notification = new Notification;
                $notification->id = null;
                $notification->user_id = $scout_id;
                $notification->subject = 'comment';
                $notification->body = 'Your post has been deleted by a Moderator. Reason(s): Violating Policy of Talent Scout.';
                $notification->object_id = null;
                $notification->is_read = 0;
                $notification->sent_at = new Carbon();
                $notification->save();
        Session::flash('message', 'Successfully deleted!');
        return Redirect::to('/home');
    }
    public function removeFeaturedFeedback($id){
        $fb = Featured::find($id);
        $destinationPath = public_path().'/files/';
        $file = $fb['image'];
        File::delete($destinationPath.$file);
        $fb->delete();
         Session::flash('message', 'Successfully deleted!');
         return Redirect::back();
    }
    public function showConnection($id){
        $yourEndorsement = array(); //people you endorsed
        $gotEndorse = array(); //people that endorsed you
        $i = 0;
        $user = User::find($id);
            $endorsed = Endorse::where('endorsed_id','=',$id)->get();
            foreach ($endorsed as $key => $value) {
                $userdetails = User::find($value['endorser_id']);
                if($userdetails['roleID'] == '2'){
                    $groupname = Group::find($userdetails['id']);
                    $gotEndorse[$i]['id'] = $userdetails['id'];
                    $gotEndorse[$i]['groupname'] = $groupname['firstname'];
                    $gotEndorse[$i]['profile_image'] = $userdetails['profile_image'];
                }
                else{
                    $gotEndorse[$i]['id'] = $userdetails['id'];
                    $gotEndorse[$i]['firstname'] = $userdetails['firstname'];
                    $gotEndorse[$i]['lastname'] = $userdetails['lastname'];
                    $gotEndorse[$i]['profile_image'] = $userdetails['profile_image'];
                }
                $i++;
            }
            $i = 0;
            $endorser = Endorse::where('endorser_id','=',$id)->get();
            foreach ($endorser as $key => $value) {
                $userdetails = User::find($value['endorsed_id']);
                if($userdetails['roleID'] == '2'){
                    $groupname = Group::find($userdetails['id']);
                    $yourEndorsement[$i]['id'] = $userdetails['id'];
                    $yourEndorsement[$i]['groupname'] = $groupname['firstname'];
                    $yourEndorsement[$i]['profile_image'] = $userdetails['profile_image'];
                }
                else{
                    $yourEndorsement[$i]['id'] = $userdetails['id'];
                    $yourEndorsement[$i]['firstname'] = $userdetails['firstname'];
                    $yourEndorsement[$i]['lastname'] = $userdetails['lastname'];
                    $yourEndorsement[$i]['profile_image'] = $userdetails['profile_image'];
                }
                $i++;
            }
            return view('connection')
                        ->with('endorsed', $gotEndorse)
                        ->with('endorser', $yourEndorsement)
                        ->with('user', $user);

    }
    public function removeEndorsement($id) {
        $user = Endorse::where('endorsed_id','=', $id)
                        ->where('endorser_id','=', Session::get('id'))
                        ->delete();
        Session::flash('message', 'Successfully unendorsed!');
        return Redirect::to('/connection'.'/'.Session::get('id'));
    }
    public function endorseUser($id){
        $endorse = new Endorse;
        $endorse->id = null;
        $endorse->endorsed_id = $id;
        $endorse->endorser_id = Session::get('id');
        $endorse->save();

        //notifications
        $notification = new Notification;
                $notification->id = null;
                $getUserID = User::find($id);
                // dd($getUserID);
                $notification->user_id = $id;
                $notification->subject = 'comment';
                $notification->body = Session::get('username').' has endorse you!';
                $notification->object_id = null;
                $notification->is_read = 0;
                $notification->sent_at = new Carbon();
                $notification->save();

        Session::flash('message', 'Successfully endorse!');
         return Redirect::to('/connection'.'/'.Session::get('id'));
    }
    public function rateScout($scout_id, $post_id){
        $data = Request::except('_token');
            $score = 0;
            $demerit = 0;
            $user = User::find($scout_id);
            $findUser = Rating::find($user['id']);
            if($findUser == null){
                $rateUser = new Rating;
                $rateUser->id = null;
                $rateUser->user_id = $user['id'];
                $rateUser->post_id = $post_id;
                foreach($data['attitude'] as $atti){
                    if($atti == 1){
                         $score += $atti * 10;
                         $demerit += 1;
                    }
                    else {
                    $score += $atti * 10;
                    }
                    
                    break;
                }
                foreach($data['management'] as $perfo){
                    if($perfo == 1){
                         $score += $perfo * 10;
                         $demerit += 1;
                    }
                    else {
                    $score += $perfo * 10;
                    }
                    break;
                }
                foreach($data['integrity'] as $punc){
                    if($punc == 1){
                         $score += $punc * 10;
                         $demerit += 1;
                    }
                    else {
                    $score += $punc * 10;
                    }
                    break;
                }
                $rateUser->score = $score;
                $rateUser->demerit = $demerit;
                $rateUser->save();
            }
            else {
                $findUser->id = null;
                $findUser->user_id = $user['id'];
                $rateUser->post_id = $post_id;
                foreach($data['attitude'] as $atti){
                    if($atti == 1){
                         $score += $atti * 10;
                         $demerit += 1;
                    }
                    else {
                    $score += $atti * 10;
                    }
                    
                    break;
                }
                foreach($data['management'] as $perfo){
                    if($perfo == 1){
                         $score += $perfo * 10;
                         $demerit += 1;
                    }
                    else {
                    $score += $perfo * 10;
                    }
                    break;
                }
                foreach($data['integrity'] as $punc){
                    if($punc == 1){
                         $score += $punc * 10;
                         $demerit += 1;
                    }
                    else {
                    $score += $punc * 10;
                    }
                    break;
                }
                // dd($findUser['score']);
                $findUser->score = $score;
                $findUser->demerit = $demerit;
                $findUser->save();            
            }
            $rating = Rating::where('user_id' , '=', $scout_id)->get();
                $tal = Scout::find($scout_id);
                $finalscore = 0;
                $finaldemerit = 0;
                if(!empty($rating)){
                    foreach ($rating as $key => $value) {
                        $finalscore += $value['score'];
                        $finaldemerit += $value['demerit'];
                    }
                }
                if(!empty($tal)) {

                    if($tal['score'] !== $finalscore){
                        $tal->score = $finalscore;
                    }
                    if($tal['demerit'] !== $finaldemerit){
                        $tal->demerit = $finaldemerit;
                    }
                    $tal->save();
                }
                Invitation::where('post_id', '=', $post_id)
                                                ->where('talent_id', '=', Session::get('id'))->delete();
                Session::flash('message', 'Successfully Rated Scout!');
                return Redirect::back();
    }
}