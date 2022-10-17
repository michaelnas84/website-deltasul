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
    var opts = document.getElementById('result_busca').childNodes;
    for (var i = 0; i < opts.length; i++) {
        // console.log(opts[i])
        if (opts[i].value === value_pesq) {
            if ($('#' + opts[i].value)[0]) {
                $("#notification").click()
                $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                $("#notification_txt_1").html("Item já incluído na ficha!")
                document.getElementById('txtBusca').value = null;
            } else {
                if (document.getElementById('nome_web').value == '' || document.getElementById('nome_web').value == null) {
                    document.getElementById('txtBusca').value = null;
                    var result_new = '<div class="sm:col-span-2 focus:ring-blue-500 focus:border-blue-500"><label style="justify-content: space-between;" class="flex text-sm font-medium text-gray-700">' + opts[i].innerHTML + '<img src="img/remove.png" style="width: 16px; height: 16px;"id="img_' + opts[i].value + '" name="' + opts[i].value + '"></label><div class="mt-1"><input type="text" name="' + opts[i].value + '" id="' + opts[i].value + '" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div>';
                    $('#box_itens').prepend(result_new)
                    break;
                } else {
                    document.getElementById('txtBusca').value = null;
                    var referencia = document.getElementById('registro').value;

                    var excluir_item = '<a style="display: flex; cursor: pointer" class="cursor-pointer ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + opts[i].value + '" onmouseup="$(this).parent().parent().remove()" onmousedown="envia_form(`excluir_item`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`)">Excluir Item</a>';

                    var result_new = '<div class="sm:col-span-8 focus:ring-blue-500 focus:border-blue-500" style="display: flex; flex-wrap: wrap; align-items: flex-end;"><div style="width: 80%"><label style="justify-content: space-between;" class="flex text-sm font-medium text-gray-700">' + opts[i].innerHTML + '<img src="img/remove.png" style="width: 16px; height: 16px;" id="img_' + opts[i].value + '" name="' + opts[i].value + '"></label><div class="mt-1"><input type="text" onKeyUp="$(`#img_' + opts[i].value + '`).css(`display`,`block`)" name="' + opts[i].value + '" id="' + opts[i].value + '" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div style="width: 20%; display: flex; justify-content: center; align-items: center;"><a style="display: flex; cursor: pointer" class="cursor-pointer ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + opts[i].value + '" onclick="envia_form(`salvar_item`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`)">Salvar Item</a>' + excluir_item + '</div></div>';
                    $('#box_itens').prepend(result_new)
                    break;
                }
            }
        }
    }
}

var url_string = window.location.href;
var url = new URL(url_string)
var get_ref = url.searchParams.get("ref")

if (get_ref !== null) {
    pesq_cod(get_ref)
    document.getElementById('registro').value = get_ref;
}

