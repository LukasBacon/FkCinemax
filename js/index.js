/**
 * tam kde je importnuty tento subor, musi byt aj overService.js
 */

$(document).ready(function(){
    over(aktualizujPosledneANaslujuceZapasy);
});


function aktualizujPosledneANaslujuceZapasy() {
    $.ajax({
        url: "servlets/getPoslednyANasledujuciZapasServlet.php",
        type: "post",
        data: {"skupiny":["Seniori"]},
        success: function (data) {
            console.log(data);
            // TODO na indexe tieto zapasy aktualizovat
            //  na testovanie mozes pouzit vysledok zo servletu getPoslednyANasledujuciZapasServletTest.php
            // potom ako bude hotovo tak ten servlet zmz
        }
    });
}

