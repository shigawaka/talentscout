<?php

namespace App\Http\Controllers;

use Request;
use Redirect;
use Validator;
use Mail;
use App\User;
use App\Talent;
use Hash;
use Auth;
use Session;
use App\Featured;
use App\Scout;
use App\Group;
use App\Subscription;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RegistrationController extends Controller
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
            'lastname' => 'required|regex:/^[\pL\s\-]+$/u',
            'firstname' => 'required|regex:/^[\pL\s\-]+$/u',
            'birthday' => 'required|date|before:2011-01-01',
            'contact' => 'required|numeric|regex:/(09)[0-9]{9}/',
            'emailaddress' => 'required|unique:users',
            'username' => 'required|unique:users|min:5',
            'password' => 'required|alphaNum|min:6',
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
            if(User::where('username', $data['username'])->first()) {
                return back()->withInput();
            } else {
                $detail = new User;
                $detail->id =null;
                $detail->roleID =1;
                $detail->firstname =strtolower($data['firstname']);
                $detail->lastname =strtolower($data['lastname']);
                $detail->birthday =$data['birthday'];
                //using Carbon package for easier date
                $created = Carbon::createFromFormat('Y-m-d', $data['birthday']);
                $now = Carbon::now(); 
                $detail->age = $created->diffInYears($now);
                $detail->gender = strtolower($data['gender']);
                $changecontact = preg_replace('/^0/','63',$data['contact']);
                $detail->contactno =$changecontact;
                $detail->emailaddress =$data['emailaddress'];
                $detail->username =strtolower($data['username']);
                $detail->password = Hash::make($data['password']);
                $detail->profile_image ='avatar.png';
                $detail->profile_description =' ';
                $confirmation_code = str_random(7);
                $detail->confirmation_code = $confirmation_code;
                $detail->first_login = 1;
                $detail->expiration = Carbon::now()->addMinutes(15);
                $detail->save();
                $talent = new Talent;
                $talent->id = $detail->id;
                $talent->talents = null;
                $talent->fee = 0;
                $talent->fee_type = null;
                $talent->save();
                Mail::send('emails.emailactivation', ['confirmation_code' => $confirmation_code], function($message) {
                $message->to(Request::get('emailaddress'), Request::get('username'))
                    ->subject('Verify your email address');
                });
                Session::flash('message', 'Thanks for signing up! Please check your email to activate your account.');
                $chikkadata = array('number'=> $changecontact, 'message'=> 'Thank you for signing up in Talent Scout! '.ucfirst($detail->firstname).' '.ucfirst($detail->lastname).'.This is where your path to stardom begins! Follow this link to activate your account! This expires in 15 minutes! http://localhost:8000/register/verify/'.$confirmation_code.'');
                ChikkaController::send($chikkadata);
                return Redirect::to('http://localhost:8000/login');
            }
        } else {
            return Redirect::back()->withInput()->withErrors($validation);
        }
    }
    public function storeGroup(Request $request)
    {
        $data = Request::all();
        
        $rules = array(
            'groupname' => 'regex:([\w ]+)|unique:group',
            'founded' => 'required|date',
            'contactgroup' => 'required|numeric|regex:/(09)[0-9]{9}/',
            'emailaddress' => 'unique:users',
            'username' => 'unique:users|min:5',
            'passwordg' => 'required|alphaNum|min:6',
        );

        $message = array(
            'founded.required' => 'Required',
            'founded.date' => 'Date',
            'contactgroup.required' => 'Required',
            'contactgroup.numeric' => 'Numbers only',
            'email-address.required' => 'Required',
            'user_name.required' => 'Required',
            'passwordg.required' => 'Required',
            'groupname.unique' => 'This name is already taken. Please choose another one.',
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()) {
            if(User::where('username', $data['username'])->first()) {
                return back()->withInput();
            } else {
                $changecontact = preg_replace('/^0/','63',$data['contactgroup']);
                $detail = new User;
                $detail->id =null;
                $detail->roleID =2;
                //using Carbon package for easier date
                $created = Carbon::createFromFormat('Y-m-d', $data['founded']);
                $now = Carbon::now(); 
                $detail->birthday = $data['founded'];
                $detail->age = $created->diffInYears($now);
                $detail->contactno =$changecontact;
                $detail->emailaddress =$data['emailaddress'];
                $detail->username =strtolower($data['username']);
                $detail->password = Hash::make($data['passwordg']);
                $detail->profile_image ='avatar.png';
                $detail->profile_description =' ';
                $confirmation_code = str_random(7);
                $detail->confirmation_code = $confirmation_code;
                $detail->first_login = 1;
                $detail->expiration = Carbon::now()->addMinutes(15);
                $detail->save();
                $talent = new Talent;
                $talent->id = $detail->id;
                $talent->talents = null;
                $talent->fee = 0;
                $talent->save();


                $group = new Group;
                $group->id =$detail->id;
                $group->groupname =strtolower($data['groupname']);
                $group->founded =$data['founded'];
                $group->contactno =$changecontact;
                $group->emailaddressg =$data['emailaddress'];
                $group->save();
                Mail::send('emails.emailactivation', ['confirmation_code' => $confirmation_code], function($message) {
                $message->to(Request::get('emailaddress'), Request::get('username'))
                    ->subject('Verify your email address');
                });
                $chikkadata = array('number'=> $changecontact, 'message'=> 'Thank you for signing up in Talent Scout! '.ucfirst($detail->groupname).'.This is where your path to stardom begins! Follow this link to activate your account! This expires in 15 minutes! http://localhost:8000/register/verify/'.$confirmation_code.'');
                ChikkaController::send($chikkadata);
                Session::flash('message', 'Thanks for signing up! Please check your email.');
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
    public function show($id)
    {
        //
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

    public function storeScout(Request $request)
    {
        $data = Request::all();

        $rules = array(
            'firstname' => 'required|regex:/^[\pL\s\-]+$/u',
            'lastname' => 'required|regex:/^[\pL\s\-]+$/u',
            'birthday' => 'required|date',
            'contact' => 'required|numeric|regex:/(09)[0-9]{9}/',
            'emailaddress' => 'required|unique:users',
            'username' => 'required|unique:users|min:5',
            'password' => 'required|alphaNum|min:6',
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
                $changecontact = preg_replace('/^0/','63',$data['contact']);
                $detail = new User;

                $detail->id =null;
                $detail->roleID =0;
                $detail->firstname =strtolower($data['firstname']);
                $detail->lastname =strtolower($data['lastname']);
                $detail->birthday =$data['birthday'];
                //using Carbon package for easier date
                $created = Carbon::createFromFormat('Y-m-d', $data['birthday']);
                $now = Carbon::now(); 
                $detail->age = $created->diffInYears($now);
                $detail->gender = strtolower($data['gender']);
                $detail->contactno =$changecontact;
                $detail->emailaddress =$data['emailaddress'];
                $detail->username =strtolower($data['username']);
                $detail->password = Hash::make($data['password']);
                $detail->profile_image ='avatar.png';
                $detail->profile_description =' ';
                $confirmation_code = str_random(7);
                $detail->confirmation_code = $confirmation_code;
                $detail->first_login = 1;
                $detail->expiration = Carbon::now()->addMinutes(15);
                $detail->save();

                $scout = new Scout;
                $scout->id = $detail->id;
                $scout->score = 0;
                $scout->demerit = 0;
                $scout->save();
                Mail::send('emails.emailactivation', ['confirmation_code' => $confirmation_code], function($message) {
                $message->to(Request::get('emailaddress'), Request::get('username'))
                    ->subject('Verify your email address');
                });
                $chikkadata = array('number'=> $changecontact, 'message'=> 'Thank you for signing up in Talent Scout!'.ucfirst($detail->firstname).' '.ucfirst($detail->lastname).'.Hope you find the right talented person fit for your needs! Follow this link to activate your account! This expires in 15 minutes! http://localhost:8000/register/verify/'.$confirmation_code.'');
                ChikkaController::send($chikkadata);
                Session::flash('message', 'Thanks for signing up! We have sent an activation code to your email.');
                return Redirect::to('http://localhost:8000/login');
            }
        } else {
            return Redirect::back()->withInput()->withErrors($validation);
        }
    }
     public function confirm($confirmation_code)
    {

        if( ! $confirmation_code) {
            Session::flash('message', 'Confirmation error! The code is either invalid or it has reached the maximum expiration time!');
            return Redirect::to('http://localhost:8000/login');
        }
        $user = User::where('confirmation_code', '=', $confirmation_code)->first();
        $expiration = Carbon::parse($user['expiration']);
        $checkExp = Carbon::now()->lte($expiration);
        if($checkExp == false){
            Session::flash('message', 'The code is either invalid or expired!');
            return Redirect::to('http://localhost:8000/login');
        }
        elseif ( ! $user)
        {
             Session::flash('message', 'Account Already Activated!');
              return Redirect::to('http://localhost:8000/login');
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        Session::flash('message', 'You have successfully activated your account!');

        return Redirect::to('http://localhost:8000/login');
    }
    // public function confirmGroup($confirmation_code)
    // {
    //     if( ! $confirmation_code) {
    //         Session::flash('message', 'Confirmation error!');
    //         return Redirect::to('http://localhost:8000/login');
    //     }
    //     $user = Group::where('confirmation_code', '=', $confirmation_code)->first();
    //     if ( ! $user)
    //     {
    //          Session::flash('message', 'Account Already Activated!');
    //           return Redirect::to('http://localhost:8000/login');
    //     }

    //     $user->confirmed = 1;
    //     $user->confirmation_code = null;
    //     $user->save();

    //     Session::flash('message', 'You have successfully activated your account!');

    //     return Redirect::to('http://localhost:8000/login');
    // }
    public function resetPasswordCode($confirmation_code)
    {
        if( ! $confirmation_code) {
            Session::flash('message', 'Confirmation error!');
            return Redirect::to('http://localhost:8000/login');
        }
        $user = User::where('confirmation_code', '=', $confirmation_code)->first();
        if ( ! $user)
        {
             Session::flash('message', 'Account Already Activated!');
              return Redirect::to('http://localhost:8000/login');
        }

        $user->confirmation_code = null;
        $random_pass = str_random(10);
        $user->password = Hash::make($random_pass);
        $user->save();

        Session::flash('message', 'Password reset successful! Please remember it and change your password now! Your new password is '.$random_pass);

        return Redirect::to('http://localhost:8000/login');
    }
    public function resendActivationcode($confirmation_code)
    {
        if( ! $confirmation_code) {
            Session::flash('message', 'Confirmation error!');
            return Redirect::to('http://localhost:8000/login');
        }
        $user = User::where('confirmation_code', '=', $confirmation_code)->first();
        if ( ! $user)
        {
             Session::flash('message', 'Account Already Activated!');
              return Redirect::to('http://localhost:8000/login');
        }

        $random_code = str_random(7);
        $user->confirmation_code = $random_code;
        $user->expiration = Carbon::now()->addMinutes(15);
        $user->save();

        Session::flash('message', 'We have sent another activation code! Please follow the link provided. It will expire in 15 minutes! ');
        return Redirect::to('http://localhost:8000/login');
    }
    public function resetPassword(){
        $detail = User::where('emailaddress', '=', Request::get('emailaddress'))->first();
        $confirmation_code = str_random(30);
        $detail->confirmation_code = $confirmation_code;
        $detail->save();    
         Mail::send('emails.resetpassword', ['confirmation_code' => $confirmation_code], function($message) {
                $message->to(Request::get('emailaddress'), Request::get('username'))
                    ->subject('Password Reset');
                });
                Session::flash('message', 'Password Reset Link sent! Kindly check your email.');
                return Redirect::to('http://localhost:8000/login');
    }
    public function resendActivation(){
        $detail = User::where('emailaddress', '=', Request::get('emailaddress'))
                        ->where('confirmed', '=', null)
                        ->first();
        if($detail == null){
            Session::flash('message', 'Account is already activated!');
            return Redirect::to('http://localhost:8000/login');
        }
        else {

        $confirmation_code = str_random(7);
        $detail->confirmation_code = $confirmation_code;
        $detail->expiration = Carbon::now()->addMinutes(15);
        $detail->save();    
         Mail::send('emails.resendActivation', ['confirmation_code' => $confirmation_code], function($message) {
                $message->to(Request::get('emailaddress'), Request::get('username'))
                    ->subject('Resend Activation Code');
                });
                Session::flash('message', 'We have sent another confirmation code! Kindly check your email.');
                return Redirect::to('http://localhost:8000/login');
        }
    }
}
