  <?php
      if (!isset($_SESSION)) session_start();
      if (!isset($_SESSION['nome_usuario'])) { session_destroy(); }
      if($_SESSION['nome_usuario'] == null || $_SESSION['permissoes'] == null || (!in_array("1", $_SESSION['permissoes']))){
        $URL_ATUAL = explode("admin/", $_SERVER["REQUEST_URI"]);
        header("Location: index.php?redir=".$URL_ATUAL[1]."");
        die();
      }


      header("Content-type: text/html; charset=utf-8");
      $pagina                 = "admin";
      $hoje                   = date('Y-m-d');
      $mes_agora              = date('m');
      $mes_anterior           = date('m', strtotime("-1 month", strtotime('now')));
      $mes_anterior_anterior  = date('m', strtotime("-2 month", strtotime('now')));
      include_once('includes/connections.php');

      $sql = "
      SELECT 
         referencia               AS REF,
         COUNT(referencia)        AS REF_QTD
      FROM
         web_buscaproduto
      GROUP BY 
         referencia
      ORDER BY 
         COUNT(referencia) DESC
      LIMIT
         5
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      $tot_itens=0;
      while($row_produtos_interessantes = $stmt->fetch()){
        $prod_mais_interessante[]                    = $row_produtos_interessantes['REF'];
        $qtd_prod_mais_interessante[]                = $row_produtos_interessantes['REF_QTD'];
      }


      $sql = "
      SELECT 
         cidade               AS CIDADE,
         COUNT(cidade)        AS CIDADE_QTD
      FROM
         web_buscaproduto
      WHERE 
        ckloja = 'N'
      GROUP BY 
         cidade
      ORDER BY 
         COUNT(cidade) DESC
      LIMIT
         5
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      $tot_itens=0;
      while($row_cidades_interesse = $stmt->fetch()){
        $cidade_mais_interesse[]                    = $row_cidades_interesse['CIDADE'];
        $qtd_cidade_mais_interesse[]                = $row_cidades_interesse['CIDADE_QTD'];
      }


      $sql = "
      SELECT
         diasemana 	 	          AS DIASEMANA,
         count(registro)        AS QTD
      FROM
         web_localacesso
      WHERE
         pagina = 'index.php'
      AND
         MONTH(data) = MONTH(NOW())
      AND 
         YEAR(data)  = YEAR(NOW())
      GROUP BY 
         diasemana
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      for($xx=0; $xx<=6; $xx++){ $qtd_cidade[$xx] = 0; }
      while($row_visitas = $stmt->fetch()){
        $qtd_cidade[$row_visitas['DIASEMANA']]                   = $row_visitas['QTD'];
      }


      $sql = "
      SELECT
         diasemana 	 	          AS DIASEMANA,
         count(registro)        AS QTD
      FROM
         web_localacesso
      WHERE
         pagina = 'index.php'
      GROUP BY 
         diasemana
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      for($xx=0; $xx<=6; $xx++){ $qtd_cidade_total[$xx] = 0; }
      while($row_visitas_total = $stmt->fetch()){
        $qtd_cidade_total[$row_visitas_total['DIASEMANA']]                   = $row_visitas_total['QTD'];
      }

      $sql = "
      SELECT
         diasemana 	 	          AS DIASEMANA,
         count(registro)        AS QTD
      FROM
        web_buscaproduto
      WHERE
        MONTH(data) = MONTH(NOW())
      AND 
        YEAR(data)  = YEAR(NOW())
      GROUP BY 
         diasemana
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      for($xx=0; $xx<=6; $xx++){ $qtd_clicks_interesse_semana[$xx] = 0; }
      while($row_clicks_interesse_semana = $stmt->fetch()){
        $qtd_clicks_interesse_semana[$row_clicks_interesse_semana['DIASEMANA']]                   = $row_clicks_interesse_semana['QTD'];
      }

      $sql = "
      SELECT
         diasemana 	 	          AS DIASEMANA,
         count(registro)        AS QTD
      FROM
        web_buscaproduto
      GROUP BY 
         diasemana
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      for($xx=0; $xx<=6; $xx++){ $qtd_clicks_interesse_semana_total[$xx] = 0; }
      while($row_clicks_interesse_semana_total = $stmt->fetch()){
        $qtd_clicks_interesse_semana_total[$row_clicks_interesse_semana_total['DIASEMANA']]                   = $row_clicks_interesse_semana_total['QTD'];
      }
  ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="img/ICON_Deltasul_laranja.ico">
   <script src="js/jquery-3.1.0.js"></script>
  <title>Relatórios | Lojas Deltasul</title>
  <link rel="stylesheet" type="text/css" href="css/styles.css<?= '?'.bin2hex(random_bytes(50))?>">
</head>
<body class="flex background-white dark:bg-gray-800">

<?php include('includes/dropdown_menu.php') ?>

<div class="w-full ml-auto flex flex-wrap mt-14 xl:mt-0 xl:w-10/12 xl:min-h-screen content-start">
<?php $header_page_1 = 'Relatórios';
      $header_page_1_url = 'dashboard_relatorios.php';
      $header_page_name = 'Dashboard Relatórios';
      $header_page_buttons = '<button onClick="exportar_csv()" class="bg-transparent transition-all hover:bg-green-700 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-700 hover:border-transparent rounded">Exportar relatório completo (DESENVOLVIMENTO)</button>';
      include('includes/header_page.php');
    ?>
<div class="w-full p-4 flex flex-wrap">
    <script src="js/chart.js"></script>


        <div class="w-full xl:w-1/2 justify-center">
          <div class="container-box">
            <div class="card-relatorios">
              <div class="titulo-relatorio">
              <h2 class="p-4 text-lg font-bold leading-7 text-gray-900 sm:truncate sm:text-xl sm:tracking-tight">Visitas x Clicks de interesse x Compras (MÊS ATUAL)</h2>
                <?php include('includes/relatorios/line-chart.php'); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full xl:w-1/2 justify-center">
          <div class="container-box">
            <div class="card-relatorios">
              <div class="titulo-relatorio" id="div-line-day-chart">
              <h2 class="p-4 text-lg font-bold leading-7 text-gray-900 sm:truncate sm:text-xl sm:tracking-tight">Visitas x Clicks de interesse x Compras (GERAL)</h2>
                <?php include('includes/relatorios/line-day-chart.php'); ?>
              </div>
            </div>
          </div>
        </div>
        <!-- <div class="w-full xl:w-1/2 justify-center">
          <div class="container-box">
            <div class="card-relatorios">
              <div class="titulo-relatorio" id="div-bar-chart">
              <h2 class="p-4 text-lg font-bold leading-7 text-gray-900 sm:truncate sm:text-xl sm:tracking-tight">5 produtos com mais clicks de interesse</h2>
                <?php // include('includes/relatorios/bar-chart.php'); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full xl:w-1/2 justify-center">
          <div class="container-box">
            <div class="card-relatorios">
              <div class="titulo-relatorio" id="div-bar-chart2">
              <h2 class="p-4 text-lg font-bold leading-7 text-gray-900 sm:truncate sm:text-xl sm:tracking-tight">5 cidades com mais clicks de interesse (SEM LOJA)</h2>
                <?php // include('includes/relatorios/bar-chart2.php'); ?>
              </div>
            </div>
          </div>
        </div> -->
  </body>

  <script src="js/dashboard_relatorios.js"></script>