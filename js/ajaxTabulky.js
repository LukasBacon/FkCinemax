$(document).ready(function(){
	$("#selectRok").change(function() {
		var table = $("#tabulkaBody");
		var rok = $("#selectRok").val();
		var skupina = $("#nazovSkupiny").text();
		if(skupina === 'Prípravka'){
			skupina = 'Pripravka';
		}
		console.log(table, rok, skupina);
		$.ajax({
			url:"servlets/tabulkaServlet.php",
			type:"post",
			data:{"rok":rok, "skupina":skupina},
			datatype: 'json',
			success: function(data){
				table.empty();
				loadTable(table, 
					['poradie','klub','p_zapasov','p_vyhier','p_remiz','p_prehier','skore','body','fp'], 
					data
				);
			}
		});

		$.ajax({
			url:"servlets/nazovLigyServlet.php",
			type:"post",
			data:{"rok":rok, "skupina":skupina},
			success: function(data){
				console.log(data);
				$("#nazovLigy").text(data);
			}
		});
	});

	function loadTable(table, fields, data) {
	    var rows = '';
	    $.each(JSON.parse(data), function(index, item) {
	    	var row;
	    	if(item['klub'].includes("FK CINEMAX Doľany")){
  				row =  "<tr class='table-warning'>";
			}
			else{
			  	row = "<tr>";
			}
	        $.each(fields, function(index, field) {
	            row += '<td>' + item[field+''] + '</td>';
	        });
	        rows += row + '</tr>';
	    });
	    table.html(rows);
	}
});