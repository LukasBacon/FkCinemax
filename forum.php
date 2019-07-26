<?php
session_start();
include('funkcie.php');
hlavicka();
?>
    <!-- Page Content -->
    <div class="container">

      <?php
      if (isset($_POST["submit"])){
        vytvorDiskusiu($_POST["meno"], $_POST["nazov"], $_POST["komentar"]);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        echo "<script>window.location.replace('". $url ."');</script>";
        // je to tu lebo to treba obnovit 
      }
      ?>

      <!-- Page Heading/Breadcrumbs -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Domov</a>
        </li>
        <li class="breadcrumb-item active">Fórum</li>
      </ol>

      <div class="row">
          <div class="col-lg-4">
            <!-- Nova diskusia -->
            <div class="card my-4">
              <strong class="card-header-match" style="font-size: 1.25rem; color:black;">Vytvor novú diskusiu:</strong>
              <div class="card-body">
                <form method="post">
                  <div class="control-group form-group">
                    <div class="controls">
                      <label>Meno:</label>
                      <input type="text" class="form-control" id="name" name="meno" required data-validation-required-message="Musíte vyplniť meno.">
                      <p class="help-block"></p>
                    </div>
                  </div>
                  <div class="control-group form-group">
                    <div class="controls">
                      <label>Názov diskusie:</label>
                      <input type="text" class="form-control" id="nazov" name="nazov" required data-validation-required-message="Musíte vyplniť názov diskusie.">
                      <p class="help-block"></p>
                    </div>
                  </div>
                  <div class="control-group form-group">
                    <div class="controls">
                      <label>Komentár:</label>
                      <textarea rows="10" cols="100" class="form-control" id="message" name="komentar" required data-validation-required-message="Musíte vylniť správu." maxlength="999" style="resize:none"></textarea>
                    </div>
                  </div>
                  <div id="success"></div>
                  <!-- For success/fail messages -->
                  <input type="submit" class="btn btn-primary" id="sendMessageButton" name="submit" value="Vytvor">
                </form>
              </div>
            </div>
          </div>

          <!-- Diskusie -->
          <div class="col-lg-8 diskusieSection">

            <div class="diskusiePage">
            </div>

            <!-- pagination navigation -->
            <ul class="pagination justify-content-center">
            </ul>
          </div>



        </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->


    </div>
    <!-- /.content -->


<?php paticka();?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/jednoduchyOver.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/forum.js"></script>
    <script type="text/javascript" src="js/responsive-paginate.js"></script>

  </body>

</html>
