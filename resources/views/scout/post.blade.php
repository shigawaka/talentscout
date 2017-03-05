<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Talent Scout</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'> -->
    <link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>

    <!-- Plugin CSS -->
    <link href="vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="../../css/creative.min.css" rel="stylesheet">
    <link href="../../css/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="../../css/creative.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/jquery-ui.css">
    <link rel="stylesheet" href="../../css/jquery-ui-timepicker-addon.css">
    <link rel="stylesheet" type="text/css" href="../../css/customcssdropdown.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top">

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                
                <a class="navbar-brand page-scroll" href="../../index.php"><img src="../../img/logo.png" class="logo"></a>

           
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>


    
    <section class="light">
        <div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <nav class="navbar navbar-inverse" role="navigation">
        <div class="collapse navbar-collapse"  id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li>
              <a href="{!! URL::to('/home') !!}">Home</a>
            </li>
            <li>
              <a href="{!! URL::to('/profile').'/'.Session::get('id') !!}">Profile</a>
            </li>
            <li>
              <a href="{!! URL::to('/about') !!}">About</a>
            </li>
            @if(Session::get('roleID') == 0)
            <li  class="active">
              <a href="{!! URL::to('/post') !!}">My Posts</a>
            </li>
            @endif
            <li>
              <a href="{{ URL::to('/search') }}">SEARCH</a>
            </li>
          </ul>
          
          <!-- {!! Form::open(['url'=>'/search', 'class' => 'navbar-form navbar-left', 'style'=>'width:600px;']) !!}
          
            
          {!! Form::text('search', '', array('placeholder' => 'Search talent', 'class' => 'form-control', 'style' => 'width:70%;')) !!}              
          {!! Form::submit('Search', array('class' => 'btn btn-default')) !!}   
            
            {!! Form::close() !!} -->
          
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              @if(count($unreadNotifications) == 0)
              <span class="glyphicon glyphicon-bell "></span>
              @else
              <span class="glyphicon glyphicon-bell notification-icon"></span>
              @endif
              </a>
              <ul class="dropdown-menu notifications" role="menu" aria-labelledby="dLabel">
    
    <div class="notification-heading"><h4 class="menu-title">Notifications ( @if(!empty($unreadNotifications)) {{ count($unreadNotifications) }} )@endif</h4><h4 class="menu-title pull-right">View all<i class="glyphicon glyphicon-circle-arrow-right"></i></h4>
    </div>
    <li class="divider"></li>
   <div class="notifications-wrapper">
    @foreach($unreadNotifications as $notification)
      @if($notification->subject == 'comment')
     <a class="content" href="{{ URL::to('/post').'/'.$notification->object_id }}">
      @elseif($notification->subject == 'invitation')
     <a class="content" href="{{ URL::to('/invitation').'/'.Session::get('id') }}">
      @elseif($notification->subject == 'connections')
     <a class="content" href="{{ URL::to('/connection').'/'.Session::get('id') }}">
      @endif
       <div class="notification-item">
        <h4 class="item-title">{{ $notification->sent_at->diffForHumans() }}</h4>
        <p class="item-info">{{$notification->body }}</p>
      </div>
    </a>
    @endforeach
   </div>
    <li class="divider"></li>
    @if(count($readNotifications) !== 0)
    <div class="notification-heading"><h4 class="menu-title">Read notifications</h4></div>
    @endif
    <div class="notifications-wrapper">
    @foreach($readNotifications as $notification)
      @if($notification->subject == 'comment')
     <a class="content" href="{{ URL::to('/post').'/'.$notification->object_id }}">
      @endif
       <div class="notification-item" style="background: #ecf0f1;">
        <h4 class="item-title">{{ $notification->sent_at->diffForHumans() }}</h4>
        <p class="item-info">{{$notification->body }}</p>
      </div>
    </a>
    @endforeach
    <li class="divider"></li>
    <div class="notification-footer"><h4 class="menu-title">Mark all as read<a href="{{ URL::to('/readNotifications') }}"><i class="glyphicon glyphicon-circle-arrow-right"></i></a></h4></div>
   </div>
  </ul>
              
            </li>
            <li>
              <a href="#">Welcome {!! ucfirst(Session::get('firstname')),' ', ucfirst(Session::get('lastname'))  !!} !</a>
            </li>
             <li>
              <a href="{!! URL::to('/logout') !!}">Logout</a>
            </li>
          </ul>
        </div>
        
      </nav>
      <div class="row">
        @if (Session::has('message'))
        <div class="alert alert-info text-center">{{ Session::get('message') }}</div>
        @endif
        <div class="col-xs-12 " style="height:auto;">
        <div class="form-group">
        <!-- <div class="col-sm-10">
        {!! Form::open(['url'=>'/sortpost']) !!}
          <h1>Sort By</h1>
          </div>
          <div class="col-sm-10">
          {!! Form::select('sort', ['' => 'Select sorting','status' => 'Closed Deals', 'date_posted' => 'Date Posted']) !!}
          </div>
        {!! Form::submit('Sort', array('class' => 'btn btn-info btn-lg')) !!} 
        {!! Form::close() !!} -->
        </div>
          
           <!-- {!! Form::button('Create Account',['id' => 'modal-403917','href' => '#modal-container-403917','role' => 'button','class'=>'btn btn-success btn-lg btn-block']) !!} -->
           <a id="modal-403917" href="#modal-container-403917" role="button" class="btn btn-success btn-lg" data-toggle="modal">+</a>
          
          <div class="modal fade" id="modal-container-403917" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                  </button>
                  {!! Form::open(['url'=>'/addpost', 'files' => true]) !!}
                  <h1 class="modal-title" id="myModalLabel">
                    Find Talents
                  </h1>
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Title
                </h3>
                <div class="col-xs-12">
                {!! Form::text('title', '', array('class' => 'col-xs-12','placeholder' => 'Enter Post Title', 'required'=>'required')) !!}
                @foreach($errors->get('title') as $message)
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Description
                </h3>
                <div class="col-xs-12">
                {!! Form::textarea('description', '', array('style' => 'min-width:100%;','class' => 'form-group','placeholder' => 'Enter Description of the Job', 'required'=>'required')) !!}
                @foreach($errors->get('description') as $message)
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Attach File
                </h3>
                <div class="col-xs-12">
                {!! Form::file('file', ['id'=>'ifile','class' => 'form-group','placeholder' => 'Upload Image/Video', 'required'=>'required']) !!}
                
                @foreach($errors->get('file') as $message)
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                   Choose Category and Talent
                </h3>
                <div class="col-xs-12" id="talentcontainer">
                <div class="col-xs-5">
                {!! Form::select('category[]', ['Select Category' => 0], null, [ 'id' => 'category']) !!}
                </div>
                <div class="col-xs-5">
                  {!! Form::select('talent[]', ['Select talent'], null, ['id' => 'talent']) !!}
                </div>
                <div class="col-xs-5">
                {!! Form::select('category[]', ['Select Category' => 0], null, [ 'id' => 'category1']) !!}
                </div>
                <div class="col-xs-5">
                  {!! Form::select('talent[]', ['Select talent'], null, ['id' => 'talent1']) !!}
                </div>
                <div class="col-xs-5">
                {!! Form::select('category[]', ['Select Category' => 0], null, [ 'id' => 'category2']) !!}
                </div>
                <div class="col-xs-5">
                  {!! Form::select('talent[]', ['Select talent'], null, ['id' => 'talent2']) !!}
                </div>
                <div class="col-xs-5">
                {!! Form::select('category[]', ['Select Category' => 0], null, [ 'id' => 'category3']) !!}
                </div>
                <div class="col-xs-5">
                  {!! Form::select('talent[]', ['Select talent'], null, ['id' => 'talent3']) !!}
                </div>
                <!-- <div class="col-xs-12">
                          <a href="javascript:void(0)" id="addtalent">Add more talent</a>
                </div> -->
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Start Date     
                </h3>
                <div class="col-xs-12">
                {!! Form::text('start_date', '', array('id' => 'datepicker','class' => 'form-control', 'placeholder' => 'Event starting', 'required'=>'required')) !!}                        
                                                  

                                                  @foreach($errors->get('start_date') as $message)
                                                    <div class="alert alert-danger text-center">{{ $message }}</div>
                                                    @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add End Date     
                </h3>
                <div class="col-xs-12">
               {!! Form::text('end_date', '', array('id' => 'datepicker2','class' => 'form-control', 'placeholder' => 'Event ending', 'required'=>'required')) !!}                        
                                                  

                                                  @foreach($errors->get('end_date') as $message)
                                                    <div class="alert alert-danger text-center">{{ $message }}</div>
                                                    @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Budget      
                </h3>
                <div class="col-xs-12">
                {!! Form::number('budget', '', array('min' => '0','placeholder' => 'Enter Budget', 'class' => 'form-group', 'required'=>'required')) !!}              
                @foreach($errors->get('budget') as $message)
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @endforeach
                </div>
                <h1 class="form-group" style="padding: 15px;border-bottom: 1px solid #e5e5e5" id="myModalLabel">
                    Talent Specification    
                </h1>
                <h3 class="form-group" style="padding-left: 17px;" id="myModalLabel">
                    Talent's Rate    
                </h3>
                <div class="col-xs-12">
                {!! Form::select('rate', array('Fixed Rate' => 'Fixed Rate', 'Hourly Rate' => 'Hourly Rate')) !!}
                </div>
                <h3 class="form-group" style="padding-left: 17px;padding-top:25px" id="myModalLabel">
                    Age Minimum Requirement    
                </h3>
                <div class="col-xs-12">
                {!! Form::select('age', array('0' => 'Any Age', '1' => 'Children (5 - 12 years old)', '2' => 'Teen (13 - 19 years old)', '3' => 'Adult (20 - 34 years old)', '4' => 'Old (35 - 50 years old)')) !!}
                </div>
                <h3 class="form-group" style="padding-left: 17px;padding-top:25px" id="myModalLabel">
                    Gender    
                </h3>
                <div class="col-xs-12">
                {!! Form::select('gender', array('any' => 'Any gender', 'male' => 'Male only', 'female' => 'Female only')) !!}
                </div>
                <h3 class="form-group" style="padding-left: 17px;padding-top:25px" id="myModalLabel">
                    Type of talent    
                </h3>
                <div class="col-xs-12">
                {!! Form::select('group', array('0' => 'Group and Individual', '1' => 'Individual Only', '2' => 'Group only')) !!}
                </div>
                <h3 class="form-group" style="padding-left: 17px;padding-top:25px" id="myModalLabel">
                    Number of Talent(s)    
                </h3>
                <div class="col-xs-12">
                {!! Form::number('hire_number', '', array('class' => 'form-control', 'placeholder' => 'Number of Talents Needed', 'required'=>'required', 'min'=>'1', 'max'=>'25')) !!}
                </div>
                <div class="modal-footer">
                {!! Form::submit('Save Changes', array('class' => 'btn btn-info')) !!}  
                {!! Form::close(); !!}          
                 <!--  <button type="button" class="btn btn-primary">
                    Save changes
                  </button>         -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                  </button> 
                </div>
              </div>
              
            </div>
            
          </div>
          
        </div>
        @foreach($posts as $post)
        <div class="col-md-3 col-md-offset-1" style="right:20px;background-color: rgb(189,245,226); border-top: 1px solid;">
           <span class="label label-default">Posted on {!! $post['date_posted']->format('F d,Y H:i A') !!}</span><p class="text-right"><a class="btn" href="{!! URL::to('/deleteYourPost/'.$post['id']) !!}">Delete this post »</a></p>
        <p>
        
        </p> 
          <h3>
            {!! $post['title'] !!}
          </h3>
          <p>
            {!! $post['description'] !!}

          </p>
          @if(strpos($post['file'],'.mp4') == true)
          <video style="width: 100%;" width="250" controls>
              <source src="{!! URL::to('/files').'/'.$post['file'] !!}" type="video/mp4">
          </video>
          @else
          <img class="img-responsive" style="height: 250px; width: 250px;" src="{!! URL::to('/files').'/'.$post['file'] !!}" alt="Chania">
          @endif
          <p>Budget: {!! $post['budget'] !!} <br />
          Start date: {!! Carbon\Carbon::parse($post['start_date'])->format('F d,Y H:i A') !!}<br />
          End date: {!! Carbon\Carbon::parse($post['end_date'])->format('F d,Y H:i A') !!}</p>
          @foreach(json_decode($post['tags'],true) as $tags => $val)
          <span class="badge"><span class="glyphicon glyphicon-tag"></span>{!! $val !!}</span>
          @endforeach
          <p>
            @if($post['status'] == 0)
           <span class="label label-primary">Status: Ongoing</span>
          @else
          <span class="label label-danger">Status: Closed</span>
          @endif
          </p>
          <p>
            <a class="btn" href="{!! URL::to('/post/'.$post['id']) !!}">View details »</a>
            
          </p>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
    </section>

    

