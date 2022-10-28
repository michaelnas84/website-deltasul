var options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
}

function error(err) {
    console.warn('ERROR(' + err.code + '): ' + err.message)
}

function success(pos) {

    var cordenadas = pos.coords.latitude + '/' + pos.coords.longitude

        if (localStorage.getItem("coordenadas_usuario") != cordenadas) {
            $.ajax({
                url: `https://api.mapbox.com/geocoding/v5/mapbox.places/${pos.coords.longitude},${pos.coords.latitude}.json?types=poi&access_token=pk.eyJ1IjoibWljaGFlbG5hczg0IiwiYSI6ImNsNzByMzd2cDBnd3Mzb3BicjVsMTh1aXkifQ.UVFe3vXA0ptl2aufUkhRbA`,
                dataType: 'html',
                scriptCharset: "UTF-8",

                success: function (retorno) {
                    const retorno_api = JSON.parse(retorno)
                    localstorage_dados_visitacao(retorno_api['features'][0]['context'])
                    console.log('Buscou API localização')
                    localStorage.setItem("coordenadas_usuario", cordenadas)
                    salva_dados_visitacao(retorno_api['features'][0]['context'], cordenadas)
                }
            })

            return false
        }

        console.log('Não buscou API localização')

        retorno_api = localStorage.getItem("retorno_api_localizacao")

        retorno_api = JSON.parse(retorno_api)

        salva_dados_visitacao(retorno_api, cordenadas)

}

function localstorage_dados_visitacao(retorno) {
    const retorno_api = JSON.stringify(retorno)
    localStorage.setItem("retorno_api_localizacao", retorno_api)
}


function salva_dados_visitacao(retorno_api, cordenadas) {

    var url_atual = window.location.href.split('.com.br/')
    if (url_atual[1] === '') { var url_atual = 'index.php' } else { var url_atual = url_atual[1] }

    var array_local_acesso = []
    let itens_pesq = ['neighborhood', 'place', 'region', 'country']


    for (xx = 0; xx < retorno_api.length; xx++) {
        for (xy = 0; xy < itens_pesq.length; xy++) {
            if (retorno_api[xx]['id'].match(itens_pesq[xy])) {
                array_local_acesso[itens_pesq[xy]] = retorno_api[xx]['text']
            }
        }
    }

    localStorage.setItem("cidade_usuario", array_local_acesso['place'])

    $.ajax({
        url: 'admin/includes/central_controller.php',
        type: 'POST',
        data: { localizacao: cordenadas, bairro: array_local_acesso['neighborhood'], cidade: array_local_acesso['place'], estado: array_local_acesso['region'], pais: array_local_acesso['country'], pagina: url_atual, acao: 'localizacao_acesso_cadastrar' },

        method: 'POST',

        dataType: 'html',
        scriptCharset: "UTF-8",
        success: function (retorno) {
            // console.log(retorno)
        }
    })
}

navigator.geolocation.getCurrentPosition(success, error, options)