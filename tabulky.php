<?php
session_start();
include('funkcie.php');
include('printers/tabulkyPrinter.php');
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
        <li class="breadcrumb-item active">Tabuľka</li>
        <li class="breadcrumb-item active" id="nazovSkupiny">Seniori</li>
      </ol>

      <div class="row">
        <div class="col-lg-9">
            <h5 id='nazovLigy' class='float-left ml-2 mt-1 font-weight-bold' style='color: #6166b5;'></h5>
        </div>
        <div class="col-lg-2">
          <form class="form-inline">
            <div class="form-group">
              <label class="control-label ml-2" for="selectRok">Vyber rok:&nbsp;</label>
              <select class="form-control" id="selectRok">
                <?php vratPrisluchajuceRokyKSkupine($skupina["kod"]); ?>
              </select>
            </div>
          </form>
        </div>
      </div>
      <br>
       <!-- /. row -->

      <div class="row">
        <!-- Post Content Column -->
        <div class="col-lg-12">
          <div class="table-responsive">
            <table class="table" id="table">
              <thead>
                <tr>
                  <th>Poradie</th><th>Klub</th><th>Počet zápasov</th><th>Počet vyhratých</th><th>Počet remíz</th><th>Počet prehratých</th><th>Skóre</th><th>Body</th><th>FP</th>
                </tr>
              </thead>
              <tbody id="tabulkaBody"></tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /. row -->

    </div>
    <!-- /.container -->

    </div>
    <!-- /.content -->


<?php paticka();?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/services/overService.js"></script>
    <script src="js/tabulky.js"></script>
  </body>

</html>
