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
              <a href="#">About</a>
            </li>
            @if(Session::get('roleID') == 0)
            <li  class="active">
              <a href="{!! URL::to('/post') !!}">My Posts</a>
            </li>
            @endif
          </ul>
          
          {!! Form::open(['url'=>'/search', 'class' => 'navbar-form navbar-left']) !!}
          
            <div class="form-group">
          {!! Form::text('search', '', array('placeholder' => 'Search talent', 'class' => 'form-control', 'style' => 'width:70%;')) !!}              
          {!! Form::submit('Search', array('class' => 'btn btn-default')) !!}   
            </div>
            {!! Form::close() !!}
          
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="glyphicon glyphicon-bell"></span>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="#">Action</a>
                </li>
                <li>
                  <a href="#">Another action</a>
                </li>
                <li>
                  <a href="#">Something else here</a>
                </li>
                <li class="divider">
                </li>
                <li>
                  <a href="#">Separated link</a>
                </li>
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
        <div class="col-xs-3 col-xs-offset-1" style="height:auto;">
        <div class="form-group">
        <div class="col-sm-10">
        {!! Form::open(['url'=>'/sortpost']) !!}
          <h1>Sort By</h1>
          </div>
          <div class="col-sm-10">
          {!! Form::select('sort', ['' => 'Select sorting','status' => 'Closed Deals', 'date_posted' => 'Date Posted']) !!}
          </div>
        {!! Form::submit('Sort', array('class' => 'btn btn-info btn-lg')) !!} 
        {!! Form::close() !!}
        </div>
           <!-- {!! Form::button('Create Account',['id' => 'modal-403917','href' => '#modal-container-403917','role' => 'button','class'=>'btn btn-success btn-lg btn-block']) !!} -->
           <a id="modal-403917" href="#modal-container-403917" role="button" class="btn btn-success btn-lg" data-toggle="modal">ADD POST</a>
          
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
                {!! Form::text('title', '', array('class' => 'col-xs-12','placeholder' => 'Enter Post Title')) !!}
                @foreach($errors->get('title') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Description
                </h3>
                <div class="col-xs-12">
                {!! Form::textarea('description', '', array('style' => 'min-width:100%;','class' => 'form-group','placeholder' => 'Enter Description of the Job')) !!}
                @foreach($errors->get('description') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Attach File
                </h3>
                <div class="col-xs-12">
                {!! Form::file('file', '', array('class' => 'form-group','placeholder' => 'Upload Image')) !!}
                @foreach($errors->get('file') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Tags (Separated by Comma)
                </h3>
                <div class="col-xs-12">
                {!! Form::text('tags[]', '', array('placeholder' => 'Add Tags', 'data-role' => 'tagsinput')) !!}              
                 @foreach($errors->get('tags') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Start Date     
                </h3>
                <div class="col-xs-12">
                {!! Form::text('start_date', '', array('id' => 'datepicker','class' => 'form-control', 'placeholder' => 'Event starting')) !!}                        
                                                  

                                                  @foreach($errors->get('birthday') as $message)
                                                    {{ $message }}
                                                    @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add End Date     
                </h3>
                <div class="col-xs-12">
               {!! Form::text('end_date', '', array('id' => 'datepicker2','class' => 'form-control', 'placeholder' => 'Event ending')) !!}                        
                                                  

                                                  @foreach($errors->get('birthday') as $message)
                                                    {{ $message }}
                                                    @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Budget      
                </h3>
                <div class="col-xs-12">
                {!! Form::text('budget', '', array('placeholder' => 'Enter Budget', 'class' => 'form-group')) !!}              
                @foreach($errors->get('budget') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Talent's Rate    
                </h3>
                <div class="col-xs-12">
                {!! Form::select('rate', array('Fixed Price' => 'Fixed Price', 'Hourly Rate' => 'Hourly')) !!}
                @foreach($errors->get('rate') as $message)
                {!! $message !!}
                @endforeach
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
        <div class="col-md-6 col-md-offset-1 pull-right" style="right:20px;background-color: rgb(189,245,226); border-top: 1px solid;">
           <span class="label label-default">Posted on {!! $post['date_posted']->format('F d,Y H:i A') !!}</span>
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
    <script src="../../js/bootstrap-tagsinput.js"></script>
    <script src="../../js/bootstrap-tagsinput-angular.js"></script>
     <script src="../../js/jquery-1.10.2.js"></script>
  <script src="../../js/jquery-ui.js"></script>
  <script src="../../js/jquery-ui-timepicker-addon.js"></script>
    <script>
  $(function() {
    $( "#datepicker" ).datetimepicker({ dateFormat: 'yy-mm-dd'});
    $( "#datepicker2" ).datetimepicker({ dateFormat: 'yy-mm-dd'});
    });
  </script>
</body>

</html>
