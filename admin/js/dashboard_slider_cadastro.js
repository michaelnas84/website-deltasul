function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader()
        reader.onload = function (e) {
            $('#input_img_exib_svg').css('display', 'none')
            $('#input_img_exib').css('display', 'block')
            $('#input_img_exib')
                .attr('src', e.target.result)
        };
        reader.readAsDataURL(input.files[0])
    }
}



function enviar_dados() {
    event.preventDefault()
    var form = $('#form')[0];
    var data = new FormData(form)
    data.append("CustomField", "This is some extra data, testing")
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "includes/central_controller.php",
        data: data,
        processData: false,
        contentType: false,
        method: 'POST',
        cache: false,
        success: function (retorno) {
            if (retorno == 'error_user') {
                $("#notification").click()
                $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                $("#notification_txt_1").html("Ocorreu um problema! (login_error)")
                $("#notification_txt_2").html("Entre em contato com o suporte")
                setTimeout(() => window.location.replace("dashboard_slider.php"), 2000)
            } else if (retorno == 'error_no_data') {
                $("#notification").click()
                $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                $("#notification_txt_1").html("Preencha todos os itens!")
            } else if (retorno == 'error') {
                $("#notification").click()
                $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
                $("#notification_txt_1").html("Ocorreu um problema! (slider_error_move_img)")
                $("#notification_txt_2").html("Entre em contato com o suporte")
                setTimeout(() => window.location.replace("dashboard_slider.php"), 2000)
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
                $("#notification_txt_1").html("Slider adicionado com sucesso!")
                $("#notification_txt_2").html("A página será recarregada")
                setTimeout(() => window.location.replace("dashboard_slider.php"), 2000)
            }
        },
        error: function (e) {
            $("#notification").click()
            $("#notification_svg").html('<svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
            $("#notification_txt_1").html("Ocorreu um problema! (slider_error_form)")
            $("#notification_txt_2").html("Entre em contato com o suporte")
            setTimeout(() => window.location.replace("dashboard_slider.php"), 2000)
        }
    })
};