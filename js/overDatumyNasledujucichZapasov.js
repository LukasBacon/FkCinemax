/**
 * Nalinkuje sa na subory, v ktorych pri inicializacii zbehne overenie nasledujucich zapasov
 */
$(document).ready(function(){
    overDatumyNasledujucichZapasov();
});

function overDatumyNasledujucichZapasov(){
    $.ajax({
        url:"servlets/overDatumyNasledujucichZapasovServlet.php",
        type:"get",
        success: function(data){
            const response = JSON.parse(data);
            if (response.enableConsoleLog) {
                console.log(response);
            }
        }
    });
}