<?php
session_start();
include('funkcie.php');
include('printers/tabulkyPrinter.php');
include('printers/zapasyPrinter.php');
hlavicka();
?>

    <!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Domov</a>
        </li>
        <li class="breadcrumb-item active">Zápasy</li>
        <li class="breadcrumb-item active" id="nazovSkupiny">Prípravka</li>
      </ol>

      <div class="row">
        <div class="col-lg-9">
          <?php vypisNazovLigy('Pripravka'); ?>
        </div>
        <div class="col-lg-3">
          <form class="form-inline">
            <div class="form-group">
              <label class="control-label" for="selectRokZapasy">Vyber rok:</label>
              <select class="form-control" id="selectRokZapasy">
                <?php vratPrisluchajuceRokyKSkupine('Pripravka'); ?>
              </select>
            </div>
          </form>
        </div>
      </div>
       <!-- /. row -->
      <br>
    <div id="zapasy">
      <?php vypisVsetkyZapasy('Pripravka');?>
    </div>


    </div>
    <!-- /.container -->

<?php paticka();?>

    <!-- Bootstrap corev JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/ajaxZapasyNENI.js"></script>

  </body>

</html>
