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
        <meta name="_token" content="{!! csrf_token() !!}"/>
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
     

    <!-- Compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/css/materialize.min.css"> -->
    <link rel="stylesheet" href="../../materialize/css/materialize.min.css">
    <link rel="stylesheet" href="../../materialize/materialize-tags.css">
    <link rel="stylesheet" type="text/css" href="../../css/rating_style.css">
    <link rel="stylesheet" type="text/css" href="../../css/jquery-ui-timepicker-addon.css">
    <link rel="stylesheet" type="text/css" href="../../css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="../../css/fullcalendar.min.css">
    <link rel="stylesheet" media="print" href="../../css/fullcalendar.print.css"/>
    <link rel="stylesheet" href="../../css/timepicker.css"/>
    <!-- Compiled and minified JavaScript -->
    <script src="../../js/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="../../materialize/js/materialize.min.js"></script>
    <script src="../../materialize/materialize-tags.js"></script>
    <script src="../../js/moment.min.js"></script>
    <script src="../../js/fullcalendar.min.js"></script>
    <script src="../../js/timepicker.js"></script>
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
                 <li><a href="/portfolio">Portfolio</a></li>
                 <li><a href="{!! URL::to('/invitation').'/'.$user['id'] !!}">Invitation</a></li>
                 <li>
                 <a href="{!! URL::to('/schedule').'/'.$user['id'] !!}" class="active">Schedule</a>
                 </li>
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

      <div class="row">
      @if (Session::has('message'))
        <div id="card-alert" class="card green">
        <div class="card-content white-text">
        <p><i class="mdi-navigation-check"></i> {{ Session::get('message') }}</p>
        </div>
        </div>
      @endif
        <!-- Modal Trigger -->
    <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Add Event</a>
          
          <!-- Modal Structure -->
          <div id="modal1" class="modal">
            <div class="modal-content">
              <h4>Add Event</h4>
              {!! Form::open(['url'=>'/addschedule/'.$user['id'].'']) !!}
                  <div class="input-field col s6">
            {!! Form::text('title', '', array('class' => 'validate','placeholder' => 'Enter Title Event')) !!}
              <!-- <input  value="Frete" id="firstname" type="text" class="validate"> -->
              <label for="firstname">Title Event</label>
              @foreach($errors->get('title') as $message)
              <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                
                @endforeach
                
                  </div>
                  <div class="input-field col s6">
                        {!! Form::label('allday', 'Is this an all day event?') !!}
                        <p>
                        {!! Form::radio('allday', 'True', true, array('id'=>'true')) !!}
                        {!! Form::label('true', 'True') !!}
                        {!! Form::radio('allday', 'False', false, array('id'=>'false')) !!}
                        {!! Form::label('false', 'False') !!}
                        </p>
              @foreach($errors->get('allday') as $message)
                <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                @endforeach
                  </div>
                  <div class="input-field col s12">
            {!! Form::text('start_date', '', array('class' => 'datepicker','placeholder' => 'Enter Start date')) !!}
              <!-- <input  value="Frete" id="firstname" type="text" class="validate"> -->
              <label for="firstname">Start Date</label>
              @foreach($errors->get('start_date') as $message)
                <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                @endforeach
                
                  </div>
                  <div class="input-field col s12">
            {!! Form::text('start_time', '', array('class' => 'timepicker','placeholder' => 'Enter Start Time')) !!}
              <!-- <input  value="Frete" id="firstname" type="text" class="validate"> -->
              <label for="firstname">Start Time</label>
              @foreach($errors->get('start_time') as $message)
                <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                @endforeach
                
                  </div>
                  <div class="input-field col s12">
                {!! Form::text('end_date', '', array('class' => 'datepicker2','placeholder' => 'Enter End date')) !!}
                  <!-- <input  value="Frete" id="firstname" type="text" class="validate"> -->
                  <label for="firstname">End Date</label>
                  @foreach($errors->get('end_date') as $message)
                <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                @endforeach
                  </div>
                  <div class="input-field col s12">
            {!! Form::text('end_time', '', array('class' => 'timepicker2','placeholder' => 'Enter End Time')) !!}
              <!-- <input  value="Frete" id="firstname" type="text" class="validate"> -->
              <label for="firstname">End Time</label>
              @foreach($errors->get('end_time') as $message)
                <div id="card-alert" class="card red">
              <div class="card-content white-text">
              <p><i class="mdi-navigation-check"></i> {!! $message !!}</p>
              </div>
              </div>
                @endforeach
                
                  </div>
            </div>
            <div class="modal-footer">
              <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancel</a>
              {!! Form::submit('Add event', array('class' => 'btn btn-info')) !!}  
              {!! Form::close(); !!}
            </div>
          </div>
        <div id="dialog" title="" style="display:none;">Are you sure want to delete it?</div>
        {!! $calendar->calendar() !!}
        {!! $calendar->script() !!}
      </div>
            


          <footer class="page-footer  black">

        <div class="footer-copyright">
          <div class="container" align="center">
    Talent Scout|   All rights reserved |   2016
          </div>
        </div>
      </footer>

          </main>
        </div>
        <script>

          $(document).ready(function(){
    // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
    $('.modal-trigger').leanModal();

  });

        </script>
        <script>
  
    $('.datepicker').pickadate({
      selectMonths: true, // Creates a dropdown to control month
      selectYears: 100, // Creates a dropdown of 15 years to control year
      format: 'mmmm dd, yyyy',
    });
    $('.timepicker').pickatime({
    twelvehour: false,
    donetext: 'Done',
    afterDone: function() {
      activeElement = $(document.activeElement)
      $(activeElement).enableClientSideValidations();
    }
  });
    $('.datepicker2').pickadate({
      selectMonths: true, // Creates a dropdown to control month
      selectYears: 100, // Creates a dropdown of 15 years to control year
      format: 'mmmm dd, yyyy',
    });
    $('.timepicker2').pickatime({
    twelvehour: false,
    donetext: 'Done',
    afterDone: function() {
      activeElement = $(document.activeElement)
      $(activeElement).enableClientSideValidations();
    }
  });
  </script>
  @if(count($errors)>0)
          <script>
              $('#modal1').openModal();
          </script>
  @endif
                <script type="text/javascript">
$.ajaxSetup({
   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
});
</script>
    

      </body>

    </html>
