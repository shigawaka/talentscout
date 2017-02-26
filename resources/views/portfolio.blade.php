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
                 <li class="active"><a href="{!! URL::to('/portfolio').'/'.$user['id'] !!}">Portfolio</a></li>
                 @if(Session::get('id') == $user['id'])
                 <li>
                 <a href="{!! URL::to('/invitation').'/'.$user['id'] !!}">Invitation</a>
                 </li>
                 <li>
                 <a href="{!! URL::to('/schedule').'/'.$user['id'] !!}">Schedule</a>
                 </li>
                 @endif
                 <li><a href="{!! URL::to('/connection').'/'.$user['id'] !!}">Connections</a></li>
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
          @if($user['id'] == Session::get('id'))
            {!! Form::open(['url'=>'/addPortfolio', 'method' => 'POST' ,'files' => true]) !!}
            <h3><a class="btn-floating btn-small waves-effect waves-light grey btn modal-trigger" data-target="modal3"><i class="material-icons">add</i></a>
            </h3>
            <div id="modal3" class="modal modal-fixed-footer">
                             <div class="modal-content">
                             <h4>Add Portfolio</h4>
                             <label for="title">Event Details</label>
                             <div class="row" id="talentcontainer">
                                <div class="input-field col s6">

                                  {!! Form::select('category', ['Select Category' => 0], null, ['class' => 'browser-default', 'id' => 'category']) !!}
                                </div>
                                <div class="input-field col s6">
                                  {!! Form::select('talent', ['Select talent'], null, ['class' => 'browser-default','id' => 'talent']) !!}
                                </div>
                                
                               </div>
                              <div class="input-field col s12">
                              {!! Form::textarea('description', '', array('style' => 'min-width:100%;resize:none;','class' => 'form-group','placeholder' => 'Enter Description of the Job', 'required'=>'required')) !!}
                                <!-- <input  value="Frete" id="firstname" type="text" class="validate"> -->
                                <label for="description">Event Description</label>
                              </div>
                              <div class="input-field col s6">
                              {!! Form::text('event_date', '', array('class' => 'datepicker','placeholder' => 'Enter date of event', 'required'=>'required')) !!}
                                <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
                                <label for="event_date">Date of Event</label>
                              </div>
                              <div class="input-field col s12" style="padding-bottom: 35px;">
                              
                              {!! Form::file('files[]', array('multiple'=>true, 'id'=>'ifile')) !!}
                                <!-- <label for="image">Add video</label> -->
                                @foreach($errors->get('files') as $message)
                                <div id="card-alert" class="card red">
                                <div class="card-content white-text">
                                <p><i class="mdi-navigation-check"></i> 
                                  {!! $message !!}
                                </div>
                                </div>
                                  @endforeach</p>
                                  
                              </div>
                          </div>
                          <div class="modal-footer">
                         <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Cancel</a>
                         {!! Form::submit('Add portfolio', array('class' => 'btn btn-info')) !!}
                          <!-- <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Save</a> -->
                          </div>
                         </div>
              {!! Form::close() !!}
          @endif

          </div>
          <div class="row">
            <div class="col s12 m7">
              
              <div class="divider"></div>
            </div>
            @foreach($portfolio as $key => $value)
            <div class="col s12 m7">
                
                <div class="card horizontal">
                  <div class="card-image">
                  @if(is_array($value['file']))
                    @foreach($value['file'] as $key => $val)
                      @if(strpos($val,'.mp4') == true)
                      <video style="width: 100%;" width="400" controls>
                          <source src="{!! URL::to('/files').'/'.$val !!}" type="video/mp4">
                      </video>
                      @else
                      <img src="{!! URL::to('/files').'/'.$val !!}">
                      @endif
                    @endforeach
                  @else
                     @if(strpos($value['file'],'.mp4') == true)
                      <video style="width: 100%;" width="400" controls>
                          <source src="{!! URL::to('/files').'/'.$value['file'] !!}" type="video/mp4">
                      </video>
                      @else
                      <img src="{!! URL::to('/files').'/'.$value['file'] !!}">
                      @endif
                  @endif
                  </div>
                  <div class="card-stacked">
                    <div class="card-content">
                    <h5 class="header">Event Name: {{ $value['event_name'] }}</h5>
                    <h5 class="header">Event Date: {{ Carbon\Carbon::parse($value['event_date'])->format('F d,Y') }}</h5>
                      <p>{{$value['description']}}</p>
                    </div>
                    <div class="card-action">
                    @if($value['post_id'] !== null)
                      <a href="{!! URL::to('/post/'.$value['post_id']) !!}">Visit Post</a>
                    @endif
                    </div>
                  </div>
                </div>
              </div>
            @endforeach

            @foreach($hiredposts as $value)
            <div class="col s12 m7">
                <div class="card horizontal">
                  <div class="card-image">
                  @if(is_array($value['file']))
                    @foreach($value['file'] as $key => $val)
                      @if(strpos($val,'.mp4') == true)
                      <video style="width: 100%;" width="400" controls>
                          <source src="{!! URL::to('/files').'/'.$val !!}" type="video/mp4">
                      </video>
                      @else
                      <img src="{!! URL::to('/files').'/'.$val !!}">
                      @endif
                    @endforeach
                  @else
                     @if(strpos($value['file'],'.mp4') == true)
                      <video style="width: 100%;" width="400" controls>
                          <source src="{!! URL::to('/files').'/'.$value['file'] !!}" type="video/mp4">
                      </video>
                      @else
                      <img src="{!! URL::to('/files').'/'.$value['file'] !!}">
                      @endif
                  @endif
                  </div>
                  <div class="card-stacked">
                    <div class="card-content">
                    <h5 style="font-size:15px;">Event Name: {{ $value['event_name'] }}</h5>
                    <h5 style="font-size:15px;">Event Date: {{ Carbon\Carbon::parse($value['event_date'])->format('F d,Y') }}</h5>
                      @if($value['score'] == null)
                      <p style="font-style:italic;color:red;">Ongoing event</p>
                      @else
                      <div class="stars" style="margin:0;">
                        <input type="radio" name="attitude" class="star-{{ $value['score'] }}" id="star-{{ $value['score'] }}" checked/>
                        <span></span>
                      </div> 
                      <p style="font-style:italic;">"{!!$value['comment']!!}"</p>
                      @endif
                    </div>
                    <div class="card-action">
                    @if($value['id'] !== null)
                      <a href="{!! URL::to('/post/'.$value['id']) !!}">Visit Post</a>
                    @endif
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
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
  @if(count($errors)>0)
          <script>
              $('#modal3').openModal();
          </script>
  @endif
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
<script>
    $("document").ready(function(){

    $("#ifile").change(function() {
        var fsize = $('#ifile')[0].files[0].size;
        var fname = $('#ifile')[0].files[0].name;
        var fextension = fname.split('.').pop();
        console.log(fextension);
        if(fsize>200000000) //do something if file size more than 1 mb (1048576)
        {
            alert("Too big!\n File Size limit: 200mb!");
            $("#ifile").val('');
        }
    });
});

    $(document).ready(function($) {
        $.ajax({
            url: "{{ URL('/revealCategory/') }}",
            method: "GET",
            dataType: "json",
            data: {},
            success: function(data){
                var next_id = $("#category");
                $("#category").empty().html(' ');
                $.each(data, function(key, value) {
                    $(next_id).append($("<option></option>").attr("value", value.value).text(value.value));
                });
                $(next_id).material_select();
                // $("#talent").html(data);
            }
          });
    $("#category").change(function() {
        var tal_cal = $(this).val();
        $.ajax({
            url: "{{ URL('/revealTalents/') }}",
            method: "GET",
            dataType: "json",
            data: {tal_cal:tal_cal},
            success: function(data){
                var next_id = $("#talent");
                console.log(data);
                $("#talent").empty().html(' ');
                $.each(data, function(key, value) {
                    $(next_id).append($("<option></option>").attr("value", value.value).text(value.value));
                });
                $(next_id).material_select();
                // $("#talent").html(data);
            }
          });
      });

    $('#talentcontainer').on('change', '.category', function() {
        var tal_cal = $(this).val();
        $.ajax({
            url: "{{ URL('/revealTalents/') }}",
            method: "GET",
            dataType: "json",
            data: {tal_cal:tal_cal},
            success: function(data){
                var next_id = $(".talent");
                console.log(data);
                $(".talent").empty().html(' ');
                $.each(data, function(key, value) {
                    $(next_id).append($("<option></option>").attr("value", value.value).text(value.value));
                });
                $(next_id).material_select();
                // $("#talent").html(data);
            }
          });
      });

      $('#talentcontainer').on('click', '#addtalent', function() {
        $.ajax({
            url: "{{ URL('/revealCategory/') }}",
            method: "GET",
            dataType: "json",
            data: {},
            success: function(data){
                var next_id = $(".category");
                $(".category").empty().html(' ');
                $.each(data, function(key, value) {
                    $(next_id).append($("<option></option>").attr("value", value.value).text(value.value));
                });
                $(next_id).material_select();
                // $("#talent").html(data);
            }
          });
        });
  });
  </script>
  @if(Session::has('duplicate'))
          <script>
              $('#modal1').openModal();
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
