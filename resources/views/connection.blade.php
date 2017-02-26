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
     

    <script src="../../js/jquery.min.js"></script>
    <!-- Compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/css/materialize.min.css"> -->
    <link rel="stylesheet" href="../../materialize/css/materialize.min.css">
    <link rel="stylesheet" href="../../materialize/materialize-tags.css">
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
                 @if(Session::get('id') == $user['id'])
                 <li>
                 <a href="{!! URL::to('/invitation').'/'.$user['id'] !!}">Invitation</a>
                 </li>
                 <li>
                 <a href="{!! URL::to('/schedule').'/'.$user['id'] !!}">Schedule</a>
                 </li>
                 @endif
                 <li class="active"><a href="{!! URL::to('/connection').'/'.$user['id'] !!}">Connections</a></li>
            </ul>

            <ul class="right">
              @if(Session::get('first_login') == 1 || Session::get('address') == null || Session::get('profile_image') == 'avatar.png')
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
      <div class="section no-pad-bot" id="index-banner">
        <div class="container">
                @if (Session::has('message'))
                  <div id="card-alert" class="card green">
                  <div class="card-content white-text">
                  <p><i class="mdi-navigation-check"></i> {{ Session::get('message') }}</p>
                  </div>
                  </div>
                @endif
          <div class="row">
          
          <div>
                {!! Form::open(['url'=>'/searchscout', 'class' => 'navbar-form navbar-left', 'style'=>'width:600px;']) !!}
          {!! Form::text('search', '', array('placeholder' => 'Search...', 'class' => 'form-control', 'style' => 'width:70%;display:none;')) !!}              
                  @if(empty($endorsed) && empty($endorser))
                <h5 class="center-align">There's nothing here. {!! Form::submit('Search for connections!', array('class' => 'btn btn-default')) !!}
                  @endif
            
          
          <!-- <a href="{{ URL::to('/searchscout') }}"> Search for connections!</a></h5> -->
              </div>
          @if(empty($endorsed))

          @else
          <h3>List of people {{ $user['firstname'] }} got endorsed by</h3>
            @foreach($endorsed as $val)
              <div class="col s2">
                <div class="card">
                    <div class="card-image">
                      <img src="{!! URL::to('/files').'/'.$val['profile_image'] !!}">
                      <!-- <span class="card-title">Card Title</span> -->
                    </div>
                    <div class="card-content">
                    @if(empty($val['groupname']))
                      <p>{!! ucfirst($val['firstname']).' '.ucfirst($val['lastname']) !!}</p>
                    @else
                      <p>{!! ucfirst($val['groupname']) !!}</p>
                    @endif
                    </div>
                    <div class="card-action">
                      <a href="{!! URL::to('/profile').'/'.$val['id'] !!}">Visit Profile</a>
                    </div>
                </div>
              </div>
              @endforeach
            @endif
            </div>
            
            <div class="row">
            @if(empty($endorser))
                
            @else
            <h3>List of people {{ $user['firstname'] }} endorses</h3>
               @foreach($endorser as $val)
            <div class="col s2">
              <div class="card">
                  <div class="card-image">
                    <img src="{!! URL::to('/files').'/'.$val['profile_image'] !!}">
                    <!-- <span class="card-title">Card Title</span> -->
                  </div>
                  <div class="card-content">
                  @if(empty($val['groupname']))
                    <p>{!! ucfirst($val['firstname']).' '.ucfirst($val['lastname']) !!}</p>
                  @else
                    <p>{!! ucfirst($val['groupname']) !!}</p>
                  @endif
                  </div>
                  <div class="card-action">
                    <a href="{!! URL::to('/profile').'/'.$val['id'] !!}">Visit Profile</a>
                    @if(Session::get('id') == $user['id'])
                    <a href="{!! URL::to('/removeEndorsement').'/'.$val['id'] !!}">Unendorse user</a>
                    @endif
                  </div>
              </div>
            </div>
              @endforeach
            @endif
            </div>
        </div>
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
  </script>
@if(Session::get('first_login') == 1 || Session::get('address') == null || Session::get('profile_image') == 'avatar.png')
  <script>
    $(document).ready(function() {
      $('.disabled').click(function(e){
      alert('You need to setup your profile! Setup the following: Profile Image, Talents, Talent fee, Address, link your card!');
     e.preventDefault();
  });
    });
  </script>
  @endif

    <!--    
     {!! HTML::script('vendor/jquery/jquery.min.js') !!}
     {!! HTML::script('vendor/bootstrap/js/bootstrap.min.js') !!}
     {!! HTML::script('vendor/scrollreveal/scrollreveal.min.js') !!}
     {!! HTML::script('vendor/magnific-popup/jquery.magnific-popup.min.js') !!}
     {!! HTML::script('js/creative.min.js') !!}
     {!! HTML::script('js/carousel.js') !!} -->
      </body>

    </html>
