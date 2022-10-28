function exibe_notificacao(svg, txt_1, txt_2){
    if(svg == 'red'){
        svg = '<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    } else {
        svg = '<svg class="h-6 w-6 text-green-400" x-description="Heroicon name: outline/check-circle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    }
    $("#notification").click()
    $("#notification_svg").html(svg)
    $("#notification_txt_1").html(txt_1)
    $("#notification_txt_2").html(txt_2)
}

function exibe_modal(a) {
    $('#title-modal').text(a.id)
    $('#img-modal').attr("src", a.src)
}

function excluir(reg) {
    $.ajax({
        data: { registro: reg, acao: 'slider_excluir' },
        type: "POST",
        url: 'includes/central_controller.php',
        method: 'POST',
        success: function (retorno) {
            if (retorno == 'error_user') {
                exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
                setTimeout(() => window.location.reload(), 2000)
            } else if (retorno == 'success') {
                exibe_notificacao('red', 'Slider excluído com sucesso!', 'A página será recarregada')
                setTimeout(() => window.location.reload(), 2000)
            }
        }
    })
}


function confirmar(reg) {
    $.ajax({
        data: { registro: reg, acao: 'slider_confirmar' },
        type: "POST",
        url: 'includes/central_controller.php',
        method: 'POST',
        success: function (retorno) {
            if (retorno == 'error_user') {
                exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
                setTimeout(() => window.location.reload(), 2000)
            } else if (retorno == 'success') {
                exibe_notificacao('green', 'Slider confirmado com sucesso!', 'A página será recarregada')
                setTimeout(() => window.location.reload(), 2000)
            }
        }
    })
}