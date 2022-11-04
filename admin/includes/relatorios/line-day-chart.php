<canvas id="line-day-chart" name="relatorio"></canvas>

<script>
      new Chart(document.getElementById("line-day-chart"), {
        type: 'line',
        data: {
                labels: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
                datasets: [{
                    label: "Visitas",
                    backgroundColor: ["#2a3959"],
                    borderColor: ["#2a3959"],
                    data: [<?= "'".implode("','", $qtd_cidade_total)."'" ?>],
                    fill: false,
                }, {
                    label: "Clicks de interesse",
                    fill: false,
                    backgroundColor: ["#F1C40F"],
                    borderColor: ["#F1C40F"],
                    data: [<?= "'".implode("','", $qtd_clicks_interesse_semana_total)."'" ?>],
                }, {
                    label: "Compras",
                    fill: false,
                    backgroundColor: ["green"],
                    borderColor: ["green"],
                    data: [0, 0, 0, 0, 0, 0, 0],
                }]
            }
      });
      </script>