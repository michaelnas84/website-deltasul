function removeAcento(text) {
    text = text.toUpperCase()
    text = text.replace(new RegExp('[ÁÀÂÃ]', 'gi'), 'A')
    text = text.replace(new RegExp('[ÉÈÊ]', 'gi'), 'E')
    text = text.replace(new RegExp('[ÍÌÎ]', 'gi'), 'I')
    text = text.replace(new RegExp('[ÓÒÔÕ]', 'gi'), 'O')
    text = text.replace(new RegExp('[ÚÙÛ]', 'gi'), 'U')
    text = text.replace(new RegExp('[Ç]', 'gi'), 'C')
    return text
}

function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
        let cidade_form = removeAcento(conteudo.localidade.toUpperCase())
        var ref_item = window.location.href
        var ref_item = ref_item.split('com.br/item.php?ref=')
        var ref_item = ref_item[1]
        var registro_item = $('#registro_produto').val().replace(/[^0-9]/g, '')
        var cep_pesquisa = $('#cep').val().replace(/[^0-9]/g, '')
        $.ajax({
            data: { cidade: cidade_form },
            type: "POST",
            url: 'admin/includes/lojas_fetch.php',
            method: 'POST',
            success: function (data) {
                document.getElementById('cidade_proxima').innerHTML = ''
                var data = JSON.parse(data)
                if (data != null) {
                    const length = Object.keys(data).length
                    let x = 1
                    while (x <= length) {
                        document.getElementById('local_sem_loja').style.display = "none"
                        document.getElementById('cidade_proxima').style.display = "flex"
                        document.getElementById('cidade_proxima').innerHTML += `
                        <div class='slick-track' style='display: flex'>
                            <div class='local'>
                                <div style='color: white; width: 100%; text-decoration: none; text-align: center; margin-bottom: 10px; font-size: 20px; text-transform: uppercase'> ${data[x]['CIDADE']} </div>
                                <div style='color: white; width: 100%; text-decoration: none;'> ${data[x]['LOGRA']} <a style='color: white' type='text'>. </a> ${data[x]['ENDERECO']} <a style='color: white' type='text'> - </a>${data[x]['NUM_END']}<br>${data[x]['CEP']}</div>
                                <div style='color: white; width: 100%; text-decoration: none; margin-bottom: 20px'>( ${data[x]['DDD']} ) ${data[x]['TELEFONES']} </div>
                                <div style='width: 50%; text-align: center; background-color: #10386b; border: none; padding: 5px'>
                                <a style='color: white; width: 50%; text-align: center' href='https://api.whatsapp.com/send?phone=SeuNúmero&text=Olá Deltasul ${data[x][' CIDADE']}! Gostaria de mais informações sobre o produto REF ${ref_item}' onclick='click_produto('${ref_item}')' target='_blank'>Chamar no WhatsApp</a>
                                        </div>
                                        <div style='width: 50%; text-align: center; background-color: #005eab; border: none; padding: 5px'>
                                            <a style='color: white; width: 50%; text-align: center' id='end_cliente_box' href='https://www.google.com.br/maps/search/${data[x]['LOGRA'].replace(/^\s+|\s+$/gm, '')}.+${data[x]['ENDERECO']},+${data[x]['NUM_END']}+${data[x]['CIDADE'].replace(' (', ' - ').replace(')', '')}' onclick='click_produto('${ref_item}')' target='_blank'>Ver endereço no mapa</a>
                                        </div>
                                    </div>
                                </div>
                        `;
                        x++
                    }

                    var data = `acao=click_interesse_prod_cadastro&ckloja=S&cidade_pesquisa=${cidade_form}&cep_pesquisa=${cep_pesquisa}&produto=${ref_item}&registro=${registro_item}`;
                    $.ajax({
                        url: 'admin/includes/central_controller.php',
                        type: 'POST',
                        data: data,
                        method: 'POST',
                        dataType: 'html',
                        scriptCharset: "UTF-8",
                    })

                } else if (data == null) {
                    document.getElementById('local_sem_loja').style.display = "flex"
                    document.getElementById('cidade_proxima').style.display = "none"

                    var data = `acao=click_interesse_prod_cadastro&ckloja=N&cidade_pesquisa=${cidade_form}&cep_pesquisa=${cep_pesquisa}&produto=${ref_item}&registro=${registro_item}`;

                    $.ajax({
                        url: 'admin/includes/central_controller.php',
                        type: 'POST',
                        data: data,
                        method: 'POST',
                        dataType: 'html',
                        scriptCharset: "UTF-8",
                    })

                }
            }
        })
    }
    else {
        alert("CEP não encontrado.")
    }
}

