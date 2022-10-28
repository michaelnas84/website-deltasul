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

function list_pesquisa() {
    $.ajax({
        url: "includes/api_result_pesq_prod.php",
        method: "post",
        data: `txtBusca=${$('#txtBusca').val()}`,
        dataType: "html",
        success: function (result) {
            $('#result_busca').html(result)
        },
    })
    var opts = document.getElementById('result_busca').childNodes;
    for (var i = 0; i < opts.length; i++) {
        console.log(opts[i])
        if (opts[i].value === value_pesq) {
            var result_new = `
            <div class="sm:col-span-2" id="${opts[i].value}">
            <label class="block text-sm font-medium text-gray-700">${opts[i].innerHTML}</label>
            <div class="mt-1">
                <input type="text" name="${opts[i].value}" id="${opts[i].value}" class="py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000">
            </div>
            </div>`
            $('#box_itens').append(result_new)
            break;
        }
    }
}


function pesq_cod() {
    $('#referencia').val($('#registro').val())
    $.ajax({
        url: 'includes/central_controller.php',
        type: 'POST',
        data: `acao=pesq_cod&prod_cod=${$('#registro').val()}`,
        method: 'POST',
        dataType: 'html',
        scriptCharset: "UTF-8",
        success: function (retorno) {
            if (retorno == 'no_result') {
                exibe_notificacao('red', 'Referência não encontrada', 'Tente novamente')
            } else {
                nome_marca = JSON.parse(retorno)
                $("#nome_item").val(nome_marca['item'][0])
                $("#marca_item").val(nome_marca['item'][1])
                $("#produto").val(nome_marca['item'][2])

                $('#footer').css('display', 'block')
                $('#nome_marca').css('display', 'contents')
                $('#box_itens').css('display', 'contents')
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
                exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
                setTimeout(() => window.location.reload(), 2000)
            } else if (retorno == 'success') {
                exibe_notificacao('green', 'Item confirmado com sucesso!', 'A página será recarregada')
                setTimeout(() => window.location.reload(), 2000)
            }
        }
    })
}