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


use App\Http\Requests;
use App\Http\Controllers\Controller;

class TalentController extends Controller
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
            'groupname' => 'regex:/^[\pL\s\-]+$/u',
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
            'groupname.alpha' => 'Letters only',
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
                $detail->firstname =$data['firstname'];
                $detail->lastname =$data['lastname'];
                $detail->birthday =$data['birthday']; 
                $detail->contactno =$data['contact'];
                if(in_array('groupname', $data)){
                    $detail->groupname =$data['groupname'];
                }
                $detail->emailaddress =$data['emailaddress'];
                $detail->username =$data['username'];
                $detail->password = Hash::make($data['password']);
                $detail->profile_image ='avatar.png';
                $detail->profile_description =' ';
                $detail->save();
                $talent = new Talent;
                $talent->id = $detail->id;
                $talent->talents = null;
                $talent->fee = 0;
                $talent->save();
                Mail::send('email.verify', $confirmation_code, function($message) {
                $message->to($data['emailaddress'], $data['username'])
                    ->subject('Verify your email address');
                });

                Flash::message('Thanks for signing up! Please check your email.');
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
            'emailaddressgroup' => 'required|unique:group',
            'username' => 'required|unique:group',
            'password' => 'required',
        );

        $message = array(
            'founded.required' => 'Required',
            'founded.date' => 'Date',
            'contactgroup.required' => 'Required',
            'contactgroup.numeric' => 'Numbers only',
            'emailaddressgroup.required' => 'Required',
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
                $detail->firstname =$data['firstname'];
                $detail->lastname =$data['lastname'];
                $detail->birthday =$data['birthday'];
                $detail->contactno =$data['contact'];
                $detail->groupname =$data['groupname'];
                $detail->emailaddress =$data['email'];
                $detail->username =$data['username'];
                $detail->password = Hash::make($data['password']);

                $detail->save();
                Session::flash('message', 'Successfully registered!');
                return Redirect::to('http://localhost:8000/');
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
}
