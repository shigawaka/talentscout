<?php

namespace App\Http\Controllers;

use Request;
use Redirect;
use Validator;
// use Storage;


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


use DB;
use Carbon\Carbon;
use Hash;
use Mail;
use Auth;
use File;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ScoutController extends Controller
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
        $data = Request::all();

        $rules = array(
            'firstname' => 'required|regex:/^[\pL\s\-]+$/u',
            'lastname' => 'required|regex:/^[\pL\s\-]+$/u',
            'birthday' => 'required|date',
            'contact' => 'required|numeric',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
        );

        $message = array(
            'firstname.required' => 'Required',
            'firstname.regex' => 'Letters only',
            'lastname.required' => 'Required',
            'lastname.regex' => 'Letters only',
            'birthday.required' => 'Required',
            'birthday.date' => 'Date',
            'contact.required' => 'Required',
            'contact.numeric' => 'Numbers only',
            'email.required' => 'Required',
            'username.required' => 'Required',
            'password.required' => 'Required',
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()) {
            if(User::where('username', $data['username'])->first()) {
                return back()->withInput();
            } else {
                $detail = new User;

                $detail->id =null;
                $detail->roleID =0;
                $detail->firstname =$data['firstname'];
                $detail->lastname =$data['lastname'];
                $detail->birthday =$data['birthday'];
                $detail->contactno =$data['contact'];
                $detail->emailaddress =$data['email'];
                $detail->username =$data['username'];
                $detail->password = Hash::make($data['password']);
                $detail->profile_image ='avatar.png';
                $detail->profile_description =' ';
                $detail->save();

                $scout = new Scout;
                $scout->id = $detail->id;
                $scout->score = 0;
                $scout->demerit = 0;
                $scout->save();
                Session::flash('message', 'Successfully registered!');
                Mail::send('emails.invitation', ['user' => $data['email']], function ($m) use ($findTalent) {
                $m->from('TalentScout.com', 'Talent Scout');

                $m->to($data['email'])->subject('Invitation!');
            });
                return Redirect::to('http://localhost:8000/login');
            }
        } else {
            return Redirect::back()->withInput()->withErrors($validation);
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
        $posts = Post::where('scout_id', Auth::user()->id)->orderBy('date_posted', 'desc')->get();
        $unreadNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 0)
                                            ->limit(6)
                                            ->get();
            $readNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 1)
                                            ->limit(6)
                                            ->get();
        return view('scout.post')
                ->with('posts',$posts)
                ->with('unreadNotifications', $unreadNotifications)
                ->with('readNotifications', $readNotifications);
    }
    public function sortPost(){
        $data = Request::except('_token');
        
        if($data['sort'] != '')
        {
            $posts = Post::where('scout_id', Auth::user()->id)->orderBy($data['sort'], 'desc')->get();
            return view('scout/post')->with('posts',$posts);
        }
        else {
            $posts = Post::where('scout_id', Auth::user()->id)->orderBy('date_posted', 'desc')->get();
        return view('scout/post')->with('posts',$posts);
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
     *
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
    public function showPost($post_id){
        $unreadNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 0)
                                            ->limit(6)
                                            ->get();
            $readNotifications = Notification::where('user_id', '=', Session::get('id'))
                                            ->where('is_read', '=', 1)
                                            ->limit(6)
                                            ->get();
            $fullcomm = array();
        $users = User::find(Session::get('id'));
        
        Session::set('post_id', $post_id);
        $exists = Proposal::where('user_id', '=', Auth::user()->id)->where('post_id', '=', $post_id)->first();
        
        

        Session::set('proposal_id', $exists['id']);
        
         $posts = Post::where('id', $post_id)->first();
         $invitationhired = false;
         $checkhires = json_decode($posts['hire_id'], true);
         if($checkhires !== 0 && $checkhires !== null){
            $invitationhired = in_array(Session::get('id'), $checkhires);
         }
         //comments
         $comments = Comment::where('post_id', $post_id)->orderBy('date_posted', 'desc')->get();
            $i = 0;
         foreach($comments as $comment){
            $commdate = Carbon::parse($comment['date_posted']);
            $now = new Carbon();
            $date_comm = $commdate->diffForHumans($now, true);
            $users = User::where('id', '=',$comment['user_id'])->get();
            foreach($users as $user){
                $fullcomm[$i]['profile_image'] = $user['profile_image'];
                $fullcomm[$i]['username'] = $user['username'];
                $fullcomm[$i]['date_posted'] = $date_comm;
                $fullcomm[$i]['body'] = $comment['body'];
                $fullcomm[$i]['commentID'] = $comment['id'];
                $i++;
            }
         }
         $retrieveprop = Proposal::where('post_id', '=', $post_id)->get();
         $fullproposals = array();
         $hires = array();
         $j = 0;
         $temp = 0;
         //saving details using 2d array
         foreach ($retrieveprop as $retrieveprops) {
             $userdet = User::where('id', '=', $retrieveprops['user_id'])->get();
             foreach ($userdet as $userdets) {
                 $fullproposals[$j]['user_id'] = $userdets['id'];
                 $fullproposals[$j]['profile_image'] = $userdets['profile_image'];
                 $fullproposals[$j]['firstname'] = $userdets['firstname'];
                 $fullproposals[$j]['lastname'] = $userdets['lastname'];
                 $fullproposals[$j]['body'] = $retrieveprops['body'];
                 $fullproposals[$j]['file'] = $retrieveprops['file'];
                 $fullproposals[$j]['proposed_rate'] = $retrieveprops['proposed_rate'];
                 $hired = Post::where('id', '=', $post_id)->get();
                 foreach($hired as $hire){
                    if(count(json_decode($hire['hire_id'], true)) >= 1) {
                    $numhire = count(json_decode($hire['hire_id'], true));
                    $temp = json_decode($hire['hire_id']);
                    // list($temp) = explode(',', implode(',',json_decode($hire['hire_id'], true)));
                    // $fullproposals[$j]['hired'] = $temp[$j++];
                    // echo $temp;
                        // for ($i=0; $i < $numhire; $i++) { 
                        // $otheruser = User::find($temp[$i]);
                         $fullproposals[$j]['hired'] = $temp;
                                // $fullproposals[$i]['user_id'] = $otheruser['id'];
                                // $fullproposals[$i]['profile_image'] = $otheruser['profile_image'];
                                // $fullproposals[$i]['firstname'] = $otheruser['firstname'];
                                // $fullproposals[$i]['lastname'] = $otheruser['lastname'];
                                // $fullproposals[$i]['body'] = $retrieveprops['body'];
                                // $fullproposals[$i]['proposed_rate'] = $retrieveprops['proposed_rate'];
                                // $fullproposals[$i]['hired'] = $temp[$i];
                        // }
                    }
                    else {
                    $fullproposals[$j]['hired'] = null;
                    }
                 }
                 $j++;
             }
         }
         $fullclosedeals = array();
         $hired = Post::where('id', '=', $post_id)->get();
                 foreach($hired as $hire){
                    if(count(json_decode($hire['hire_id'], true)) >= 1 && json_decode($hire['hire_id'], true) !== 0) {
                    $numhire = count(json_decode($hire['hire_id'], true));
                    $temp = json_decode($hire['hire_id']);
                    // list($temp) = explode(',', implode(',',json_decode($hire['hire_id'], true)));
                    // $fullproposals[$j]['hired'] = $temp[$j++];
                    // echo $temp;
                    for ($i=0; $i < $numhire; $i++) { 
                    $otheruser = User::find($temp[$i]);
                            $fullclosedeals[$i]['user_id'] = $otheruser['id'];
                            $fullclosedeals[$i]['profile_image'] = $otheruser['profile_image'];
                            $fullclosedeals[$i]['firstname'] = $otheruser['firstname'];
                            $fullclosedeals[$i]['lastname'] = $otheruser['lastname'];
                            // $fullclosedeals[$i]['body'] = $retrieveprops['body'];
                            // $fullclosedeals[$i]['proposed_rate'] = $retrieveprops['proposed_rate'];
                            $fullclosedeals[$i]['hired'] = $temp[$i];
                        }
                    }
                 }
         //finding the recommended profiles for this post
         $recommended = array();
         $recommendednewbie = array();
         $k = 0;
         $t = 0;

         
         foreach (json_decode($posts['tags'],true) as $tal){

            //joining 3 tables talent, talent_details and users(for profiling).
            $temp = DB::table('users')
            ->join('talent', function ($join) use ($posts){
            $join->on('users.id', '=', 'talent.id')
                 ->where('talent.fee', '<=', $posts['budget'])
                 ->where('talent.demerit', '<=', 1500);
                 // ->where('talent.fee_type', '=', $posts['rate']);
            })
            ->join('talent_details', function ($join) use ($tal) {
            $join->on('users.id', '=', 'talent_details.talent_id')
                 ->where('talent_details.talent', '=', $tal);
            })
            ->join('users AS u', function ($join) use ($posts) {
                // $hires = json_decode($posts['hire_id'], true);
                //     if($hires !== null){
                //     $checkhired = array_search($findProf['id'], $hires);
                //     }
                //     else {
                //         $checkhired = false;
                //     }
                // if($checkhired == false){
                    if($posts['age'] == 1) {
                        if($posts['gender'] == 'any'){
                            if($posts['group'] == 0){
                                $join->on('users.id', '=', 'u.id')
                                     ->where('u.age', '>=', 5)
                                     ->where('u.age', '<=', 12);
                            }
                            else {
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 5)
                                     ->where('u.age', '<=', 12)
                                     ->where('u.roleID', '=', $posts['group']);
                            }
                        }
                        else {
                            if($posts['group'] == 0){
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 5)
                                     ->where('u.age', '<=', 12)
                                     ->where('u.gender', '=', $posts['gender']);
                            }
                            else {
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 5)
                                     ->where('u.age', '<=', 12)
                                     ->where('u.gender', '=', $posts['gender'])
                                     ->where('u.roleID', '=', $posts['group']);
                            }
                        }
                    }
                    elseif($posts['age'] == 2) {
                        if($posts['gender'] == 'any'){
                            if($posts['group'] == 0){
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 13)
                                     ->where('u.age', '<=', 19);
                            }
                            else {
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 13)
                                     ->where('u.age', '<=', 19)
                                     ->where('u.roleID', '=', $posts['group']);
                            }
                        }
                        else {
                            if($posts['group'] == 0){
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 13)
                                     ->where('u.age', '<=', 19)
                                     ->where('u.gender', '=', $posts['gender']);
                            }
                            else {
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 13)
                                     ->where('u.age', '<=', 19)
                                     ->where('u.gender', '=', $posts['gender'])
                                     ->where('u.roleID', '=', $posts['group']);
                            }
                        }
                    }
                    elseif($posts['age'] == 3) {
                        if($posts['gender'] == 'any'){
                            if($posts['group'] == 0){
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 20)
                                     ->where('u.age', '<=', 34);
                            }
                            else {
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 20)
                                     ->where('u.age', '<=', 34)
                                     ->where('u.roleID', '=', $posts['group']);
                            }
                        }
                        else {
                            if($posts['group'] == 0){
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 20)
                                     ->where('u.age', '<=', 34)
                                     ->where('u.gender', '=', $posts['gender']);
                            }
                            else {
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 20)
                                     ->where('u.age', '<=', 34)
                                     ->where('u.gender', '=', $posts['gender'])
                                     ->where('u.roleID', '=', $posts['group']);
                            }
                        }
                    }
                    elseif($posts['age'] == 4) {
                        if($posts['gender'] == 'any'){
                            if($posts['group'] == 0){
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 35)
                                     ->where('u.age', '<=', 50);
                            }
                            else {
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 35)
                                     ->where('u.age', '<=', 50)
                                     ->where('u.roleID', '=', $posts['group']);
                            }
                        }
                        else {
                            if($posts['group'] == 0){
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 35)
                                     ->where('u.age', '<=', 50)
                                     ->where('u.gender', '=', $posts['gender']);
                            }
                            else {
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '>=', 35)
                                     ->where('u.age', '<=', 50)
                                     ->where('u.gender', '=', $posts['gender'])
                                     ->where('u.roleID', '=', $posts['group']);
                            }
                        }
                    }
                    else {
                        if($posts['gender'] == 'any'){
                            if($posts['group'] == 0){
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '<=', 100);
                            }
                            else {
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '<=', 100)
                                     ->where('u.roleID', '=', $posts['group']);
                            }
                        }
                        else {
                            if($posts['group'] == 0){
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '<=', 100)
                                     ->where('u.gender', '=', $posts['gender']);
                            }
                            else {
                                $join->on('users.id', '=', 'u.id')
                                ->where('u.age', '<=', 100)
                                     ->where('u.gender', '=', $posts['gender'])
                                     ->where('u.roleID', '=', $posts['group']);
                            }
                        }
                    }
                // }
            })
            ->get();
            foreach ($temp as $key) {
                $hires = json_decode($posts['hire_id'], true);
                    if($hires !== null && $hires !== 0){
                    $checkhired = array_search($key->id, $hires);
                    }
                    else {
                        $checkhired = false;
                    }
                if($checkhired == false && $checkhired !== 0){
                    $checkexperience = Rating::where('user_id', '=', $key->id)->get();
                    $invited = Invitation::where('post_id', '=', $post_id)
                                        ->where('talent_id', '=', $key->id)
                                        ->first();
                    if(empty($invited)){
                        if(count($checkexperience) == 0){
                                $recommendednewbie[$t]['id'] = $key->id;
                                $recommendednewbie[$t]['profile_image'] = $key->profile_image;
                            if($key->firstname == null){
                                $group = Group::find($key->id);
                                $recommendednewbie[$t]['firstname'] = ucfirst($group['groupname']);
                                $recommendednewbie[$t]['lastname'] = '';
                            }
                            else {
                                $recommendednewbie[$t]['firstname'] = ucfirst($key->firstname);
                                $recommendednewbie[$t]['lastname'] = ucfirst($key->lastname);
                            }
                            $t++;
                            }
                        else {
                            $recommended[$t]['id'] = $key->id;
                            $recommended[$t]['profile_image'] = $key->profile_image;
                            if($key->firstname == null){
                                $group = Group::find($key->id);
                                $recommended[$t]['firstname'] = ucfirst($group['groupname']);
                                $recommended[$t]['lastname'] = '';
                            }
                            else {
                                $recommended[$t]['firstname'] = ucfirst($key->firstname);
                                $recommended[$t]['lastname'] = ucfirst($key->lastname);
                            }
                            $t++;
                            }
                        }
                    }
                }
            }
         
         // dd($recommendednewbie);
         //remove duplicate from recommended array;
         $one_dimension = array_map("serialize", $recommended);
            $unique_one_dimension = array_unique($one_dimension);
            $unique_multi_dimension = array_map("unserialize", $unique_one_dimension);
            //pagination for recommended profile
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $col = new Collection($unique_multi_dimension);
            $perPage = 4;
            $currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $entries = new LengthAwarePaginator($currentPageSearchResults, count($col), $perPage);
            $entries->setpath('');
            // //pagination for comments
            // $currentPage = LengthAwarePaginator::resolveCurrentPage('comm');
            // $col = new Collection($fullcomm);
            // $perPage = 4;
            // $currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
            // $comm = new LengthAwarePaginator($currentPageSearchResults, count($col), $perPage);
            // $comm->setpath('');
            // $comm->setPageName('comm');
            // dd($exists);
         return view('scout.viewpost')
         ->with('posts', $posts)
         ->with('invitationhired', $invitationhired)
         ->with('users', $users)
         ->with('proposal', $exists)
         ->with('recommended', $entries)
         ->with('recommendednewbie', $recommendednewbie)
         ->with('fullproposals', $fullproposals)
         ->with('fullclosedeals', $fullclosedeals)
         ->with('unreadNotifications', $unreadNotifications)
         ->with('readNotifications', $readNotifications)
         ->with('comments', $fullcomm)
         ->with('tag', json_decode($posts['tags'], true));
    }
    public function deleteYourPost($post_id){
        $post = Post::find($post_id);
        $today = Carbon::now();
        $days = $today->diffInDays(Carbon::parse($post['start_date']));
        $findHireID = json_decode($post['hire_id'],true);
        //penalty
                if($days <= 3 && $days !== 1){
                    foreach ($findHireID as $key => $value) {
                        //call function to penalize user
                        PaypalController::penalizeUser($value);
                        $notification = new Notification;
                        $notification->id = null;
                        $notification->user_id = $value;
                        $notification->subject = 'invitation';
                        $notification->body = Session::get('username').' has cancelled his event entitled: '.$post['title'];
                        $notification->object_id = $post['id'];
                        $notification->is_read = 0;
                        $notification->sent_at = new Carbon();
                        $notification->save();
                    }
                        //send money to system
                        PaypalController::sendPaymentToSystem();
                        //delete all schedules of hired talents
                        $cancelevent = EventModel::where('post_id', '=', $post['id'])->get();
                        foreach ($cancelevent as $key2 => $value2) {
                            $value2->delete();
                        }
                        $destinationPath = public_path().'/files/';
                        $file = $post['file'];
                        File::delete($destinationPath.$file);
                        $post->delete();
                        Session::flash('message', 'Successfully deleted!');
                         return Redirect::back();
                }
                //just inform the hired talents no penalty
                else {
                    foreach ($findHireID as $key => $value) {
                        $notification = new Notification;
                        $notification->id = null;
                        $notification->user_id = $value;
                        $notification->subject = 'invitation';
                        $notification->body = Session::get('username').' has cancelled his event entitled: '.$post['title'];
                        $notification->object_id = $post['id'];
                        $notification->is_read = 0;
                        $notification->sent_at = new Carbon();
                        $notification->save();
                    }
                    //delete all schedules of hired talents
                        $cancelevent = EventModel::where('post_id', '=', $post['id'])->get();
                        foreach ($cancelevent as $key2 => $value2) {
                            $value2->delete();
                        }
                        $destinationPath = public_path().'/files/';
                        $file = $post['file'];
                        File::delete($destinationPath.$file);
                        $post->delete();
                        Session::flash('message', 'Successfully deleted!');
                         return Redirect::back();
                }
    }
    public function addPost(Request $request)
    {
        $data = Request::all();
       
        $rules = array(
            'title' => 'required|unique:post',
            'description' => 'required',
            'budget' => 'required|numeric',
            'file' => 'required|max:200000|mimes:png,jpeg,jpg,mp4,ogg,mkv,avi',
        );
        $message = array(
            'title.required' => 'Required',
            'description.required' => 'Required',
            'budget.required' => 'Required',
            'budget.numeric' => 'Numbers Only!',
        );

        $validation = Validator::make($data, $rules, $message);
        $data['talent'] = array_unique($data['talent']);
        if($validation->passes()) {
            if(Post::where('title', $data['title'])->first()) {
                return back()->withInput();
            } else {
                $postStartdate = Carbon::parse($data['start_date']);
                $postEnddate = Carbon::parse($data['end_date']);
                $schedule = EventModel::where('user_id','=', Session::get('id'))->get();
                foreach ($schedule as $key => $value) {
                    $schedStartdate = Carbon::parse($value['start_date']);
                    $schedEnddate = Carbon::parse($value['end_date']);
                if($postStartdate->gte($schedEnddate) && $postEnddate->lte($schedStartdate)){
                    if($value['isAllDay'] == '0'){
                    Session::flash('message', 'You are not available in that event day!');
                    return Redirect::back();
                    }
                    elseif($data['allday'] == 'True'){
                    Session::flash('message', 'You are not available in that event day!');
                    return Redirect::back();
                    }
                }
                elseif($postStartdate->isSameDay($schedStartdate)){ //check same day
                     if($value['isAllDay'] == '0'){
                    Session::flash('message', 'You are not available in that event day!');
                    return Redirect::back();
                    }
                    elseif($data['allday'] == 'True'){
                    Session::flash('message', 'You are not available in that event day!');
                    return Redirect::back();
                    }
                }
                }
                $detail = new Post;
                $detail->id =null;
                $detail->scout_id = Auth::user()->id;
                $detail->title =strtolower($data['title']);
                $detail->description =strtolower($data['description']);
                if( Request::hasFile('file') ) {
                        $destinationPath = public_path().'/files/';
                        $file = Request::file('file');
                        $filename = str_random(10).".".$file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);
                        $detail->file = $filename;
                    }
                    
                $to_remove = array('Select Category', 'Select talent', 0);
                //remove from array if user didn't choose from category or talent
                $res =  array_diff($data['talent'], $to_remove);
                
                //convert 
                $data['talent'] = implode(',', $res);
                $tags = json_encode(explode(',', $data['talent']));
                
                $detail->tags =$tags;
                $detail->budget =$data['budget'];
                $detail->rate = $data['rate'];
                $detail->age = $data['age'];
                $detail->gender = $data['gender'];
                $detail->group = $data['group'];
                $detail->hire_number = $data['hire_number'];
                $detail->date_posted = date('Y-m-d H:i:s');
                $detail->start_date = $data['start_date'];
                $detail->end_date = $data['end_date'];
                $detail->status = 0;
                $detail->save();
                //save schedule in db
                $newSchedule = new EventModel;
                    $newSchedule->id = null;
                    $newSchedule->user_id = Session::get('id');
                    $newSchedule->title = $data['title'];
                    $newSchedule->isAllDay = 1;
                    $newSchedule->start_date = $postStartdate;
                    $newSchedule->end_date = $postEnddate;
                    $newSchedule->post_id = $detail->id;
                    $newSchedule->save();
                Session::flash('message', 'Successfully posted!');
                return Redirect::to('http://localhost:8000/post');
            }
        } else {
            return Redirect::back()->withInput()->withErrors($validation);
        }

    }
    public function editpost(){
        $data = Request::all();
         $new_post = Post::find(Session::get('post_id'));
         $new_post->title =$data['title'];
                $new_post->description =$data['description'];
                if( Request::hasFile('file') ) {
                        $destinationPath = public_path().'/files/';
                        $file = Request::file('file');
                        $filename = $file->getClientOriginalName();
                        $file->move($destinationPath, $filename);
                        $new_post->file = $filename;
                    }

        $tags = json_encode(explode(',', $data['tags'][0]));
        $new_post->tags =$tags;
        $new_post->budget =$data['budget'];
        $new_post->rate = $data['rate'];
        $new_post->date_posted = date('Y-m-d H:i:s');
         $new_post->status = 0;
         $new_post->save();
         Session::flash('message', 'Successfully Edited!');
         return Redirect::back();
    }
    public function hire($talent_id){
        $hire = Post::find(Session::get('post_id'));
        $postStartdate = Carbon::parse($hire['start_date']);
            $postEnddate = Carbon::parse($hire['end_date']);
        if(empty($hire['hire_id'])){
            $hire->hire_id = json_encode(explode(',', $talent_id)); //$talent_id[0]
        }
        else {
            $getHire = json_decode($hire['hire_id'], true);
            // dd(implode(',',$getHire));
            array_push($getHire, $talent_id);
            // $getHire[] = json_encode(explode(',', $talent_id[0]));
            // $test = json_encode(explode(',', $getHire));
            // dd(implode(',',$getHire));
            $hire->hire_id = json_encode($getHire);
        }

        // $hire->hire_id = json_encode(explode(',', $talent_id[0]));
        $hire->save();

        $invite = new Invitation;
                $invite->id = null;
                $invite->post_id = Session::get('post_id');
                $invite->talent_id = $talent_id;
                $invite->status = 1;
                $invite->save();
                    $notification = new Notification;
                    $notification->id = null;
                    $notification->user_id = $talent_id;
                    $notification->subject = 'invitation';
                    $notification->body = Session::get('username').' has accepted your booking on event entitled: '.$hire['title'];
                    $notification->object_id = $hire['id'];
                    $notification->is_read = 0;
                    $notification->sent_at = new Carbon();
                    $notification->save();
        $newSchedule = new EventModel;
            $newSchedule->id = null;
            $newSchedule->user_id = $talent_id;
            $newSchedule->title = $hire['title'];
            $newSchedule->isAllDay = 1;
            $newSchedule->start_date = $postStartdate;
            $newSchedule->end_date = $postEnddate;
            $newSchedule->post_id = Session::get('post_id');
            $newSchedule->save();

        $changecontact = preg_replace('/^0/','63',Session::get('contactno'));
        $chikkadata = array('number'=> $changecontact, 'message'=> 'Congratulations! A scout has accepted your booking! Please visit Talent Scout for more details!');
        ChikkaController::send($chikkadata);
        return Redirect::back();
    }
    public function closePost($post_id){
        $data = Request::except('_token');
        $post = Post::find($post_id);
        // $i = 0;
            $test = json_decode($post['hire_id']);
            for($i = 0; $i < count($test);$i++){
            $score = 0;
            $demerit = 0;
            $user = User::find($test[$i]);
            $findUser = Rating::find($user['id']);
            // if($findUser == null){
                $rateUser = new Rating;
                $rateUser->id = null;
                $rateUser->user_id = $user['id'];
                $rateUser->post_id = $post['id'];

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
                foreach($data['performance'] as $perfo){
                    if($perfo == 1){
                         $score += $perfo * 10;
                         $demerit += 1;
                    }
                    else {
                    $score += $perfo * 10;
                    }
                    break;
                }
                foreach($data['punctuality'] as $punc){
                    if($punc == 1){
                         $score += $punc * 10;
                         $demerit += 1;
                    }
                    else {
                    $score += $punc * 10;
                    }
                    break;
                }
                $rateUser->attitude = $data['attitude'][$i];
                $rateUser->performance = $data['performance'][$i];
                $rateUser->punctuality = $data['punctuality'][$i];
                $rateUser->comment = $data['comment'][$i];
                if(empty($data['testi_score'])){
                $rateUser->testimonial_score = null;
                $rateUser->testimonial_comment = null;
                }
                else {
                $rateUser->testimonial_score = $data['testi_score'];
                $rateUser->testimonial_comment = $data['testimonial_comment'];
                }
                $rateUser->score = $score;
                $rateUser->demerit = $demerit;
                $rateUser->save();
            // }
            // else {
            //     $findUser->id = null;
            //     $findUser->user_id = $user['id'];
            //     $rateUser->post_id = $post['id'];
            //     foreach($data['attitude'] as $atti){
            //         if($atti == 1){
            //              $score += $atti * 10;
            //              $demerit += 1;
            //         }
            //         else {
            //         $score += $atti * 10;
            //         }
                    
            //         break;
            //     }
            //     foreach($data['performance'] as $perfo){
            //         if($perfo == 1){
            //              $score += $perfo * 10;
            //              $demerit += 1;
            //         }
            //         else {
            //         $score += $perfo * 10;
            //         }
            //         break;
            //     }
            //     foreach($data['punctuality'] as $punc){
            //         if($punc == 1){
            //              $score += $punc * 10;
            //              $demerit += 1;
            //         }
            //         else {
            //         $score += $punc * 10;
            //         }
            //         break;
            //     }
            //     $rateUser->attitude = $data['attitude'][$i];
            //     $rateUser->performance = $data['performance'][$i];
            //     $rateUser->punctuality = $data['punctuality'][$i];
            //     $rateUser->comment = $data['comment'][$i];
            //     if(empty($data['testi_score'])){
            //     $rateUser->testimonial_score = null;
            //     $rateUser->testimonial_comment = null;
            //     }
            //     else {
            //     $rateUser->testimonial_score = $data['testi_score'];
            //     $rateUser->testimonial_comment = $data['testimonial_comment'];
            //     }
            //     $findUser->score = $score;
            //     $findUser->demerit = $demerit;
            //     $findUser->save();
            //     $rateUser->save();
            // }
                $rating = Rating::where('user_id' , '=', $user['id'])->get();
                $tal = Talent::find($user['id']);
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
          } //end for   
        
        //change status of post to done

        $setPost = Post::find($post_id);
        $setPost->status = 1;
        $setPost->save();
        return Redirect::to('/post');
    }
    public function inviteTalent($talent_id){
        $findTalent = User::find($talent_id);
        // dd($findTalent);
        if(!empty($findTalent)){
            $invite = Invitation::where('talent_id', '=', $talent_id)
                                ->where('post_id', '=', Session::get('post_id'))->first();
            if(empty($invite)){
                $invite = new Invitation;
                $invite->id = null;
                $invite->post_id = Session::get('post_id');
                $invite->talent_id = $talent_id;
                $invite->status = 0;
                $invite->save();

                $notification = new Notification;
                $notification->id = null;
                $getUserID = Post::find(Session::get('post_id'));
                $notification->user_id = $talent_id;
                $notification->subject = 'invitation';
                $notification->body = Session::get('username').' has invited you on a post. Titled: '.$getUserID['title'];
                $notification->object_id = Session::get('post_id');
                $notification->is_read = 0;
                $notification->sent_at = new Carbon();
                $notification->save();
                $changecontact = preg_replace('/^0/','63',$findTalent['contactno']);
                Mail::send('emails.invitation', ['findTalent' => $findTalent], function($message) use ($findTalent) {
                $message->to($findTalent['emailaddress'], $findTalent['username'])
                    ->subject('Congratulations! You have been invited.');
                });
                $chikkadata = array('number'=> $changecontact, 'message'=> 'A scout has seen your potential. You have been invited to an event! Please visit Talent Scout for more information!');
                ChikkaController::send($chikkadata);
                Session::flash('invite', 'User successfully invited!');
                return Redirect::back();
            }
            else {
                if($invite['status'] == 1 && Session::get('post_id') == $invite['post_id']) {
                Session::flash('invite', 'User already invited!');
                return Redirect::back();
                }
                else {
                Session::flash('invite', 'User already invited!');
                return Redirect::back();
                }
            }
        }
        else {
            Session::flash('invite', 'User not found!');
            return Redirect::back();
        }
    }
}