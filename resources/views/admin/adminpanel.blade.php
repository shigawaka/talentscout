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
            <li  >
              <a href="{!! URL::to('/home') !!}">Home</a>
            </li>
            <li class="active">
              <a href="{!! URL::to('/featured') !!}">Administrator Panel</a>
            </li>
            <li>
              <a href="{!! URL::to('/about') !!}">About</a>
            </li>
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
    </div>
    <div class="col-md-12">
     @if (Session::has('message'))
          <div class="alert alert-info text-center">{{ Session::get('message') }}</div>
     @endif
    <!-- begin modal for adding featured profile -->
    <a id="modal-403917" href="#modal-container-403917" role="button" class="btn btn-success btn-lg" data-toggle="modal">Add Featured Profile</a>
          
          <div class="modal fade" id="modal-container-403917" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                  </button>
                  {!! Form::open(['url'=>'/addFeaturedProfile', 'method' => 'POST']) !!}
                  <h1 class="modal-title" id="myModalLabel">
                    Add featured profile
                  </h1>
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Search User
                </h3>
                <div class="col-xs-12">
                {!! Form::text('q', '', array('id' => 'autocomplete-input','class' => 'autocomplete', 'require' => 'required')) !!}
                </div>
                <div id="mem"></div>
                {!! Form::hidden('invisible', 'secret', array('id' => 'invisible_id')) !!}
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add Start Date     
                </h3>
                <div class="col-xs-12">
                {!! Form::text('start_date', '', array('id' => 'datepicker','class' => 'form-control', 'placeholder' => 'Event starting', 'required'=>'required')) !!}                        
                </div>
                <h3 class="form-group" style="padding-left: 10px;" id="myModalLabel">
                    Add End Date     
                </h3>
                <div class="col-xs-12">
               {!! Form::text('end_date', '', array('id' => 'datepicker2','class' => 'form-control', 'placeholder' => 'Event ending', 'required'=>'required')) !!}                        
                                                  
                </div>

                                                  @foreach($errors->all() as $message)
                                                    <div class="col-xs-12"><div class="alert alert-danger text-center">{{ $message }}
                                                    </div></div>
                                                    @endforeach
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
        <!-- end adding featured profile -->
        <h1>Featured Profiles</h1>

        @foreach($profilearray as $value)
        <div class="col-md-3" style="width: 250px;height:auto;">
          <div class="thumbnail" style="height: 100%;">
            <a href="{{ URL::to('/removefeaturedprofile').'/'.$value['id'] }}">Remove from feature</a>
            <img alt="Bootstrap Thumbnail First" src="{!! URL::to('/files').'/'.$value['profile_image'] !!}" />
            <div class="caption">
              <h4>
                <b>{!! ucfirst($value['firstname']).' '.ucfirst($value['lastname']) !!}</b>
              </h4>
              <h5>
                Start date: </br>{{ Carbon\Carbon::parse($value['start_date'])->format('F d,Y H:i A') }}
              </h5>
              <h5>
                End date: </br>{{ Carbon\Carbon::parse($value['end_date'])->format('F d,Y H:i A') }}
              </h5>
              <h5>
                Remaining: {{ Carbon\Carbon::parse($value['end_date'])->diffForHumans(null, true) }} left
              </h5>
            </div>
          </div>
        </div>
        <!-- end of col -->
        @endforeach
        </div>
        <!-- begin featured slideshow -->
        <!-- begin modal for adding featured profile -->
    <a id="modal-403918" href="#modal-container-403918" role="button" class="btn btn-success btn-lg" data-toggle="modal">Add Featured Feedbacks</a>
          
          <div class="modal fade" id="modal-container-403918" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                  </button>
                  {!! Form::open(['url'=>'/addFeaturedFeedback', 'files' => true]) !!}
                  <h1 class="modal-title" id="myModalLabel">
                    Add featured feedback
                  </h1>
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
        <!-- end adding featured profile -->
        <div class="col-md-12">
        <h1>Featured Slideshow</h1>
        @foreach($feedbacks as $value)
        <div class="col-md-3" style="width: 250px;height:auto;">
          <div class="thumbnail" style="height: 100%;">
            <a href="{{ URL::to('/removefeaturedfeedback').'/'.$value['id'] }}">Remove from feature</a>
            <img alt="Bootstrap Thumbnail First" src="{!! URL::to('/files').'/'.$value['image'] !!}" />
          </div>
        </div>
        <!-- end of col -->
        @endforeach
        </div>
        <!-- end featured slideshow -->
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
  <script src="../../js/jquery.materialize-autocomplete.js"></script>
  @if(count($errors)>0)
          <script>
              $('#modal-container-403917').modal('show');
          </script>
  @endif
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
                url: "{{ URL('/searchUserFeaturedProfile/') }}",
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
                   $("#mem").append("<div class='thumbnail'><div class='caption' style='text-align:center;'><p>"+name+"</p></div></div>");
                   }
                   else {
                    
                      $("#mem").append("<div class='thumbnail'><img width='120px;' src=http://localhost:8000/files/"+pic+" /><div class='caption' style='text-align:center;'><p>"+name+"</p>"+'Select <input type="checkbox" checked value='+id+' name="selected">'+"</div></div>");
                      $('input[name=invisible]').val(id);

                   }
                   // $("#autocomplete-input").val(id);
                  });
                }
            });
    });
});
</script>
</body>

</html>