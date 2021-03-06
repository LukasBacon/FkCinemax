<?php
session_start();
include('funkcie.php');
include('printers/tabulkyPrinter.php');
include('printers/zapasyPrinter.php');
hlavicka();

if (isset($_GET['skupina'])) {
    $skupina = dajSkupinuPodlaKodu($_GET['skupina']);
} else {
    $skupina = dajDefaultnuSkupinu();
}

?>

    <!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Domov</a>
        </li>
        <li class="breadcrumb-item active">Zápasy</li>
        <li class="breadcrumb-item active" id="nazovSkupiny"><?php echo $skupina["nazov"] ?></li>
      </ol>

      <div class="row">
        <div class="col-lg-9">
            <h5 id='nazovLigy' class='float-left ml-2 mt-1 font-weight-bold' style='color: #6166b5;'></h5>
        </div>
        <div class="col-lg-3">
          <form class="form-inline">
            <div class="form-group">
              <label class="control-label ml-2" for="selectRokZapasy">Vyber rok:&nbsp;</label>
              <select class="form-control" id="selectRokZapasy">
                <?php vratPrisluchajuceRokyKSkupine($skupina["kod"]); ?>
              </select>
            </div>
          </form>
        </div>
      </div>
       <!-- /. row -->
      <br>
    <div id="zapasy"></div>
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
    <script type="text/javascript" src="js/services/overService.js"></script>
    <script type="text/javascript" src="js/overDatumyNasledujucichZapasov.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/zapasy.js"></script>

  </body>

</html>
