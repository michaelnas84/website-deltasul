function abre_modal(a){
    document.getElementById('email_contato_deltasul').value = a
    document.getElementById('modal').classList.add("modal_aberto")
    document.body.classList.add("body_modal_aberto")
}

function fecha_modal(){
    document.getElementById('modal').classList.remove("modal_aberto")
    document.body.classList.remove("body_modal_aberto")
    document.getElementById('nome').value = null
    document.getElementById('email').value = null
    document.getElementById('telefone').value = null
    document.getElementById('assunto').value = null
    document.getElementById('mensagem').value = null
    document.getElementById('cidade').value = null
    document.getElementById('estado').value = null
    document.getElementById('pais').value = null
}

function envia_form(){
    var contador = 0;
    var tot_inputs = $('form#form_fale_conosco').find('input, textarea, select').each(function () {
        if ($(this).prop('required') && !$(this).val()) {
            alert('O campo ' + $(this).attr('aria-label') + ' é obrigatório!');
            return false;
        }
        contador++;
    }).length;
    if (tot_inputs === contador) {

        let retorno_api = JSON.parse(localStorage.getItem("retorno_api_localizacao"))

        if(!retorno_api){
            alert('Para entrar em contato conosco, precisamos dos seus dados de localização! Por favor, libere nas configurações do seu navegador')
        }

        var array_local_acesso = []
        let itens_pesq = ['neighborhood', 'place', 'region', 'country']
        for (xx = 0; xx < retorno_api.length; xx++) {
            for (xy = 0; xy < itens_pesq.length; xy++) {
                if (retorno_api[xx]['id'].match(itens_pesq[xy])) {
                    array_local_acesso[itens_pesq[xy]] = retorno_api[xx]['text']
                }
            }
        }
        

        document.getElementById('cidade').value = array_local_acesso['place']
        document.getElementById('estado').value = array_local_acesso['region']
        document.getElementById('pais').value   = array_local_acesso['country']

        $.ajax({
            url: 'admin/includes/central_email.php',
            type: 'POST',
            data: $("#form_fale_conosco").serialize(),

            method: 'POST',

            dataType: 'html',
            scriptCharset: "UTF-8",
            success: function (retorno) {
                if(retorno === 'success'){
                    alert('Mensagem enviada com sucesso!')
                    fecha_modal()
                    return false
                }
                console.log(retorno)
                alert('Erro ao enviar mensagem, tente novamente mais tarde!')
                fecha_modal()
            }
        })
    }
}