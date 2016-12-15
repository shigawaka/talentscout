<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redirect;
class ChikkaController extends Controller
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
    public static function send($data) {
        $client = env('CHIKKA_CLIENT_ID');
        $secret = env('CHIKKA_CLIENT_SECRET');
        $shortcode = env('CHIKKA_CLIENT_SHORTCODE');
        $number =   $data['number'];
        $message = $data['message'];
        // Validate API
        if ( (empty($client)) || (empty($secret)) || (empty($shortcode)) ) {
            $request->session()->flash('error', 'You have incomplete Chikka SMS API credentials.');
        }

        // Send
        $arr_post_body = array(
            "message_type"      =>      "SEND",
            "mobile_number"     =>      $number,
            "shortcode"         =>      $shortcode,
            "message_id"        =>      str_random(32),
            "message"           =>      urlencode($message),
            "client_id"         =>      $client,
            "secret_key"        =>      $secret
        );
    
        $query_string = "";
        foreach($arr_post_body as $key => $frow)
        {
            $query_string .= '&'.$key.'='.$frow;
        }

        $URL = "https://post.chikka.com/smsapi/request";

        $curl_handler = curl_init();
        curl_setopt($curl_handler, CURLOPT_URL, $URL);
        curl_setopt($curl_handler, CURLOPT_POST, count($arr_post_body));
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $query_string);
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($curl_handler);
       
        curl_close($curl_handler);
        $resp = json_decode($response);
        if ($resp->status == 200) {
            // dd('succ');
            echo 'success!';
        } else {
            // dd($resp);
            // dd('fail');
            echo 'something went wrong!';
        }
        return Redirect::back();
    }
}
