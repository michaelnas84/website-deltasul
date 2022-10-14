var vistos_por_ultimo = JSON.parse(localStorage.getItem('vistos_por_ultimo'));
$(document).ready(function () {
    createCookie("vistos_por_ultimo", vistos_por_ultimo, "10");
});
function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + new Date(2147483647 * 1000).toUTCString();
    }
    else {
        expires = "";
    }
    document.cookie = escape(name) + "=" +
        escape(value) + expires + "; path=/";
}

$(window).on('load', function () {
    $('#preloader .inner').fadeOut();
    $('#preloader').delay(350).fadeOut('slow');
    $('body').delay(350).css({ 'overflow': 'visible' });
})

function list_pesquisa() {
    var value_pesq = document.getElementById("txtBusca").value;
    let form_ajax = 'txtBusca=' + value_pesq + '';
    $.ajax({
        url: "includes/api_result_pesq.php",
        method: "post",
        data: form_ajax,
        dataType: "html",
        success: function (result) {
            $('#result_busca').html(result)
        },
    })
}