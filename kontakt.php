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
					<a href="index.php">Domov</a>
				</li>
				<li class="breadcrumb-item active">Kontakt</li>
			</ol>

			<!-- Content Row -->
			<div class="row">

				<!-- Contact Details Column -->
				<div class="col-lg-4 mb-4">
					<p>
						&#127968; Futbalový Klub CINEMAX Doľany<br> &nbsp&nbsp&nbsp&nbsp&nbsp Doľany 193<br>&nbsp&nbsp&nbsp&nbsp&nbsp 90088 Doľany<br>&nbsp&nbsp&nbsp&nbsp&nbsp Slovensko<br>
						&#9993; <a href="mailto:fkcinemax@gmail.com">fkcinemax@gmail.com</a><br><br>
						<strong>IČO: </strong> 17641411 <br>
						<strong>Banka:</strong> Slovenská sporiteľňa, a.s.<br>
						<strong>IBAN:</strong> SK28 0900 0000 0000 1919 4247
					</p>
					<p>
						<strong>Predseda:</strong> Ing. Roman Mikovič <br>
						&#9990; <a href="tel:0903 437 282">0903 437 282</a><br>
						<strong>Tajomník, ISS manažér:</strong> Peter Tichý <br>
						&#9990; <a href="tel:0902 740 595">0902 740 595</a> <br>
						<strong>Pokladník: </strong> Roman Schwarz <br>
						&#9990; <a href="tel:0905 203 985">0905 203 985</a> <br>
						<strong>Šéf tréner: </strong> František Bednárovský <br>
						&#9990; <a href="tel:0905 840 180">0905 840 180</a> <br>
						<strong> Manažér: </strong> Rudolf Vrbinkovič<br>
						&#9990; <a href="tel:0907 266 400<">0907 266 400</a> <br>
						<strong>Členovia výboru: </strong> <br>Matúš Ješko, Matej Vandák, Jozef Tomašovič, Pavol Ješko, Jozef Schmidt, Marek Vizváry
					</p>

				</div>
				 <div class="col-lg-8 mb-4">
				 	<div id="map" style="width:99%; height: 550px; margin: 0px 0px 20px 0px;">	</div>
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
		
		<!-- Map Column -->
		<script>
			function myMap() {
			var myCenter = new google.maps.LatLng(48.417347, 17.384569);
			var mapProp = {center:myCenter, zoom:18, scrollwheel:false, draggable:false, mapTypeId: 'satellite'};
			var map = new google.maps.Map(document.getElementById("map"),mapProp);
			var marker = new google.maps.Marker({position:myCenter});
			marker.setMap(map);
		}
		</script>

		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHOVnzvYBQnLcghJiZ_LvRgYqc9Zy3JgU&callback=myMap"></script>

	</body>

</html>
