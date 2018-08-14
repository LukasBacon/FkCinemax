<?php
session_start();
include('funkcie.php');
hlavicka();

?> 
    <!-- Page Content -->
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Domov</a>
        </li>
        <li class="breadcrumb-item active">Administrátorské rozhranie</li>
      </ol>
<?php
if(isset($_POST['odhlas'])){
   unset($_SESSION['admin']);
}
if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1){?>
      <div class="row justify-content-center">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
           <form method="post">
             <button type="submit" class="btn btn-admin" name="odhlas">Odhlás</button>
          </form>
          </div>
        </div>
        <div class="col-lg-4"></div>
        </div>
<?php
}
else if(isset($_POST['heslo']) && $_POST['heslo'] == $heslo ){
  $_SESSION['admin'] = 1;
  echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
}
else{
?>      
      <div class="row justify-content-center">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
          <div class="card">
            <h5 class="card-header-admin">Heslo</h5>
            <form method="post">
              <div class="card-body">
                <input type="password" class="form-control" id="heslo" name="heslo">
                <?php if(isset($_POST['heslo'])) { echo '<strong>Zadali ste zlé heslo!</strong>';} ?>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-admin">Prihlás</button>
              </div>
          </form>
          </div>
        </div>
        <div class="col-lg-4"></div>
        </div>
      </div>
    </div>

<?php } paticka();?>
    
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.js"></script>

  </body>

</html>

