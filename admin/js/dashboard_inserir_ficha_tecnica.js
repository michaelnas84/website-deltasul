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

var url_string = window.location.href
var url = new URL(url_string)

if (url.searchParams.get("ref") !== null) {
    pesq_cod(url.searchParams.get("ref"))
    $("#registro").val(url.searchParams.get("ref"))
}

function pesq_cod(ref) {
    $("#box_itens, #box_itens_fotos, #button_box_footer").css("display","none")
    $.ajax({
        url: 'includes/central_controller.php',
        type: 'POST',
        data: `acao=pesq_cod&prod_cod=${ref}`,
        method: 'POST',
        dataType: 'html',
        scriptCharset: "UTF-8",

        success: function (retorno) {
            if (retorno == 'no_result') {
                exibe_notificacao('red', 'Sem itens ficha cadastrados para esta categoria/subcategoria!', 'A página será recarregada')
                setTimeout(() => window.location.reload(), 2000)
                return false
            }
            exibe_itens(retorno)
        }
    })
}

function exibe_itens(dados){
    var nome_marca = JSON.parse(dados)
            
    $('#form_itens_fotos').html('')
    $('#box_itens').html('')

    var result_html = `
    <input hidden name="acao" value="cadastro_fotos_ficha_tecnica">
    <input type="text" hidden name="referencia" id="referencia">
    <input type="text" hidden name="produto" id="produto" class="itens_json">
    <input type="text" hidden name="tipo_prod_int" id="tipo_prod_int">`
    $('#form_itens_fotos').append(result_html)


    $("#box_itens, #box_itens_fotos, #button_box_itens, #adiciona_item_no_site, #button_box_footer, #button_cadastrar_fotos").css("display","none")
    // document.getElementById('button_adicionar_box_itens').style.display = 'none'
    $("#referencia").val($("#registro").val())
    $("#nome_item").val(nome_marca['item'][0])
    $("#marca_item").val(nome_marca['item'][1])
    $("#produto").val(nome_marca['item'][2])

    $("#nome_marca, #box_itens").css("display","contents")
    $("#button_box_itens").css("display","inline-block")

    if(nome_marca['status_banco'] == 'N') {
        $("#adiciona_item_no_site").css("display","flex")
    }
    // document.getElementById('button_adicionar_box_itens').style.display = 'flex'
    var referencia = $("#registro").val()

    /* VERIFICA SE JÁ TEM ITENS CADASTRADOS */
    if (nome_marca['status'][0] == 'S') {
        $("#tipo_ficha").css("display","flex")
        $("#button_cadastrar_fotos").css("display","flex")
        $("#tipo_prod_int").val(nome_marca['status'][0])
        var descricao_web = `
        <div class="sm:col-span-8 focus:ring-blue-500 focus:border-blue-500" style="display: flex; flex-wrap: wrap; align-items: flex-end;">
        <div style="width: 80%">
            <label style="justify-content: space-between;" class="flex text-sm font-medium text-gray-700">NOME WEB <img src="img/remove.png" style="display: none; width: 16px; height: 16px;" id="img_nome_web">
            </label>
            <div class="mt-1">
            <input type="text" onKeyUp="$('#img_nome_web').css('display','block')" name="nome_web" id="nome_web" value="${nome_marca['item'][3]}" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000">
            </div>
        </div>
        <div style="width: 20%; display: flex; justify-content: center; align-items: center;">
            <a style="display: flex; cursor: pointer" class="cursor-pointer ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="nome_web" onclick="envia_form('salvar_item', this, '${nome_marca['item'][2]}', '${referencia}')">Salvar Item</a>
        </div>
        </div>`
        $('#box_itens').append(descricao_web)

            var xx
            for (xx = 0; xx <= 4; xx++) {
                if (nome_marca["imagens"]["url"][parseInt(xx) + parseInt('1')] != null) {
                    var result_html = `
                    <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Foto 0${(parseInt(xx) + parseInt('1'))}</label>
                    <div class="mt-1">
                        <input name="upload[]" onchange="readURL(this)" id="input_img_${(parseInt(xx) + parseInt('1'))}" type="file" accept="image/png, image/jpeg, image/webp" />
                    </div>
                    <a style="display: none; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_salva_input_img_${(parseInt(xx) + parseInt('1'))}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="${(parseInt(xx) + parseInt('1'))}" onmouseup="$(this).css('display','none')" onClick="envia_form('salvar_item_foto', this, '${nome_marca['item'][2]}', '${referencia}', '${(parseInt(xx) + parseInt('1'))}')">Salvar foto</a>
                    <a style="display: flex; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_excl_input_img_${(parseInt(xx) + parseInt('1'))}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="${(parseInt(xx) + parseInt('1'))}" onmouseup="$('#input_img_exib_input_img_${(parseInt(xx) + parseInt('1'))}').css('display','none') $(this).css('display','none') $('#input_img_${(parseInt(xx) + parseInt('1'))}').val('')" onmousedown="envia_form('excluir_item_foto', this, '${nome_marca['item'][2]}', '${referencia}', '${(parseInt(xx) + parseInt('1'))}')">Excluir foto</a>
                    <img id="input_img_exib_input_img_${(parseInt(xx) + parseInt('1'))}" style="margin-top: 20px; width: 100%; height: 300px; object-fit: contain;" src="../img_base/produtos/${referencia.replace(/([^\d])+/gim, "")}/${nome_marca["imagens"]["url"][parseInt(xx) + parseInt('1')]}">
                    </div>`
                    $('#form_itens_fotos').append(result_html)
                } else {
                    var result_html = `
                    <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Foto 0${(parseInt(xx) + parseInt('1'))}</label>
                    <div class="mt-1">
                        <input name="upload[]" onchange="readURL(this)" id="input_img_${(parseInt(xx) + parseInt('1'))}" type="file" accept="image/png, image/jpeg, image/webp" />
                    </div>
                    <a style="display: none; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_salva_input_img_${(parseInt(xx) + parseInt('1'))}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="${(parseInt(xx) + parseInt('1'))}" onmouseup="$(this).css('display','none')" onClick="envia_form('salvar_item_foto', this, '${nome_marca['item'][2]}', '${referencia}', '${(parseInt(xx) + parseInt('1'))}')">Salvar foto</a>
                    <a style="display: none; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_excl_input_img_${(parseInt(xx) + parseInt('1'))}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="${(parseInt(xx) + parseInt('1'))}" onmouseup="$('#input_img_exib_input_img_${(parseInt(xx) + parseInt('1'))}').css('display','none') $(this).css('display','none') $('#input_img_${(parseInt(xx) + parseInt('1'))}').val('')" onmousedown="envia_form('excluir_item_foto', this, '${nome_marca['item'][2]}', '${referencia}', '${(parseInt(xx) + parseInt('1'))}')">Excluir foto</a>
                    <img id="input_img_exib_input_img_${(parseInt(xx) + parseInt('1'))}" style="display: none; margin-top: 20px; width: 100%; height: 300px; object-fit: contain;" src="">
                    </div>`
                    $('#form_itens_fotos').append(result_html)
                }
            }

        for (xx = 0; xx < nome_marca['itens_ficha']['ref'].length; xx++) {
            var result_html = `
            <div class="sm:col-span-8" style="display: flex; flex-wrap: wrap; align-items: flex-end;">
            <div style="width: 80%">
                <label style="justify-content: space-between;" class="flex text-sm font-medium text-gray-700">${nome_marca["itens_ficha"]["nome"][xx]}<img src="img/remove.png" style="display: none; width: 16px; height: 16px;" id="img_${nome_marca["itens_ficha"]["ref"][xx]}"></label>
                <div class="mt-1">
                    <input type="text" onKeyUp="$('#img_${nome_marca["itens_ficha"]["ref"][xx]}').css('display','block')" value="${nome_marca["itens_ficha"]["descr_ficha"][xx]}" name="${nome_marca["itens_ficha"]["ref"][xx] }" id="${nome_marca["itens_ficha"]["ref"][xx]}" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000">
                    </div>
                </div>
                <div style="width: 20%; display: flex; justify-content: center; align-items: center;">
                    <a style="display: flex; cursor: pointer" class="cursor-pointer ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="${nome_marca["itens_ficha"]["ref"][xx]}" onclick="envia_form('salvar_item', this, '${nome_marca['item'][2]}','${referencia}')">Salvar Item</a>
                </div>
            </div>`
            $('#box_itens').append(result_html)
        }
        return false
    }

    /* EXECUTA CASO NÃO TENHA FICHA CADASTRADA */
    $("#tipo_prod_int").val(nome_marca['status'][0])
    $("#button_box_footer").css("display","block")

    var itens_base = `
    <div class="sm:col-span-8 focus:ring-blue-500 focus:border-blue-500">
    <label class="block text-sm font-medium text-gray-700">NOME WEB</label>
    <div class="mt-1">
        <input type="text" name="nome_web" id="nome_web" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000">
    </div>
    </div>
    <div class="sm:col-span-8">
    <label class="block text-sm font-medium text-gray-700">DESCRIÇÃO</label>
    <div class="mt-1">
        <textarea type="text" name="11" id="11" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></textarea>
    </div>
    </div>`;
    $('#box_itens').append(itens_base)

    for (xx = 0; xx < nome_marca['itens_ficha']['ref'].length; xx++) {
        var result_html = `
        <div class="sm:col-span-2 focus:ring-blue-500 focus:border-blue-500" id="${nome_marca["itens_ficha"]["nome"][xx]}">
        <label class="block text-sm font-medium text-gray-700">${nome_marca["itens_ficha"]["nome"][xx]}</label>
        <div class="mt-1">
            <input type="text" name="${nome_marca["itens_ficha"]["ref"][xx]}" id="${nome_marca["itens_ficha"]["ref"][xx]}" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000">
            </div>
        </div>`
        $('#box_itens').append(result_html)
    }

    for (xx = 0; xx <= 4; xx++) {
        var result_html = `
        <div class="sm:col-span-2 focus:ring-blue-500 focus:border-blue-500">
        <label class="block text-sm font-medium text-gray-700">Foto 0${(parseInt(xx) + parseInt('1'))}</label>
        <div class="mt-1">
            <input name="upload[]" onchange="readURL(this)" id="input_img_${(parseInt(xx) + parseInt('1'))}" type="file" accept="image/png, image/jpeg, image/webp">
        </div>
        <a style="display: none; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_salva_input_img_${(parseInt(xx) + parseInt('1'))}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="${(parseInt(xx) + parseInt('1'))}" onmouseup="$(this).css('display','none')" onClick="envia_form('salvar_item_foto', this, '${nome_marca['item'][2]}', '${referencia}', '${(parseInt(xx) + parseInt('1'))}')">Salvar foto</a>
        <a style="display: none; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_excl_input_img_${(parseInt(xx) + parseInt('1'))}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="${(parseInt(xx) + parseInt('1'))}" onmouseup="$('#input_img_exib_input_img_${(parseInt(xx) + parseInt('1'))}').css('display','none') $(this).css('display','none') $('#input_img_${(parseInt(xx) + parseInt('1'))}').val('')" onmousedown="envia_form('excluir_item_foto', this, '${nome_marca['item'][2]}', '${referencia}', '${(parseInt(xx) + parseInt('1'))}')">Excluir foto</a>
        <img id="input_img_exib_input_img_${(parseInt(xx) + parseInt('1'))}" style="margin-top: 20px; width: 100%; height: 300px; object-fit: contain;" src="">
        </div>`
        $('#form_itens_fotos').append(result_html)
    }
}



