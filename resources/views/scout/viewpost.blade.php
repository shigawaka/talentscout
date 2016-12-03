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
    <link rel="stylesheet" type="text/css" href="../../css/rating_style.css">
  <script type="text/javascript">
</script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
    .thumbnail {
    padding:0px;
}
.panel {
  position:relative;
}
.panel>.panel-heading:after,.panel>.panel-heading:before{
  position:absolute;
  top:11px;left:-16px;
  right:100%;
  width:0;
  height:0;
  display:block;
  content:" ";
  border-color:transparent;
  border-style:solid solid outset;
  pointer-events:none;
}
.panel>.panel-heading:after{
  border-width:7px;
  border-right-color:#f7f7f7;
  margin-top:1px;
  margin-left:2px;
}
.panel>.panel-heading:before{
  border-right-color:#ddd;
  border-width:8px;
}
    </style>
      
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
            <li  class="active">
              <a href="{!! URL::to('/home') !!}">Home</a>
            </li>
            <li>
              <a href="{!! URL::to('/profile').'/'.Session::get('id') !!}">Profile</a>
            </li>
            <li>
              <a href="#">About</a>
            </li>
            @if(Session::get('roleID') == 0)
            <li>
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
        <div class="col-xs-6 col-xs-offset-1" style="height:auto;">
        <div class="form-group">
        <div class="col-sm-12 page-header">
        @if(strpos($posts['file'],'.mp4') == true)
        <video style="width: 100%;" width="400" controls>
            <source src="{!! URL::to('/files').'/'.$posts['file'] !!}" type="video/mp4">
        </video>
        @else
         <img class="img-responsive" style="height: 300px; width: 450px;" src="{!! URL::to('/files').'/'.$posts['file'] !!}" alt="Chania"> 
        @endif
          <h1>{!! $posts['title'] !!} <h6>{!! $posts['date_posted']->format('F d,Y H:i A')!!}</h6></h1>

        </div>
        <div class="col-sm-10">
        @foreach($tag as $tags => $val)
          <span class="badge"><span class="glyphicon glyphicon-tag"></span>{!! $val !!}</span>
        @endforeach
        </div>
        <div class="col-sm-4" style="padding-top: 10px;">
          <p><span class="glyphicon glyphicon-info-sign"></span>  {!! $posts['rate'] !!}</p>
        </div>
        <div class="col-sm-4" style="padding-top: 10px;">
          <p><strong>BUDGET</strong> ₱{!! $posts['budget'] !!}</p>
        </div>
        </div>
          <div class="form-group">
            <div class="col-md-10">
              <h2>Details</h2>
            </div>
            <div class="col-md-10">
              <p>{!! $posts['description'] !!}</p>
            </div>
          </div>
          @if(Session::get('roleID') == 0 && Session::get('id') == $posts['scout_id'] && $posts['status'] == 0)
          <div class="form-group">
            <div class="col-md-10" style="border-top: 2px solid;">
              <h2>Recommended Profiles!</h2>
            @if (Session::has('invite'))
            <label class="label label-danger">{!! Session::get('invite') !!}!</label>
            @endif
            </div>
            @foreach($recommended as $recom)
            <div class="col-md-5">
              <div class="card">
              <img style="width:200px; height:200px;"class="card-img-top" src="{!! URL::to('/files').'/'.$recom['profile_image'] !!}" alt="Card image cap">
              <div class="card-block">
                <h4 class="card-title">{!! $recom['firstname'].' '.$recom['lastname'] !!}</h4>
                <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
                <a href="{!! URL::to('/profile').'/'.$recom['id'] !!}" class="btn btn-success">Visit Profile</a>
                <a href="{!! URL::to('/profile/invite').'/'.$recom['id'] !!}" class="btn btn-primary">Invite</a>
              </div>
            </div>
            </div>
            @endforeach
          </div>
          @endif
        <!-- left row -->
        </div>
        <div class="col-md-4 col-md-offset-1">
        <div class="form-group">
        @if (Session::has('message'))
          <div class="alert alert-info text-center">{{ Session::get('message') }}</div>
          @endif
          @if(Session::get('roleID') == 0 && $posts['scout_id'] == Session::get('id') && $posts['status'] == 0)
          <!-- edit post -->

        <a id="modal-403917" href="#modal-container-403917" role="button" class="btn btn-info btn-lg" data-toggle="modal">EDIT</a>
          
          <div class="modal fade" id="modal-container-403917" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                  </button>
                  {!! Form::open(['url'=>'/editpost', 'files' => true]) !!}
                  <h1 class="modal-title" id="myModalLabel">
                    Edit Details
                  </h1>
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Title
                </h3>
                <div class="col-xs-12">
                {!! Form::text('title', $posts['title'], array('class' => 'col-xs-12','placeholder' => 'Enter Post Title')) !!}
                @foreach($errors->get('title') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Description
                </h3>
                <div class="col-xs-12">
                {!! Form::textarea('description', $posts['description'], array('style' => 'min-width:100%;','class' => 'form-group','placeholder' => 'Enter Description of the Job')) !!}
                @foreach($errors->get('description') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Attach File
                </h3>
                <div class="col-xs-12">
                {!! Form::file('file', '', array('class' => 'form-group','placeholder' => 'Upload Image')) !!}
                @foreach($errors->get('image') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Tags (Separated by Comma)
                </h3>
                <div class="col-xs-12">
                {!! Form::text('tags[]', implode(',', $tag) , array('placeholder' => 'Add Tags', 'data-role' => 'tagsinput')) !!}              
                 @foreach($errors->get('tags') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Budget      
                </h3>
                <div class="col-xs-12">
                {!! Form::text('budget', $posts['budget'], array('placeholder' => 'Enter Budget', 'class' => 'form-group')) !!}              
                @foreach($errors->get('budget') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Talent's Rate    
                </h3>
                <div class="col-xs-12">
                {!! Form::select('rate', array('fixed' => 'Fixed Price', 'hourly' => 'Hourly')) !!}
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
          <!-- end of modal -->
          <!-- close deal -->
        <a id="modal-403918" href="#modal-container-403918" role="button" class="btn btn-success btn-lg" data-toggle="modal">CLOSE DEAL</a>
          
          <div class="modal fade" id="modal-container-403918" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                  </button>
                  {!! Form::open(['url'=>'/closepost'.'/'.$posts['id'], 'files' => true]) !!}
                  <h1 class="modal-title text-center" id="myModalLabel">
                    Rate talent(s)
                  </h1>
                </div>
                
                @foreach($fullproposals as $fullprop => $val)
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    {!! $val['firstname'] , ' ',  $val['lastname'] !!}
                </h3>
                <div class="col-xs-12">
                <a href="{!! URL::to('/profile').'/'.$val['user_id'] !!}"><img style="width: 150px; height: 130px;" src="{!! URL::to('/files').'/'.$val['profile_image'] !!}"></a>
                </div>
                <!-- attitude -->
                <div class="col-xs-12">
                <label class="label label-info">Attitude</label>
    <div class="stars" style="margin:0;">
        {!! Form::radio('attitude['.$fullprop.']', 1, false, ['class' => 'star-1', 'id' => 'star-1a'.$fullprop.'']) !!}
        <!-- <input  type="radio" name="attitude{{$fullprop}}" class="star-1" id="star-1a{{$fullprop}}" /> -->
        <label title="Bad!" class="star-1" for="star-1a{{$fullprop}}">1</label>
        {!! Form::radio('attitude['.$fullprop.']', 2, false, ['class' => 'star-2', 'id' => 'star-2a'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-2" id="star-2a{{$fullprop}}" /> -->
        <label title="Not Bad!" class="star-2" for="star-2a{{$fullprop}}">2</label>
        {!! Form::radio('attitude['.$fullprop.']', 3, false, ['class' => 'star-3', 'id' => 'star-3a'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-3" id="star-3a{{$fullprop}}" /> -->
        <label title="Good!" class="star-3" for="star-3a{{$fullprop}}">3</label>
        {!! Form::radio('attitude['.$fullprop.']', 4, false, ['class' => 'star-4', 'id' => 'star-4a'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-4" id="star-4a{{$fullprop}}" /> -->
        <label title="Very Good!" class="star-4" for="star-4a{{$fullprop}}">4</label>
        {!! Form::radio('attitude['.$fullprop.']', 5, false, ['class' => 'star-5', 'id' => 'star-5a'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-5" id="star-5a{{$fullprop}}" /> -->
        <label title="Excellent!" class="star-5" for="star-5a{{$fullprop}}">5</label>
        <span></span>
    </div>
                </div>
                <!-- performance -->
                <div class="col-xs-12">
                <label class="label label-info">Performance</label>
    <div class="stars" style="margin:0;">
        {!! Form::radio('performance['.$fullprop.']', 1, false, ['class' => 'star-1', 'id' => 'star-1p'.$fullprop.'']) !!}
        <!-- <input  type="radio" name="attitude{{$fullprop}}" class="star-1" id="star-1a{{$fullprop}}" /> -->
        <label title="Bad!" class="star-1" for="star-1p{{$fullprop}}">1</label>
        {!! Form::radio('performance['.$fullprop.']', 2, false, ['class' => 'star-2', 'id' => 'star-2p'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-2" id="star-2a{{$fullprop}}" /> -->
        <label title="Not Bad!" class="star-2" for="star-2p{{$fullprop}}">2</label>
        {!! Form::radio('performance['.$fullprop.']', 3, false, ['class' => 'star-3', 'id' => 'star-3p'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-3" id="star-3a{{$fullprop}}" /> -->
        <label title="Good!" class="star-3" for="star-3p{{$fullprop}}">3</label>
        {!! Form::radio('performance['.$fullprop.']', 4, false, ['class' => 'star-4', 'id' => 'star-4p'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-4" id="star-4a{{$fullprop}}" /> -->
        <label title="Very Good!" class="star-4" for="star-4p{{$fullprop}}">4</label>
        {!! Form::radio('performance['.$fullprop.']', 5, false, ['class' => 'star-5', 'id' => 'star-5p'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-5" id="star-5a{{$fullprop}}" /> -->
        <label title="Excellent!" class="star-5" for="star-5p{{$fullprop}}">5</label>
        <span></span>
    </div>
                </div>
                <!-- punctuality -->
                <div class="col-xs-12">
                <label class="label label-info">Punctuality</label>
    <div class="stars" style="margin:0;">
        {!! Form::radio('punctuality['.$fullprop.']', 1, false, ['class' => 'star-1', 'id' => 'star-1pun'.$fullprop.'']) !!}
        <!-- <input  type="radio" name="attitude{{$fullprop}}" class="star-1" id="star-1a{{$fullprop}}" /> -->
        <label title="Bad!" class="star-1" for="star-1pun{{$fullprop}}">1</label>
        {!! Form::radio('punctuality['.$fullprop.']', 2, false, ['class' => 'star-2', 'id' => 'star-2pun'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-2" id="star-2a{{$fullprop}}" /> -->
        <label title="Not Bad!" class="star-2" for="star-2pun{{$fullprop}}">2</label>
        {!! Form::radio('punctuality['.$fullprop.']', 3, false, ['class' => 'star-3', 'id' => 'star-3pun'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-3" id="star-3a{{$fullprop}}" /> -->
        <label title="Good!" class="star-3" for="star-3pun{{$fullprop}}">3</label>
        {!! Form::radio('punctuality['.$fullprop.']', 4, false, ['class' => 'star-4', 'id' => 'star-4pun'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-4" id="star-4a{{$fullprop}}" /> -->
        <label title="Very Good!" class="star-4" for="star-4pun{{$fullprop}}">4</label>
        {!! Form::radio('punctuality['.$fullprop.']', 5, false, ['class' => 'star-5', 'id' => 'star-5pun'.$fullprop.'']) !!}
        <!-- <input type="radio" name="attitude{{$fullprop}}" class="star-5" id="star-5a{{$fullprop}}" /> -->
        <label title="Excellent!" class="star-5" for="star-5pun{{$fullprop}}">5</label>
        <span></span>
    </div>
                </div>
                @endforeach  
                <div class="modal-footer">
                {!! Form::submit('Rate talent(s) and close deal!', array('class' => 'btn btn-info')) !!}  
                {!! Form::close(); !!}          
                 <!--  <button type="button" class="btn btn-primary">
                    Save changes
                  </button>         -->
                  <button type="button" class="btn btn-default" data-dismiss="modal">
                    Cancel
                  </button> 
                </div>
              </div>
              
            </div>
            
          </div>
          @elseif(empty($proposal) && Session::get('roleID') == 1 && $posts['status'] == 0)
          <!-- end of modal -->
        <a id="modal-403917" href="#modal-container-403917" role="button" class="btn btn-success btn-lg" data-toggle="modal">Add proposal</a>
          
          <div class="modal fade" id="modal-container-403917" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                  </button>
                  {!! Form::open(['url'=>'/addProposal', 'files' => true]) !!}
                  <h1 class="modal-title" id="myModalLabel">
                    Proposal Form
                  </h1>
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Description of yourself
                </h3>
                <div class="col-xs-12">
                {!! Form::textarea('body', '', array('style' => 'min-width:100%;','class' => 'form-group','placeholder' => 'Enter Description of yourself and why you think you are perfect for this job')) !!}
                @foreach($errors->get('body') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Proposed rate  <small class="col-xs-offset-1">Client's Budget: ₱{!! $posts['budget'] !!}</small>
                </h3>

                <div class="col-xs-12">
                {!! Form::number('rate', '', array('placeholder' => 'Enter your proposed rate', 'class' => 'form-group')) !!}              
                @foreach($errors->get('budget') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <div class="modal-footer">
                {!! Form::submit('Propose', array('class' => 'btn btn-info')) !!}  
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
          <!-- end of modal -->
          @elseif(Session::get('roleID') == 1 && $posts['status'] == 0)
          <!-- start of modal -->
          <a id="modal-403917" href="#modal-container-403917" role="button" class="btn btn-success btn-lg" data-toggle="modal">Edit Proposal</a>
          
          <div class="modal fade" id="modal-container-403917" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                  </button>
                  {!! Form::open(['url'=>'/editProposal', 'files' => true]) !!}
                  <h1 class="modal-title" id="myModalLabel">
                    Proposal Form
                  </h1>
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Description of yourself
                </h3>
                <div class="col-xs-12">
                {!! Form::textarea('body', $proposal['body'], array('style' => 'min-width:100%;','class' => 'form-group','placeholder' => 'Enter Description of yourself and why you think you are perfect for this job')) !!}
                @foreach($errors->get('body') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Proposed rate  <small class="col-xs-offset-1">Client's Budget: ₱{!! $posts['budget'] !!}</small>
                </h3>

                <div class="col-xs-12">
                {!! Form::number('rate', $proposal['proposed_rate'], array('placeholder' => 'Enter your proposed rate', 'class' => 'form-group')) !!}              
                @foreach($errors->get('budget') as $message)
                {!! $message !!}
                @endforeach
                </div>
                <div class="modal-footer">
                {!! Form::submit('Edit Proposal', array('class' => 'btn btn-info')) !!}  
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
          <!-- end of modal -->
          @endif
        </div>
        @if(Session::get('roleID') == 0 && $posts['scout_id'] == Session::get('id') && $posts['status'] == 0)
        <div class="form-group">
          <a id="modal-403919" href="#modal-container-403919" role="button" class="btn btn-default btn-lg" data-toggle="modal"><span class="glyphicon glyphicon-user"></span> Proposals</a>
          
          <div class="modal fade" id="modal-container-403919" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                  </button>
                  
                  <h1 class="modal-title" id="myModalLabel">
                    Proposals
                  </h1>
                </div>
                @foreach($fullproposals as $fullprop => $val)
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    {!! $val['firstname'] , ' ',  $val['lastname'] !!}
                    @if(!empty($val['hired']))
                      @if($val['user_id'] === $val['hired'])
                      <a disabled class="btn btn-success" href="{!! URL::to('/hire').'/'.$val['user_id'] !!}">Hired</a>
                      @else
                      <a class="btn btn-info" href="{!! URL::to('/hire').'/'.$val['user_id'] !!}">Hire</a>
                      @endif
                    @else
                    <a class="btn btn-info" href="{!! URL::to('/hire').'/'.$val['user_id'] !!}">Hire</a>
                    @endif
                </h3>
                <div class="col-xs-12">
                <a href="{!! URL::to('/profile').'/'.$val['user_id'] !!}"><img style="width: 150px; height: 130px;" src="{!! URL::to('/files').'/'.$val['profile_image'] !!}"></a>
                <blockquote class="blockquote">
                <p class="m-b-0">{!! $val['body'] !!}</p>
                <p class="m-b-0">Proposed Rate: ₱{!! $val['proposed_rate'] !!}</p>
                </blockquote>
                </div>
                
                @endforeach          
                <div class="modal-footer">
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
          <!-- end of modal -->
        </div>
        @endif
        </div>
        <!-- comment area -->
            {!! Form::open(['url'=>'/addComment']) !!}
                          <div class="col-sm-1">
                  <div class="thumbnail">
                  <img class="img-responsive user-photo" src="{!! URL::to('/files').'/'.Session::get('profile_image') !!}">
                  </div><!-- /thumbnail -->
                  </div><!-- /col-sm-1 -->

                  <div class="col-sm-4">
                  <div class="panel panel-default">
                  <div class="panel-heading">
                  {!! Form::textarea('comment', '', array('style' => 'min-width:100%;resize:none;height:120px;width:20px;','class' => 'form-group','placeholder' => 'Type some comment')) !!}
                  @foreach($errors->get('body') as $message)
                  {!! $message !!}
                  @endforeach
                </div>
                  <div class="panel-body">
                  {!! Form::button('Post Comment',['class'=>'btn btn-info col-xs-12', 'type' => 'submit ']) !!}
<!--                   <button class="btn btn-info col-xs-12">Post Comment</button>
 -->                  </div><!-- /panel-body -->
                  </div><!-- /panel panel-default -->
                  </div><!-- /col-sm-5 -->
                  @foreach($comments as $comment => $val)
                  <div class="col-sm-1">
                  <div class="thumbnail">
                  <img class="img-responsive user-photo" src="{!! URL::to('/files').'/'.$val['profile_image'] !!}">
                  </div><!-- /thumbnail -->
                  </div><!-- /col-sm-1 -->

                  <div class="col-sm-4">
                  <div class="panel panel-default">
                  <div class="panel-heading">
                  <strong>{!! $val['username'] !!}</strong> <span class="text-muted">commented {!! $val['date_posted'] !!} ago</span>
                  </div>
                  <div class="panel-body">
                  {!! $val['body'] !!}
                  </div><!-- /panel-body -->
                  </div><!-- /panel panel-default -->
                  </div><!-- /col-sm-5 -->
                  @endforeach
                  
        <!-- end of comment area -->
        <!-- right row -->
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
</body>

</html>
