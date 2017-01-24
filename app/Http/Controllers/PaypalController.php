<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Session;
use Redirect;
use Input;

use App\Paypalpayment;
use App\User;

class PaypalController extends Controller
{
    private $_api_context;
    public function __construct()
    {
        
        // setup PayPal api context
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }
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
    public function postPayment(Request $request)
    {
        // dd($request['duration']);
    $payer = new Payer();
    $payer->setPaymentMethod('paypal');
    $total = 0;
    Session::set('duration', $request['duration']);
    if($request['duration'] == '1'){
        $item_1 = new Item();
        $item_1->setName('1 week duration') // item name
        ->setCurrency('PHP')
        ->setQuantity(1)
        ->setPrice('129.99'); // unit price
        $total = 129.99;
    }
    elseif($request['duration'] == '2'){
        $item_1 = new Item();
        $item_1->setName('2 weeks duration') // item name
        ->setCurrency('PHP')
        ->setQuantity(1)
        ->setPrice('229.99'); // unit price
        $total = 229.99;
    }
    elseif ($request['duration'] == '3') {
        $item_1 = new Item();
        $item_1->setName('3 weeks duration') // item name
        ->setCurrency('PHP')
        ->setQuantity(1)
        ->setPrice('311.99'); // unit price
        $total = 311.99;
    }
    // $item_1 = new Item();
    // $item_1->setName('Item 1') // item name
    //     ->setCurrency('PHP')
    //     ->setQuantity(2)
    //     ->setPrice('15'); // unit price

    // $item_2 = new Item();
    // $item_2->setName('Item 2')
    //     ->setCurrency('PHP')
    //     ->setQuantity(4)
    //     ->setPrice('7');
    // $item_3 = new Item();
    // $item_3->setName('Item 3')
    //     ->setCurrency('PHP')
    //     ->setQuantity(1)
    //     ->setPrice('20');
    // add item to list
    $item_list = new ItemList();
    $item_list->setItems(array($item_1));
    $amount = new Amount();
    $amount->setCurrency('PHP')
        ->setTotal($total);
    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setItemList($item_list)
        ->setDescription('Your transaction description');
    $redirect_urls = new RedirectUrls();
    $redirect_urls->setReturnUrl(\URL::route('payment.status'))
        ->setCancelUrl(\URL::route('payment.status'));
    $payment = new Payment();
    $payment->setIntent('Sale')
        ->setPayer($payer)
        ->setRedirectUrls($redirect_urls)
        ->setTransactions(array($transaction));
    try {
        $payment->create($this->_api_context);
    } catch (\PayPal\Exception\PPConnectionException $ex) {
        if (\Config::get('app.debug')) {
            echo "Exception: " . $ex->getMessage() . PHP_EOL;
            $err_data = json_decode($ex->getData(), true);
            exit;
        } else {
            die('Some error occur, sorry for inconvenient');
        }
    }
    foreach($payment->getLinks() as $link) {
        if($link->getRel() == 'approval_url') {
            $redirect_url = $link->getHref();
            break;
        }
    }
    // add payment ID to session
    Session::put('paypal_payment_id', $payment->getId());
    if(isset($redirect_url)) {
        // redirect to paypal
        return Redirect::away($redirect_url);
    }
    Session::flash('failed', 'Something went wrong!');
    return Redirect::to('/home')
        ->with('error', 'Unknown error occurred');
    }

    public function getPaymentStatus()
    {
    // Get the payment ID before session clear
    $payment_id = Session::get('paypal_payment_id');
    // clear the session payment ID
    Session::forget('paypal_payment_id');
    if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
        Session::flash('failed', 'Something went wrong!');
        return Redirect::to('/paymentprocess')
            ->with('error', 'Payment failed');
    }
    $payment = Payment::get($payment_id, $this->_api_context);
    // PaymentExecution object includes information necessary 
    // to execute a PayPal account payment. 
    // The payer_id is added to the request query parameters
    // when the user is redirected from paypal back to your site
    $execution = new PaymentExecution();
    $execution->setPayerId(Input::get('PayerID'));
    
    //Execute the payment
    $result = $payment->execute($execution, $this->_api_context);
    // echo '<pre>';print_r($result);echo '</pre>';exit; // DEBUG RESULT, remove it later
    // dd($result->id);
    if ($result->getState() == 'approved') { // payment made
        $data = new Paypalpayment;
        $user = User::find(Session::get('id'));
        $data->id = null;
        $data->payment_id = $result->id;
        $data->user_id = Session::get('id');
        $data->firstname = $user['firstname'];
        $data->lastname = $user['lastname'];
        $data->state = 'pending'
        $data->duration = Session::get('duration');
        $data->save();
        Session::forget('duration');
        Session::flash('success', 'Payment Successful!');
        return Redirect::to('/paymentprocess')
            ->with('success', 'Payment success');
    }
    Session::flash('failed', 'Payment failed!!');
    return Redirect::to('/paymentprocess')
        ->with('error', 'Payment failed');
    }
}
