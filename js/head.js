$(window).on('load', function () {
    $('#preloader .inner').fadeOut()
    $('#preloader').delay(350).fadeOut('slow')
    $('body').delay(350).css({ 'overflow': 'visible' })
})

function list_pesquisa() {
    var value_pesq = document.getElementById("txtBusca").value
    let form_ajax = 'txtBusca=' + value_pesq + ''
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

document.onkeypress = function (event) {
    event = (event || window.event);
    if (event.keyCode == 123) {
        return false;
    }
}

document.onmousedown = function (event) {
    event = (event || window.event);
    if (event.keyCode == 123) {
        return false;
    }
}
document.onkeydown = function (event) {
    event = (event || window.event);
    if (event.keyCode == 123) {
        return false;
    }
}

jQuery(document).ready(function ($) {
    $(document).keydown(function (event) {
        var pressedKey = String.fromCharCode(event.keyCode).toLowerCase();
        if (event.ctrlKey && (pressedKey == "c" || pressedKey == "u" || pressedKey == "x" || pressedKey == "F12")) {
            return false;
        }
    });
});