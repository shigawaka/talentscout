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
    <script src="../../js/jquery-ui.js"></script>
    <!-- Compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/css/materialize.min.css"> -->
    <link rel="stylesheet" href="../../css/jquery-ui.css">
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
                 <li><a href="#" class="active">Overview</a></li>
                 <li><a href="/portfolio">Portfolio</a></li>
                 @if(Session::get('id') == $user['id'])
                 <li>
                 <a href="{!! URL::to('/invitation').'/'.$user['id'] !!}">Invitation</a>
                 </li>
                 <li>
                 <a href="{!! URL::to('/schedule').'/'.$user['id'] !!}">Schedule</a>
                 </li>
                 @endif
                 <li><a href="#">Connections</a></li>

            </ul>

            <ul class="right">
              <li><a href="/home">Home</a></li>
                 <li class="dropdown">
                 
                  <a href="#" "dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    <!--  --> <span class="caret"></span>
                  </a>
             
                </li>
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
            {!! Form::text('groupname', ucfirst($groupdetails['groupname']), array('class' => 'validate','placeholder' => 'Enter Firstname', 'disabled' => 'disabled')) !!}
              <!-- <input  value="Frete" id="firstname" type="text" class="validate"> -->
              <label for="firstname">Groupname</label>
              @foreach($errors->get('groupname') as $message)
                {!! $message !!}
                @endforeach
            </div>

            <div class="input-field col s6">
            {!! Form::text('birthday', $user['birthday'], array('class' => 'datepicker','placeholder' => 'Enter date of birth')) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="lastname">Date Founded</label>
              @foreach($errors->get('birthday') as $message)
                <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                @endforeach
            </div>
            <div class="input-field col s6">
            {!! Form::text('contactno', $user['contactno'], array('class' => 'validate','placeholder' => '')) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="lastname">Contact Number</label>
              @foreach($errors->get('contact') as $message)
               <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                @endforeach
            </div>
            <div class="input-field col s6">
            {!! Form::text('emailaddress', '', array('class' => 'validate','placeholder' => $user['emailaddress'])) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="lastname">Email address</label>
              @foreach($errors->get('email') as $message)
                <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                @endforeach
            </div>
            <div class="input-field col s12">
            {!! Form::textarea('description', $user['profile_description'], array('style' => 'min-width:100%;resize:none;', 'placeholder' => '')) !!}
              <!-- <input value="Basak Cebu" id="address" type="text" class="validate"> -->
              <label for="address">Personal Description</label>
               @foreach($errors->get('description') as $message)
                <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                @endforeach
            </div>
               <div class="input-field col s12">
            {!! Form::text('address', $user['address'], array('class' => 'validate','placeholder' => '')) !!}
              <!-- <input value="Basak Cebu" id="address" type="text" class="validate"> -->
              <label for="address">Address</label>
              @foreach($errors->get('address') as $message)
                <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                @endforeach
            </div>
             <div class="input-field col s6">
            {!! Form::text('username', '', array('class' => 'validate','placeholder' => $user['username'])) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="username">Change Username</label>
            </div>
            <div class="input-field col s6">
            {!! Form::password('password', '', array('class' => 'validate','placeholder' => 'Enter new password')) !!}
              <!-- <input value="Dela Rosa" id="lastname" type="text" class="validate"> -->
              <label for="password">Change Password</label>
            </div>

            <div class="input-field col s12" style="padding-bottom: 35px;">
              <label for="image">Change Profile Picture</label>
            {!! Form::file('image', '', array('class' => 'validate','placeholder' => 'Upload Image')) !!}
                @foreach($errors->all() as $message)
                <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                @endforeach
            </div>
              <div class="input-field col s3">
            {!! Form::text('fee', $fee['fee'], array('class' => 'validate','placeholder' => 'Enter your rate')) !!}
              <label for="rate" class="left-align">Talent Fee (₱)</label>
              @foreach($errors->get('rate') as $message)
                <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
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
                  <h2><b>{!! ucfirst($groupdetails['groupname']) !!}</b></h2>
                   <div class="card-action">
              <blockquote><h4><i>{!! $user['profile_description'] !!}</i></h4></blockquote>
            </div>
                   <div class="card-panel grey lighten-3"><h6><i>Talent Fee: ₱{!! $fee['fee'] !!}</i></h6></div>
                       @if($fee['score'] <= 1000)
                       <div class="card-panel orange lighten-2"><img style="width: 50px;font-family: 'Quasiparticles';"class="responsive-img" src="{!! URL::to('/files') !!}/newbie.png"><h6>New Talent</h6></div>
                       @elseif($fee['score'] >= 1500)
                       <div class="card-panel blue lighten-2"><img style="width: 50px;"class="responsive-img" src="{!! URL::to('/files') !!}/risingtalent.png"><h4><i>Rising Talent</i></h4></div>
                       @elseif($fee['score'] >= 2000)
                       <div class="card-panel gold lighten-2"><img style="width: 50px;"class="responsive-img" src="{!! URL::to('/files') !!}/startalent.png"><h4><i>Star Talent</i></h4></div>
                       @endif
                        @if($fee['demerit'] <= 1000)
                      <div class="card-panel orange lighten-2" > <h6><i><i class="large material-icons light-blue-text">sentiment_neutral</i><br />Demerit: {!! $fee['demerit'] !!}</i></h6></div>
                       @elseif($fee['score'] >= 1500)
                       <div class="card-panel blue lighten-2" ><h4><i><i class="large material-icons light-blue-text">sentiment_dissatisfied</i><br />Demerit: {!! $fee['demerit'] !!}</i></h4></div>
                       @elseif($fee['score'] >= 2000)
                       <div class="card-panel gold lighten-2" ><h4><i class="large material-icons light-blue-text">sentiment_very_dissatisfied</i><br /><i>Demerit: {!! $fee['demerit'] !!}</i></h4></div>
                       @endif
                 </div>
          </div>
          
              </div>

               <div class="col s12 m7">
      
              <div class="card horizontal">
                  


                   <div class="card-stacked">
                   <div class="card-content">
                    <i class="material-icons cyan-text darken-text">date_range</i>Date founded
                       <h6>{!! $user['birthday'] !!}</h6>
                    </div>
                    </div>
                    <div class="card-stacked">
                    <div class="card-content">
                    <i class="material-icons cyan-text darken-text">place</i> ADDRESS 
                       <h6>  {!! $user['address'] !!}</h6>
                    </div>
                    </div>
                    <div class="card-stacked">
                    <div class="card-content">
                    <i class="material-icons cyan-text darken-text">contact_phone</i> CONTACT (MOBILE/EMAIL-ADDRESS) 
                       <h6>  {!! $user['contactno'] !!} / {!! $user['emailaddress'] !!}</h6>
                    </div>
                    </div>

                 
          
               </div>

              </div>

              <div class="col s12 m7">
      
              <div class="card horizontal">
                  
                    <div class="card-stacked">
                    <div class="card-content">
                    <div class="card2 ">
                @if($user['id'] == Session::get('id'))
                   <a class="btn-floating btn-small waves-effect waves-light grey btn modal-trigger" data-target="modaladmem"><i class="material-icons">add members</i></a>
                @endif
                </div>  
             <div id="modaladmem" class="modal modal-fixed-footer">
              {!! Form::open(['url'=>'/savemember/', 'method' => 'POST']) !!}
                       <div class="modal-content">
                       <div class="wrap">
                        {!! Form::text('q', '', array('id' => 'autocomplete-input','class' => 'autocomplete')) !!}
                        <div id="mem"></div>
                        </div>
                       </div>
                    <div class="modal-footer">  
                    {!! Form::close() !!}
                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>                    </div>
              </div>
                       <h6> <i class="material-icons cyan-text darken-text">group</i> Group Members</h6>
                       @foreach($grouparray as $ga)
                       <h6> <a href="{!! URL::to('/removeMember').'/'.$ga['id'] !!}"> Remove member </a></h6>
                       <img class="circle" src="{!! URL::to('/files').'/'.$ga['profile_image'] !!}" style="width:50px; height:50px; ">
                       <h6> <a href="{!! URL::to('/profile').'/'.$ga['id'] !!}"> {!! ucfirst($ga['fullname']) !!} </a></h6>
                       @endforeach
                    </div>
                    </div>
                    

                 
          
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
                <h5>Talents</h5>
                @if(!empty($talent))
                  @foreach($talent as $tal)
                <div class="chip">
                  {!! $tal !!}
                </div>
                  @endforeach
                @endif
                <div class="card2 ">
                @if($user['id'] == Session::get('id'))
                   <a class="btn-floating btn-small waves-effect waves-light grey btn modal-trigger" data-target="modal1"><i class="material-icons">add</i></a>
                @endif
                </div>  
             <div id="modal1" class="modal modal-fixed-footer">
              {!! Form::open(['url'=>'/addtalent/'.$user['id'].'', 'files' => true]) !!}
                       <div class="modal-content">
                       <h4>Your talents</h4>
                       <div class="divider"></div>
                       <p>Top talents</p>
                       <div class="wrap">
                        @if(empty($talent))
                        {!! Form::text('talents[]', '', array('data-role' => 'materialtags')) !!}
                         @else
                        {!! Form::text('talents[]', implode(',', $talent), array('data-role' => 'materialtags')) !!}
                         @endif
                         @foreach($errors->get('talents') as $message)
                        {!! $message !!}
                        @endforeach
                        </div>
                       </div>
                    <div class="modal-footer">
                    {!! Form::submit('Save Changes', array('class' => 'btn btn-info')) !!}  
                    {!! Form::close() !!}
                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Cancel</a>                    </div>
              </div>

           <h5>Experience</h5>
            <div class="card2">
            @if($user['id'] == Session::get('id'))
              <a class="btn-floating btn-small waves-effect waves-light grey btn modal-trigger" data-target="modal2"><i class="material-icons">add</i></a>
            @endif
            </div>
                    <div id="modal2" class="modal modal-fixed-footer">
                             <div class="modal-content">
                             <h4>Experience</h4>
                             <div class="input-field col s12">
                             <input id="agency" type="text" class="validate">
                             <label for="agency">Agency</label>
                             </div>
                             <div class="input-field col s12">
                             <input id="title" type="text" class="validate">
                             <label for="title">Job title</label>
                             </div>
                          </div>
                          <div class="modal-footer">
                          <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Cancel</a>
                          <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Save</a>
                          </div>
                         </div>
           <h5>Qualifications</h5>
            <div class="card2">
            @if($user['id'] == Session::get('id'))
    <a class="btn-floating btn-small waves-effect waves-light grey btn modal-trigger" data-target="modal3"><i class="material-icons">add</i></a>
            @endif
            </div>
                  <div id="modal3" class="modal modal-fixed-footer">
                             <div class="modal-content">
                             <h4>Edit Qualifications</h4>
                              <div class="input-field col s12">
                             <input id="qualification" type="text" class="validate">
                             <label for="qualification">Add a qualification</label>
                             </div>
                           
                          </div>
                          <div class="modal-footer">
                         <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Cancel</a>
                          <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Save</a>
                          </div>
                         </div>

            <h5>Group</h5>
            <div class="card2">
            @if($user['id'] == Session::get('id'))
    <a class="btn-floating btn-small waves-effect waves-light grey btn modal-trigger" data-target="modal4"><i class="material-icons">add</i></a>
            @endif
            </div>
                  <div id="modal4" class="modal modal-fixed-footer">
                             <div class="modal-content">
                             <h4>Modal Header</h4>
                             <p>A bunch of text</p>
                          </div>
                          <div class="modal-footer">
                          <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</a>
                          </div>
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
    Talent Scout |  All rights reserved |   2016
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
  <script>
  
    $('.datepicker').pickadate({
      selectMonths: true, // Creates a dropdown to control month
      selectYears: 100, // Creates a dropdown of 15 years to control year
      format: 'yyyy-m-d',
    });
    
  </script>
 @if(count($errors)>0)
          <script>
              $('#modal').openModal();
          </script>
  @endif
  <script src="../../js/jquery.materialize-autocomplete.js"></script>
<script>
//    $('document').ready(function(){
// /* $('#search-input').attr('autocomplete', 'on');*/
// $("#q").autocomplete({
// source : "{{ URL('/addmembers/') }}",
// minlength: 1,

//        select: function(event,ui){

//            $('#q').val(ui.item.value);

//             }
//     });
// });
   $(document).ready(function(){
    $( "#autocomplete-input" ).keyup(function() {
      var q = $( "#autocomplete-input" ).val();
     $.ajax({
                url: "{{ URL('/addmembers/') }}",
                type: 'GET', // your request type
                dataType: "json",
                data: {q : q},
                success: function(data) {
                  
                  // $.each(data, function(index, element) {
                  //   console.log(element.value); 
                  //   d = element.id;
                  //   });
                  console.log(data[0].picture);
                  $.each(data,function(index, value){
                  var name = value.value;
                  var id = value.id;
                  var pic = value.picture;
                  var invited = value.invited;
                   $("#mem").empty();
                   if(pic === void(0)) {
                    //no match found
                   $("#mem").append("<div class='thumbnail'><div class='caption'><p>"+name+"</p></div></div>");
                   }
                   else {
                    //check if already invited
                      if(invited === void(0)) {
                        $("#mem").append("<div class='thumbnail'><img width='120px;' src=http://localhost:8000/files/"+pic+" /><div class='caption'><p>"+name+"</p><a class='btn btn-primary' href='#' disabled>Already invited</a></div></div>");
                      }
                      else {
                      $("#mem").append("<div class='thumbnail'><img width='120px;' src=http://localhost:8000/files/"+pic+" /><div class='caption'><p>"+name+"</p><a class='btn btn-primary' href='http://localhost:8000/savemember/"+id+"'>Invite</a></div></div>");
                      }
                   }
                   // $("#autocomplete-input").val(id);
                  });
                }
            });
    });
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