function envia_form(tipo, id, reg_prod, ref, sequencia) {
    if (tipo == 'box_itens') {
        var data_array = [];
        $(".itens_json").each(function (index) {
            data_array.push($(this).attr('id') + ":" + $(this).val())
        })
        $.ajax({
            url: 'includes/central_controller.php',
            type: 'POST',
            data: `acao=cadastro_ficha_tecnica&json=${JSON.stringify(data_array)}`,
            method: 'POST',
            dataType: 'html',
            scriptCharset: "UTF-8",
            success: function (retorno) {
                console.log(retorno)
                if (retorno == 'error_user') {
                    exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
                    setTimeout(() => window.location.reload(), 2000)
                } else if (retorno == 'error_no_data') {
                    exibe_notificacao('red', 'Preencha todos os dados!')
                } else if (retorno == 'success') {
                    exibe_notificacao('green', 'Ficha cadastrada com sucesso!', 'Você será redirecionado para cadastrar fotos')
                    $("#button_box_footer").css("display","none")
                    $("#tipo_ficha").css("display","flex")
                    $("#button_cadastrar_fotos").css("display","flex")
                    setTimeout(() => $("#button_cadastrar_fotos").trigger("click"), 2000)
                }
            }
        })
    } 
    
    if (tipo == 'salvar_item') {
        var valor_altera = $(`#${id.name}`).val()
        if (document.getElementById(`img_${id.name}`)) {
            $(`#img_${id.name}`).css("display","none")
        }
        $.ajax({
            url: 'includes/central_controller.php',
            type: 'POST',
            data: `acao=atualizar_ficha_tecnica&atualiza_ficha_id=${id.name}&atualiza_ficha_descr=${valor_altera}&registro=${reg_prod}&referencia=${ref}`,
            method: 'POST',
            dataType: 'html',
            scriptCharset: "UTF-8",
            success: function (retorno) {
                if (retorno == 'error_user') {
                    exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
                    setTimeout(() => window.location.reload(), 2000)
                } else if (retorno == 'success') {
                    exibe_notificacao('green', 'Item salvo com sucesso!')
                }
            }
        })
    }
    //  if (tipo == 'excluir_item') {
    //     var valor_altera = document.getElementById(id.name).value;
    //     if (valor_altera != null) {
    //         var data_array = 'acao=atualizar_ficha_tecnica_remove&atualiza_ficha_id=' + id.name + '&atualiza_ficha_descr=' + valor_altera + '&registro=' + reg_prod + '&referencia=' + ref;
    //         $.ajax({
    //             url: 'includes/central_controller.php',
    //             type: 'POST',
    //             data: data_array,
    //             method: 'POST',
    //             dataType: 'html',
    //             scriptCharset: "UTF-8",
    //             success: function (retorno) {
    //                 if (retorno == 'error_user') {
    //                     $("#notification").click()
    //                     $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
    //                     $("#notification_txt_1").html("Ocorreu um problema! (login_error)")
    //                     $("#notification_txt_2").html("Entre em contato com o suporte")
    //                     setTimeout(() => window.location.reload(), 2000)
    //                 } else if (retorno == 'success') {
    //                     $("#notification").click()
    //                     $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
    //                     $("#notification_txt_1").html("Item excluído sucesso!")
    //                 }
    //             }
    //         })
    //     }
    // } 
    if (tipo == 'salvar_item_foto') {
        var formData = new FormData()
        formData.append('acao', 'cadastro_fotos_ficha_tecnica')
        formData.append('referencia', ref)
        formData.append('produto', reg_prod)
        formData.append('sequencia', sequencia)
        formData.append('upload', $(`#input_img_${sequencia}`)[0].files[0])
        $.ajax({
            url: 'includes/central_controller.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (retorno) {
                if (retorno == 'error_user') {
                    exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
                    setTimeout(() => window.location.reload(), 2000)
                } else if (retorno == 'error_size') {
                    exibe_notificacao('red', 'Imagem acima do tamanho permitido!', 'Tamanho máximo 1MB')
                } else if (retorno == 'error_format') {
                    exibe_notificacao('red', 'Formato não permitido!', 'Aceitos jpeg, png ou webp')
                } else if (retorno == 'success') {
                    exibe_notificacao('green', 'Imagem adicionada com sucesso!')
                }
            },
            error: function () {
                exibe_notificacao('red', 'Ocorreu um problema! (prod_error_move_img)', 'Entre em contato com o suporte')
                setTimeout(() => window.location.reload(), 2000)
            }
        })
    } 
    
    if (tipo == 'excluir_item_foto') {
        var data_array = `acao=atualizar_ficha_tecnica_remove_foto&registro=${reg_prod}&sequencia=${sequencia}`;
        $.ajax({
            url: 'includes/central_controller.php',
            type: 'POST',
            data: data_array,
            method: 'POST',
            dataType: 'html',
            scriptCharset: "UTF-8",
            success: function (retorno) {
                if (retorno == 'error_user') {
                    exibe_notificacao('red', 'Ocorreu um problema! (login_error)', 'Entre em contato com o suporte')
                    setTimeout(() => window.location.reload(), 2000)
                } else if (retorno == 'success') {
                    exibe_notificacao('red', 'Imagem excluída com sucesso!')
                }
            }
        })
    }
}



