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
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

    <!-- Plugin CSS -->
    <link href="vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="../../css/creative.min.css" rel="stylesheet">
    <link href="../../css/creative.css" rel="stylesheet">

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
              <a href="{!! URL::to('/featured') !!}">Administrator Panel</a>
            </li>
            <li>
              <a href="{!! URL::to('/about') !!}">About</a>
            </li>
            @if(Session::get('roleID') == 0)
            <li>
              <a href="{!! URL::to('/post') !!}">My Posts</a>
            </li>
            @endif
          </ul>
          {!! Form::open(['url'=>'/searchscout', 'class' => 'navbar-form navbar-left', 'style'=>'width:600px;']) !!}
          
            
          {!! Form::text('search', '', array('id' => 'q','placeholder' => 'Search...', 'class' => 'form-control', 'style' => 'width:70%;')) !!}              
          {!! Form::submit('Search', array('class' => 'btn btn-default')) !!}   
            
            {!! Form::close() !!}
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="glyphicon glyphicon-bell"></span>
              </a>
              {{-- @foreach($unreadNotifications as $notification)
              <div class="notification {{ $notification->type }}">    
                  <p class="subject">{{ $notification->subject }}</p>
                  <p class="body">{{ $notification->body }}</p>
              </div>
              @endforeach --}}
            </li>
            @if(Session::get('roleID') == 2)
            <li>
              <a href="#">Welcome {!! ucfirst(Session::get('groupname'))  !!} !</a>
            </li>
            @else
            <li>
              <a href="#">Welcome {!! ucfirst(Session::get('firstname')),' ', ucfirst(Session::get('lastname'))  !!} !</a>
            </li>
            @endif
             <li>
              <a href="{!! URL::to('/logout') !!}">Logout</a>
            </li>
          </ul>
        </div>
        
      </nav>
      <div class="row">
      .<div class="carouselcontainer">
       

                <!--  START OF carousel -->




                <div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img style="width:1280px; height:380px;" src="img/testi1.jpg" alt="Chania">
    </div>

    <div class="item">
      <img class="img-responsive" style="width:79%; height:380px;" src="img/testi2.jpg" alt="Chania">
    </div>

    <div class="item">
      <img style="width:1280px; height:380px;" src="img/header.jpg" alt="Chania">
    </div>

   
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

    <div class="col-md-12">
      <div class="page-header">
        <h1 style="text-align:center;">
          Recent Deals Posted<br />
        </h1>
      </div>
      <div class="row">
        @foreach($posts as $post)
        <div class="col-md-3 pull-left" style="width:250px;height: auto;">
          <div class="thumbnail" style="height:100%;">
          @if(strpos($post['file'],'.mp4') == true)
            <video style="width: 100%;" width="400" controls>
              <source src="{!! URL::to('/files').'/'.$post['file'] !!}" type="video/mp4">
            </video>
          @else
            <img class="img-responsive" style="height:300px; width:200px;"  alt="Bootstrap Thumbnail First" src="{!! URL::to('/files').'/'.$post['file'] !!}" />
          @endif
            <div class="caption text-center">
            <span class="label label-default">Posted on {!! $post['date_posted']->format('F d,Y H:i A') !!}</span>
              <h3>
                {!! $post['title'] !!}
              </h3>
              <p style="font-size:13px;">
              {!! $post['description'] !!}
              </p>
              <p>
                <a class="btn btn-primary" href="{!! URL::to('/post/'.$post['id']) !!}">View Details</a>
              </p>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      <div class="page-header" style="background-color: #86E2D5; padding-top: 5px;">
        <h1 style="text-align:center;">
          Recent Successful Deals
        </h1>
      </div>
      <div class="row">
        @foreach($succ as $succs)
         <!-- <div class="col-md-2 col-md-offset-1" >
          <img style="height: 150px; width: 200px;" alt="Bootstrap Image Preview" src="{!! URL::to('/files').'/'.$post['image'] !!}" /></br>
        </div> -->
        <div class="col-md-7 col-md-offset-2" style="padding-top: 5px;">
           <span class="label label-default">Posted on {!! $succs['date_posted']->format('F d,Y H:i A') !!}</span>
           <span class="label label-default">{!! $succs['rate'] !!}</span>
        <p>
        </p> 
          <h3>
            <b>{!! $succs['title'] !!}</b>
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
    <script src="../../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../../css/jquery-ui.css">
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
   $(document).ready(function() {
    src = "{{ URL('/addmembers/') }}";
     $("#q").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                    console.log(data);
                }
            });
        },
        min_length: 3,
       
    });
});
</script>
</body>

</html>
