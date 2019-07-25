/**
 * Jednoduchy over sa da do suborov, kde nie je treba po overeni aktualizovat udaje.
 */

$(document).ready(function(){
    jednoduchyOver();
});

function jednoduchyOver(){
    $.ajax({
        url:"servlets/overServlet.php",
        type:"get",
        success: function(data){
            console.log("jednoduchyOver");
        }
    });
}