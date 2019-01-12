<?php
session_start();
include('funkcie.php');
include('printers/hraciPrinter.php');
hlavicka();
?>
    <!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Domov</a>
        </li>
        <li class="breadcrumb-item active">Hráči</li>
        <li class="breadcrumb-item active">Prípravka</li>
      </ol>

      <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
          vypis_pridaj_noveho("Pripravka");
          vypis_hracov_admin("Pripravka");
        }
        else{
          vypis_hracov("Pripravka");
        }
      ?>

    </div>
    <!-- /.container -->

    </div>
    <!-- /.content -->


<?php paticka();?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/hraci.js"></script>

  </body>

</html>