<section class="bg-dark">
<div class="footer navbar-fixed-bottom">
  
                    <p class="text-center">Talent Scout.</br>
                        All Rights Reserved. 2016 </br>
                        <small style="color: gray;">Contact Talent scout: talentscoutphil@gmail.com</small>
                        </p>
</div>
</section>
    <!-- jQuery -->
    <script src="../../vendor/jquery/jquery.min.js"></script>
     <script src="../../js/jquery-1.10.2.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="../../vendor/scrollreveal/scrollreveal.min.js"></script>
    <script src="../../vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

    <!-- Theme JavaScript -->
    <script src="../../js/creative.min.js"></script>
    <script src="../../js/carousel.js"></script>
    <script src="../../js/bootstrap-tagsinput.js"></script>
    <script src="../../js/bootstrap-tagsinput-angular.js"></script>
  <script src="../../js/jquery-ui.js"></script>
  <script src="../../js/jquery-ui-timepicker-addon.js"></script>
    <script>
  $(function() {
    $( "#datepicker" ).datetimepicker({ dateFormat: 'yy-mm-dd'});
    $( "#datepicker2" ).datetimepicker({ dateFormat: 'yy-mm-dd'});
    
    });
  </script>
  @if(count($errors)>0)
          <script>
              $('#modal-container-403917').modal('show');
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
        // else if(fextension !== 'mp4'){
        //   alert('File extension is not MP4!');
        //   $("#ifile").val('');
        // }
    });
});
  </script>
  <script>
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
                for (var i = 1; i <= 3; i++) {
                    var next_id = $("#category"+i);
                  $("#category"+i).empty().html(' ');
                  $.each(data, function(key, value) {
                      $(next_id).append($("<option></option>").attr("value", value.value).text(value.value));
                  });
                };
                // $(next_id).material_select();
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
                // $("#talent").html(data);
            }
          });
      });
      
          $("#category1").change(function() {
          var tal_cal = $(this).val();
          $.ajax({
              url: "{{ URL('/revealTalents/') }}",
              method: "GET",
              dataType: "json",
              data: {tal_cal:tal_cal},
              success: function(data){
                  var next_id = $("#talent1");
                  console.log(next_id);
                  console.log(data);
                  $("#talent1").empty().html(' ');
                  $.each(data, function(key, value) {
                      $(next_id).append($("<option></option>").attr("value", value.value).text(value.value));
                  });
                  // $("#talent").html(data);
              }
            });
        });

          $("#category2").change(function() {
          var tal_cal = $(this).val();
          $.ajax({
              url: "{{ URL('/revealTalents/') }}",
              method: "GET",
              dataType: "json",
              data: {tal_cal:tal_cal},
              success: function(data){
                  var next_id = $("#talent2");
                  console.log(next_id);
                  console.log(data);
                  $("#talent2").empty().html(' ');
                  $.each(data, function(key, value) {
                      $(next_id).append($("<option></option>").attr("value", value.value).text(value.value));
                  });
                  // $("#talent").html(data);
              }
            });
        });

          $("#category3").change(function() {
          var tal_cal = $(this).val();
          $.ajax({
              url: "{{ URL('/revealTalents/') }}",
              method: "GET",
              dataType: "json",
              data: {tal_cal:tal_cal},
              success: function(data){
                  var next_id = $("#talent3");
                  console.log(next_id);
                  console.log(data);
                  $("#talent3").empty().html(' ');
                  $.each(data, function(key, value) {
                      $(next_id).append($("<option></option>").attr("value", value.value).text(value.value));
                  });
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
                // $(next_id).material_select();
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
                // $(next_id).material_select();
                // $("#talent").html(data);
            }
          });
        });


    $("#addtalent").on("click", function (event) {
      $("#talentcontainer").append("<div id='temprow'><div class='col-xs-5'><select class='category' name='category[]'></select></div><div class='col-xs-5'><select class='talent' name='talent[]'><option value='Select Talent'>Select Talent</option></select></div><div class='col-xs-12'><a href='javascript:void(0)' id='removetalent'>Remove</a></div></div>");
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
</body>

</html>
