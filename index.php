<?php
include('funkcie.php');
include('printers/aktualityPrinter.php');
include('printers/zapasyPrinter.php');
include('dbLoader.php');
session_start();
hlavicka();
dbLoader::over();
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
          <div class="carousel-item active" style="background-image: url('fotky/main1.jpg')">
            <div class="carousel-caption d-none d-md-block"></div>
          </div>
          <div class="carousel-item" style="background-image: url('fotky/main4.jpg')">
            <div class="carousel-caption d-none d-md-block"></div>
          </div>
          <div class="carousel-item" style="background-image: url('fotky/main2.jpg')">
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
      <h1 class="my-4">Vitajte na stránke FK CINEMAX!</h1>

      <!-- row -->
      <div class="row">
        <!-- Zapasy -->
        <div class="col-lg-5">
          <div class="card">
            <h5 class="card-header-success">Posledný zápas</h5>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <?php vypisPosledneZapasy(array('Seniori', 'Pripravka')); ?>
              </ul>
            </div>
          </div>
          <div class="card">
            <h5 class="card-header-match">Nasledujúci zápas</h5>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <?php vypisNasledujuceZapasy(array('Seniori', 'Pripravka')); ?>
              </ul>
            </div>
          </div>
          <div>
            <strong style="font-size: 20px;">Nájdete nás aj na </strong>
            <a href="https://www.facebook.com/groups/100495186674652/?ref=bookmarks">
              <img src="fotky/facebook.jpg" height="30px">
            </a>
            <a href="http://tj-dolany.futbalnet.sk/tim/26389">
              <img style="padding-top: 1px; margin-left: 5px;"  src="fotky/futbalnet.png" height="30px">
            </a>
          </div>
        </div>
        <!-- /.Zapasy -->

        <!-- Aktuality -->
        <div class="col-lg-7">
          <!-- admin - pridaj aktualitu a vypis-->
          <?php 
          if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1 ){ ?>
            <div class="card">
              <h5 class="card-header-admin">Pridaj aktualitu</h5>
              <div class="card-body">
                <form novalidate method="post">
                  <div class="control-group form-group">
                    <div class="controls">
                      <label>Nadpis</label>
                      <input type="text" class="form-control" id="nadpis" required data-validation-required-message="Zadaj nadpis aktuality.">
                      <p class="help-block"></p>
                    </div>
                  </div>
                  <div class="control-group form-group">
                    <div class="controls">
                      <label>Text:</label>
                      <textarea rows="4" cols="100" class="form-control" id="text" required data-validation-required-message="Zadaj text" maxlength="999" style="resize:none"></textarea>
                    </div>
                  </div>
                  <div id="success"></div>
                  <!-- For success/fail messages -->
                    <button type="submit" class="btn btn-admin">Pridaj</button>
                </form>
              </div>

            </div>
            <?php vypis_aktuality_admin(); 
          }
          //pouzivatel - vypis aktualit
          else{
            vypis_aktuality();
            
          } 
          //aktualityPagination();
          ?>
          <div id="pagination-container"></div>
        </div>
        <!-- /.Aktuality -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container -->
<?php paticka();?>>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.js"></script>
  </body>

</html>
