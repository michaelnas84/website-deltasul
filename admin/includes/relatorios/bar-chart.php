<canvas id="bar-chart" name="relatorio"></canvas>

    <script>
        // Bar chart
      new Chart(document.getElementById("bar-chart"), {
          type: 'bar',
          data: {
            labels: [<?= "'".implode("','", $prod_mais_interessante)."'" ?>],
            datasets: [
              {
                label: "Clicks",
                backgroundColor: ["red", "green","#2a3959","#F1C40F","#c45850"],
                data: [<?= "'".implode("','", $qtd_prod_mais_interessante)."'" ?>]
              }
            ]
          }
      });
      </script>