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

function envia_form() {
    $.ajax({
        url: 'includes/central_controller.php',
        type: 'POST',
        data: $("#form_cad").serialize(),

        method: 'POST',

        dataType: 'html',
        scriptCharset: "UTF-8",

        success: function (retorno) {
            if (retorno == 'error_user') {
                exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
                setTimeout(() => window.location.reload(), 2000)
            }
            if (retorno == 'success') {
                exibe_notificacao('green', 'Colaborador cadastrado com sucesso!', 'A página será recarregada')
                setTimeout(() => window.location.reload(), 2000)
            }
        }
    })
}


function exibe_modal_altera(reg, nome, apelido, ddd_cel, nro_celular, cod_emp) {
    $('#registro').val(reg)
    $('#nome').val(nome)
    $('#apelido').val(apelido)
    $('#ddd_cel').val(ddd_cel)
    $('#nro_celular').val(nro_celular)
    $("#cod_emp").val(cod_emp).change()
    
    $('#acao').val('colaboradores_alterar')
}

function excluir(reg) {
    $.ajax({
        data: { registro: reg, acao: 'colaboradores_excluir' },
        type: "POST",
        url: 'includes/central_controller.php',
        method: 'POST',
        success: function (retorno) {
            if (retorno == 'error_user') {
                exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
                setTimeout(() => window.location.reload(), 2000)
            } else if (retorno == 'success') {
                exibe_notificacao('red', 'Colaborador excluído com sucesso!', 'A página será recarregada')
                setTimeout(() => window.location.reload(), 2000)
            }
        }
    })
}


function confirmar(reg) {
    $.ajax({
        data: { registro: reg, acao: 'colaboradores_confirmar' },
        type: "POST",
        url: 'includes/central_controller.php',
        method: 'POST',
        success: function (retorno) {
            if (retorno == 'error_user') {
                exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
                setTimeout(() => window.location.reload(), 2000)
            } else if (retorno == 'success') {
                exibe_notificacao('green', 'Colaborador confirmado com sucesso!', 'A página será recarregada')
                setTimeout(() => window.location.reload(), 2000)
            }
        }
    })
}

$('#select_cidades').on('change', function () {
    window.location.href = `?loja=${this.value}`;
})