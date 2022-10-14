function list_pesquisa() {
    var value_pesq = document.getElementById("txtBusca").value;
    let form_ajax = 'txtBusca=' + value_pesq + '';
    $.ajax({
        url: "includes/api_result_pesq_prod.php",
        method: "post",
        data: form_ajax,
        dataType: "html",
        success: function (result) {
            $('#result_busca').html(result)
        },
    })
    var opts = document.getElementById('result_busca').childNodes;
    for (var i = 0; i < opts.length; i++) {
        console.log(opts[i])
        if (opts[i].value === value_pesq) {
            var result_new = '<div class="sm:col-span-2" id="' + opts[i].value + '"><label class="block text-sm font-medium text-gray-700">' + opts[i].innerHTML + '</label><div class="mt-1"><input type="text" name="' + opts[i].value + '" id="' + opts[i].value + '" class="py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div>';
            $('#box_itens').append(result_new)
            break;
        }
    }
}


function pesq_cod() {
    document.getElementById('referencia').value = document.getElementById('registro').value;
    var value_cod = document.getElementById('registro').value;
    var prod_cod = 'prod_cod=' + value_cod;
    var acao = 'acao=pesq_cod';
    let dados = acao + '&' + prod_cod;
    $.ajax({
        url: 'includes/central_controller.php',
        type: 'POST',
        data: dados,

        method: 'POST',

        dataType: 'html',
        scriptCharset: "UTF-8",

        success: function (retorno) {
            if (retorno == 'no_result') {
                $("#notification").click()
                $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                $("#notification_txt_1").html("Referência não encontrada")
                $("#notification_txt_2").html("Tente novamente")
            } else {
                nome_marca = JSON.parse(retorno)
                document.getElementById('footer').style.display = 'block';
                document.getElementById('nome_item').value = nome_marca['item'][0];
                document.getElementById('marca_item').value = nome_marca['item'][1];
                document.getElementById('produto').value = nome_marca['item'][2];
                document.getElementById('nome_marca').style.display = 'contents';
                document.getElementById('box_itens').style.display = 'contents';
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
                $("#notification_txt_1").html("Item confirmado com sucesso!")
                $("#notification_txt_2").html("A página será recarregada")
                setTimeout(() => window.location.reload(), 2000)
            }
        }
    })
}