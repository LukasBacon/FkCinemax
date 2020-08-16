$(document).ready(function(){
	var rok = $("#selectRok").val();
	var skupina = location.search.split('skupina=')[1] ? location.search.split('skupina=')[1] : 'Seniori';

	over(() => vypisTabulku(rok, skupina));

	refresh(rok, skupina);

	$("#selectRok").change(function() {
		refresh(rok, skupina);
	});

	function refresh(rok, skupina) {
		rok = $("#selectRok").val();
		vypisTabulku(rok, skupina);
	}

	function vypisTabulku(rok, skupina) {
		var table = $("#tabulkaBody");
		$.ajax({
			url:"servlets/tabulkaServlet.php",
			type:"post",
			data:{"rok":rok, "skupina":skupina},
			datatype: 'json',
			success: function(data){
				console.log(data);
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
				$("#nazovLigy").text(data);
			}
		});
	}

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