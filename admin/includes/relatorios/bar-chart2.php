<canvas id="bar-chart2" name="relatorio"></canvas>

<script>
        // Bar chart
      new Chart(document.getElementById("bar-chart2"), {
          type: 'bar',
          data: {
            labels: [<?= "'".implode("','", $cidade_mais_interesse)."'" ?>],
            datasets: [
              {
                label: "Clicks",
                backgroundColor: ["red", "green","#2a3959","#F1C40F","#c45850"],
                data: [<?= "'".implode("','", $qtd_cidade_mais_interesse)."'" ?>]
              }
            ]
          }
      });
      </script>