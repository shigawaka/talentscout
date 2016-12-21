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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
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
        <div class="container text-center">
            <h3 class="red">Talent Registration</h3>
            <h5>Register for free and experience the dashboard today</h5>
            <hr>

            <div class="registerbox">
              <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center">
                </br>

                <div id="content">
    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li class="active"><a href="#individual" data-toggle="tab">Individual</a></li>
        <li><a href="#group" data-toggle="tab">Group</a></li>
        
    </ul>
    <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="individual">
            
              </br>
               {!! Form::open(['url'=>'/talentregister']) !!}
                  <!-- <form method="POST"  class="form-horizontal" role="form"> -->
                        
                           <div class="form-group">
                       
                        <div class="col-sm-10">
                        {!! Form::text('firstname',null,['class'=>'form-control', 'placeholder'=>'Enter your first name', 'required'=>'required']) !!}
                          <!-- <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Your Last Name"> -->
                              

                                                  @foreach($errors->get('firstname') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>                              
                      </div>
                         <div class="form-group">
                       
                        <div class="col-sm-10">
                          {!! Form::text('lastname',null,['class'=>'form-control', 'placeholder'=>'Enter your last name', 'required'=>'required']) !!}
                          <!-- <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Your First Name"> -->
                            

                                                  @foreach($errors->get('lastname') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>
                         <div class="form-group">
                       
                        <div class="col-sm-10">
                        {!! Form::text('birthday', '', array('id' => 'datepicker','class' => 'form-control', 'placeholder' => 'Your Date of Birth', 'required'=>'required')) !!}                        
                                                  

                                                  @foreach($errors->get('birthday') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>                     
                      </div>
                      <div class="form-group">
                       
                        <div class="col-sm-10">
                        {!! Form::label('gender', 'Gender', array('class' => 'form-control')) !!}
                        {!! Form::label('gender', 'Male') !!}
                        {!! Form::radio('gender', 'Male', false) !!}
                        {!! Form::label('gender', 'Female') !!}
                        {!! Form::radio('gender', 'Female', true) !!}
                        </div>
                      </div>
                      <div class="form-group">
                       
                        <div class="col-sm-10">
                        <div class="input-group">
                        <div class="input-group-addon">+63</div>
                        {!! Form::number('contact', '', array('class' => 'form-control','style'=>'z-index:0', 'placeholder' => 'Your Contact Number', 'required'=>'required')) !!}
                          </div>
                          <!-- <input type="text" class="form-control" name="contactno" id="contactno" placeholder="Enter email"> -->
                                                

                                                  @foreach($errors->get('contact') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>          
                      <div class="form-group">
                        
                        <div class="col-sm-10"> 
                        <!-- {!! Form::email('email', '', array('class' => 'form-control', 'placeholder' => 'Your Email Address')) !!} -->
                        {!! Form::email('emailaddress', '', ['class' => 'form-control', 'placeholder' => 'Your Email Address', 'required'=>'required']); !!}
                            <!-- <input type="input" class="form-control" name="email" id="email" placeholder="Enter Email address"> -->
                                                

                                                  @foreach($errors->get('emailaddress') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>                 
                      <div class="form-group">
                        
                        <div class="col-sm-10"> 
                        {!! Form::text('username', '', array('class' => 'form-control', 'placeholder' => 'Your username', 'required'=>'required')) !!}
                          <!-- <input type="input" class="form-control" name="username" id="username" placeholder="Enter username"> -->
                                                

                                                  @foreach($errors->get('username') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>               
                      <div class="form-group">
                        
                        <div class="col-sm-10"> 
                        <!-- {!! Form::password('password', '', array('class' => 'form-control', 'placeholder' => 'Enter your password')) !!} -->
                        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter your password', 'required'=>'required']) !!}
                          <!-- <input type="password" class="form-control" name="password" id="pwd" placeholder="Enter password"> -->
                                                

                                                  @foreach($errors->get('password') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>              
                      <div class="form-group"> 
                        <div class="col-sm-10">
                          <div class="checkbox">
                            <label><input type="checkbox">Yes, I understand and agree to the <a href="">Talent Scout Terms of Service</a></label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group"> 
                        <div class=" col-sm-10">
                          {!! Form::button('Create Account',['class'=>'btn btn-success btn-lg btn-block', 'type' => 'submit ', 'required'=>'required']) !!}
                          <!-- <button type="submit" class="btn btn-default">Create Account</button> -->
                        </div>
                      </div>
                    {!! Form::close() !!}
                    Already have an account? <a href="{{ URL::to('/login') }}">Log In</a>
                </br>
        </div>

        <div class="tab-pane" id="group">
            
                </br>
               {!! Form::open(['url'=>'/talentregisterGroup']) !!}
                  <!-- <form method="POST"  class="form-horizontal" role="form"> -->
                        
                           <div class="form-group">
                       
                        <div class="col-sm-10">
                        {!! Form::text('groupname',null,['class'=>'form-control', 'placeholder'=>'Enter your groupname', 'required'=>'required']) !!}
                          <!-- <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Your Last Name"> -->
                              

                                                  @foreach($errors->get('groupname') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>                              
                      </div>
                         <div class="form-group">
                       
                        <div class="col-sm-10">
                        {!! Form::text('founded', '', array('id' => 'datepicker2','class' => 'form-control', 'placeholder' => 'Date founded', 'required'=>'required')) !!}                        
                                                  

                                                  @foreach($errors->get('founded') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>                     
                      </div>
                      <div class="form-group">
                       
                        <div class="col-sm-10">
                        <div class="input-group">
                        <div class="input-group-addon">+63</div>
                        {!! Form::number('contactgroup', '', array('class' => 'form-control','style'=>'z-index:0', 'placeholder' => 'Group Contact Number', 'required'=>'required')) !!}
                        </div>
                          <!-- <input type="text" class="form-control" name="contactno" id="contactno" placeholder="Enter email"> -->
                                                

                                                  @foreach($errors->get('contactgroup') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>         
                      <div class="form-group">
                        
                        <div class="col-sm-10"> 
                        <!-- {!! Form::email('email', '', array('class' => 'form-control', 'placeholder' => 'Your Email Address')) !!} -->
                        {!! Form::email('emailaddressg', '', ['class' => 'form-control', 'placeholder' => 'Group Email Address', 'required'=>'required']); !!}
                            <!-- <input type="input" class="form-control" name="email" id="email" placeholder="Enter Email address"> -->
                                                

                                                  @foreach($errors->get('emailaddressg') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>                 
                      <div class="form-group">
                        
                        <div class="col-sm-10"> 
                        {!! Form::text('user_name', '', array('class' => 'form-control', 'placeholder' => 'Your username', 'required'=>'required')) !!}
                          <!-- <input type="input" class="form-control" name="username" id="username" placeholder="Enter username"> -->
                                                

                                                  @foreach($errors->get('user_name') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>               
                      <div class="form-group">
                        
                        <div class="col-sm-10"> 
                        <!-- {!! Form::password('password', '', array('class' => 'form-control', 'placeholder' => 'Enter your password')) !!} -->
                        {!! Form::password('passwordg', ['class' => 'form-control', 'placeholder' => 'Enter your password', 'required'=>'required']) !!}
                          <!-- <input type="password" class="form-control" name="password" id="pwd" placeholder="Enter password"> -->
                                                

                                                  @foreach($errors->get('password') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>              
                      <div class="form-group"> 
                        <div class="col-sm-10">
                          <div class="checkbox">
                            <label><input type="checkbox">Yes, I understand and agree to the <a href="">Talent Scout Terms of Service</a></label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group"> 
                        <div class=" col-sm-10">
                          {!! Form::button('Create Account',['class'=>'btn btn-success btn-lg btn-block', 'type' => 'submit ']) !!}
                          <!-- <button type="submit" class="btn btn-default">Create Account</button> -->
                        </div>
                      </div>
                    {!! Form::close() !!}
                    Already have an account? <a href="{{ URL::to('/login') }}">Log In</a>
                </br>
        </div>
     
    </div>
</div>


<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#tabs').tab();
    });
</script> 
</div>




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
    <script src="../../js/jquery.easing.min.js"></script>
    <script src="../../vendor/scrollreveal/scrollreveal.min.js"></script>
    <script src="../../vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <!-- Theme JavaScript -->
    <script src="../../js/creative.min.js"></script>
    <script src="../../js/carousel.js"></script>


    </script>
<script src="../../js/jquery-1.10.2.js"></script>
  <script src="../../js/jquery-ui.js"></script>
  <script>
  $(function() {
    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true,
      yearRange: '1960:2001'  });
    $( "#datepicker2" ).datepicker({ dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true,
      yearRange: '1960:2016'  });
  });
  </script>
</body>

</html>
