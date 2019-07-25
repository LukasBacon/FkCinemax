<?php
session_start();
include('funkcie.php');
include('printers/tabulkyPrinter.php');
include('printers/zapasyPrinter.php');
hlavicka();
//$dbLoader = new dbLoader;
//$dbLoader->overDatumyNasledujucichNZapasov(6, "Pripravka");
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
              <label class="control-label ml-2" for="selectRokZapasy">Vyber rok:&nbsp;</label>
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
    <a href="javascript:scrollUp();">
      <img src="fotky/arrow.png" id="scrollArrow" width="50">
    </a>
    </div>
    <!-- /.container -->

    </div>
    <!-- /.content -->

<?php paticka();?>

    <!-- Bootstrap corev JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/services/overService.js"></script>
    <script src="js/ajaxZapasy.js"></script>

  </body>

</html>