function exibe_secao(a) {
    document.getElementById('box_itens').style.display = 'none'
    document.getElementById('box_itens_fotos').style.display = 'none'
    document.getElementById('button_box_itens').style.display = 'none'
    // document.getElementById('button_adicionar_box_itens').style.display = 'none'
    document.getElementById('button_box_footer').style.display = 'none'
    document.getElementById(a).style.display = 'contents'

    if (a == 'box_itens' & document.getElementById('tipo_prod_int').value == 'N') {
        document.getElementById('button_box_footer').style.display = 'block'
        document.getElementById('button_box_itens').style.display = 'inline-block'
    }

    if (a == 'box_itens') {
        // document.getElementById('button_adicionar_box_itens').style.display = 'block'
    }
    if (a == 'box_itens_fotos') {
        // document.getElementById('button_adicionar_box_itens').style.display = 'block'
    }
}



function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader()
        reader.onload = function (e) {
            $(`#input_img_exib_button_salva_${input.id}`).css('display', 'flex')
            $(`#input_img_exib_button_excl_${input.id}`).css('display', 'flex')
            $(`#input_img_exib_${input.id}`).css('display', 'block')
            $(`#input_img_exib_${input.id}`)
                .attr('src', e.target.result)
        }
        reader.readAsDataURL(input.files[0])
    }
}



