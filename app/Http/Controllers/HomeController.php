<?php

namespace App\Http\Controllers;

use Request;
use Redirect;
use Validator;
use Calendar;
use Response;

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
            return view('talent.home')
                ->with('posts',$posts)
                ->with('succ',$succ)
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
        $posts = Post::where('scout_id', Auth::user()->id)->orderBy('status', 'asc')->get();
        $succ = Post::where('status', 1)->get();
        $profile = Featured::where('isProfile','=',1)->get();
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
            }
        }
        // dd($profilearray);

        // dd($succ);
        return view('scout.home')
                ->with('posts',$posts)
                ->with('succ',$succ)
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
            }
        }
        $tf = Talent::find(Auth::user()->id);
        if(!empty($tf)){
        Session::set('talentfee', $tf['fee']);
        }
        if($cred['roleID'] == 1 || $cred['roleID'] == 2){
        return Redirect::to('http://localhost:8000/hometalent');
        }
        elseif($cred['roleID'] == 3) {
        return Redirect::to('http://localhost:8000/hometalent');
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
        return Redirect::to('/home'); // redirect the user to the login screen
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
                return view('talent.profile')
                        ->with('rating', $rating)
                        ->with('user', $user)
                        ->with('fee', $tal)
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
                $groups = json_decode($groupdetails['member'], true);
                for ($i=0; $i < count($groups); $i++) { 
                    $searchusergroup = User::find($groups[$i]);
                    $grouparray[$i]['id'] = $searchusergroup['id'];
                    $grouparray[$i]['fullname'] = $searchusergroup['firstname'].' '.$searchusergroup['lastname'];
                    $grouparray[$i]['profile_image'] = $searchusergroup['profile_image'];
                }
                //end finding group
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
                return view('talent.profilegroup')
                        ->with('rating', $rating)
                        ->with('user', $user)
                        ->with('groupdetails', $groupdetails)
                        ->with('grouparray', $grouparray)
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
                return view('scout.profile')
                        ->with('rating', $rating)
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
            $new_profile->address = $data['address'];
            $new_profile->contactno = $data['contactno'];
            if($new_profile['username'] !== $data['username'] &&  !empty($data['username'])){
            $new_profile->username = $data['username'];
            }
            else {
                $old_profile->username = $old_profile['username'];
            }
            if($new_profile['emailaddress'] !== $data['emailaddress'] &&  !empty($data['emailaddress'])) {
            $new_profile->emailaddress = $data['emailaddress'];
            }
            else {
                $old_profile->username = $old_profile['emailaddress'];
            }
            $new_profile->profile_description = $data['description'];
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
            'talents' => 'required',
        );

        $message = array(
            'talents.required' => 'Required',
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()) {
            $findtal = Talent::find($user_id);
            if($findtal) {
                $talenttags = json_encode(explode(',', $data['talents'][0]));
                $findtal->talents =$talenttags;
                $findtal->save();
                return Redirect::back();
            } else {
                $detail = new Talent;
                $detail->id = $user_id;
                $talenttags = json_encode(explode(',', $data['talents'][0]));
                $detail->talents =$talenttags;
                $detail->save();
                Session::flash('message', 'Successfully posted!');
                return Redirect::back();
            }
        } else {
            return Redirect::back()->withInput()->withErrors($validation);
        }
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
                                             "DELETE": function() {
                                                $.ajax({
                                                            method: "POST", // Type of response and matches what we said in the route
                                                            url: "/deleteschedule", // This is the url we gave in the route
                                                            data: {id : id}, // a JSON object to send back
                                                            success: function(response){ // What to do if we succeed
                                                                console.log(id);
                                                                alert("Successfully Deleted Event!");
                                                                location.reload();
                                                            }
                                                        });
                                             }
                                           }

                            });


                        }',
                        "dayClick" => "function() { 
                             $('#modal1').openModal();
                        }",


                        
                    ]); 
                return view('schedule', compact('calendar'))
                        ->with('user', $user);
            
    }
    public function deleteSchedule(Request $request){
        $eventID = Request::all('id');
        EventModel::where('id', '=', $eventID)->delete();
        Session::flash('message', 'Successfully deleted event!');
        return Redirect::to('/schedule/'.Session::get('id'));
    }
    public function addSchedule(Request $request, $user_id){
        $data = Request::all();
        $start_date = Carbon::parse($data['start_date'].$data['start_time']);
        $end_date = Carbon::parse($data['end_date'].$data['end_time']);

        $rules = array(
            'title' => 'required|regex:/^[\pL\s\-]+$/u',
            'start_date' => 'required|date', //before:tomorrow
            'start_time' => 'required',
            'end_date' => 'required|date', //before:tomorrow
            'end_time' => 'required|after:start_time',
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
                if($data['allday'] == 'true'){
                    $data['allday'] = 0;
                }
                else {
                    $data['allday'] = 1;
                }
                $detail->isAllDay =$data['allday'];
                $detail->title =$data['title'];
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
        if(!empty($invited)){
            $invited->status = 1;
            $invited->save();
            $newSchedule = new EventModel;
            $newSchedule->id = null;
            $newSchedule->user_id = Session::get('id');
            $newSchedule->isAllDay = 0;

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
    public function searchTalent(){
        $data = Request::except('_token');
        // dd($data);
        $keyword = strtolower($data['search']);
        //categorize talents
        $guitar = array('acoustic', 'guitar', 'wood guitar','acoustic guitar');
        $bass = array('bass', 'acoustic bass',);
        $dancing = array('dancing', 'dance', 'hip-hop', 'modern dance', 'dance troupe', 'dancers', 'dancer');
        $drum = array('drum', 'drummer', 'drummer band', 'marching band');
        $vocalist = array('singing', 'singer', 'songer', 'sing', 'vocalist', 'choir', 'vocal');

        $searchUser = User::all();
        $arr = array();
        $counter = 0;
        if(!empty($data['search']) && !empty($data['fee']) && !empty($data['category'])) {
            foreach ($searchUser as $key => $value) {
            if(stripos(strtolower($value['firstname']), strtolower($keyword)) !== false || stripos(strtolower($value['lastname']), strtolower($keyword)) !== false){
            $fee = Talent::where('id', '=', $value['id'])->where('fee', '<=', $data['fee'])->first();
            // dd($fee);
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
        elseif(!empty($data['search']) && !empty($data['fee'])) {
            foreach ($searchUser as $key => $value) {
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
        elseif (!empty($data['search'])) {
        foreach ($searchUser as $key => $value) {
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
        arsort($arr);
        // dd($arr);
        return view('scout.searchtalent')->with('result', $arr);
    }
    public function searchScout(){
        $data = Request::except('_token');
        $keyword = strtolower($data['search']);

        $searchUser = User::all();
        $arr = array();
        $counter = 0;
        // if(!empty($data['search']) && $data['category'] !== null && $data['category'] === 'post'){
            
        // }

        if(!empty($data['search']) && !empty($data['category'])) {
            foreach ($searchUser as $key => $value) {
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
        elseif(!empty($data['search']) && !empty($data['fee'])) {
            foreach ($searchUser as $key => $value) {
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
        elseif (!empty($data['search'])) {
        foreach ($searchUser as $key => $value) {
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
        arsort($arr);
        // dd($arr);
        return view('talent.searchscout')->with('result', $arr);
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
                $results[] = [ 'id' => $query->id, 'value' => $query->firstname.' '.$query->lastname, 'picture' => $query->profile_image, 'invited' => 'Already invited' ];
            }
            else {
            $results[] = [ 'id' => $query->id, 'value' => $query->firstname.' '.$query->lastname, 'picture' => $query->profile_image ];
            }
        }
        if(count($results))
            return Response::json($results);
        else
            $results[] = [ 'id' => '', 'value' => 'No Result Found' ];
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
    public function showPortfolio($id){
        dd($id);
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
            Session::flash('message', 'User successfully invited!');
            return Redirect::back();
    }
    public function showFeatured(){
    $profile = Featured::where('isProfile','=',1)->get();
    $profilearray = array();
    $i = 0;
    foreach ($profile as $key => $value) {
        $userdetail = User::find($value['profile_id']);
        $profilearray[$i]['id'] = $value['profile_id'];
        $profilearray[$i]['profile_image'] = $userdetail['profile_image'];
        $profilearray[$i]['firstname'] = $userdetail['firstname'];
        $profilearray[$i]['lastname'] = $userdetail['lastname'];
        $profilearray[$i]['start_date'] = $value['start_date'];
        $profilearray[$i]['end_date'] = $value['end_date'];
        $i++;
    }
    $feedbacks = Featured::where('isFeedback','=',1)->get();
    // dd($feedbacks);

    return view('admin.adminpanel')
            ->with('profile',$profile)
            ->with('feedbacks', $feedbacks)
            ->with('profilearray',$profilearray);
    }
    public function addFeaturedProfile(){
        $data = Request::all();
        dd($data['invisible']);
        $start_date = Carbon::parse($data['start_date']);
        $end_date = Carbon::parse($data['end_date']);
        $checkstartdate = Carbon::now()->gt($start_date);
        $checkendate = $end_date->lt($start_date);
        if($checkstartdate || $checkendate){
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
            $details->end_date = $data['end_date'];
            $details->save();
            Session::flash('message', 'Successfully added!');
            return Redirect::back();
        }
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
        Featured::where('profile_id','=', $id)->delete();
         Session::flash('message', 'Successfully deleted!');
         return Redirect::back();
    }
    public function removeFeaturedFeedback($id){
        Featured::where('id','=', $id)->delete();
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
        return Redirect::back();
    }
    public function endorseUser($id){
        $endorse = new Endorse;
        $endorse->id = null;
        $endorse->endorsed_id = $id;
        $endorse->endorser_id = Session::get('id');
        $endorse->save();
        Session::flash('message', 'Successfully endorse!');
        return Redirect::back();
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
                // $deleteinvitation = Invitation::where('post_id', '=', $post_id)
                //                                 ->where('talent_id', '=', Session::get('id'))->first();
                
                return Redirect::back();
    }
}