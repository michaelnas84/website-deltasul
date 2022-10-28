function whatsapp() {
    alert('Aguardando WhatsApp de cada loja')
}

function exibe_loja_selecionada(loja){
    $("#scroll").click()
    if (loja == 'todos') {
        $('.card').show()
        return false
    }
    $('.card').each(function () {
        if (!$(this).hasClass(loja)) {
            $(this).hide()
        } else {
            $(this).show()
        }
    })
}

$('.loja_cidade').on('click', function () {
    document.getElementById('custom-select').value = this.id
    document.getElementById('select2-custom-select-container').title = this.id
    document.getElementById('select2-custom-select-container').innerHTML = this.id.replace(/_/g, ' ')
    exibe_loja_selecionada(this.id)
})

$('.loja_cidade').mouseover(function() {
    $(this).addClass("hover_map")
    $(`#text_${this.id}`).css("opacity","1")
})

$('.loja_cidade').mouseleave(function() {
    $(this).removeClass("hover_map")
    $(`#text_${this.id}`).css("opacity","0")
})

$('#custom-select').on('change', function () {
    exibe_loja_selecionada(this.value)
})

$(document).ready(function () {
    
    $('.custom-select').select2()

    $("option").each(function (index) {
        var cidade_usuario = localStorage.getItem("cidade_usuario").normalize('NFD').replace(/[\u0300-\u036f]/g, "").toUpperCase().replaceAll(' ', '_')
        if(this.value.includes(cidade_usuario)){
            $('#custom-select').val(cidade_usuario).change()
            return false
        }
    })

})


