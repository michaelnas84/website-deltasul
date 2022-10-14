function exportar_csv(reg) {
    $.ajax({
        data: { registro: reg, acao: 'click_interesse_prod_exportar_csv' },
        type: "POST",
        url: 'includes/central_controller.php',
        method: 'POST',
        success: function (retorno) {
            var retorno_url = retorno.split("html")
            $("#notification").click()
            $("#notification_svg").html('<svg class="h-6 w-6 text-green-400" x-description="Heroicon name: outline/check-circle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>')
            $("#notification_txt_1").html("Arquivo exportado com sucesso!")
            window.location.replace(retorno_url[1])
        }
    })
}