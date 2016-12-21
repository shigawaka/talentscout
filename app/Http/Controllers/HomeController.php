<?php

namespace App\Http\Controllers;

use Request;
use Redirect;
use Validator;
use Calendar;

use App\Scout;
use App\User;
use App\Talent;
use App\Post;
use App\Rating;
use App\Comment;
use App\Proposal;
use App\Invitation;
use App\EventModel;
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
        if(Session::get('roleID') == 1){
            $posts = Post::where('status', '=', 0)->orderBy('date_posted', 'desc')->get();
            $succ = Post::where('status', 1)->get();
            return view('talent.home')
                ->with('posts',$posts)
                ->with('succ',$succ);
        }
        else {
        $posts = Post::where('scout_id', Auth::user()->id)->orderBy('status', 'asc')->get();
        $succ = Post::where('status', 1)->get();
        // dd($succ);
        return view('scout.home')
                ->with('posts',$posts)
                ->with('succ',$succ);
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
        $tf = Talent::find(Auth::user()->id);
        if(!empty($tf)){
        Session::set('talentfee', $tf['fee']);
        }
        if($cred['roleID'] == 1){
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
            if($role == 1){
                $user = User::find($user_id);
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

        $rules = array(
            'lastname' => 'required|regex:/^[\pL\s\-]+$/u',
            'firstname' => 'required|regex:/^[\pL\s\-]+$/u',
            'birthday' => 'required|date|before:2011-01-01',
            'contact' => 'required|numeric|regex:/(09)[0-9]{9}/',
            'emailaddress' => 'required|unique:users',
            'username' => 'required|unique:users|min:5',
            'password' => 'required|alphaNum|min:6',
            'image' => 'max:1500|mimes:png,jpeg,jpg',
        );

        $message = array(
            'lastname.required' => 'Required',
            'lastname.regex' => 'Letters only',
            'firstname.required' => 'Required',
            'firstname.regex' => 'Letters only',
            'birthday.required' => 'Required',
            'birthday.date' => 'Date',
            'contact.required' => 'Required',
            'contact.numeric' => 'Numbers only',
            'email.required' => 'Required',
            'username.required' => 'Required',
            'password.required' => 'Required',
            'birthday.before' => 'You must be 5 years older and above!',
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()) {
            if(User::find($user_id)){
                return back()->withInput();
            }
            else {
            $new_profile->address = $data['address'];
            $new_profile->contactno = $data['contactno'];
            $new_profile->emailaddress = $data['email'];
            $new_profile->profile_description = $data['description'];
            $new_profile->birthday = date("Y-m-d", strtotime($data['birthday']));
            if( Request::hasFile('image') ) {
                        $destinationPath = public_path().'/files/';
                        $file = Request::file('image');
                        $filename = str_random(10).".".$file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);
                        $new_profile->profile_image = $filename;
                    }
            $new_profile->password = Hash::make($data['password']);
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
            return Redirect::back();
        
            }
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
                $counter = 0;
                $temparr = array();
                $userdetails = array();
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
                // dd($temparr);
                return view('talent.invitation')
                        ->with('rating', $rating)
                        ->with('user', $user)
                        ->with('fee', $tal)
                        ->with('hired', $hired)
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
                return view('talent.schedule', compact('calendar'))
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
            'start_date' => 'required|date|before:tomorrow',
            'start_time' => 'required',
            'end_date' => 'required|date|before:tomorrow',
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
        if(!empty($invited)){
            $invited->status = 1;
            $invited->save();
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