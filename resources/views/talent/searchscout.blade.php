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
    <link href="../../css/creative.css" rel="stylesheet">
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
            <li>
              <a href="{!! URL::to('/post') !!}">My Posts</a>
            </li>
            @endif
            <li class="active">
              <a href="{{ URL::to('/searchscout') }}">SEARCH</a>
            </li>
          </ul>
         <!--  {!! Form::open(['url'=>'/searchscout', 'class' => 'navbar-form navbar-left', 'style'=>'width:600px;']) !!}
          
            
          {!! Form::text('search', '', array('placeholder' => 'Search...', 'class' => 'form-control', 'style' => 'width:70%;')) !!}              
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
        <div class="col-xs-3" style="height:900px;">
        <div class="form-group">
        <div class="col-sm-10">
          <h3>Search Criteria</h3>
          </div>
          {!! Form::open(['url'=>'/searchingkeyscout', 'files' => true]) !!}
          <div class="col-sm-10">
          {!! Form::text('search', '', array('class' => 'form-control','placeholder' => 'Enter the Keywords')) !!}
          <!-- <input class="form-control" type="text" name="keyword" placeholder="Enter the Keywords"> --><br>
          </div>
          <div class="col-sm-10">
          <label for="sel1">By Talent Category:</label>
          {!! Form::select('category', ['' => 'Select category'], null, ['id' => 'category','class' => 'form-control']) !!}
          <br>
          </div>
        </div>
           {!! Form::submit('Search',['class'=>'btn btn-success btn-lg btn-block', 'placeholder' =>'Search']) !!}
          {!! Form::close() !!}
        </div>
        @if(count($result) == 0)
        No match found!
        @else
        @foreach($result as $res)
        <div class="col-md-3" style="width: 250px;height:500px;">
          <div class="thumbnail" style="height: 100%;">
            <img alt="Bootstrap Thumbnail First" src="{!! URL::to('/files').'/'.$res['profile_image'] !!}" />
            <div class="caption">
              <h4>
                 @if(empty($res['groupname']))
                <b>{!! ucfirst($res['firstname']).' '.ucfirst($res['lastname']) !!}</b>
                @else
                <b>{!! ucfirst($res['groupname']) !!}</b>
                @endif
              </h4>
              <p style="font-size: 13px;">
                <i>{!! $res['profile_description'] !!}</i><br>
              </p>
              <p>
                <a class="btn btn-primary" href="{{ URL::to('/endorseUser').'/'.$res['id'] }}">Endorse</a> <a class="btn" href="{{ URL::to('/profile').'/'.$res['id'] }}">View</a>@if(!empty($res['fee']))<span class="label label-default"> Talent Fee:  {!! $res['fee'] !!} @endif 
                </span>
                </br>
                @if($res['rank'] <= 1000)
                <span class="label label-success">Profile Rank: Newbie</span>
                @elseif($res['rank'] >= 1500)
                <span class="label label-success">Profile Rank: Rising Talent</span>
                @else
                 <span class="label label-success">Profile Rank: Star Talent</span>
                @endif
              </p>
            </div>
          </div>
        </div>
        <!-- end of col -->
        @endforeach
        @endif
      </div>
    </div>
  </div>
</div>
    </section>

    

<section class="bg-dark">

<div class="col-lg-12 text-center">
                    <p>Talent Scout.</br>
                        All Rights Reserved.</br>
                        2016</p>
                </div>
</section>
    <!-- jQuery -->
    <script src="../../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="../../vendor/scrollreveal/scrollreveal.min.js"></script>
    <script src="../../vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

    <!-- Theme JavaScript -->
    <script src="../../js/creative.min.js"></script>
    <script src="../../js/carousel.js"></script>
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
      });

</script>
</body>

</html>