function list_pesquisa_geral(a) {
    let form_ajax = `txtBusca=${a.value}`
    $.ajax({
        url: "includes/api_result_pesq_prod_geral.php",
        method: "post",
        data: form_ajax,
        dataType: "html",
        success: function (result) {
            $('#result_busca_geral').html(result)
        },
    })
}

jQuery(document.body).on('keypress', function (e) {
    if (e.keyCode === 13) {
        e.preventDefault()
        $("#search_button").trigger("click")
    }
})

function envia_post() {
    event.preventDefault()
    var item = $('#registro').val()
    var ref = `../item.php?ref=${item}`
    $.redirect(ref, {'post': 'admin'}, "POST")
}

// function list_pesquisa() {
//     var value_pesq = document.getElementById("txtBusca").value;
//     let form_ajax = 'txtBusca=' + value_pesq + ''
//     $.ajax({
//         url: "includes/api_result_pesq.php",
//         method: "post",
//         data: form_ajax,
//         dataType: "html",
//         success: function (result) {
//             $('#result_busca').html(result)
//         },
//     })
//     var opts = document.getElementById('result_busca').childNodes;
//     for (var i = 0; i < opts.length; i++) {
//         // console.log(opts[i])
//         if (opts[i].value === value_pesq) {
//             if ($('#' + opts[i].value)[0]) {
//                 $("#notification").click()
//                 $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
//                 $("#notification_txt_1").html("Item já incluído na ficha!")
//                 document.getElementById('txtBusca').value = null;
//             } else {
//                 if (document.getElementById('nome_web').value == '' || document.getElementById('nome_web').value == null) {
//                     document.getElementById('txtBusca').value = null;
//                     var result_new = '<div class="sm:col-span-2 focus:ring-blue-500 focus:border-blue-500"><label style="justify-content: space-between;" class="flex text-sm font-medium text-gray-700">' + opts[i].innerHTML + '<img src="img/remove.png" style="width: 16px; height: 16px;"id="img_' + opts[i].value + '" name="' + opts[i].value + '"></label><div class="mt-1"><input type="text" name="' + opts[i].value + '" id="' + opts[i].value + '" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div>'
//                     $('#box_itens').prepend(result_new)
//                     break;
//                 } else {
//                     document.getElementById('txtBusca').value = null;
//                     var referencia = document.getElementById('registro').value;

