<?php

namespace App\Http\Controllers;

use Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Agreement;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\ChargeModel;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Api\Payer;
use PayPal\Api\Payee;
use PayPal\Api\PaymentCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PayerInfo;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Session;
use Redirect;
use Input;
use URL;
use App\Paypalpayment;
use App\User;
use App\VaultCreditCard;

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
    public function createBillingPlan(){
        // Create a new billing plan
        $plan = new Plan();
        $plan->setName('Talent Scout Billing Plan')
          ->setDescription('This is a subscription fee for using Talent Scout. It will be use to maintain the site and make users secure.')
          ->setType('fixed');

        // Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
          ->setType('REGULAR')
          ->setFrequency('Month')
          ->setFrequencyInterval('2')
          ->setCycles('12')
          ->setAmount(0);
          //new Currency(array('value' => 1, 'currency' => 'USD'))
        // Set charge models
        $chargeModel = new ChargeModel();
        $chargeModel->setType('SHIPPING')
          ->setAmount(0);
          //new Currency(array('value' => 1, 'currency' => 'USD'))
        $paymentDefinition->setChargeModels(array($chargeModel));

        // Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl(\URL::route('processagreement'))
          ->setCancelUrl(URL::to('/profile').'/'.Session::get('id'))
          ->setAutoBillAmount('yes')
          ->setInitialFailAmountAction('CONTINUE')
          ->setMaxFailAttempts('0')
          ->setSetupFee(new Currency(array('value' => 1, 'currency' => 'USD')));

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        try {
          $createdPlan = $plan->create($this->_api_context);

          try {
            $patch = new Patch();
            $value = new PayPalModel('{"state":"ACTIVE"}');
            $patch->setOp('replace')
              ->setPath('/')
              ->setValue($value);
            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);
            $createdPlan->update($patchRequest,  $this->_api_context);
            $plan = Plan::get($createdPlan->getId(),  $this->_api_context);
            
            // Output plan id
            echo $plan->getId();
          } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
          } catch (Exception $ex) {
            die($ex);
          }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
          echo $ex->getCode();
          echo $ex->getData();
          die($ex);
        } catch (Exception $ex) {
          die($ex);
        }
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
    public function createAgreement(){
        // Create new agreement
        $agreement = new Agreement();
        $agreement->setName('Base Agreement')
          ->setDescription('This is a subscription fee for using Talent Scout. It will be use to maintain the site and make users secure.')
          ->setStartDate('2019-06-17T9:45:04Z');

        // Set plan id
        $plan = new Plan();
        //id is from billing plan
        $plan->setId('P-7LL68738C38226707IGPZCFA');
        $agreement->setPlan($plan);

        // Add payer type
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        // Adding shipping details
        $shippingAddress = new ShippingAddress();
        $shippingAddress->setLine1('111 First Street')
          ->setCity('Saratoga')
          ->setState('CA')
          ->setPostalCode('95070')
          ->setCountryCode('US');
        $agreement->setShippingAddress($shippingAddress);
        try {
          // Create agreement
          $agreement = $agreement->create($this->_api_context);

          // Extract approval URL to redirect user
          // dd($agreement);
          foreach($agreement->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
          if (\Config::get('app.debug')) {
            // dd($ex);
            echo "Exception: " . $ex->getMessage() . PHP_EOL;
            $err_data = json_decode($ex->getData(), true);
            exit;
        } else {
            die('Some error occur, sorry for inconvenient');
        }
        } catch (Exception $ex) {
          die($ex);
        }
        if(isset($redirect_url)) {
        // redirect to paypal
        return Redirect::away($redirect_url);
        }
    }
    public function processAgreement(){
        if (isset($_GET['token']) && $_GET['token'] !== null) {
          $token = $_GET['token'];
          $agreement = new \PayPal\Api\Agreement();
          try {
            // Execute agreement
            $agreement->execute($token, $this->_api_context);
            $agreement = \PayPal\Api\Agreement::get($agreement->getId(), $this->_api_context);
            // dd($agreement->getPayer()->getPayerInfo());
            return Redirect::to('/home');
          } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            if (\Config::get('app.debug')) {
            echo "Exception: " . $ex->getMessage() . PHP_EOL;
            $err_data = json_decode($ex->getData(), true);
            exit;
        } else {
            die('Some error occur, sorry for inconvenient');
        }
          } catch (Exception $ex) {
            die($ex);
          }
        } else {
            echo "user canceled agreement";
        }
    }
    public function processCreditCard() {
      //CARD-7LW79227G14871210LCSVLDA
      //retrieve DB for card id
      $card = VaultCreditCard::where('user_id', '=', Session::get('id'))->first();
      $creditCardToken = new CreditCardToken();
      $creditCardToken->setCreditCardId($card['creditcardID']);
      // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // For stored credit card payments, set the CreditCardToken
        // field on this object.
        $fi = new FundingInstrument();
        $fi->setCreditCardToken($creditCardToken);
        // ### Payer
        // A resource representing a Payer that funds a payment
        // For stored credit card payments, set payment method
        // to 'credit_card'.
        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));
        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $item1 = new Item();
        $item1->setName('Ground Coffee 40 oz')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(7.5);
        $item2 = new Item();
        $item2->setName('Granola bars')
            ->setCurrency('USD')
            ->setQuantity(5)
            ->setPrice(2);
        $itemList = new ItemList();
        $itemList->setItems(array($item1, $item2));
        // ### Additional payment details
        // Use this optional field to set additional
        // payment information such as tax, shipping
        // charges etc.
        $details = new Details();
        $details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(17.5);
        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(20)
            ->setDetails($details);
        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());
        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to 'sale'
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));
        // For Sample Purposes Only.
        $request = clone $payment;
        try {
        $payment->create($this->_api_context);
        dd($payment);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
              dd($ex);
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
          }
    }
    public function linkCreditCard() {
      $data = Request::all();
      if($data['firstname'] == null || $data['lastname'] == null ) {
        $data['firstname'] = str_random(10);
        $data['lastname'] = str_random(10);
      }
      // dd($data);
      // ### PaymentCard
      // A resource representing a payment card that can be
      // used to fund a payment.
      $card = new CreditCard();
      $card->setType($data['cardtype'])
          ->setNumber($data['cardnumber'])
          ->setExpireMonth($data['cardmonth'])
          ->setExpireYear($data['cardyear'])
          ->setCvv2($data['cardcvv'])
          ->setFirstName($data['firstname'])
          ->setLastName($data['lastname']);

      $card->setMerchantId("TalentScout");
      $card->setExternalCardId("CardNumber123" . uniqid());
      $card->setExternalCustomerId(Session::get('emailaddress'));
      $request = clone $card;
        try {
        $card->create($this->_api_context);
        if($card->getState() == 'ok'){
          $cred = new VaultCreditCard();
          $cred->id = null;
          $cred->user_id = Session::get('id');
          $cred->creditcardID = $card->getId();
          $cred->ppemail = $data['paypalemail'];
          $cred->save();
          Session::set('cc','activated');
          Session::set('first_login', 0);
          $changeFirstLogin = User::find(Session::get('id'));
          $changeFirstLogin->first_login = 0;
          $changeFirstLogin->save();
          Session::flash('message', 'Successfully linked card!');
          return Redirect::back();
        }
    } catch (\PayPal\Exception\PayPalConnectionException $ex) {
        if (\Config::get('app.debug')) {
          return Redirect::back()->withInput()->with('errorpaypal', json_decode($ex->getData(), true));
            echo "Exception: " . $ex->getMessage() . PHP_EOL;
            $err_data = json_decode($ex->getData(), true);
            exit;
        } else {
            die('Some error occur, sorry for inconvenient');
        }
      }
    }
    public function unlinkCard($id){
      $cred = VaultCreditCard::where('user_id', '=', $id)->first();
      $cred->delete();
      Session::forget('cc');
      Session::set('first_login', 1);
          $changeFirstLogin = User::find(Session::get('id'));
          $changeFirstLogin->first_login = 1;
          $changeFirstLogin->save();
      Session::flash('message', 'Successfully unlinked card!');
          return Redirect::back();
    }
    public static function penalizeUser($id){
       $cc = VaultCreditCard::where('user_id', '=', Session::get('id'))->first();
      $creditCardToken = new CreditCardToken();
      $creditCardToken->setCreditCardId($cc['creditcardID']);
      $fi = new FundingInstrument();
        $fi->setCreditCardToken($creditCardToken);
        // ### Payer
        // A resource representing a Payer that funds a payment
        // For stored credit card payments, set payment method
        // to 'credit_card'.
        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
              ->setFundingInstruments(array($fi));
        $total = 0;
        $item_1 = new Item();
            $item_1->setName('Penalty') // item name
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice('5.00'); // unit price
            $total = 5.00;
            // add item to list
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($total);
            //get the scout's paypal email to send the payment
            $scoutpp = VaultCreditCard::where('user_id', '=', $id)->first();
        $payee = new Payee();
        $payee->setEmail($scoutpp['ppemail']);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setPayee($payee)
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
          $paypal_conf = \Config::get('paypal');
          $_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
          $_api_context->setConfig($paypal_conf['settings']);
            $payment->create($_api_context);
            // dd($payment);
            if ($payment->getState() == 'approved') { // payment made
                    Session::forget('duration');
                    return Redirect::back();
                }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
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
        return Redirect::back()
            ->with('error', 'Unknown error occurred');
    }
    public static function sendPaymentToSystem() {
      $cc = VaultCreditCard::where('user_id', '=', Session::get('id'))->first();
      $creditCardToken = new CreditCardToken();
      $creditCardToken->setCreditCardId($cc['creditcardID']);
      $fi = new FundingInstrument();
        $fi->setCreditCardToken($creditCardToken);
        // ### Payer
        // A resource representing a Payer that funds a payment
        // For stored credit card payments, set payment method
        // to 'credit_card'.
        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
              ->setFundingInstruments(array($fi));
        $total = 0;
        $item_1 = new Item();
            $item_1->setName('Penalty') // item name
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice('5.00'); // unit price
            $total = 5.00;
            // add item to list
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($total);
            //the system's paypal email must be put there
        $payee = new Payee();
        $payee->setEmail("talentscoutphil-facilitator@gmail.com");

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setPayee($payee)
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
           $paypal_conf = \Config::get('paypal');
           $_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
           $_api_context->setConfig($paypal_conf['settings']);
            $payment->create($_api_context);
            // dd($payment);
            if ($payment->getState() == 'approved') { // payment made
                    Session::forget('duration');
                   return Redirect::back();
                }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
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
    public function paythroughcard($price, $duration){
      $cc = VaultCreditCard::where('user_id', '=', Session::get('id'))->first();
      $creditCardToken = new CreditCardToken();
      $creditCardToken->setCreditCardId($cc['creditcardID']);
      // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // For stored credit card payments, set the CreditCardToken
        // field on this object.
        $fi = new FundingInstrument();
        $fi->setCreditCardToken($creditCardToken);
        // ### Payer
        // A resource representing a Payer that funds a payment
        // For stored credit card payments, set payment method
        // to 'credit_card'.
        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
              ->setFundingInstruments(array($fi));
        $total = 0;
        Session::set('duration', $duration);
        if($duration == '1'){
            $item_1 = new Item();
            $item_1->setName('1 week duration') // item name
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($price); // unit price
            $total = $price;
        }
        elseif($duration == '2'){
            $item_1 = new Item();
            $item_1->setName('2 weeks duration') // item name
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($price); // unit price
            $total = $price;
        }
        elseif ($duration == '3') {
            $item_1 = new Item();
            $item_1->setName('3 weeks duration') // item name
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($price); // unit price
            $total = $price;
        }
        // add item to list
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
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
            if ($payment->getState() == 'approved') { // payment made
                    $data = new Paypalpayment;
                    $user = User::find(Session::get('id'));
                    $data->id = null;
                    $data->payment_id = $payment->id;
                    $data->user_id = Session::get('id');
                    $data->firstname = $user['firstname'];
                    $data->lastname = $user['lastname'];
                    $data->state = 'pending';
                    $data->duration = Session::get('duration');
                    $data->save();
                    Session::forget('duration');
                    Session::flash('success', 'Payment Successful!');
                    return Redirect::to('/paymentprocess')
                        ->with('success', 'Payment success');
                }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            if (\Config::get('app.debug')) {
              dd($ex);
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
    public function postPayment()
    {
      $data = Request::all();
      $payer = new Payer();
    $payer->setPaymentMethod('paypal');
    $total = 0;
    Session::set('duration', $data['duration']);
    if($data['duration'] == '1'){
        $item_1 = new Item();
        $item_1->setName('1 week duration') // item name
        ->setCurrency('USD')
        ->setQuantity(1)
        ->setPrice($data['hiddenprice'][0]); // unit price
        $total = $data['hiddenprice'][0];
    }
    elseif($data['duration'] == '2'){
        $item_1 = new Item();
        $item_1->setName('2 weeks duration') // item name
        ->setCurrency('USD')
        ->setQuantity(1)
        ->setPrice($data['hiddenprice'][1]); // unit price
        $total = $data['hiddenprice'][1];
    }
    elseif ($data['duration'] == '3') {
        $item_1 = new Item();
        $item_1->setName('3 weeks duration') // item name
        ->setCurrency('USD')
        ->setQuantity(1)
        ->setPrice($data['hiddenprice'][2]); // unit price
        $total = $data['hiddenprice'][2];
    }
    // add item to list
    $item_list = new ItemList();
    $item_list->setItems(array($item_1));
    $amount = new Amount();
    $amount->setCurrency('USD')
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
    } catch (\PayPal\Exception\PayPalConnectionException $ex) {
        if (\Config::get('app.debug')) {
          dd($ex);
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
        $data->state = 'pending';
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
