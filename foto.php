<?php
session_start();
include('funkcie.php');
hlavicka();
?>

    <!-- Page Content -->
    <div class="container">

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Domov</a>
        </li>
        <li class="breadcrumb-item active">Fotogaléria</li>
      </ol>
       <div class="row justify-content-center">
          <div class="col-lg-12">
              <div class="row" style="height: 200px;">
                  <a href="fotky/f1.jpg" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-3" style=" position: relative; " >
                      <img src="fotky/f1.jpg" class="img-fluid" style="position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%); ">
                  </a>
                  <a href="fotky/f2.jpg" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-3" style=" position: relative;">
                      <img src="fotky/f2.jpg" class="img-fluid" style="position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%);" >
                  </a>
                  <a href="fotky/f3.jpg" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-3" style=" position: relative; ">
                      <img src="fotky/f3.jpg" class="img-fluid"  style="position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%); ">
                  </a>
                  <a href="fotky/f4.jpg" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-3" style="position: relative; ">
                      <img src="fotky/f4.jpg" class="img-fluid" style="position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%); ">
                  </a>
              </div>
              <div class="row" style="height: 200px;">
                  <a href="fotky/f5.jpg" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-3" style="position: relative;">
                      <img src="fotky/f5.jpg" class="img-fluid" style="position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%);" >
                  </a>
                  <a href="fotky/f6.jpg" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-3" style="position: relative;">
                      <img src="fotky/f6.jpg" class="img-fluid" style="position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%);">
                  </a>
                  <a href="fotky/f7.jpg" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-3" style="position: relative; ">
                      <img src="fotky/f7.jpg" class="img-fluid" style="position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%);" >
                  </a>
                  <a href="fotky/f8.jpg" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-3" style="position: relative; ">
                      <img src="fotky/f8.jpg" class="img-fluid" style="position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%); ">
                  </a>
              </div>
              <div class="row" style="height: 200px;"> 
                  <a href="https://www.youtube.com/watch?time_continue=2&v=3gt1Dc7lcPA" data-toggle="lightbox" data-gallery="youtubevideos" class="col-sm-3" style=" position: relative;">
                    <img src="fotky/video.jpg" class="img-fluid" style="position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%);">
                  </a>
              </div>
          </div>
      </div>
    </div>

<?php paticka();?>
      <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js.map"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js.map"></script>
    <script type="text/javascript">   
      $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
      });
    </script>


  </body>

</html>