function pesquisacep(valor) {
    var cep = valor.replace(/\D/g, '')
    if (cep != "") {
        var validacep = /^[0-9]{8}$/

        if (validacep.test(cep)) {
            var script = document.createElement('script')
            script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback'
            document.body.appendChild(script)
        }
        else {
            alert("Formato de CEP inválido.")
        }
    }
    else {
    }
}

function changeImage(a) {
    var data_array = []
    $(".MagicZoom").each(function (index) {
        data_array.push($(this).attr('id'))
    })
    var xx = 0
    while (xx < data_array.length) {
        document.getElementById(data_array[xx]).style.display = 'none'
        xx++
    }
    document.getElementById(a).style.display = "flex"
    document.getElementById(a).style.justifyContent = "center"

}


if (localStorage.visto_ultimo == null) {
    localStorage.setItem("visto_ultimo", '[]')
}
try {
    var ref_item = window.location.href
    var ref_item = ref_item.split('com.br/item.php?ref=')
    var ref_item = ref_item[1]
    var propIdToAdd = ref_item
    var myFavouriteProp = JSON.parse(localStorage.getItem("visto_ultimo"))
    if (myFavouriteProp == null) {
        myFavouriteProp = [];
    }

    if (myFavouriteProp != null) {
        for (var j = 0; j < myFavouriteProp.length; j++) {
            if (propIdToAdd == myFavouriteProp[j]) {
                delete myFavouriteProp[j];

                function difnull(value) {
                    return value != null;
                }

                myFavouriteProp = myFavouriteProp.filter(difnull)

            }
        }
    }
    myFavouriteProp.push(propIdToAdd);
    localStorage.setItem("visto_ultimo", JSON.stringify(myFavouriteProp));

}
catch (e) {
}

$("#button_adiciona_item_site").on("click", function(){
    var ref_item = $('#registro_produto').val()
    var data = `acao=confirma_produto_site&prod_cod=${ref_item}`;
    $.ajax({
        url: 'admin/includes/central_controller.php',
        type: 'POST',
        data: data,
        method: 'POST',
        dataType: 'html',
        scriptCharset: "UTF-8",
        success: function (retorno) {
            if(retorno == 'success'){
                alert('Item confirmado com sucesso!')
                window.location.replace("/admin/dashboard_produtos_no_site.php")
                return false
            } 
                alert('Erro ao confirmar produto no site')
        }
    })
})

$("#button_voltar").on("click", function(){
    var ref_item = $('#registro_produto').val()
    window.location.replace(`/admin/dashboard_inserir_ficha_tecnica.php?ref=${ref_item}`)
})

var visto_ultimo = localStorage.getItem('visto_ultimo')
$(document).ready(function () {
    createCookie("visto_ultimo", visto_ultimo, "10")
})

function createCookie(name, value, days) {
    var expires
    if (days) {
        var date = new Date()
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000))
        expires = "; expires=" + new Date(2147483647 * 1000).toUTCString()
    }
    else {
        expires = "";
    }
    document.cookie = escape(name) + "=" +
        escape(value) + expires + "; path=/"
}