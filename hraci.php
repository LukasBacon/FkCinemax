<?php
session_start();
include('funkcie.php');
include('printers/hraciPrinter.php');
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
        <li class="breadcrumb-item active">
            <?php echo $skupina["nazov"]  ?>
        </li>
    </ol>


    <?php

    if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
        vypis_pridaj_noveho($skupina);
        vypis_hracov_admin($skupina["kod"]);
    }
    else{
        vypis_hracov($skupina["kod"]);
    }
    ?>

</div>
<!-- /.container -->

</div>
<!-- /.content -->


<?php paticka();?>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/jednoduchyOver.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/hraci.js"></script>

</body>

</html>