function pesq_cod(ref) {
    document.getElementById('box_itens').style.display = 'none';
    document.getElementById('box_itens_fotos').style.display = 'none';
    document.getElementById('button_box_footer').style.display = 'none';
    var prod_cod = 'prod_cod=' + ref;
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
                $("#notification_txt_1").html("Sem itens ficha cadastrados para esta categoria/subcategoria!")
                $("#notification_txt_2").html("A página será recarregada")
                setTimeout(() => window.location.reload(), 2000)
            } else {
                $('#form').html('')
                $('#box_itens').html('')
                var result_html = '<input hidden name="acao" value="cadastro_fotos_ficha_tecnica"><input type="text" hidden name="referencia" id="referencia"><input type="text" hidden name="produto" id="produto" class="itens_json"><input type="text" hidden name="tipo_prod_int" id="tipo_prod_int">';
                $('#form').append(result_html)
                nome_marca = JSON.parse(retorno)
                document.getElementById('box_itens').style.display = 'none';
                document.getElementById('box_itens_fotos').style.display = 'none';
                document.getElementById('button_box_itens').style.display = 'none';
                // document.getElementById('button_adicionar_box_itens').style.display = 'none';
                document.getElementById('adiciona_item_no_site').style.display = 'none';
                document.getElementById('button_box_footer').style.display = 'none';

                document.getElementById('referencia').value = document.getElementById('registro').value;
                document.getElementById('nome_item').value = nome_marca['item'][0];
                document.getElementById('marca_item').value = nome_marca['item'][1];
                document.getElementById('produto').value = nome_marca['item'][2];
                document.getElementById('nome_marca').style.display = 'contents';
                document.getElementById('box_itens').style.display = 'contents';
                document.getElementById('tipo_ficha').style.display = 'flex';
                document.getElementById('button_box_itens').style.display = 'inline-block';
                if(nome_marca['status_banco'] == 'N') {
                document.getElementById('adiciona_item_no_site').style.display = 'flex';
                }
                // document.getElementById('button_adicionar_box_itens').style.display = 'flex';
                var referencia = document.getElementById('registro').value;

                if (nome_marca['status'][0] == 'N') {
                    document.getElementById('tipo_prod_int').value = nome_marca['status'][0];
                    document.getElementById('button_box_footer').style.display = 'block';
                    var itens_base = '<div class="sm:col-span-8 focus:ring-blue-500 focus:border-blue-500"><label class="block text-sm font-medium text-gray-700">NOME WEB</label><div class="mt-1"><input type="text" name="nome_web" id="nome_web" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div class="sm:col-span-8"><label class="block text-sm font-medium text-gray-700">DESCRIÇÃO</label><div class="mt-1"><textarea type="text" name="11" id="11" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></textarea></div></div><div class="sm:col-span-2" id="modelo"><label class="block text-sm font-medium text-gray-700">MODELO</label><div class="mt-1"><input type="text" name="1" id="1" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div class="sm:col-span-2" id="cor"><label class="block text-sm font-medium text-gray-700">COR</label><div class="mt-1"><input type="text" name="2" id="2" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div class="sm:col-span-2" id="garantia_do_fabricante"><label class="block text-sm font-medium text-gray-700">GARANTIA DO FABRICANTE</label><div class="mt-1"><input type="text" name="3" id="3" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div class="sm:col-span-2" id="peso_gramas"><label class="block text-sm font-medium text-gray-700">PESO</label><div class="mt-1"><input type="text" name="7" id="7" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div class="sm:col-span-2" id="altura_mm"><label class="block text-sm font-medium text-gray-700">ALTURA</label><div class="mt-1"><input type="text" name="4" id="4" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div class="sm:col-span-2" id="largura_mm"><label class="block text-sm font-medium text-gray-700">LARGURA</label><div class="mt-1"><input type="text" name="5" id="5" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div class="sm:col-span-2" id="profundidade_mm"><label class="block text-sm font-medium text-gray-700">PROFUNDIDADE</label><div class="mt-1"><input type="text" name="6" id="6" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div class="sm:col-span-2" id="recomenda_uso"><label class="block text-sm font-medium text-gray-700">RECOMENDAÇÕES DE USO</label><div class="mt-1"><input type="text" name="8" id="8" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div class="sm:col-span-2" id="como_receber"><label class="block text-sm font-medium text-gray-700">COMO VOU RECEBER</label><div class="mt-1"><input type="text" name="9" id="9" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div class="sm:col-span-2" id="conteudo"><label class="block text-sm font-medium text-gray-700">CONTEÚDO DA CAIXA</label><div class="mt-1"><input type="text" name="10" id="10" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div>';
                    $('#box_itens').append(itens_base)

                    for (xx = 0; xx <= 3; xx++) {
                        var result_html = '<div class="sm:col-span-2 focus:ring-blue-500 focus:border-blue-500"><label class="block text-sm font-medium text-gray-700">Foto 0' + (parseInt(xx) + parseInt('1')) + '</label><div class="mt-1"><input name="upload[]" onchange="readURL(this)" id="input_img_' + (parseInt(xx) + parseInt('1')) + '" type="file" accept="image/png, image/jpeg, image/webp"></div><img id="input_img_exib_input_img_' + (parseInt(xx) + parseInt('1')) + '" style="margin-top: 20px; width: 100%; height: 300px; object-fit: contain;" src=""></div>';
                        $('#form').append(result_html)
                    }


                    for (xx = 0; xx < nome_marca['itens_ficha']['ref'].length; xx++) {
                        var result_html = '<div class="sm:col-span-2 focus:ring-blue-500 focus:border-blue-500" id="' + nome_marca["itens_ficha"]["nome"][xx] + '"><label class="block text-sm font-medium text-gray-700">' + nome_marca["itens_ficha"]["nome"][xx] + '</label><div class="mt-1"><input type="text" name="' + nome_marca["itens_ficha"]["ref"][xx] + '" id="' + nome_marca["itens_ficha"]["ref"][xx] + '" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div>';
                        $('#box_itens').append(result_html)
                    }

                } else if (nome_marca['status'][0] == 'S') {
                    document.getElementById('tipo_prod_int').value = nome_marca['status'][0];
                    var descricao_web = '<div class="sm:col-span-8 focus:ring-blue-500 focus:border-blue-500" style="display: flex; flex-wrap: wrap; align-items: flex-end;"><div style="width: 80%"><label style="justify-content: space-between;" class="flex text-sm font-medium text-gray-700">NOME WEB<img src="img/remove.png" style="display: none; width: 16px; height: 16px;" id="img_nome_web"></label><div class="mt-1"><input type="text" onKeyUp="$(`#img_nome_web`).css(`display`,`block`)" name="nome_web" id="nome_web" value="' + nome_marca['item'][3] + '" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div style="width: 20%; display: flex; justify-content: center; align-items: center;"><a style="display: flex; cursor: pointer" class="cursor-pointer ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="nome_web" onclick="envia_form(`salvar_item`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`)">Salvar Item</a></div></div>';
                    $('#box_itens').append(descricao_web)

                    if (typeof nome_marca["imagens"] != "undefined") {
                        for (xx = 0; xx <= 4; xx++) {
                            if (xx != 4) {
                                var nome_banner = '';
                            } else {
                                var nome_banner = ' - (BANNER DESCRIÇÃO)';
                            };
                            if (nome_marca["imagens"]["url"][parseInt(xx) + parseInt('1')] != null) {
                                var result_html = '<div class="sm:col-span-2"><label class="block text-sm font-medium text-gray-700">Foto 0' + (parseInt(xx) + parseInt('1')) + nome_banner + '</label><div class="mt-1"><input name="upload[]" onchange="readURL(this)" id="input_img_' + (parseInt(xx) + parseInt('1')) + '" type="file" accept="image/png, image/jpeg, image/webp"/></div><a style="display: none; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_salva_input_img_' + (parseInt(xx) + parseInt('1')) + '" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + (parseInt(xx) + parseInt('1')) + '" onmouseup="$(this).css(`display`,`none`)" onClick="envia_form(`salvar_item_foto`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`, `' + (parseInt(xx) + parseInt('1')) + '`)">Salvar foto</a><a style="display: flex; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_excl_input_img_' + (parseInt(xx) + parseInt('1')) + '" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + (parseInt(xx) + parseInt('1')) + '" onmouseup="$(`#input_img_exib_input_img_' + (parseInt(xx) + parseInt('1')) + '`).css(`display`,`none`) $(this).css(`display`,`none`) $(`#input_img_' + (parseInt(xx) + parseInt('1')) + '`).val(``)" onmousedown="envia_form(`excluir_item_foto`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`, `' + (parseInt(xx) + parseInt('1')) + '`)">Excluir foto</a><img id="input_img_exib_input_img_' + (parseInt(xx) + parseInt('1')) + '" style="margin-top: 20px; width: 100%; height: 300px; object-fit: contain;" src="../img_base/produtos/' + referencia.replace(/([^\d])+/gim, "") + '/' + nome_marca["imagens"]["url"][parseInt(xx) + parseInt('1')] + '"></div>';
                                $('#form').append(result_html)
                            } else {
                                var result_html = '<div class="sm:col-span-2"><label class="block text-sm font-medium text-gray-700">Foto 0' + (parseInt(xx) + parseInt('1')) + nome_banner + '</label><div class="mt-1"><input name="upload[]" onchange="readURL(this)" id="input_img_' + (parseInt(xx) + parseInt('1')) + '" type="file" accept="image/png, image/jpeg, image/webp"/></div><a style="display: none; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_salva_input_img_' + (parseInt(xx) + parseInt('1')) + '" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + (parseInt(xx) + parseInt('1')) + '" onmouseup="$(this).css(`display`,`none`)" onClick="envia_form(`salvar_item_foto`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`, `' + (parseInt(xx) + parseInt('1')) + '`)">Salvar foto</a><a style="display: none; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_excl_input_img_' + (parseInt(xx) + parseInt('1')) + '" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + (parseInt(xx) + parseInt('1')) + '" onmouseup="$(`#input_img_exib_input_img_' + (parseInt(xx) + parseInt('1')) + '`).css(`display`,`none`) $(this).css(`display`,`none`) $(`#input_img_' + (parseInt(xx) + parseInt('1')) + '`).val(``)" onmousedown="envia_form(`excluir_item_foto`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`, `' + (parseInt(xx) + parseInt('1')) + '`)">Excluir foto</a><img id="input_img_exib_input_img_' + (parseInt(xx) + parseInt('1')) + '" style="display: none; margin-top: 20px; width: 100%; height: 300px; object-fit: contain;" src=""></div>';
                                $('#form').append(result_html)
                            }
                        }
                    } else {
                        for (xx = 0; xx <= 4; xx++) {
                            if (xx != 4) {
                                var nome_banner = '';
                            } else {
                                var nome_banner = ' - (BANNER DESCRIÇÃO)';
                            };
                            var result_html = '<div class="sm:col-span-2"><label class="block text-sm font-medium text-gray-700">Foto 0' + (parseInt(xx) + parseInt('1')) + nome_banner + '</label><div class="mt-1"><input name="upload[]" onchange="readURL(this)" id="input_img_' + (parseInt(xx) + parseInt('1')) + '" type="file" accept="image/png, image/jpeg, image/webp"/></div><a style="display: none; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_salva_input_img_' + (parseInt(xx) + parseInt('1')) + '" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + (parseInt(xx) + parseInt('1')) + '" onmouseup="$(this).css(`display`,`none`)" onClick="envia_form(`salvar_item_foto`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`, `' + (parseInt(xx) + parseInt('1')) + '`)">Salvar foto</a><a style="display: none; width: 100%; margin-top: 20px; cursor: pointer;" id="input_img_exib_button_excl_input_img_' + (parseInt(xx) + parseInt('1')) + '" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + (parseInt(xx) + parseInt('1')) + '" onmouseup="$(`#input_img_exib_input_img_' + (parseInt(xx) + parseInt('1')) + '`).css(`display`,`none`) $(this).css(`display`,`none`) $(`#input_img_' + (parseInt(xx) + parseInt('1')) + '`).val(``)" onmousedown="envia_form(`excluir_item_foto`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`, `' + (parseInt(xx) + parseInt('1')) + '`)">Excluir foto</a><img id="input_img_exib_input_img_' + (parseInt(xx) + parseInt('1')) + '" style="display: none; margin-top: 20px; width: 100%; height: 300px; object-fit: contain;" src=""></div>';
                            $('#form').append(result_html)
                        }
                    }


                    var array_itens_obgt = [10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 11];
                    for (xx = 0; xx < nome_marca['itens_ficha']['ref'].length; xx++) {
                        if (array_itens_obgt.includes(nome_marca["itens_ficha"]["ref"][xx])) {
                            var excluir_item = '';
                        } else {
                            var excluir_item = '<a style="display: flex; cursor: pointer" class="cursor-pointer ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + nome_marca["itens_ficha"]["ref"][xx] + '" onmouseup="$(this).parent().parent().remove()" onmousedown="envia_form(`excluir_item`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`)">Excluir Item</a>';
                        }
                        var result_html = '<div class="sm:col-span-8" style="display: flex; flex-wrap: wrap; align-items: flex-end;"><div style="width: 80%"><label style="justify-content: space-between;" class="flex text-sm font-medium text-gray-700">' + nome_marca["itens_ficha"]["nome"][xx] + '<img src="img/remove.png" style="display: none; width: 16px; height: 16px;"id="img_' + nome_marca["itens_ficha"]["ref"][xx] + '"></label><div class="mt-1"><input type="text" onKeyUp="$(`#img_' + nome_marca["itens_ficha"]["ref"][xx] + '`).css(`display`,`block`)" value="' + nome_marca["itens_ficha"]["descr_ficha"][xx] + '" name="' + nome_marca["itens_ficha"]["ref"][xx] + '" id="' + nome_marca["itens_ficha"]["ref"][xx] + '" class="itens_json py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" maxlength="1000"></div></div><div style="width: 20%; display: flex; justify-content: center; align-items: center;"><a style="display: flex; cursor: pointer" class="cursor-pointer ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" name="' + nome_marca["itens_ficha"]["ref"][xx] + '" onclick="envia_form(`salvar_item`, this, ' + nome_marca['item'][2] + ', `' + referencia + '`)">Salvar Item</a>' + excluir_item + '</div></div>';
                        $('#box_itens').append(result_html)
                    }
                }
            }
        }
    })
}



function envia_form(tipo, id, reg_prod, ref, sequencia) {
    if (tipo == 'box_itens') {
        var data_array = [];
        $(".itens_json").each(function (index) {
            data_array.push($(this).attr('id') + ":" + $(this).val())
        })
        var data_array = JSON.stringify(data_array)
        var data_array = 'acao=cadastro_ficha_tecnica&json=' + data_array
        $.ajax({
            url: 'includes/central_controller.php',
            type: 'POST',
            data: data_array,
            method: 'POST',
            dataType: 'html',
            scriptCharset: "UTF-8",
            success: function (retorno) {
                console.log(retorno)
                if (retorno == 'error_user') {
                    $("#notification").click()
                    $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                    $("#notification_txt_1").html("Ocorreu um problema! (login_error)")
                    $("#notification_txt_2").html("Entre em contato com o suporte")
                    setTimeout(() => window.location.reload(), 2000)
                } else if (retorno == 'error_no_data') {
                    $("#notification").click()
                    $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                    $("#notification_txt_1").html("Preencha todos os dados!")
                } else if (retorno == 'success') {
                    $("#notification").click()
                    $("#notification_svg").html('<svg class="h-6 w-6 text-green-400" x-description="Heroicon name: outline/check-circle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                    $("#notification_txt_1").html("Ficha cadastrada com sucesso!")
                    $("#notification_txt_2").html("Você será redirecionado para cadastrar fotos")
                    setTimeout(() => $("#button_cadastrar_fotos").trigger("click"), 2000)
                }
            }
        })
    } else if (tipo == 'salvar_item') {
        var valor_altera = document.getElementById(id.name).value;
        var data_array = 'acao=atualizar_ficha_tecnica&atualiza_ficha_id=' + id.name + '&atualiza_ficha_descr=' + valor_altera + '&registro=' + reg_prod + '&referencia=' + ref;
        if (document.getElementById('img_' + id.name)) {
            document.getElementById('img_' + id.name).style.display = 'none';
        }
        $.ajax({
            url: 'includes/central_controller.php',
            type: 'POST',
            data: data_array,
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
                    $("#notification_txt_1").html("Item salvo com sucesso!")
                }
            }
        })
    } else if (tipo == 'excluir_item') {
        var valor_altera = document.getElementById(id.name).value;
        if (valor_altera != null) {
            var data_array = 'acao=atualizar_ficha_tecnica_remove&atualiza_ficha_id=' + id.name + '&atualiza_ficha_descr=' + valor_altera + '&registro=' + reg_prod + '&referencia=' + ref;
            $.ajax({
                url: 'includes/central_controller.php',
                type: 'POST',
                data: data_array,
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
                        $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                        $("#notification_txt_1").html("Item excluído sucesso!")
                    }
                }
            })
        }
    } else if (tipo == 'salvar_item_foto') {
        var formData = new FormData()
        formData.append('acao', 'cadastro_fotos_ficha_tecnica')
        formData.append('referencia', ref)
        formData.append('produto', reg_prod)
        formData.append('sequencia', sequencia)
        formData.append('upload', $('#input_img_' + sequencia + '')[0].files[0])
        $.ajax({
            url: 'includes/central_controller.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (retorno) {
                if (retorno == 'error_user') {
                    $("#notification").click()
                    $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                    $("#notification_txt_1").html("Ocorreu um problema! (login_error)")
                    $("#notification_txt_2").html("Entre em contato com o suporte")
                    setTimeout(() => window.location.reload(), 2000)
                } else if (retorno == 'error_size') {
                    $("#notification").click()
                    $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                    $("#notification_txt_1").html("Imagem acima do tamanho permitido!")
                    $("#notification_txt_2").html("Tamanho máximo 1MB")
                } else if (retorno == 'error_format') {
                    $("#notification").click()
                    $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                    $("#notification_txt_1").html("Formato não permitido!")
                    $("#notification_txt_2").html("Aceitos jpeg, png ou webp")
                } else if (retorno == 'success') {
                    $("#notification").click()
                    $("#notification_svg").html('<svg class="h-6 w-6 text-green-400" x-description="Heroicon name: outline/check-circle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                    $("#notification_txt_1").html("Imagem adicionada com sucesso!")
                }
            },
            error: function () {
                $("#notification").click()
                $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                $("#notification_txt_1").html("Ocorreu um problema! (prod_error_move_img)")
                $("#notification_txt_2").html("Entre em contato com o suporte")
                setTimeout(() => window.location.reload(), 2000)
            }
        })
    } else if (tipo == 'excluir_item_foto') {
        var data_array = 'acao=atualizar_ficha_tecnica_remove_foto&registro=' + reg_prod + '&sequencia=' + sequencia;
        $.ajax({
            url: 'includes/central_controller.php',
            type: 'POST',
            data: data_array,
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
                    $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                    $("#notification_txt_1").html("Imagem excluída com sucesso!")
                }
            }
        })
    }
}



