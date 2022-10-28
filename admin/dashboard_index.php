<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['nome_usuario'])) { session_destroy(); }
    if($_SESSION['nome_usuario'] != null && $_SESSION['permissoes'] != null){

      header("Content-type: text/html; charset=utf-8");
        include_once('includes/connections.php');
        include_once('includes/rotinas/rotina_exc_prods.php');
        include_once('includes/rotinas/rotina_exc_slider.php');
        
      $pagina                             = "admin";

      $hoje                               = date('Y-m-d');
      $amanha                             = date('Y-m-d', strtotime("+1 day", strtotime('now')));


      $sql = "
      SELECT
         COUNT(registro)             AS TOT_SLIDERS
      FROM
         web_slideshow
      WHERE
         dataexibe <= CURDATE()
      AND
         dataexpira >= CURDATE()
      AND 
         primeiroacesso = 'N'
      AND 
         bloqueado = 'N'
      AND 
         status = 'S'
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $row_slider = $pdo->query($sql);
      $row_slider = $row_slider->fetch();


      $sql = "
      SELECT
         COUNT(registro)             AS TOT_CLIENTES
      FROM
         web_contatocliente
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $row_contato_cliente = $pdo->query($sql);
      $row_contato_cliente = $row_contato_cliente->fetch();

      $sql = "
      SELECT
         registro                      AS REGISTRO,
         primeiroacesso                AS PRIMEIRO_ACESSO,
         bloqueado                     AS BLOQUEADO,
         excluido                      AS EXCLUIDO
      FROM
         web_avaliacaocliente
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $tot_avaliacoes_ativas=0;
      $tot_avaliacoes=0;
      $stmt = $pdo->query($sql);
      while($row_avaliacoes = $stmt->fetch()){
         $tot_avaliacoes++;
         if($row_avaliacoes['PRIMEIRO_ACESSO'] == 'N' && $row_avaliacoes['BLOQUEADO'] == 'N' && $row_avaliacoes['EXCLUIDO'] == 'N'){
            $tot_avaliacoes_ativas++;
         }
      }


   // $sql = "
   // SELECT
   //     DATA_EXIBICAO               AS DATA_EXIBICAO
   // FROM
   //     PRODUTOS_OFERTA_DIA
   // WHERE
   //    PRIMEIRO_ACESSO = 'N'
   // AND 
   //    BLOQUEADO = 'N'
   // AND 
   //    EXCLUIDO = 'N'
   // ";
   // // echo '<pre>' . $sql . '</pre>'; exit;
   // $result_produtos_oferta_dia = $conn->query($sql);

      $sql = "
      SELECT
         count(registro)                        AS TOT_ITENS_SITE
      FROM
         web_produto
      WHERE
        status = 'S'
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      $tot_itens=0;
      while($row_produtos = $stmt->fetch()){
        $tot_itens_site              = $row_produtos['TOT_ITENS_SITE'];
      }

      $sql = "
      SELECT 
         referencia              AS REF,
         COUNT(referencia)       AS REF_QTD
      FROM
         web_buscaproduto
      GROUP BY 
         referencia
      ORDER BY 
         COUNT(referencia) DESC
      LIMIT
         1
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      $tot_itens=0;
      while($row_produtos_interessantes = $stmt->fetch()){
        $prod_mais_interessante                    = $row_produtos_interessantes['REF'];
        $qtd_prod_mais_interessante                = $row_produtos_interessantes['REF_QTD'];
      }

      $sql = "
      SELECT 
         CONCAT(cidade, ' - ', estado)             AS CIDADE,
         COUNT(cidade)                             AS CIDADE_QTD
      FROM
         web_localacesso
      WHERE 
         pagina = 'index.php'
      GROUP BY 
         cidade
      ORDER BY 
         COUNT(cidade) DESC
      LIMIT
         1
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      $tot_itens=0;
      while($row_cidades_recorrentes = $stmt->fetch()){
        $cidade_mais_interessante                    = $row_cidades_recorrentes['CIDADE'];
        $qtd_cidade_mais_interessante                = $row_cidades_recorrentes['CIDADE_QTD'];
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
  <title>Gerenciador | Lojas Deltasul</title>
  <link rel="stylesheet" type="text/css" href="css/styles.css<?= '?'.bin2hex(random_bytes(50))?>">
</head>
  <body class="flex background-white dark:bg-gray-800">

  <?php include('includes/dropdown_menu.php') ?>

<div class="w-full ml-auto flex flex-wrap mt-14 xl:mt-0 xl:w-10/12 xl:min-h-screen content-start">
   <div class="w-full h-full flex flex-wrap">
      <div class="w-full xl:w-1/4 flex items-center justify-center">
         <div class="p-8">
            <?php if ($row_slider['TOT_SLIDERS'] > 1){ $color = 'color: green;'; } else { $color = 'color: red;'; } ?> 
            <div class="flex flex-wrap justify-center">
               <div class="flex justify-center">
                  <i style="<?= $color ?>" class='material-icons fa fa-home text-9xl cursor-default'>slideshow</i>
               </div>
               <div class="w-full flex flex-wrap my-4">
                  <a class="w-full flex justify-center text-center">Banners/Slider ativos:</a>
                  <a class="w-full flex justify-center text-center" style="<?= $color ?>"><?= $row_slider['TOT_SLIDERS'] ?></a>
                  <?php if(in_array("3", $_SESSION['permissoes'])){ ?>
                  <div class="w-full flex justify-center my-4">
                     <span><svg onclick="location.href='dashboard_slider_cadastro.php'" class="cursor-pointer mx-4 h-5 w-5 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg></span>
                     <span><svg onclick="location.href='dashboard_slider.php'" class="cursor-pointer mx-4 h-5 w-5 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="w-full xl:w-1/4 flex items-center justify-center">
         <div class="p-8">
            <?php $color = 'color: green' ?> 
            <div class="flex flex-wrap justify-center">
               <div class="flex justify-center">
                  <i style="<?= $color ?>" class='material-icons fa fa-home text-9xl cursor-default'>inventory_2</i>
               </div>
               <div class="w-full flex flex-wrap my-4">
                  <a class="w-full flex justify-center text-center">Produtos no site:</a>
                  <a class="w-full flex justify-center text-center" style="<?= $color ?>"><?= $tot_itens_site ?></a>
                  <?php if(in_array("2", $_SESSION['permissoes'])){ ?>
                  <div class="w-full flex justify-center my-4">
                     <span><svg onclick="location.href='dashboard_produtos_no_site.php'" class="cursor-pointer mx-4 h-5 w-5 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>

      <div class="w-full xl:w-1/2 flex items-center justify-center">
         <div class="p-8">
            <div class="flex flex-wrap justify-center">
               <div class="flex justify-center">
                  <i style="color: green;" class='material-icons fa fa-home text-9xl cursor-default'>location_city</i>
               </div>
               <div class="w-full flex flex-wrap my-4">
                  <a class="w-full flex justify-center text-center">Cidade mais recorrente:</a>
                  <a class="w-full flex justify-center text-center" style="color: green;"><?= $cidade_mais_interessante . ' aparece ' . $qtd_cidade_mais_interessante . ' vezes' ?></a>
                  <?php if(in_array("6", $_SESSION['permissoes'])){ ?>
                  <div class="w-full flex justify-center my-4">
                     <span><svg onclick="location.href='dashboard_cidades_recorrentes.php'" class="cursor-pointer mx-4 h-5 w-5 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>


      <div class="w-full xl:w-1/4 flex items-center justify-center">
         <div class="p-8">
            <?php $color = 'color: green;'; ?> 
            <div class="flex flex-wrap justify-center">
               <div class="flex justify-center">
                  <i style="<?= $color ?>" class='material-icons fa fa-home text-9xl cursor-default'>connect_without_contact</i>
               </div>

               <div class="w-full flex flex-wrap my-4">
                  <a class="w-full flex justify-center text-center">Contato de clientes:</a>
                  <a class="w-full flex justify-center text-center" style="color: green;"><?= $row_contato_cliente['TOT_CLIENTES'] ?></a>
                  <?php if(in_array("4", $_SESSION['permissoes'])){ ?>
                  <div class="w-full flex justify-center my-4">
                     <span><svg onclick="location.href='dashboard_contato_clientes.php'" class="cursor-pointer mx-4 h-5 w-5 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="w-full xl:w-1/4 flex items-center justify-center">
         <div class="p-8">
            <?php $color = 'color: green;';?> 
            <div class="flex flex-wrap justify-center">
               <div class="flex justify-center">
                  <i style="<?= $color ?>" class='material-icons fa fa-home text-9xl cursor-default'>insights</i>
               </div>

               <div class="w-full flex flex-wrap my-4">
                  <a class="w-full flex justify-center text-center">Avaliações ativas:</a>
                  <a class="w-full flex justify-center text-center" style="color: green;"><?= $tot_avaliacoes_ativas.'/'.$tot_avaliacoes ?></a>
                  <?php if(in_array("5", $_SESSION['permissoes'])){ ?>
                  <div class="w-full flex justify-center my-4">
                     <span><svg onclick="location.href='dashboard_avaliacoes_clientes.php'" class="cursor-pointer mx-4 h-5 w-5 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>


      <div class="w-full xl:w-1/4 flex items-center justify-center">
         <div class="p-8">
            <?php $color = 'color: green;'; ?>
            <div class="flex flex-wrap justify-center">
               <div class="flex justify-center">
                  <i style="<?= $color ?>" class='material-icons fa fa-home text-9xl cursor-default'>highlight_alt</i>
               </div>

               <div class="w-full flex flex-wrap my-4">
                  <a class="w-full flex justify-center text-center">Produto mais interessante:</a>
                  <a class="w-full flex justify-center text-center" style="color: green;"><?= 'Ref ' . $prod_mais_interessante . ' clicado ' . $qtd_prod_mais_interessante . ' vezes'?></a>
                  <?php if(in_array("7", $_SESSION['permissoes'])){ ?>
                  <div class="w-full flex justify-center my-4">
                     <span><svg onclick="location.href='dashboard_clicks_de_interesse.php'" class="cursor-pointer mx-4 h-5 w-5 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="w-full xl:w-1/4 flex items-center justify-center">
         <div class="p-8">
            <?php $color = 'color: green;';?> 
            <div class="flex flex-wrap justify-center">
               <div class="flex justify-center">
                  <i style="<?= $color ?>" class='material-icons fa fa-home text-9xl cursor-default'>summarize</i>
               </div>

               <div class="w-full flex flex-wrap my-4">
                  <a class="w-full flex justify-center text-center">Relatórios ativos:</a>
                  <a class="w-full flex justify-center text-center" style="color: green;">4</a>
                  <?php if(in_array("1", $_SESSION['permissoes'])){ ?>
                  <div class="w-full flex justify-center my-4">
                     <span><svg onclick="location.href='dashboard_relatorios.php'" class="cursor-pointer mx-4 h-5 w-5 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>

   </div>

</body>
<?php } else { 
    $URL_ATUAL = explode("admin/", $_SERVER["REQUEST_URI"]);
    header("Location: index.php?redir=".$URL_ATUAL[1]."");
    die(); } ?>