function todas_perm() {
    var inputs = document.querySelectorAll('[name="itens_permissao[]"]')
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].checked = true;
    }
}



function autocomplete_perm(a) {
    var data_array = 'acao=cadastro_permissoes_view&item_perfil_name=' + a.value;
    $.ajax({
        url: 'includes/central_controller.php',
        type: 'POST',
        data: data_array,
        method: 'POST',
        dataType: 'html',
        scriptCharset: "UTF-8",
        success: function (retorno) {
            var inputs = document.querySelectorAll('[name="itens_permissao[]"]')
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].checked = false;
            }
            if (retorno != 'null') {
                var retorno_itens = JSON.parse(retorno)
                for (let i = 0; i < inputs.length; i++) {
                    for (let x = 0; x < retorno_itens.length; x++) {
                        if (inputs[i].value == retorno_itens[x]) {
                            inputs[i].checked = true;
                        }
                    }
                }
            }
        }
    })
}


function envia_form() {
    $.ajax({
        url: 'includes/central_controller.php',
        type: 'POST',
        data: $(".form").serialize(),

        method: 'POST',

        dataType: 'html',
        scriptCharset: "UTF-8",

        success: function (retorno) {
            if (retorno == 'error_user') {
                $("#notification").click()
                $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                $("#notification_txt_1").html("Ocorreu um problema! (login_error)")
                $("#notification_txt_2").html("Entre em contato com o suporte")
                setTimeout(() => window.location.reload(), 2000)
            } else if (retorno == 'success') {
                $("#notification").click()
                $("#notification_svg").html('<svg class="h-6 w-6 text-green-400" x-description="Heroicon name: outline/check-circle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                $("#notification_txt_1").html("Permissões liberadas com sucesso!")
                $("#notification_txt_2").html("A página será recarregada")
                setTimeout(() => window.location.reload(), 2000)
            }
        }
    })
}