function exibe_secao(a) {
    document.getElementById('box_itens').style.display = 'none';
    document.getElementById('box_itens_fotos').style.display = 'none';
    document.getElementById('button_box_itens').style.display = 'none';
    // document.getElementById('button_adicionar_box_itens').style.display = 'none';
    document.getElementById('button_box_footer').style.display = 'none';
    document.getElementById(a).style.display = 'contents';

    if (a == 'box_itens' & document.getElementById('tipo_prod_int').value == 'N') {
        document.getElementById('button_box_footer').style.display = 'block';
        document.getElementById('button_box_itens').style.display = 'inline-block';
    }

    if (a == 'box_itens') {
        // document.getElementById('button_adicionar_box_itens').style.display = 'block';
    }
    if (a == 'box_itens_fotos') {
        // document.getElementById('button_adicionar_box_itens').style.display = 'block';
    }
}



function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader()
        reader.onload = function (e) {
            $('#input_img_exib_button_salva_' + input.id + '').css('display', 'flex')
            $('#input_img_exib_button_excl_' + input.id + '').css('display', 'flex')
            $('#input_img_exib_' + input.id + '').css('display', 'block')
            $('#input_img_exib_' + input.id + '')
                .attr('src', e.target.result)
        };
        reader.readAsDataURL(input.files[0])
    }
}



function list_pesquisa_geral(a) {
    let form_ajax = 'txtBusca=' + a.value + '';
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