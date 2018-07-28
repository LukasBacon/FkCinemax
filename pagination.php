<?php
include('funkcie.php');
session_start();
hlavicka();
?>
<div class="container">
	<div class="row">
		<div id="data-cont"></div>
		<div id="pagination-cont">
			<div>Text1</div>
			<div>Text2</div>
			<div>Text3</div>
			<div>Text4</div>
			<div>Text5</div>
			<div>Text6</div>
		</div>
	</div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="js/pagination.js"></script>
<script type="text/javascript">

	$(document).ready(function(){

		function simpleTemplating(data) {
	    var html = '<ul>';
	    $.each(data, function(index, item){
	        html += '<li>'+ item +'</li>';
	    });
	    html += '</ul>';
	    return html;
		}

		$('#pagination-cont').pagination({
			items: 3,
	    callback: function(data, pagination) {
	      var html = simpleTemplating(data);
	      $('#data-cont').html(html);
	    }
		});
	});


</script>