<?php
session_start();
include('funkcie.php');
include('printers/fotoPrinter.php');
hlavicka();
?>
    <link rel="stylesheet" type="text/css" href="magnific-popup/magnific-popup.css"">
    <!-- Page Content -->
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.php">Domov</a>
            </li>
            <li class="breadcrumb-item">
                <a href="fotogaleria.php">Fotogaléria</a>
            </li>
            <li class="breadcrumb-item active">
                <?php
                    if (isset($_GET['nazov'])) {
                        $nazov = $_GET['nazov'];
                        echo $nazov;
                    }
                ?>
            </li>
        </ol>

        <div class="popupgallery">
            <?php
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    printPhotosOfAlbumWithId($id);
                }
            ?>
        </div>
    </div>


    <?php paticka();?>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="magnific-popup/jquery.magnific-popup.js"></script>
    <script src="js/fotky.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>


</html>