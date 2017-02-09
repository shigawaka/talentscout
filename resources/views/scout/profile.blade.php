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
                 <li class="active"><a href="{!! URL::to('/profile').'/'.$user['id'] !!}">Overview</a></li>
                 <li><a href="{!! URL::to('/portfolio').'/'.$user['id'] !!}">Portfolio</a></li>
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
      <div class="section no-pad-bot" id="index-banner">
        <div class="container">
            <div class="col s12 m7">

              <div class="cardcard-content valign center">
                <div class="card-image">
                   <img class="circle" src="{!! URL::to('/files').'/'.$user['profile_image'] !!}" style="width:250px; height:250px; ">
                 </div>
                
                 <div class="card-stacked">
                  <div class="card-content">
                  <div align="right"> 
                  @if($user['id'] == Session::get('id'))
                  <button data-target="modal" class="btn modal-trigger" id="btn1">
                  <i class="material-icons">more_vert</i>
                </button>
                  @endif
                </div>
                @if (Session::has('message'))
                  <div id="card-alert" class="card green">
                  <div class="card-content white-text">
                  <p><i class="mdi-navigation-check"></i> {{ Session::get('message') }}</p>
                  </div>
                  </div>
                @endif
                  <div id="modal" class="modal modal-fixed-footer">
                       <div class="modal-content">
                       <h4>Edit Profile</h4>
                       <div class="divider"></div>
                      <div class="wrap">
                        <div class="row">
                        {!! Form::open(['url'=>'/profile/edit/'.$user['id'].'', 'files' => true]) !!}
                          <div class="input-field col s6">
            {!! Form::text('firstname', ucfirst($user['firstname']), array('class' => 'validate','placeholder' => 'Enter Firstname', 'disabled' => 'disabled')) !!}
              <!-- <input  value="Frete" id="firstname" type="text" class="validate"> -->
              <label for="firstname">First Name</label>

            </div>
            <div class="input-field col s6">
            {!! Form::text('lastname', ucfirst($user['lastname']), array('class' => 'validate','placeholder' => 'Enter Firstname', 'disabled' => 'disabled')) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="lastname">Last Name</label>

            </div>
            <div class="input-field col s6">
            {!! Form::text('birthday', $user['birthday'], array('class' => 'datepicker','placeholder' => 'Enter date of birth')) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="lastname">Date of Birth</label>
              @foreach($errors->get('birthday') as $message)
                {!! $message !!}
                @endforeach
            </div>
            <div class="input-field col s6">
            {!! Form::text('contactno', $user['contactno'], array('class' => 'validate','placeholder' => 'Enter contact number')) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="lastname">Contact Number</label>
              @foreach($errors->get('contact') as $message)
                {!! $message !!}
                @endforeach
            </div>
            <div class="input-field col s6">
            {!! Form::email('emailaddress', '', array('class' => 'validate','placeholder' => $user['emailaddress'])) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="lastname">Email address</label>
              @foreach($errors->get('emailaddress') as $message)
                {!! $message !!}
                @endforeach
            </div>
            <div class="input-field col s6">
            {!! Form::text('username', '', array('class' => 'validate','placeholder' => $user['username'])) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="password">Change Username</label>
            </div>
            <div class="input-field col s6">
            {!! Form::password('password', '', array('class' => 'validate','placeholder' => 'Enter new password')) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="password">Change Password</label>
            </div>
            <div class="input-field col s6">
            {!! Form::text('gender', ucfirst($user['gender']), array('class' => 'validate','placeholder' => 'Enter Firstname', 'disabled' => 'disabled')) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="lastname">Gender</label>
            </div>
               <div class="input-field col s12">
            {!! Form::text('address', $user['address'], array('class' => 'validate','placeholder' => 'Enter your address')) !!}
              <!-- <input value="Basak Cebu" id="address" type="text" class="validate"> -->
              <label for="address">Address</label>
              @foreach($errors->get('address') as $message)
                {!! $message !!}
                @endforeach
            </div>
            <div class="input-field col s12" style="padding-bottom: 35px;">
              <label for="image">Change Profile Picture</label>
            {!! Form::file('image', '', array('class' => 'validate','placeholder' => 'Upload Image')) !!}
                @foreach($errors->get('image') as $message)
                {!! $message !!}
                @endforeach
            </div>


                      
                      </div>
                       

                        </div>
                       </div>
                    <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Cancel</a>
                    {!! Form::submit('Edit Profile', array('class' => 'btn btn-info')) !!}  
                      {!! Form::close(); !!}   
                    <!-- <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Save</a> -->
                    </div>
              </div>
                  <h2><b>{!! ucfirst($user['firstname']) .' '. ucfirst($user['lastname']) !!}</b></h2>
                   <h5>{!! $user['address'] !!}</h5>
                       @if($rating['score'] <= 1000)
                      <div class="card-panel orange lighten-2" > <img style="width: 50px;font-family: 'Quasiparticles';"class="responsive-img" src="{!! URL::to('/files') !!}/Newbie Scout.png"><h6><i>Newbie Scout <br /> Points: {{ $rating['score'] }}</i></h6></div>
                       @elseif($rating['score'] >= 1500)
                       <div class="card-panel blue lighten-2" ><img style="width: 50px;"class="responsive-img" src="{!! URL::to('/files') !!}/Rising Scout.png"><h4><i>Rising Scout <br /> Points: {{ $rating['score'] }}</i></h4></div>
                       @elseif($rating['score'] >= 2000)
                       <div class="card-panel gold lighten-2" ><img style="width: 50px;"class="responsive-img" src="{!! URL::to('/files') !!}/Star Scout.png"><h4><i>Star Scout <br /> Points: {{ $rating['score'] }}</i></h4></div>
                       @endif
                       @if($rating['demerit'] <= 1000)
                      <div class="card-panel orange lighten-2" > <img style="width: 50px;font-family: 'Quasiparticles';"class="responsive-img" src="{!! URL::to('/files') !!}/neutral.png"><h6><i>Demerit: {!! $rating['demerit'] !!}</i></h6></div>
                       @elseif($rating['score'] >= 1500)
                       <div class="card-panel blue lighten-2" ><img style="width: 50px;"class="responsive-img" src="{!! URL::to('/files') !!}/bad.png"><h4><i>Demerit: {!! $rating['demerit'] !!}</i></h4></div>
                       @elseif($rating['score'] >= 2000)
                       <div class="card-panel gold lighten-2" ><img style="width: 50px;"class="responsive-img" src="{!! URL::to('/files') !!}/sad.png"><h4><i>Demerit: {!! $rating['demerit'] !!}</i></h4></div>
                       @endif
                 </div>
            <div class="card-action">
              <blockquote><h4><i>{!! $user['profile_description'] !!}</i></h4></blockquote>
            </div>
          </div>
          
              </div>

               <div class="col s12 m7">
      
              <div class="card horizontal">


                   <div class="card-stacked">
                   <div class="card-content">
                    <i class="material-icons cyan-text darken-text">date_range</i>Birthday
                       <h6>{!! $user['birthday'] !!}</h6>
                    </div>
                    </div>
                   <div class="card-stacked">
                   <div class="card-content">
                    <i class="material-icons cyan-text darken-text md-48">perm_contact_calendar</i>AGE
                       <h6>{!! $user['age'] !!}</h6>
                    </div>
                    </div>
                    <div class="card-stacked">
                    <div class="card-content">
                    <i class="material-icons cyan-text darken-text">contact_phone</i> CONTACT 
                       <h6>  {!! $user['contactno'] !!}</h6>
                       <h6>  {!! $user['emailaddress'] !!}</h6>
                    </div>
                    </div>
                    <div class="card-stacked">
                    <div class="card-content">
                    <i class="material-icons cyan-text darken-text">person</i> GENDER 
                       <h6>  {!! ucfirst($user['gender']) !!}</h6>
                    </div>
                    </div>
                    <!-- <div class="card-stacked">
                    <div class="card-content">
                       <h6> <i class="material-icons cyan-text darken-text">group</i>Group</h6>
                    </div>
                    </div> -->
                

                 
          
               </div>
              </div>
            </div>




        </div>
      </div>


      <div class="container">
        <div class="section">

          <!--   Icon Section   -->
          <div class="row">
           

            <div class="col s12 m12 card-panel white">
              <div class="icon-block">
                <h5>Talents @if($user['id'] == Session::get('id'))
                   <a class="btn-floating btn-small waves-effect waves-light grey btn modal-trigger" data-target="modal1"><i class="material-icons">add</i></a>
                @endif</h5>
                @if(!empty($td))
                  @foreach($td as $key => $tal)
                <div class="chip">
                  {!! $tal['talent'] !!}
                </div>
                  @endforeach
                @endif
                <div class="card2 ">
                </div>  
             <div id="modal1" class="modal modal-fixed-footer" style="width:80%;">
              {!! Form::open(['url'=>'/addtalent/'.$user['id'].'', 'files' => true]) !!}
                       <div class="modal-content">
                       <h4>Your talents</h4>
                       <div class="divider"></div>
                       <div class="wrap">
                       @if (Session::has('duplicate'))
                          <div id="card-alert" class="card red">
                          <div class="card-content white-text">
                          <p><i class="mdi-navigation-check"></i> {{ Session::get('duplicate') }}</p>
                          </div>
                          </div>
                        @endif

                          
                       <div class="row" id="talentcontainer">
                        <div class="col s5">
                          {!! Form::select('category[]', ['Select Category' => 0], null, ['class' => 'browser-default', 'id' => 'category']) !!}
                        </div>
                        <div class="col s5">
                          {!! Form::select('talent[]', ['Select talent'], null, ['class' => 'browser-default','id' => 'talent']) !!}
                        </div>
                        <div class="col s12">
                          <a href="javascript:void(0)" id="addtalent">Add more talent</a>
                        </div>
                       </div>
                      @foreach($td as $key => $value)
                          <div class="row" id="preload">
                            <div class="col s5">
                              {!! Form::select('category2[]', [$value['category'] => $value['category']], $value['category'], ['class' => 'browser-default', 'class' => 'browser-default']) !!}
                            </div>
                            <div class="col s5">
                            {!! Form::select('talent2[]', [$value['talent'] => $value['talent']], $value['talent'], ['class' => 'browser-default','class' => 'browser-default']) !!}
                          </div>
                            <div class="col s2">
                              <input type="hidden" id="talid" value="{!! $value['id'] !!}" />
                              <a href="javascript:void(0)" id="removetalent" data-id="{!! $value['id'] !!}">Remove</a>
                            </div>
                          </div>
                      @endforeach

                        </div>
                       </div>
                    <div class="modal-footer">
                    {!! Form::submit('Save Changes', array('class' => 'btn btn-info')) !!}  
                    {!! Form::close() !!}
                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Cancel</a>                    </div>
              </div>
              </div>
            </div>

          </div>

        </div>
        <br><br>

        <div class="section">

        </div>
      </div>


          <footer class="page-footer  black">

        <div class="footer-copyright">
          <div class="container" align="center">
     <p class="text-center">Talent Scout.
                        All Rights Reserved. 2016 
                        <small style="color: gray;">Contact Talent scout: talentscoutphil@gmail.com</small>
                        </p>
          </div>
        </div>
      </footer>

          </main>
        </div>


                
    <script>
    $(document).ready(function() {
      // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
      $('.modal-trigger').leanModal();
      });
    </script>
    @if(count($errors)>0)
          <script>
              $('#modal').openModal();
          </script>
  @endif
  <script>
  
    $('.datepicker').pickadate({
      selectMonths: true, // Creates a dropdown to control month
      selectYears: 100 // Creates a dropdown of 15 years to control year
    });
    $('.disabled').click(function(e){
      alert('Setup your profile first!');
     e.preventDefault();
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


    $("#addtalent").on("click", function (event) {
      $("#talentcontainer").append("<div class='row' id='temprow'><div class='col s5'><select class='category browser-default' name='category[]'></select></div><div class='col s5'><select class='talent browser-default' name='talent[]'><option value='Select Talent'>Select Talent</option></select></div><div class='col s2'><a href='javascript:void(0)' id='removetalent'>Remove</a></div></div>");
    });

    $("#talentcontainer").on("click", '#removetalent',function (event) {
      $("#temprow").remove();
    });

    $(".wrap").on("click", '#removetalent',function (event) {
      var tal_cal = $(this).data("id");
      $.ajax({
            url: "{{ URL('/removeTalent/') }}",
            method: "GET",
            data: {tal_cal:tal_cal},
            success: function(data){
                console.log(data);
                $("#preload").remove();
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
