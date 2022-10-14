$(".toggle").slideUp()
$(".trigger").click(function () {
    $(this).next(".toggle").slideToggle()
})

$('a[href*="#"]')
    .not('[href="#"]')
    .not('[href="#0"]')
    .click(function (event) {
        if (
            location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
            &&
            location.hostname == this.hostname
        ) {
            var target = $(this.hash)
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']')
            if (target.length) {
                event.preventDefault()
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 1000, function () {
                    var $target = $(target)
                    $target.focus()
                    if ($target.is(":focus")) {
                        return false
                    } else {
                        $target.attr('tabindex', '-1')
                        $target.focus()
                    }
                })
            }
        }
    })

function enviar_dados(acao) {
    $.ajax({
        url: 'admin/includes/central_controller.php',
        type: 'POST',
        data: $(".form").serialize(),
        method: 'POST',
        dataType: 'html',
        scriptCharset: "UTF-8",
        success: function (retorno) {
            document.getElementById('enviar').disabled = 'true'
            alert(retorno)
        }
    })
}