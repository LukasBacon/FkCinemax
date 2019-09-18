/**
 * tam kde je importnuty tento subor, musi byt aj overService.js
 */

$(document).ready(function(){
    over(aktualizujPosledneANaslujuceZapasy);
});


function aktualizujPosledneANaslujuceZapasy() {
    $.ajax({
        url: "servlets/getSkupiny.php",
        type: "get",
        success: function (skupiny) {
            skupiny = JSON.parse(skupiny);
            skupinyCodes = skupiny.map(s => s["kod"]);
            $.ajax({
                url: "servlets/getPoslednyANasledujuciZapasServlet.php",
                type: "post",
                data: {"skupiny":skupinyCodes},
                success: function (data) {
                    data = JSON.parse(data);
                    let lastMatches = $("#posledne-zapasy");
                    let lastMatchesText = printMatches(data, "posledny", skupiny);
                    lastMatches.html(lastMatchesText);
                    let nextMatches = $("#nasledujuce-zapasy");
                    let nextMatchesText = printMatches(data, "nasledujuci", skupiny);
                    nextMatches.html(nextMatchesText)
                }
            });
        }
    });

    function printMatches(zapasyData, type, skupiny){
        let text = '';
        let title = type === 'posledny' ? 'Posledné zápasy' : 'Nasledujúce zápasy';
        text += '<h5 class="card-header-match">' + title + '</h5>';
        text += '<div class="card-body">';
        text += '<ul class="list-group list-group-flush index-zapasy-card">';
        for (var skupinaKod in zapasyData) {
            skupina = getSupinaWithCode(skupiny, skupinaKod);
            if (skupina["zobrazenie_nasl_a_predch_zapasov"] === 1) {
                text += printMatchesForGroup(skupina, zapasyData[skupina["kod"]], type)
            } else {
                text += '<li class="list-group-item">';
                text += '<a class="btn btn-primary" href="zapasy.php?skupina=' + skupinaKod + '">Zápasy ' + skupina["nazov_genitiv"] + '</a>';
                text += '</li>';
            }
        }
        text += '</ul>';
        text += '</div>';
        return text;
    }

    function printMatchesForGroup(skupina, data, type){
        const info = data[type];
        const poznamka = info["poznamka"] === null ? '' : info["poznamka"];
        let text = '';
        text += '<li class="list-group-item">';
        text += '<p class="card-text"><strong>' + skupina["nazov"] + '</strong><br>';
        text += 'Kolo ' + info["kolo"] + ' - ' + vypisDatumACas(info["datum"]) + '<br>';
        text += (info["domaci"] === "" || info["hostia"] === "") ? vypisVolno() : vypisZapasInfo(info);
        text += '</p></li>';
        return text;
    }

    function vypisZapasInfo(info) {
        let text = '';
        text += info["domaci"] + ' ';
        text += vypisSkore(info);
        text += ' ' + info["hostia"] + '<br>';
        text += '<small>' + poznamka + '</small>';
        return text;
    }

    function vypisVolno() {
        return 'VOĽNO';
    }

    function vypisSkore(data){
        const skoreD = data["skoreD"] === null ? '' : data["skoreD"];
        const skoreH = data["skoreH"] === null ? '' : data["skoreH"];
        let score = data["skoreD"] === null ? '' : '<strong>';
        score += skoreD;
        score += ':';
        score += skoreH;
        score += (data["skoreD"] === null ? '' : '</strong>');

        return score;
    }

    function vypisDatumACas(datetime){
        const date = new Date(datetime);
        const year = date.getFullYear();
        const month = date.getMonth() + 1;
        const day = date.getDate();
        const hours = date.getHours();
        const minutes = date.getMinutes();
        return setTwoDigits(day) + '.' + setTwoDigits(month) + '.' + year + ' ' + setTwoDigits(hours) + ':' + setTwoDigits(minutes);
    }

    function setTwoDigits(num){
        return ((num) < 10 ? '0' : '') + num;
    }

    function getSupinaWithCode(skupiny, code) {
        for (let skupina of skupiny) {
            if (skupina["kod"] === code) {
                return skupina;
            }
        }
        return null;
    }
}

