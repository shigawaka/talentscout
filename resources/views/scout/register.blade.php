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
<link rel="stylesheet" href="../../css/jquery-ui.css">
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
            <h3 class="red">Talent Scout Registration</h3>
            <h5>Register for free and experience the dashboard today</h5>
            <hr>

            <div class="registerbox">
                <div class="row">

                <div class="col-md-6 text-center col-md-offset-3">
                </br>
                  {!! Form::open(['url'=>'/scoutregister']) !!}
                        
                           <div class="form-group">
                       
                        <div class="col-sm-10">
                          {!! Form::text('firstname',null,['class'=>'form-control', 'placeholder'=>'Enter your first name']) !!}
                          <!-- <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Your Last Name"> -->
                              

                                                  @foreach($errors->get('firstname') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>
                      <div class="form-group">
                       
                        <div class="col-sm-10">
                          {!! Form::text('lastname',null,['class'=>'form-control', 'placeholder'=>'Enter your last name']) !!}
                          <!-- <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Your Last Name"> -->
                              

                                                  @foreach($errors->get('lastname') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>
                         <div class="form-group">
                       
                        <div class="col-sm-10">
                          {!! Form::text('address',null,['class'=>'form-control', 'placeholder'=>'Enter your address']) !!}
                          <!-- <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Your First Name"> -->
                            

                                                  @foreach($errors->get('address') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>
                         <div class="form-group">
                       
                        <div class="col-sm-10">
                          {!! Form::email('emailaddress', '', ['class' => 'form-control', 'placeholder' => 'Your Email Address']); !!}
                            <!-- <input type="input" class="form-control" name="email" id="email" placeholder="Enter Email address"> -->
                                                

                                                  @foreach($errors->get('emailaddress') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>
                      <div class="form-group">
                       
                        <div class="col-sm-10">
                          {!! Form::number('contact', '', array('class' => 'form-control', 'placeholder' => 'Your Contact Number')) !!}
                          <!-- <input type="text" class="form-control" name="contactno" id="contactno" placeholder="Enter email"> -->
                                                

                                                  @foreach($errors->get('contact') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>
                      <div class="form-group">
                       
                        <div class="col-sm-10">
                        {!! Form::text('birthday', '', array('id' => 'datepicker','class' => 'form-control', 'placeholder' => 'Your Date of Birth')) !!}                        
                                                  

                                                  @foreach($errors->get('birthday') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>                     
                      </div>
                       <div class="form-group">
                        
                        <div class="col-sm-10"> 
                          {!! Form::text('username', '', array('class' => 'form-control', 'placeholder' => 'Your username')) !!}
                          <!-- <input type="input" class="form-control" name="username" id="username" placeholder="Enter username"> -->
                                                

                                                  @foreach($errors->get('username') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>
                      <div class="form-group">
                        
                        <div class="col-sm-10"> 
                          {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter your password']) !!}
                          <!-- <input type="password" class="form-control" name="password" id="pwd" placeholder="Enter password"> -->
                                                

                                                  @foreach($errors->get('password') as $message)
                                                    {{ $message }}
                                                    @endforeach
                        </div>
                      </div>
                      <div class="form-group"> 
                        <div class="col-sm-10">
                          <div class="checkbox">
                            <label><input type="checkbox">Yes, I understand and agree to the Talent Scout Terms of Service</label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group"> 
                        <div class=" col-sm-10">
                          {!! Form::button('Create Account',['class'=>'btn btn-success btn-lg btn-block', 'type' => 'submit ']) !!}
                        </div>
                      </div>
                    {!! Form::close() !!}
                    Already have an account? <a href="{{ URL::to('/login') }}">Log In</a>
                </br>
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
    <script src="../../js/jquery-1.10.2.js"></script>
  <script src="../../js/jquery-ui.js"></script>
    <script>
  $(function() {
    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,
      changeYear: true,
      yearRange: '1960:1998'});
  });
  </script>
</body>

</html>
