<canvas id="line-day-chart" name="relatorio"></canvas>

<?php 
function valueZiro($val){
    return ($val == '')?0:count($val);
}
?>
    
<script>
        var config = {
            type: 'line',
            data: {
                labels: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
                datasets: [{
                    label: "Visitas",
                    backgroundColor: ["#2a3959"],
                    borderColor: ["#2a3959"],
                    data: [<?= valueZiro($arr_dados_localizacao_acesso_dia[0]) ?>, <?= valueZiro($arr_dados_localizacao_acesso_dia[1]) ?>, <?= valueZiro($arr_dados_localizacao_acesso_dia[2]) ?>, <?= valueZiro($arr_dados_localizacao_acesso_dia[3]) ?>, <?= valueZiro($arr_dados_localizacao_acesso_dia[4]) ?>, <?= valueZiro($arr_dados_localizacao_acesso_dia[5]) ?>, <?= valueZiro($arr_dados_localizacao_acesso_dia[6]) ?>],
                    fill: false,
                }, {
                    label: "Clicks de interesse",
                    fill: false,
                    backgroundColor: ["#F1C40F"],
                    borderColor: ["#F1C40F"],
                    data: [<?= valueZiro($arr_dados_click_interesse_prod_dia[0]) + valueZiro($arr_dados_click_interesse_prod_sem_loja_dia_mes_geral[0]) ?>, <?= valueZiro($arr_dados_click_interesse_prod_dia[1]) + valueZiro($arr_dados_click_interesse_prod_sem_loja_dia_mes_geral[1]) ?>, <?= valueZiro($arr_dados_click_interesse_prod_dia[2]) + valueZiro($arr_dados_click_interesse_prod_sem_loja_dia_mes_geral[2]) ?>, <?= valueZiro($arr_dados_click_interesse_prod_dia[3]) + valueZiro($arr_dados_click_interesse_prod_sem_loja_dia_mes_geral[3]) ?>, <?= valueZiro($arr_dados_click_interesse_prod_dia[4]) + valueZiro($arr_dados_click_interesse_prod_sem_loja_dia_mes_geral[4]) ?>, <?= valueZiro($arr_dados_click_interesse_prod_dia[5]) + valueZiro($arr_dados_click_interesse_prod_sem_loja_dia_mes_geral[5]) ?>, <?= valueZiro($arr_dados_click_interesse_prod_dia[6]) + valueZiro($arr_dados_click_interesse_prod_sem_loja_dia_mes_geral[6]) ?>],
                }, {
                    label: "Compras",
                    fill: false,
                    backgroundColor: ["green"],
                    borderColor: ["green"],
                    data: [0, 0, 0, 0, 0, 0, 0],
                }]
            }
        };

        window.onload = function() {
            var ctx = document.getElementById("line-day-chart").getContext("2d");
            window.myLine = new Chart(ctx, config);
        };
    </script>