/**
 * Zavola over servlet a na uspesne overenie zavola vstupnu funckiu
 * @param functionOnSuccess
 */
function over(functionOnSuccess){
    $.ajax({
        url:"servlets/overServlet.php",
        type:"get",
        success: function(data){
            functionOnSuccess();
        }
    });
}