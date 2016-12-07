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

{!! HTML::style('vendor/bootstrap/css/bootstrap.min.css') !!}
    <!-- Custom Fonts -->
    
{!! HTML::style('vendor/font-awesome/css/font-awesome.min.css') !!}

{!! HTML::style('vendor/magnific-popup/magnific-popup.css') !!}
{!! HTML::style('css/creative.min.css') !!}
{!! HTML::style('css/creative.css') !!}

 <link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>

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
                
                <a class="navbar-brand page-scroll" href="{!! URL::to('/') !!}"><img src="../img/logo.png" class="logo"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    <section>
    <div class="row">
    @if (Session::has('message'))
        <div class="alert alert-info col-xs-12 text-center">{{ Session::get('message') }}</div>
        @endif
                <div class="col-sm-offset-1 col-sm-10">
                    {!! Form::open(['url'=>'/login']) !!}
                      <div class="form-group">
                        <!-- {{--{!! Form::input('number','userID',null,['class'=>'form-control','placeholder'=>'ID NUMBER']) !!}--}} -->
                        {!! Form::text('username',null,['class'=>'form-control','placeholder'=>'Enter your Username', 'required'=>'required']) !!}
                      </div>
                      <div class="form-group">
                        @foreach($errors->get('email') as $message)
                            {{ $message }}
                        @endforeach
                      </div>
                      <div class="form-group">
                        {!! Form::password('password',['class'=>'form-control','placeholder'=>'Password', 'required'=>'required']) !!}
                      </div>
                      <div class="form-group">
                         @foreach($errors->get('password') as $message)
                          {{ $message }}
                        @endforeach
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                            {!! Form::button('Login',['class'=>'btn btn-success btn-lg btn-block', 'type' => 'submit ']) !!}
                        </div>
                        <div class="col-sm-6">
                            <a class="pull-right" href="{!! URL::to('/forgotpassword') !!}">Forgot Password?</a>
                            Don't have an account? <a href="{{ URL::to('/#services') }}">Register</a>
                        </div>
                      </div>
                    {!! Form::close() !!}
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

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
   
 {!! HTML::script('vendor/jquery/jquery.min.js') !!}
 {!! HTML::script('vendor/bootstrap/js/bootstrap.min.js') !!}
 {!! HTML::script('vendor/scrollreveal/scrollreveal.min.js') !!}
 {!! HTML::script('vendor/magnific-popup/jquery.magnific-popup.min.js') !!}
 {!! HTML::script('js/creative.min.js') !!}
 {!! HTML::script('js/carousel.js') !!}
</body>

</html>
