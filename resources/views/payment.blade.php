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
    <link rel="stylesheet" type="text/css" href="../../css/customcssdropdown.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
      div.clear
{
    clear: both;
}

div.product-chooser{
    
}

    div.product-chooser.disabled div.product-chooser-item
  {
    zoom: 1;
    filter: alpha(opacity=60);
    opacity: 0.6;
    cursor: default;
  }

  div.product-chooser div.product-chooser-item{
    padding: 11px;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    border: 1px solid #efefef;
    margin-bottom: 10px;
        margin-left: 10px;
        margin-right: 10x;
  }
  
  div.product-chooser div.product-chooser-item.selected{
    border: 4px solid #428bca;
    background: #efefef;
    padding: 8px;
    filter: alpha(opacity=100);
    opacity: 1;
  }
  
    div.product-chooser div.product-chooser-item img{
      padding: 0;
    }
    
    div.product-chooser div.product-chooser-item span.title{
      display: block;
      margin: 10px 0 5px 0;
      font-weight: bold;
      font-size: 12px;
    }
    
    div.product-chooser div.product-chooser-item span.description{
      font-size: 12px;
    }
    
    div.product-chooser div.product-chooser-item input{
      position: absolute;
      left: 0;
      top: 0;
      visibility:hidden;
    }
    </style>
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
    <a  href="{{ URL::to('/paymentprocess') }}">Get your profile featured!</a>
    </div>
    <div class="col-md-12">
      <nav class="navbar navbar-inverse" role="navigation">
        <div class="collapse navbar-collapse"  id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li >
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
              <a href="{{ URL::to('/searchscout') }}">SEARCH</a>
            </li>
          </ul>
         <!--  {!! Form::open(['url'=>'/searchscout', 'class' => 'navbar-form navbar-left', 'style'=>'width:600px;']) !!}
          
            
          {!! Form::text('search', '', array('id' => 'q','placeholder' => 'Search...', 'class' => 'form-control', 'style' => 'width:70%;')) !!}              
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
    </div>
    <div class="col-md-12">
    @if (Session::has('success'))
                  <div class="alert alert-success">
                    <strong>{{ Session::get('success') }}</strong>
                  </div>
    @elseif (Session::has('failed'))
                  <div class="alert alert-success">
                    <strong>{{ Session::get('success') }}</strong>
                  </div>
    @endif
        <h1 class="text-center" style="color:#F22613;">Get your profile to be featured!</h1>
        <h3 class="text-center">How does this process work? </h3></br> <p class="text-center">Scouts homepage is different from Talent's homepage. The first thing Scouts see is the featured profiles which gets more chance of getting known and trusted. (Note: You will receive a notification when will your profile starts to get featured.) </p>
        <h3 class="text-center">Why do I need it? </h3> <p class="text-center">Getting your profile featured in this site makes you more exposed to Scouts that are finding talents and increasing your chance of getting hired!</p>
        <h3 class="text-center">How long do I get featured? </h3> <p class="text-center">Minimum duration of getting featured is 1 week and the longest is 3 weeks.</p>
        <h3 class="text-center">How do I pay? </h3> <p class="text-center">You can process your payment using Paypal.</p> </br></br></br>
        <h1 class="text-center">Choose your duration!</h1>
    <div class="row form-group product-chooser">
    {!! Form::open(['url'=>'/payment']) !!}
      
      @foreach($subscription as $key => $value)
      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="product-chooser-item selected">
          <img src="{{ URL::to('/images').'/'.$value['file']}}" class="img-rounded col-xs-4 col-sm-4 col-md-12 col-lg-12" alt="Mobile and Desktop">
                <div class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
            <span class="title">{{$key + 1}} week · For only ‎${{ $value['price']  }}  or in Peso‎(₱{!! $value['price']*49 !!}) </span>
            <span class="description">{{ $value['description'] }}</span>
            <!-- <input type="radio" name="product" value="mobile_desktop" checked="checked"> -->
            {!! Form::hidden('hiddenprice[]',$value['price'] , array('id' => 'hehe')) !!}
            {!! Form::radio('duration',  $key + 1 , true) !!}
          </div>
          <div class="clear"></div>
        </div>
      </div>
      @endforeach
      
      <button type="submit"><img class="img-responsive center-block" src="{{ URL::to('images/paynow_button.png') }}"></button>
      {!! Form::close() !!}
      <a href="" class="btn btn-info" id="linkcc">USE LINKED CARD</a>
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
    <script type="text/javascript">
      $(function(){
  $('div.product-chooser').not('.disabled').find('div.product-chooser-item').on('click', function(){
    $(this).parent().parent().find('div.product-chooser-item').removeClass('selected');
    $(this).addClass('selected');
    $(this).find('input[type="radio"]').prop("checked", true);
    var duration = $(this).find("input[type='hidden']").val();
    var price = $(this).find("[name=duration]").val();
    $("#linkcc").attr("href", "http://localhost:8000/paythroughcard"+"/"+duration+"/"+price);
  });
});
    </script>
</body>

</html>
