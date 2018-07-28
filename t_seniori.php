<?php
session_start();
include('funkcie.php');
include('printers/tabulkyPrinter.php');
hlavicka();
?>
    <!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.html">Domov</a>
        </li>
        <li class="breadcrumb-item active">Tabuľka</li>
        <li class="breadcrumb-item active" id="nazovSkupiny">Seniori</li>
      </ol>

      <div class="row">
        <div class="col-lg-9">
          <?php vypisNazovLigy('Seniori'); ?>
        </div>
        <div class="col-lg-3">
          <form class="form-inline">
            <div class="form-group">
              <label class="control-label" for="selectRok">Vyber rok:</label>
              <select class="form-control" id="selectRok">
                <?php vratPrisluchajuceRokyKSkupine('Seniori'); ?>
              </select>
            </div>
          </form>
        </div>
      </div>
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
              <tbody id="tabulkaBody">
                <?php vypisTabulku("Seniori"); ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /. row -->

    </div>
    <!-- /.container -->


<?php paticka();?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/ajaxTabulky.js"></script>
  </body>

</html>
