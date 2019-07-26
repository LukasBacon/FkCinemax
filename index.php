<?php
include('funkcie.php');
include('printers/zapasyPrinter.php');
session_start();
hlavicka();
//$dbLoader = new dbLoader;
//$dbLoader->overDatumyNasledujucichNZapasov(2, "Seniori");
?>
    <!-- hlavicka - pohyblive obrazky -->
    <header>
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
          <div class="carousel-item active" style="background-image: url('fotky/main/main1.jpg')">
            <div class="carousel-caption d-none d-md-block"></div>
          </div>
          <div class="carousel-item" style="background-image: url('fotky/main/main4.jpg')">
            <div class="carousel-caption d-none d-md-block"></div>
          </div>
          <div class="carousel-item" style="background-image: url('fotky/main/main2.jpg')">
            <div class="carousel-caption d-none d-md-block"></div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </header>

    <!-- Page Content -->
    <div class="container">
      <?php
      if (isset($_POST["submit"])){
        vytvorAktualitu($_POST["nadpis"], $_POST["text"]);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        echo "<script>window.location.replace('". $url ."');</script>";
      }
      ?>

      <br>
      <!-- row -->
      <div class="row">
        <!-- Zapasy -->
        <div class="col-lg-5">
          <div class="card">
            <h5 class="card-header-match">Posledné zápasy</h5>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <?php vypisPosledneZapasy(array('Seniori')); ?>
                <li class="list-group-item">
                  <a class="btn btn-primary" href="z_pripravka.php">Zápasy prípravky</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="card">
            <h5 class="card-header-match">Nasledujúce zápasy</h5>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <?php vypisNasledujuceZapasy(array('Seniori')); ?>
                <li class="list-group-item">
                  <a class="btn btn-primary" href="z_pripravka.php">Zápasy prípravky</a>
                </li>
              </ul>
            </div>
          </div>
          <div>
            <strong style="font-size: 20px;">Nájdete nás aj na </strong>
            <a href="http://tj-dolany.futbalnet.sk/tim/26389">
              <img style="padding-top: 1px; margin-left: 5px;"  src="fotky/futbalnet.png" height="30px">
            </a>
          </div>
          <br>
        </div>
        <!-- /.Zapasy -->

        <!-- Aktuality -->
        <div class="col-lg-7" id="aktuality-section">
          <!-- admin - pridaj aktualitu a vypis-->
          <?php
          if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1 ){ ?>
            <div class="card">
              <h5 class="card-header-admin">Pridaj aktualitu</h5>
              <div class="card-body">
                <form method="post" id="pridajAktualituForm">
                  <div class="control-group form-group">
                    <div class="controls">
                      <label>Nadpis</label>
                      <input type="text" class="form-control" id="pridaj-nadpis" name="nadpis" required data-validation-required-message="Zadaj nadpis aktuality.">
                      <p class="help-block"></p>
                    </div>
                  </div>
                  <div class="control-group form-group">
                    <div class="controls">
                      <label>Text:</label>
                      <textarea rows="4" cols="100" class="form-control" id="pridaj-text" name="text" required data-validation-required-message="Zadaj text" maxlength="999" style="resize:none"></textarea>
                    </div>
                  </div>
                  <div id="info-div"></div>
                  <!-- For success/fail messages -->
                  <button type="submit" name="submit" class="whithHover" id="pridaj-button-akt">
                    <img class="withHover" width="40" src="fotky/add.png">
                  </button>
                </form>
              </div>
            </div>
            <?php
          }
          ?>
          <!-- aktuality -->
          <div class="aktualityPage">
          </div>

          <!-- pagination navigation -->
          <ul class="pagination justify-content-center">
          </ul>
        <!-- /.Aktuality -->
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container -->
    </div>
    <!-- /.content -->
    <?php paticka();?>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="js/aktuality.js"></script>
    <script type="text/javascript" src="js/services/overService.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <script type="text/javascript" src="js/responsive-paginate.js"></script>
    <script type="text/javascript">
      $('#carouselExampleIndicators').carousel({
          interval: 5000
      });
    </script>
  </body>

</html>
