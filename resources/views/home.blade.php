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
                
                <a class="navbar-brand page-scroll" href="#page-top"><img src="img/logo.png" class="logo"></a>
                <div class="col-sm-10 col-md-offset-5">
                    Already have an account? <a href="{{ URL::to('/login') }}">Log In</a>
                </div>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <div class="carouselcontainer">
       

                <!--  START OF carousel -->




                <div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="img/carousel_1.jpg" alt="Chania">
    </div>

    <div class="item">
      <img src="img/carousel_2.jpg" alt="Chania">
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

    <section class="bg-primary" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    
                    <h3 class="section-heading">Tell us what you want.</h3>
                    <a href="#services" class="page-scroll btn btn-default btn-xl sr-button">Let's Get Started!</a>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="login">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">At Your Service</h2>
                    <hr class="primary">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 text-center">
                    <div class="service-box">
                        <i class="fa fa-4x fa-search text-primary sr-icons"></i>
                        <h3>Talent Scout</h3>
                        <p>I want to discover talents.</p></br>
                        <a href="/scoutregister" class="btn btn-default btn-xl sr-button">Hire</a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 text-center">
                    <div class="service-box">
                        <i class="fa fa-4x fa-microphone text-primary sr-icons"></i>
                        <h3>Talent</h3>
                        <p>I want people to discover my talents.</p></br>
                        <a href="talentregister" class="btn btn-default btn-xl sr-button">Get Discovered</a>
                    </div>
                </div>
               
                
            </div>
        </div>
    </section>


    <section class="bg-light">
        <div class="container text-center">
            <div class="call-to-action">
                <h2>Work with someone perfect for your needs.</h2>
                
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                
                <div class="col-lg-2 text-center">
                    <img src="img/1.png" class="icon">
                    <p><a href="">Drums</a></p>
                </div>
                <div class="col-lg-2 text-center">
                    <img src="img/2.png" class="icon">
                    <p><a href="">Dancing</a></p>
                </div>
                   <div class="col-lg-2  text-center">
                    <img src="img/3.png" class="icon">
                    <p><a href="">Art</a></p>
                </div>
                <div class="col-lg-2 text-center">
                    <img src="img/4.png" class="icon">
                    <p><a href="">Vocals</a></p>
                </div>
                   <div class="col-lg-2 text-center">
                   <img src="img/5.png" class="icon">
                    <p><a href="">Acoustic</a></p>
                </div>
                <div class="col-lg-2 text-center">
                    <img src="img/6.png" class="icon">
                    <p><a href="">Entertainment</a></p>
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