//                     var excluir_item = '<a style="display: flex; cursor: pointer" class="cursor-pointer ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + opts[i].value + '" onmouseup="$(this).parent().parent().remove()" onmousedown="envia_form(`excluir_item`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`)">Excluir Item</a>'

//                     var result_new = '<div class="sm:col-span-8 focus:ring-blue-500 focus:border-blue-500" style="display: flex; flex-wrap: wrap; align-items: flex-end;"><div style="width: 80%"><label style="justify-content: space-between;" class="flex text-sm font-medium text-gray-700">' + opts[i].innerHTML + '<img src="img/remove.png" style="width: 16px; height: 16px;" id="img_' + opts[i].value + '" name="' + opts[i].value + '"></label><div class="mt-1"><input type="text" onKeyUp="$(`#img_' + opts[i].value + '`).css(`display`,`block`)" name="' + opts[i].value + '" id="' + opts[i].value + '" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div style="width: 20%; display: flex; justify-content: center; align-items: center;"><a style="display: flex; cursor: pointer" class="cursor-pointer ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + opts[i].value + '" onclick="envia_form(`salvar_item`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`)">Salvar Item</a>' + excluir_item + '</div></div>'
//                     $('#box_itens').prepend(result_new)
//                     break;
//                 }
//             }
//         }
//     }
// }