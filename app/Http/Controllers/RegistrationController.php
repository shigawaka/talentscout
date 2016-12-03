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
use App\Scout;
use App\Group;
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
            'birthday' => 'required|date',
            'contact' => 'required|numeric',
            'emailaddress' => 'required|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required',
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
                $detail->contactno =$data['contact'];
                $detail->emailaddress =$data['emailaddress'];
                $detail->username =strtolower($data['username']);
                $detail->password = Hash::make($data['password']);
                $detail->profile_image ='avatar.png';
                $detail->profile_description =' ';
                $confirmation_code = str_random(30);
                $detail->confirmation_code = $confirmation_code;
                $detail->save();
                $talent = new Talent;
                $talent->id = $detail->id;
                $talent->talents = null;
                $talent->fee = 0;
                $talent->save();
                Mail::send('emails.emailactivation', ['confirmation_code' => $confirmation_code], function($message) {
                $message->to(Request::get('emailaddress'), Request::get('username'))
                    ->subject('Verify your email address');
                });
                Session::flash('message', 'Thanks for signing up! Please check your email.');
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
            'groupname' => 'regex:/^[\pL\s\-]+$/u',
            'founded' => 'required|date',
            'contactgroup' => 'required|numeric',
            'emailaddressg' => 'required|unique:group',
            'user_name' => 'required|unique:group',
            'passwordg' => 'required',
        );

        $message = array(
            'founded.required' => 'Required',
            'founded.date' => 'Date',
            'contactgroup.required' => 'Required',
            'contactgroup.numeric' => 'Numbers only',
            'email-address.required' => 'Required',
            'user_name.required' => 'Required',
            'passwordg.required' => 'Required',
        );

        $validation = Validator::make($data, $rules, $message);

        if($validation->passes()) {
            if(Group::where('user_name', $data['user_name'])->first()) {
                return back()->withInput();
            } else {
                $detail = new Group;
                $detail->id =null;
                $detail->groupname =strtolower($data['groupname']);
                $detail->founded =$data['founded'];
                $detail->contactno =$data['contactgroup'];
                $detail->emailaddressg =$data['emailaddressg'];
                $detail->user_name =strtolower($data['user_name']);
                $detail->password = Hash::make($data['passwordg']);
                $confirmation_code = str_random(30);
                $detail->confirmation_code = $confirmation_code;
                $detail->save();
                if(!Mail::send('emails.emailactivation', ['confirmation_code' => $confirmation_code], function($message) {
                $message->to(Request::get('emailaddressg'), Request::get('user_name'))
                    ->subject('Verify your email address');
                })) {
                    dd('test');
                } 
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
            'contact' => 'required|numeric',
            'emailaddress' => 'required|unique:users',
            'username' => 'required|unique:users',
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
                $detail->firstname =strtolower($data['firstname']);
                $detail->lastname =strtolower($data['lastname']);
                $detail->birthday =$data['birthday'];
                $detail->contactno =$data['contact'];
                $detail->emailaddress =$data['emailaddress'];
                $detail->username =strtolower($data['username']);
                $detail->password = Hash::make($data['password']);
                $detail->profile_image ='avatar.png';
                $detail->profile_description =' ';
                $confirmation_code = str_random(30);
                $detail->confirmation_code = $confirmation_code;
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
            Session::flash('message', 'Confirmation error!');
            return Redirect::to('http://localhost:8000/login');
        }
        $user = User::where('confirmation_code', '=', $confirmation_code)->first();
        if ( ! $user)
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
    public function confirmGroup($confirmation_code)
    {
        if( ! $confirmation_code) {
            Session::flash('message', 'Confirmation error!');
            return Redirect::to('http://localhost:8000/login');
        }
        $user = Group::where('confirmation_code', '=', $confirmation_code)->first();
        if ( ! $user)
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
}
