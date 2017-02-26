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
                
                <a class="navbar-brand page-scroll" href="{!! URL::to('/home') !!}"><img src="../../img/logo.png" class="logo"></a>

           
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
            <li  class="active">
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
    <div class="col-md-12">
      <div class="carouselcontainer" style="background-color:#2c3e50;">
       

                <!--  START OF carousel -->




                <div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    @if(count($testimonialarr) !== 0)
        @for($i = 1; $i <= count($testimonialarr); $i++)
        <li data-target="#myCarousel" data-slide-to="{!! $i !!}"></li>
        @endfor
    @endif()
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="img/carousel_1.jpg" alt="Chania">
    </div>

    <div class="item">
      <img src="img/carousel_2.jpg" alt="Chania">
    </div>
    @if(!empty($testimonialarr))
        @foreach($testimonialarr as $key => $value)
        <div class="item">
            <div class="col-md-3 text-center">
          <img style="max-width:65%;" src="{{ URL::to('/files').'/'.$value['picture'] }}" alt="Chania">
            </div>
          <blockquote class="blockquote" style="padding-top:105px;">
          <p class="mb-0" style="color:white;">{{ $value['comment'] }}</p>
          <footer class="blockquote-footer">Talent Scout User <cite title="Source Title">{{ ucfirst($value['firstname']) }} {{ ucfirst($value['lastname']) }}.</cite><br /><cite>Rated Talent Scout {{ $value['score'] }}/5</cite></footer>
        </blockquote>
        </div>
        @endforeach
    @endif
  </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

<!--end of carousel-->
               


    </div>
    <div class="page-header" style="background-color: #86E2D5; padding-top: 5px;">
        <h1 style="text-align:center;">
          Talents which matches your criteria
        </h1>
      </div>
      <div class="row">
      @if(count($recomprofile) == 0)
        <div class="col-md-4">
            <p>None for now</p>
        </div>
      @else
        @foreach($recomprofile as $pro)
          <div class="col-md-2 text-center">
            <img style="height:150px;" src="{{ URL::to('/files').'/'.$pro['profile_image'] }}" alt="Chania">
            <p>{!! ucfirst($pro['firstname']) !!} {!! ucfirst($pro['lastname']) !!}</p>
            <p style="font-size:10px;">{!! ucfirst($pro['profile_description']) !!}</p>
            @if($pro['gender'] == 'group')
            <small>Group</small></br>
            @else
            <small>Gender: {!! ucfirst($pro['gender']) !!}</small> </br>
            <small>Age: {!! $pro['age'] !!}</small> </br>
            @endif
            <small>Score accumulated: {!! $pro['score'] !!} points</small>
            <a class="btn btn-info" href="{!! URL::to('/profile').'/'.$pro['id'] !!}">Visit profile</a>
          </div>
        @endforeach
      @endif
      </div>
      <div class="page-header" style="background-color: #EB9532; padding-top: 5px;">
        <h1 style="text-align:center;">
          Your Posted Deals
        </h1>
      </div>
      <div class="row">
      @if(count($posts) == 0)
      @else
        @foreach($posts as $post)
         <div class="col-md-2 col-md-offset-1" >
         @if(strpos($post['file'],'.mp4') == true)
        <video style="width: 100%;" width="400" controls>
            <source src="{!! URL::to('/files').'/'.$post['file'] !!}" type="video/mp4">
        </video>
        @else
          <img style="height: 150px; width: 200px;" alt="Bootstrap Image Preview" src="{!! URL::to('/files').'/'.$post['file'] !!}" /></br>
        @endif
        </div>
        <div class="col-md-7 col-md-offset-2" style="padding-top: 5px;">
           <span class="label label-default">Posted on {!! $post['date_posted']->format('F d,Y H:i A') !!}</span>
           <span class="label label-default">{!! $post['rate'] !!}</span>
        <p>
        </p> 
          <h3>
            {!! $post['title'] !!}
          </h3>
          <p>
            {!! $post['description'] !!}

          </p>
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
      @endif
      </div>
      <div class="page-header" style="background-color: #86E2D5; padding-top: 5px;">
        <h1 style="text-align:center;">
          Recent Successful Deals
        </h1>
      </div>
      <div class="row">
       @if(count($succ) == 0)
       @else
        @foreach($succ as $succs)
        <div class="col-md-7 col-md-offset-2" style="padding-top: 5px;">
           <span class="label label-default">Posted on {!! $succs['date_posted']->format('F d,Y H:i A') !!}</span>
           <span class="label label-default">{!! $succs['rate'] !!}</span>
        <p>
        </p> 
          <h3>
            {!! $succs['title'] !!}
          </h3>
          <p>
            {!! $succs['description'] !!}

          </p>
          <p>
            @if($succs['status'] == 0)
           <span class="label label-primary">Status: Ongoing</span>
          @else
          <span class="label label-danger">Status: Closed</span>
          @endif
          </p>
          <p>
            <a class="btn" href="{!! URL::to('/post/'.$succs['id']) !!}">View details »</a>
          </p>
        </div>
        @endforeach
        @endif
      </div>

    </div>
  </div>
    </div>
  </div>
</div>
    </section>

    

<section class="bg-dark">
                    <p class="text-center">Talent Scout.</br>
                        All Rights Reserved. 2016 </br>
                        <small style="color: gray;">Contact Talent scout: talentscoutphil@gmail.com</small>
                        </p>
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

</body>

</html>
