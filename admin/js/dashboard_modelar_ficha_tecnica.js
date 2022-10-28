function exibe_notificacao(svg, txt_1, txt_2) {
    if (svg == 'red') {
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
        data: $(".form").serialize(),

        method: 'POST',

        dataType: 'html',
        scriptCharset: "UTF-8",

        success: function (retorno) {
            if (retorno == 'error_user') {
                exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
                setTimeout(() => window.location.reload(), 2000)
            } else if (retorno == 'success') {
                exibe_notificacao('green', 'Item confirmado com sucesso!', 'A página será recarregada')
                setTimeout(() => window.location.reload(), 2000)
            }
        }
    })
}

function autocomplete_ficha_tecnica(a) {
    $.ajax({
        url: 'includes/central_controller.php',
        type: 'POST',
        data: `acao=cadastro_itens_ficha_view&item_cat_name=${a}`,
        method: 'POST',
        dataType: 'html',
        scriptCharset: "UTF-8",
        success: function (retorno) {
            var inputs = document.querySelectorAll('[name="itens_exibe_cadastro_ficha[]"]')
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

document.querySelector('#item_ficha_id').addEventListener('input', function (e) {
    var input = e.target,
        list = input.getAttribute('list'),
        options = document.querySelectorAll('#' + list + ' option[value="' + input.value + '"]'),
        hiddenInput = document.getElementById(input.getAttribute('id') + '_hidden')

    if (options.length > 0) {
        hiddenInput.value = input.value
        autocomplete_ficha_tecnica(input.value)
        input.value = options[0].innerText
    }

})

$(document).on('input', '#item_ficha_id', function () {
    $('#ck_item_ficha_name').prop("checked", false);

})

keepDatalistOptions('#item_ficha_id');

function keepDatalistOptions(selector = '') {
    selector = !selector ? "input[list]" : selector
    let datalistInputs = document.querySelectorAll(selector)
    if (datalistInputs.length) {
    for (let i = 0; i < datalistInputs.length; i++) {
        let input = datalistInputs[i]
        input.addEventListener("change", function (e) {
            e.target.setAttribute("value", e.target.value)
        })
        input.addEventListener("focus", function (e) {
            e.target.setAttribute("value", e.target.value)
            e.target.value = ""
        })
    }
    }
}