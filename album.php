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
                <a href="index.html">Domov</a>
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
                    $nazovPriecinku = $_GET['nazovPriecinku'];
                    printPhotosOfAlbumWithId($id, $nazovPriecinku);
                }
            ?>
        </div>
    </div>


    <?php paticka();?>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="magnific-popup/jquery.magnific-popup.js"></script>
    <script type="text/javascript"> 
        $(document).ready(function() {

            $('.popupgallery').magnificPopup({ 
                type: 'image', 
                delegate: 'a', 
                closeOnContentClick: false, 
                closeOnBgClick: true,
                image: { 
                    verticalFit: true
                }, 
                removalDelay: 300,
                gallery: {
                    enabled: true,
                    preload: [0,2],
                    navigateByImgClick: true,
                    arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
                    tPrev: 'Previous (Left arrow key)',
                    tNext: 'Next (Right arrow key)', 
                    tCounter: '<span class="mfp-counter">%curr% of %total%</span>'
                }
            }); 
        });
    </script>

    </body>

</html>