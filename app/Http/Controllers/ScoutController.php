<?php

namespace App\Http\Controllers;

use Request;
use Redirect;
use Validator;
// use Storage;


use App\User;
use App\Post;
use App\Comment;
use App\Proposal;
use App\Rating;
use App\Invitation;
use App\Talent;
use Carbon\Carbon;
use Hash;
use Mail;
use Auth;
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
        return view('scout/post')->with('posts',$posts);
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
            $fullcomm = array();
        Session::set('post_id', $post_id);
        $exists = Proposal::where('user_id', '=', Auth::user()->id)->where('post_id', '=', $post_id)->first();
        
        Session::set('proposal_id', $exists['id']);
        
         $posts = Post::where('id', $post_id)->first();
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
                 $fullproposals[$j]['proposed_rate'] = $retrieveprops['proposed_rate'];
                 $hired = Post::where('id', '=', $post_id)->get();
                 foreach($hired as $hire){
                    if(count(json_decode($hire['hire_id'], true)) > 1) {
                    $numhire = count(json_decode($hire['hire_id'], true));
                    $temp = json_decode($hire['hire_id']);
                    // list($temp) = explode(',', implode(',',json_decode($hire['hire_id'], true)));
                    // $fullproposals[$j]['hired'] = $temp[$j++];
                    // echo $temp;
                    for ($i=0; $i < $numhire; $i++) { 
                    $otheruser = User::find($temp[$i]);
                     $fullproposals[$j]['hired'] = $temp[$i];
                            // $fullproposals[$i]['user_id'] = $otheruser['id'];
                            // $fullproposals[$i]['profile_image'] = $otheruser['profile_image'];
                            // $fullproposals[$i]['firstname'] = $otheruser['firstname'];
                            // $fullproposals[$i]['lastname'] = $otheruser['lastname'];
                            // $fullproposals[$i]['body'] = $retrieveprops['body'];
                            // $fullproposals[$i]['proposed_rate'] = $retrieveprops['proposed_rate'];
                            // $fullproposals[$i]['hired'] = $temp[$i];
                    }
                    // dd($otheruser);
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
                    if(count(json_decode($hire['hire_id'], true)) >= 1) {
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
                            $fullclosedeals[$i]['body'] = $retrieveprops['body'];
                            $fullclosedeals[$i]['proposed_rate'] = $retrieveprops['proposed_rate'];
                            $fullclosedeals[$i]['hired'] = $temp[$i];
                        }
                    }
                 }
         //finding the recommended profiles for this post
         $recommended = array();
         $k = 0;
         foreach (json_decode($posts['tags']) as $tal){
            $getTalent = Talent::where('fee', '<=', $posts['budget'])
                                ->where('demerit', '<', 1500)
                                ->get();
            foreach ($getTalent as $key => $value) {
                
                if(stripos($value['talents'], $tal) !== false){
                    $findProf = User::find($value['id']);
                    if(!empty($recommended)){
                                    $recommended[$k]['id'] = $findProf['id'];
                                    $recommended[$k]['profile_image'] = $findProf['profile_image'];
                                    $recommended[$k]['firstname'] = ucfirst($findProf['firstname']);
                                    $recommended[$k]['lastname'] = ucfirst($findProf['lastname']);
                                    $k++;
                    }
                    else { 
                            $recommended[$k]['id'] = $findProf['id'];
                            $recommended[$k]['profile_image'] = $findProf['profile_image'];
                            $recommended[$k]['firstname'] = ucfirst($findProf['firstname']);
                            $recommended[$k]['lastname'] = ucfirst($findProf['lastname']);
                            $k++;
                    }
                }
            }
         }
         // dd($fullproposals);
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
         return view('scout.viewpost')
         ->with('posts', $posts)
         ->with('proposal', $exists)
         ->with('recommended', $entries)
         ->with('fullproposals', $fullproposals)
         ->with('fullclosedeals', $fullclosedeals)
         ->with('comments', $fullcomm)
         ->with('tag', json_decode($posts['tags'], true));
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

        if($validation->passes()) {
            if(Post::where('title', $data['title'])->first()) {
                return back()->withInput();
            } else {
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
                $tags = json_encode(explode(',', $data['tags'][0]));
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
        if(empty($hire['hire_id'])){
            $hire->hire_id = json_encode(explode(',', $talent_id[0]));
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
            if($findUser == null){
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
                $rateUser->score = $score;
                $rateUser->demerit = $demerit;
                $rateUser->save();
            }
            else {
                $findUser->id = null;
                $findUser->user_id = $user['id'];
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
                // dd($findUser['score']);
                $findUser->score = $score;
                $findUser->demerit = $demerit;
                $findUser->save();            
            }
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

        $setPost = Post::find(Session::get('post_id'));
        $setPost->status = 1;
        $setPost->save();
        return Redirect::to('/post');
    }
    public function inviteTalent($talent_id){
        $findTalent = User::find($talent_id);
        // dd($findTalent['emailaddress']);
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
                Session::flash('invite', 'User successfully invited!');
            //     Mail::send('emails.invitation', ['user' => $findTalent], function ($m) use ($findTalent) {
            //     $m->from('TalentScout.com', 'Talent Scout');

            //     $m->to($findTalent['emailaddress'])->subject('Invitation!');
            // });
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