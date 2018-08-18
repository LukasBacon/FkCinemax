<?php
session_start();
include('funkcie.php');
include('printers/albumyPrinter.php');
hlavicka();
?>
    <!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Domov</a>
        </li>
        <li class="breadcrumb-item active">Fotogaléria</li>
      </ol>

      <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
          if(isset($_POST['novyAlbumBtn'])){
            if(!pridaj_album($_POST['nazov'])){
              echo '<p><strong style="color:red;">Album so zadaným názvom už existuje!</strong></p>';
            }
          }
          vypis_albumy_admin();
        }
        else{
          vypis_albumy();
        }
      ?>

    </div>
    <!-- /.container -->


<?php paticka();?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/albumy.js"></script>
  </body>

</html>
