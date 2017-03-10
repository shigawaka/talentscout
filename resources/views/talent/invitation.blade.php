    @extends('master')

@section('content') 

    <!doctype html>

    <html lang="en">
      <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Talent Scout</title>

        <!-- Add to homescreen for Chrome on Android -->
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="icon" sizes="192x192" href="images/android-desktop.png">

        <!-- Add to homescreen for Safari on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="Material Design Lite">
        <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

        <!-- Tile icon for Win8 (144x144 + tile color) -->
        <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
        <meta name="msapplication-TileColor" content="#3372DF">

        <link rel="shortcut icon" href="images/favicon.png">

        <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
        <!--
        <link rel="canonical" href="http://www.example.com/">
        -->

        <link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
       <!-- <link rel="stylesheet" href="../../materialize/materialize.min.css"> 
        <link rel="stylesheet" href="../../materialize/styles.css"> -->
     

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- Compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/css/materialize.min.css"> -->
    <link rel="stylesheet" href="../../materialize/css/materialize.min.css">
    <link rel="stylesheet" href="../../materialize/materialize-tags.css">
    <link rel="stylesheet" type="text/css" href="../../css/rating_style.css">
    <!-- Compiled and minified JavaScript -->
    <script src="../../materialize/js/materialize.min.js"></script>
    <script src="../../materialize/materialize-tags.js"></script>    
        <style>
        #view-source {
          position: fixed;
          display: block;
          right: 0;
          bottom: 0;
          margin-right: 40px;
          margin-bottom: 40px;
          z-index: 900;
        }
        </style>

      </head>

          <nav class="red lighten-1" role="navigation">
        <div class="container">
          <div class="nav-wrapper">
            <ul class="left">
                 <li><a href="{!! URL::to('/profile').'/'.$user['id'] !!}">Overview</a></li>
                 <li><a href="{!! URL::to('/portfolio').'/'.$user['id'] !!}">Portfolio</a></li>
                 <li><a href="{!! URL::to('/invitation').'/'.$user['id'] !!}" class="active">Invitation</a></li>
                 <li>
                 <a href="{!! URL::to('/schedule').'/'.$user['id'] !!}">Schedule</a>
                 </li>
                 <li><a href="{!! URL::to('/connection').'/'.$user['id'] !!}">Connections</a></li>

            </ul>

            <ul class="right">
              @if(Session::get('first_login') == 1)
              <li><a href="/home" class="disabled">Home</a></li>
            @else
              <li><a href="/home">Home</a></li>
            @endif
                 <li><a href="/logout">Logout</a></li>
            </ul>
            <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
          </div>
        </div>
      </nav>
      <main>
      <div class="row">
       @if (Session::has('message'))
                  <div id="card-alert" class="card green">
                  <div class="card-content white-text">
                  <p><i class="mdi-navigation-check"></i> {{ Session::get('message') }}</p>
                  </div>
                  </div>
                @endif
    @if(!empty($group))
          @foreach($group as $g => $details)
                <div class="col s12 m7">
                    @if($details['roleID'] == 1)
                    <h2 class="header">Requesting to join group</h2>
                    @else
                    <h2 class="header">Group Invitation</h2>
                    @endif
                    
                    <div class="card horizontal">
                      <div class="card-image">
                        <img style="width:150px;" src="{{ URL::to('/files/').'/'.$details['picture'] }}">
                      </div>
                      <div class="card-stacked">
                        <div class="card-content">
                          @if($details['groupname'] == null)
                          <p>Full Name: {{ ucfirst($details['fullname']) }}</p>
                          @else
                          <p>Group Name: {{ $details['groupname'] }}</p>
                          @endif
                          
                        </div>
                        <div class="card-action">
                        @if($details['roleID'] == 1)
                        <a href="{{ URL::to('/requestjoingroup/accept').'/'.$details['id'] }}">Accept Invitation</a>
                        <a href="{!! URL::to('/requestjoingroup/decline').'/'.$details['id'] !!}">Decline Invitation</a>
                        @else
                        <a href="{{ URL::to('/groupinvitation/accept').'/'.$details['id'] }}">Accept Invitation</a>
                        <a href="{!! URL::to('/groupinvitation/decline').'/'.$details['id'] !!}">Decline Invitation</a>
                        @endif
                        </div>
                      </div>
                    </div>
                  </div>
        @endforeach
    @elseif(empty($postDetails))
      <div>
    <h5 class="center-align">There's nothing here.</h5>
  </div>
    @else
      @foreach($postDetails as $fullprop => $details)
        <div class="col s12 m3">
  <div class="card">
    <div class="card-image waves-effect waves-block waves-light">
    @if(strpos($details['file'],'.mp4') == true)
    <video width="400" controls>
            <source src="{!! URL::to('/files').'/'.$details['file'] !!}" type="video/mp4">
    </video>
    @else
      <img class="activator" src="{!! URL::to('/files').'/'.$details['file'] !!}">
    @endif
    </div>
    <div class="card-content">
      <span class="card-title activator grey-text text-darken-4">{!! $details['title'] !!}<i class="material-icons right">more_vert</i></span>
      @if(!empty($details['hire_id']))
        @if($details['status'] == 0)
          @if(!in_array($hired,json_decode($details['hire_id'])))
          <p><a href="{!! URL::to('/invitation/accept').'/'.$details['id'] !!}">Accept Invitation</a></p>
          <p><a href="{!! URL::to('/invitation/decline').'/'.$details['id'] !!}">Decline Invitation</a></p>
          @else
              <div class="card-panel blue lighten-3 align center">BOOKED</div>
          @endif
        @else 
               <!-- Modal begin -->
               {!! Form::open(['url'=>'/ratescout'.'/'.$details['scout_id'].'/'.$details['id'], 'files' => true]) !!}
          <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Rate Scout</a>
          <div id="modal1" class="modal">
          <div class="modal-content">
            <h4>Rate Scout</h4>
            <h5>{!! $details['firstname'].' '.$details['lastname'] !!}</h5>
            <a href="{!! URL::to('/profile').'/'.$details['scout_id'] !!}"><img style="width: 150px; height: 130px;" src="{!! URL::to('/files').'/'.$details['profile_image'] !!}"></a>
          <!-- attitude -->
                <div class="col-xs-12">
                <label class="label label-info">Attitude</label>
    <div class="stars" style="margin:0;">
        {!! Form::radio('attitude['.$fullprop.']', 1, false, ['class' => 'star-1', 'id' => 'star-1a'.$fullprop.'']) !!}
        <!-- <input  type="radio" name="attitude{{$fullprop}}" class="star-1" id="star-1a{{$fullprop}}" /> -->
        <label title="Bad!" class="star-1" for="star-1a{{$fullprop}}">1</label>
        {!! Form::radio('attitude['.$fullprop.']', 2, false, ['class' => 'star-2', 'id' => 'star-2a'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-2" id="star-2a{{$fullprop}}" /> -->
        <label title="Not Bad!" class="star-2" for="star-2a{{$fullprop}}">2</label>
        {!! Form::radio('attitude['.$fullprop.']', 3, false, ['class' => 'star-3', 'id' => 'star-3a'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-3" id="star-3a{{$fullprop}}" /> -->
        <label title="Good!" class="star-3" for="star-3a{{$fullprop}}">3</label>
        {!! Form::radio('attitude['.$fullprop.']', 4, false, ['class' => 'star-4', 'id' => 'star-4a'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-4" id="star-4a{{$fullprop}}" /> -->
        <label title="Very Good!" class="star-4" for="star-4a{{$fullprop}}">4</label>
        {!! Form::radio('attitude['.$fullprop.']', 5, false, ['class' => 'star-5', 'id' => 'star-5a'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-5" id="star-5a{{$fullprop}}" /> -->
        <label title="Excellent!" class="star-5" for="star-5a{{$fullprop}}">5</label>
        <span></span>
    </div>
                </div>
                <!-- management -->
                <div class="col-xs-12">
                <label class="label label-info">Management</label>
    <div class="stars" style="margin:0;">
        {!! Form::radio('management['.$fullprop.']', 1, false, ['class' => 'star-1', 'id' => 'star-1p'.$fullprop.'']) !!}
        <!-- <input  type="radio" name="attitude{{$fullprop}}" class="star-1" id="star-1a{{$fullprop}}" /> -->
        <label title="Bad!" class="star-1" for="star-1p{{$fullprop}}">1</label>
        {!! Form::radio('management['.$fullprop.']', 2, false, ['class' => 'star-2', 'id' => 'star-2p'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-2" id="star-2a{{$fullprop}}" /> -->
        <label title="Not Bad!" class="star-2" for="star-2p{{$fullprop}}">2</label>
        {!! Form::radio('management['.$fullprop.']', 3, false, ['class' => 'star-3', 'id' => 'star-3p'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-3" id="star-3a{{$fullprop}}" /> -->
        <label title="Good!" class="star-3" for="star-3p{{$fullprop}}">3</label>
        {!! Form::radio('management['.$fullprop.']', 4, false, ['class' => 'star-4', 'id' => 'star-4p'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-4" id="star-4a{{$fullprop}}" /> -->
        <label title="Very Good!" class="star-4" for="star-4p{{$fullprop}}">4</label>
        {!! Form::radio('management['.$fullprop.']', 5, false, ['class' => 'star-5', 'id' => 'star-5p'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-5" id="star-5a{{$fullprop}}" /> -->
        <label title="Excellent!" class="star-5" for="star-5p{{$fullprop}}">5</label>
        <span></span>
    </div>
                </div>
                <!-- integrity -->
                <div class="col-xs-12">
                <label class="label label-info">Integrity</label>
    <div class="stars" style="margin:0;">
        {!! Form::radio('integrity['.$fullprop.']', 1, false, ['class' => 'star-1', 'id' => 'star-1pun'.$fullprop.'']) !!}
        <!-- <input  type="radio" name="attitude{{$fullprop}}" class="star-1" id="star-1a{{$fullprop}}" /> -->
        <label title="Bad!" class="star-1" for="star-1pun{{$fullprop}}">1</label>
        {!! Form::radio('integrity['.$fullprop.']', 2, false, ['class' => 'star-2', 'id' => 'star-2pun'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-2" id="star-2a{{$fullprop}}" /> -->
        <label title="Not Bad!" class="star-2" for="star-2pun{{$fullprop}}">2</label>
        {!! Form::radio('integrity['.$fullprop.']', 3, false, ['class' => 'star-3', 'id' => 'star-3pun'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-3" id="star-3a{{$fullprop}}" /> -->
        <label title="Good!" class="star-3" for="star-3pun{{$fullprop}}">3</label>
        {!! Form::radio('integrity['.$fullprop.']', 4, false, ['class' => 'star-4', 'id' => 'star-4pun'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-4" id="star-4a{{$fullprop}}" /> -->
        <label title="Very Good!" class="star-4" for="star-4pun{{$fullprop}}">4</label>
        {!! Form::radio('integrity['.$fullprop.']', 5, false, ['class' => 'star-5', 'id' => 'star-5pun'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-5" id="star-5a{{$fullprop}}" /> -->
        <label title="Excellent!" class="star-5" for="star-5pun{{$fullprop}}">5</label>
        <span></span>
    </div>
                </div>
          </div>
          <div class="col-xs-12">
                {!! Form::textarea('comment', '', array('style' => 'min-width:100%;resize:none;','class' => 'materialize-textarea','placeholder' => 'Describe your experience with this scout!')) !!}
                </div>
                <div class="col-xs-12" id="testimony">
                <hr />
                <label class="label label-info">How helpful was Talent Scout to you?</label>
                <div class="stars" style="margin:0;">
                {!! Form::radio('testi_score', 1, false, ['class' => 'star-1', 'id' => 'star-1']) !!}
                <label title="Bad!" class="star-1" for="star-1">1</label>
                {!! Form::radio('testi_score', 2, false, ['class' => 'star-2', 'id' => 'star-2']) !!}
                <label title="Not Bad!" class="star-2" for="star-2">2</label>
                {!! Form::radio('testi_score', 3, false, ['class' => 'star-3', 'id' => 'star-3']) !!}
                <label title="Good!" class="star-3" for="star-3">3</label>
                {!! Form::radio('testi_score', 4, false, ['class' => 'star-4', 'id' => 'star-4']) !!}
                <label title="Very Good!" class="star-4" for="star-4">4</label>
                {!! Form::radio('testi_score', 5, false, ['class' => 'star-5', 'id' => 'star-5']) !!}
                <label title="Excellent!" class="star-5" for="star-5">5</label>
                <span></span>
                </div>
                {!! Form::textarea('testimonial_comment', '', array('style' => 'min-width:100%;resize:none;','class' => 'materialize-textarea','placeholder' => 'Describe how helpful was Talent Scout to you.')) !!}
                <a href="#" id="skip">Skip review</a>
                </div>
          <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
          {!! Form::submit('Rate scout!', array('class' => 'btn btn-info')) !!}  
          {!! Form::close(); !!}    
            </div>
          </div>
          <!-- modal ends -->
        @endif
      @else
        <p><a href="{!! URL::to('/invitation/accept').'/'.$details['id'] !!}">Accept Invitation</a></p>
        <p><a href="{!! URL::to('/invitation/decline').'/'.$details['id'] !!}">Decline Invitation</a></p>
      @endif
      <p><a href="{!! URL::to('/post').'/'.$details['id'] !!}">View Post</a></p>
    </div>
    <div class="card-reveal">
      <span class="card-title grey-text text-darken-4">{!! $details['title'] !!}<i class="material-icons right">close</i></span>
      <p>{!! $details['description'] !!}</p>
    </div>
  </div>
        </div>
        @endforeach
    @endif
      </div>
            </main>


          <footer class="page-footer  black">

        <div class="footer-copyright">
          <div class="container" align="center">
     <p class="text-center">Talent Scout.
                        All Rights Reserved. 2016 
                        <small style="color: gray;">Contact Talent scout: talentscoutphil@gmail.com</small>
                        </p>
          </div>
        </div>
        <style type="text/css">
          body {
             display: flex;
             min-height: 100vh;
             flex-direction: column;
         }
         main {
             flex: 1 0 auto;
         }
        </style>
      </footer>

          </main>
        </div>


                
    <script>
    $(document).ready(function() {
      // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
      $('.modal-trigger').leanModal();
      });
    </script>
  <script>
  
    $('.datepicker').pickadate({
      selectMonths: true, // Creates a dropdown to control month
      selectYears: 100 // Creates a dropdown of 15 years to control year
    });
    $('.disabled').click(function(e){
      alert('Setup your profile first!');
     e.preventDefault();
  });
    $("#skip").click(function(){
        $("#testimony").remove();
    });
  </script>


    <!--    
     {!! HTML::script('vendor/jquery/jquery.min.js') !!}
     {!! HTML::script('vendor/bootstrap/js/bootstrap.min.js') !!}
     {!! HTML::script('vendor/scrollreveal/scrollreveal.min.js') !!}
     {!! HTML::script('vendor/magnific-popup/jquery.magnific-popup.min.js') !!}
     {!! HTML::script('js/creative.min.js') !!}
     {!! HTML::script('js/carousel.js') !!} -->
      </body>

    </html>
