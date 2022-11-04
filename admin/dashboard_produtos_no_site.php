<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['nome_usuario'])) { session_destroy(); }
    if($_SESSION['nome_usuario'] == null || $_SESSION['permissoes'] == null || (!in_array("2", $_SESSION['permissoes']))){
      $URL_ATUAL = explode("admin/", $_SERVER["REQUEST_URI"]);
      header("Location: index.php?redir=".$URL_ATUAL[1]."");
      die();
    }


      header("Content-type: text/html; charset=utf-8");

      $pagina = "admin";
      $hoje = date('Y-m-d');

      include_once('includes/connections.php');


      if (isset($_GET['pageno'])) {
      $pageno = $_GET['pageno'];
      } else {
          $pageno = 1;
      }
      $no_of_records_per_page = 30;
      $offset = ($pageno-1) * $no_of_records_per_page; 

      $sql_totrows = "
        SELECT
          count(referencia)                 as totrows
        FROM
          web_produto
        WHERE
          descricaoweb IS NOT NULL
      ";
      $result_totrows = $pdo->query($sql_totrows);
      $total_rows = $result_totrows->fetch();
      $total_pages = ceil($total_rows["totrows"] / $no_of_records_per_page);

      
      $sql = "
      SELECT
        A.categoria                        AS CATEGORIA,
        A.subcategoria                     AS SUB_CATEGORIA,
        A.referencia                       AS REF,
        A.descricaoweb                     AS NOME_EXIBE,
        A.marca                            AS MARCA,
        A.cadastro                         AS CADASTRO,
        B.url  							               AS URL_ARQ,
        A.status                           AS STATUS
      FROM
        web_produto A,
        web_produtoimagem B
      WHERE
        (A.referencia = B.referencia AND B.sequencia = 1)
      AND
        (A.descricaoweb IS NOT NULL AND B.status = 'S')
      ORDER BY
        A.status
      LIMIT
        $offset,
        $no_of_records_per_page
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $tot_itens=0;
      $stmt = $pdo->query($sql);
      while($row_produtos = $stmt->fetch()){
        $arr_dados['categoria'][$tot_itens]              = $row_produtos['CATEGORIA'];
        $arr_dados['sub_categoria'][$tot_itens]          = $row_produtos['SUB_CATEGORIA'];
        $arr_dados['registro'][$tot_itens]               = $row_produtos['REGISTRO'];
        $arr_dados['ref'][$tot_itens]                    = $row_produtos['REF'];
        $arr_dados['nome_exibe'][$tot_itens]             = $row_produtos['NOME_EXIBE'];
        $arr_dados['marca'][$tot_itens]                  = $row_produtos['MARCA'];
        $arr_dados['cadastro'][$tot_itens]               = $row_produtos['CADASTRO'];
        $arr_dados['status'][$tot_itens]                 = $row_produtos['STATUS'];
        $arr_dados['URL_ARQ'][$tot_itens]                = $row_produtos['URL_ARQ'];
        $tot_itens++;
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
   <script src="js/flowbite.js"></script>
  <title>Produtos Ativos no Site | Lojas Deltasul</title>
  <link rel="stylesheet" type="text/css" href="css/styles.css<?= '?'.bin2hex(random_bytes(50))?>">
</head>
<body class="flex background-white dark:bg-gray-800">

<?php include('includes/dropdown_menu.php') ?>

<div class="w-full ml-auto flex flex-wrap mt-14 xl:mt-0 xl:w-10/12 xl:min-h-screen content-start">

<?php $header_page_1 = 'Produtos no Site';
      $header_page_1_url = 'dashboard_produtos_no_site.php';
      $header_page_name = 'Dashboard Produtos no Site';
      $header_page_buttons = '<button onClick="exportar_csv()" class="bg-transparent transition-all hover:bg-green-700 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-700 hover:border-transparent rounded">Exportar</button>';
      include('includes/header_page.php');
    ?>

<div class="w-full p-4 flex flex-wrap">
<div class="w-full overflow-hidden bg-white shadow sm:rounded-md">
  <ul role="list" class="divide-y divide-gray-200">
  <?php for($xy=0; $xy<$tot_itens; $xy++){ ?>
    <li>
      <a <?php if(in_array("8", $_SESSION['permissoes'])) { ?>href="dashboard_inserir_ficha_tecnica.php?ref=<?= $arr_dados['ref'][$xy] ?>" <?php } ?> class="block hover:bg-gray-50">
        <div class="px-4 py-4 sm:px-6">
          <div class="flex items-center justify-between">
            <p class="xl:truncate text-sm font-medium text-blue-600 hover:text-blue-800 transition-all"><?= $arr_dados['nome_exibe'][$xy]; ?></p>
            <div class="ml-2 flex flex-shrink-0">
              <?php if($arr_dados['status'][$xy] == 'S') { ?>
              <a class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800 mr-2">Ativo</a>
              <?php } else { ?>
              <a class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800 mr-2">Inativo</a>
              <?php } ?>
              <?php if(in_array("8", $_SESSION['permissoes'])) { ?>
              <a href="dashboard_inserir_ficha_tecnica.php?ref=<?= $arr_dados['ref'][$xy] ?>" class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">Editar</a>
              <?php } ?>
            </div>
          </div>
          <div class="mt-2 sm:flex sm:justify-between">
            <div class="flex">
              <p class="flex items-center text-sm text-gray-500">
                <!-- Heroicon name: mini/users -->
                <svg class="w-5 h-5 mr-1.5 text-gray-400 hidden xl:flex" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <img type="button" data-modal-toggle="defaultModal" onClick="exibe_modal(this)" src="../img_base/produtos/<?= preg_replace('/[^0-9]/', '', $arr_dados['ref'][$xy]) ?>/<?= $arr_dados['URL_ARQ'][$xy] ?>" id="<?= $arr_dados['nome_exibe'][$xy] ?>" class="cursor-pointer w-full xl:w-12 xl:h-10 object-contain rounded-lg mr-1.5"/>
              </p>
              <p class="flex items-center text-sm text-gray-500">
                <!-- Heroicon name: mini/users -->
                <svg class="w-5 h-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                <?= $arr_dados['ref'][$xy]; ?>
              </p>
              <p class="flex items-center text-sm text-gray-500 ml-2 sm:ml-6">
                <!-- Heroicon name: mini/map-pin -->
                <svg class="w-5 h-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                R$<?= $arr_dados['preco_novo'][$xy]; ?> ou <?= $arr_dados['qtd_vezes'][$xy]; ?>x de <?= $arr_dados['valor_parcelado'][$xy]; ?>
              </p>
            </div>
            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
              <!-- Heroicon name: mini/calendar -->
              <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
              </svg>
              <p>
                <time><?= date('d/m/Y', strtotime($arr_dados['cadastro'][$xy])) ?></time>
              </p>
            </div>
          </div>
        </div>
      </a>
    </li>
  <?php } ?>
  </ul>
</div>



<div class="bg-white dark:bg-gray-700 rounded-lg w-full sm:m-4 px-4 py-3 flex items-center justify-between sm:px-6">
  <div class="flex-1 flex justify-between sm:hidden">
    <a href="<?php if($pageno <= 1){ echo '#'; } else { if($filter != null){ echo "?filter=".$filter."&pageno=".($pageno - 1); } else { echo "?pageno=".($pageno - 1); } } ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Anterior </a>
    <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { if($filter != null){ echo "?filter=".$filter."&pageno=".($pageno + 1); } else { echo "?pageno=".($pageno + 1); } } ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Próxima </a>
  </div>
  <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-end">
    <div>
      <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
        <a href="<?php if($pageno <= 1){ echo '#'; } else { if($filter != null){ echo "?filter=".$filter."&pageno=".($pageno - 1); } else { echo "?pageno=".($pageno - 1); } } ?>" <?php if($pageno <= 1){ echo 'hidden'; } ?> class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
          <span class="sr-only">Anterior</span>
          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
        </a>
        
        <?php for($xx=1; $xx <= $total_pages; $xx++){ ?>
        <a href="<?php if($filter != null){ echo "?filter=".$filter."&pageno=".($xx); } else { echo "?pageno=".($xx); } ?>" aria-current="page" class="<?php if($pageno == $xx){ echo 'z-10 bg-blue-50 border-blue-500 text-blue-600'; } else { echo "bg-white border-gray-300 text-gray-500 hover:bg-gray-50"; } ?> relative inline-flex items-center px-4 py-2 border text-sm font-medium"> <?= $xx ?> </a>
        <?php } ?>
        <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { if($filter != null){ echo "?filter=".$filter."&pageno=".($pageno + 1); } else { echo "?pageno=".($pageno + 1); } } ?>" <?php if($pageno >= $total_pages){ echo 'hidden'; } ?> class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
          <span class="sr-only">Próxima</span>

          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
        </a>
      </nav>
    </div>
  </div>
</div>

<!-- Main modal -->
<div id="defaultModal" tabindex="-1" aria-hidden="true" class="bg-gray-600 bg-opacity-75 backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed xl:top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                <h3 id="title-modal" class="text-xl font-semibold text-gray-900 dark:text-white">
                   
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="defaultModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Fechar</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6 flex justify-center">
              <img id="img-modal" style="max-height: 50vh;width: auto;" src="" class="w-full"/>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                <button data-modal-toggle="defaultModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Fechar</button>
            </div>
        </div>
    </div>
</div>


<div class="bg-gray-100 absolute" style="min-height: 320px">
      <div x-data="{ show: false }" aria-live="assertive" class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6">
          <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
            <!-- Notification panel, dynamically insert this into the live region when it needs to be displayed -->
            <div x-show="show" x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="p-4">
                  <div class="flex items-start">
                      <div class="flex-shrink-0" id="notification_svg"></div>
                      <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p id="notification_txt_1" class="text-sm font-medium text-gray-900"></p>
                        <p id="notification_txt_2" class="mt-1 text-sm text-gray-500"></p>
                      </div>
                      <div class="ml-4 flex flex-shrink-0">
                        <button type="button" @click="show = false;" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" x-description="Heroicon name: mini/x-mark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                              <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"></path>
                            </svg>
                        </button>
                        <button id="notification" type="button" @click="show = true; setTimeout(() => show = false, 1800)" class="hidden"></button>
                      </div>
                  </div>
                </div>
            </div>
          </div>
      </div>
    </div>
</body>

<script src="js/dashboard_produtos_no_site.js"></script>