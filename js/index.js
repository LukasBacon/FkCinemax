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
            let lastMatches = $("#posledne-zapasy");
            let lastMatchesText = printMatches(data, "posledny");
            lastMatches.html(lastMatchesText);
            let nextMatches = $("#nasledujuce-zapasy");
            let nextMatchesText = printMatches(data, "nasledujuci");
            nextMatches.html(nextMatchesText)
        }
    });

    function printMatches(zapasyData, type){
        let text = '';
        let title = type === 'posledny' ? 'Posledné zápasy' : 'Nasledujúce zápasy';
        text += '<h5 class="card-header-match">' + title + '</h5>';
        text += '<div class="card-body">';
        text += '<ul class="list-group list-group-flush">';
        $.each(JSON.parse(zapasyData), function(index, skupinaZapasy){
            text += printMatchesForGroup(index, skupinaZapasy, type)
        });
        text += '<li class="list-group-item">';
        text += '<a class="btn btn-primary" href="z_pripravka.php">Zápasy prípravky</a>';
        text += '</li>';
        text += '</ul>';
        text += '</div>';
        return text;
    }

    function printMatchesForGroup(skupina, data, type){
        const info = data[type];
        console.log(info);
        const skoreD = info["skoreD"] === null ? '' : info["skoreD"];
        const skoreH = info["skoreH"] === null ? '' : info["skoreH"];
        const poznamka = info["poznamka"] === null ? '' : info["poznamka"];
        let text = '';
        text += '<li class="list-group-item">';
        text += '<p class="card-text"><strong>' + skupina + '</strong><br>';
        text += 'Kolo ' + info["kolo"] + ' - ' + vypisDatumACas(info["datum"]) + '<br>';
        text += info["domaci"] + ' '  + '<strong>' + skoreD + ':' + skoreH + '</strong>' + ' ' + info["hostia"] + '<br>';
        text += '<small>' + poznamka + '</small></p>';
        text += '</li>';
        return text;
    }

    function vypisDatumACas(datetime){
        const date = new Date(datetime);
        const year = date.getFullYear();
        const month = date.getMonth();
        const day = date.getDate();
        const hours = (date.getHours() < 10 ? '0' : '') + date.getHours();
        const minutes = (date.getMinutes() < 10 ?'0':'') + date.getMinutes();
        return day + '.' + month + '.' + year + ' ' + hours + ':' + minutes;
    }
}

