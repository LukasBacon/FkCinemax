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
        <li class="breadcrumb-item active">Fórum</li>
      </ol>

      <div class="row">
          <div class="col-lg-4">
            <!-- Nova diskusia -->
            <div class="card my-4">
              <h5 class="card-header-match">Vytvor novú diskusiu:</h5>
              <div class="card-body">
                <form novalidate>
                  <div class="control-group form-group">
                    <div class="controls">
                      <label>Meno:</label>
                      <input type="text" class="form-control" id="name" required data-validation-required-message="Musíte vyplniť meno.">
                      <p class="help-block"></p>
                    </div>
                  </div>
                  <div class="control-group form-group">
                    <div class="controls">
                      <label>Názov diskusie:</label>
                      <input type="text" class="form-control" id="nazov" required data-validation-required-message="Musíte vyplniť názov diskusie.">
                      <p class="help-block"></p>
                    </div>
                  </div>
                  <div class="control-group form-group">
                    <div class="controls">
                      <label>Komentár:</label>
                      <textarea rows="10" cols="100" class="form-control" id="message" required data-validation-required-message="Musíte vylniť správu." maxlength="999" style="resize:none"></textarea>
                    </div>
                  </div>
                  <div id="success"></div>
                  <!-- For success/fail messages -->
                  <button type="submit" class="btn btn-primary" id="sendMessageButton">Vytvor</button>
                </form>
              </div>
            </div>
          </div>

          <!-- Diskusie -->
          <div class="col-lg-8">
            <?php vypis_diskusie(); ?>
          </div>

        </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

<?php paticka();?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
      
      function rollDown(){
        $('#').hide();

      }
    </script>

  </body>

</html>
