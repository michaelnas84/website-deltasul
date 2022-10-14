var options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
};
function success(pos) {
    var crd = pos.coords;
    var cordenadas = crd.latitude + '/' + crd.longitude;
    var url_atual = window.location.href;
    var url_atual = url_atual.split('.com.br/')
    if (url_atual[1] === '') { var url_atual = 'index.php' } else { var url_atual = url_atual[1] }
    $.ajax({
        url: 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + crd.longitude + ',' + crd.latitude + '.json?types=poi&access_token=pk.eyJ1IjoibWljaGFlbG5hczg0IiwiYSI6ImNsNzByMzd2cDBnd3Mzb3BicjVsMTh1aXkifQ.UVFe3vXA0ptl2aufUkhRbA',
        dataType: 'html',
        scriptCharset: "UTF-8",

        success: function (retorno) {
            const obj = JSON.parse(retorno);
            let tot_itens = obj['features'][0]['context'].length;
            let itens_pesq = [["neighborhood"], ["place"], ["region"], ["country"]];
            for (xx = 0; xx < tot_itens; xx++) {
                for (xy = 0; xy < itens_pesq.length; xy++) {
                    if (obj['features'][0]['context'][xx]['id'].match(itens_pesq[xy])) {
                        itens_pesq[xy].push(obj['features'][0]['context'][xx]['text']);
                    }
                }
            }
            $.ajax({
                url: 'admin/includes/central_controller.php',
                type: 'POST',
                data: { localizacao: cordenadas, bairro: itens_pesq[0][1], cidade: itens_pesq[1][1], estado: itens_pesq[2][1], pais: itens_pesq[3][1], place_name: obj['features'][0]['place_name'], pagina: url_atual, acao: 'localizacao_acesso_cadastrar' },

                method: 'POST',

                dataType: 'html',
                scriptCharset: "UTF-8",
                success: function (retorno) {
                    // console.log(retorno);
                }
            })
        }
    })
};
function error(err) {
    console.warn('ERROR(' + err.code + '): ' + err.message);
};
navigator.geolocation.getCurrentPosition(success, error, options);