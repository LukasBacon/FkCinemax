<?php
session_start();
include('funkcie.php');
hlavicka();
?>
    <!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.html">Domov</a>
        </li>
        <li class="breadcrumb-item active">Hráči</li>
        <li class="breadcrumb-item active">Prípravka</li>
      </ol>

      <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
          vypis_hracov_admin("Pripravka");
        }
        else{
          vypis_hracov("Pripravka");
        }
      ?>

    </div>
    <!-- /.container -->


<?php paticka();?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>
