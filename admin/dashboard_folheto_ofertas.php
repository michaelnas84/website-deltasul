<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['nome_usuario'])) { session_destroy(); }
    if($_SESSION['nome_usuario'] == null || $_SESSION['permissoes'] == null || (!in_array("11", $_SESSION['permissoes']))){
      $URL_ATUAL = explode("admin/", $_SERVER["REQUEST_URI"]);
      header("Location: index.php?redir=".$URL_ATUAL[1]."");
      die();
    }

      $filter = $_GET['filter'];
      header("Content-type: text/html; charset=utf-8");
      $pagina = "admin";
      
      
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
        count(REGISTRO)                 as totrows
      FROM
        web_folhetooferta
    ";
      if($filter == 'ativos'){
      $sql_totrows .= "
      WHERE
        (status = 'S' AND primeiroacesso = 'N' AND bloqueado = 'N')
      ";
      }
      if($filter == 'excluidos_vencidos'){
      $sql_totrows .= "
      WHERE
        (status = 'N' AND primeiroacesso = 'N' AND bloqueado = 'S')
      ";
      }
      if($filter == 'excluidos'){
      $sql_totrows .= "
      WHERE
        (status = 'S' AND primeiroacesso = 'N' AND bloqueado = 'S')
      ";
      }
      if($filter == 'atencao'){
      $sql_totrows .= "
      WHERE
        (status = 'S' AND primeiroacesso = 'S' AND bloqueado = 'N')
      ";
      }
    $result_totrows = $pdo->query($sql_totrows);
    $total_rows = $result_totrows->fetch();
    $total_pages = ceil($total_rows["totrows"] / $no_of_records_per_page);

      $sql = "
      SELECT
          registro                      AS REGISTRO,
          descricao                     AS DESCR,
          url                           AS URL,
          dataexibe                     AS DATA_EXIBICAO,
          dataexpira                    AS DATA_EXPIRACAO,
          primeiroacesso                AS PRIMEIRO_ACESSO,
          bloqueado                     AS BLOQUEADO,
          status                        AS STATUS,
          usuario                       AS USUARIO
      FROM
          web_folhetooferta
      "; 
      if($filter == 'ativos'){
      $sql .= "
      WHERE
        (status = 'S' AND primeiroacesso = 'N' AND bloqueado = 'N')
      ";
      }
      if($filter == 'excluidos_vencidos'){
      $sql .= "
      WHERE
        (status = 'N' AND primeiroacesso = 'N' AND bloqueado = 'S')
      ";
      }
      if($filter == 'excluidos'){
      $sql .= "
      WHERE
        (status = 'S' AND primeiroacesso = 'N' AND bloqueado = 'S')
      ";
      }
      if($filter == 'atencao'){
      $sql .= "
      WHERE
        (status = 'S' AND primeiroacesso = 'S' AND bloqueado = 'N')
      ";
      }

      $sql .= "
      ORDER BY
        dataexpira DESC
      LIMIT
          $offset,
          $no_of_records_per_page
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      $tot_folheto_ofertas = 0;

      $hoje = date('Y-m-d');    
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
      <title>Dashboard Folheto Ofertas | Lojas Deltasul</title>
      <link rel="stylesheet" type="text/css" href="css/styles.css<?= '?'.bin2hex(random_bytes(50))?>">
    </head>
    <body class="flex background-white dark:bg-gray-800">

    <?php include('includes/dropdown_menu.php') ?>

    <div class="w-full ml-auto flex flex-wrap mt-14 xl:mt-0 xl:w-10/12 xl:min-h-screen content-start">

    <?php $header_page_1 = 'Folheto Ofertas';
          $header_page_1_url = 'dashboard_folheto.php';
          $header_page_name = 'Dashboard Folheto Ofertas';
          $header_page_buttons = '<a data-modal-toggle="defaultModal" OnClick="exibe_modal_limpo()" class="pointer bg-transparent transition-all hover:bg-green-700 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-700 hover:border-transparent rounded m-1">Criar</a>
          <a class="bg-transparent transition-all hover:bg-green-700 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-700 hover:border-transparent rounded m-1" href="?filter=todos">Todos</a>
          <a class="bg-transparent transition-all hover:bg-green-700 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-700 hover:border-transparent rounded m-1" href="?filter=ativos">Ativos</a>
          <a class="bg-transparent transition-all hover:bg-red-700 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-700 hover:border-transparent rounded m-1" href="?filter=excluidos_vencidos">Vencidos</a>
          <a class="bg-transparent transition-all hover:bg-red-700 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-700 hover:border-transparent rounded m-1" href="?filter=excluidos">Excluidos</a>
          <a class="bg-transparent transition-all hover:bg-yellow-700 text-yellow-700 font-semibold hover:text-white py-2 px-4 border border-yellow-700 hover:border-transparent rounded m-1" href="?filter=atencao">Necessitam aten????o</a>';
          include('includes/header_page.php');
        ?>

    <div class="w-full p-4 flex flex-wrap">
    <div class="w-full overflow-hidden bg-white shadow sm:rounded-md">
      <ul role="list" class="divide-y divide-gray-200">
      <?php foreach ($stmt as $row_folheto_ofertas){
                  $css_yellow = "bg-yellow-100 text-yellow-800";
                  $css_red    = "bg-red-100 text-red-800";
                  $css_green  = "bg-green-100 text-green-800";
                  if ($row_folheto_ofertas['PRIMEIRO_ACESSO'] == 'S' && $row_folheto_ofertas['DATA_EXPIRACAO'] >= $hoje) {
                    $css_aviso  = $css_yellow;
                    $text_aviso = "Necessita aten????o";
                  } else if ($row_folheto_ofertas['PRIMEIRO_ACESSO'] == 'S' && $row_folheto_ofertas['DATA_EXPIRACAO'] <= $hoje) { 
                    $text_aviso =  "Vencido";
                  } else if ($row_folheto_ofertas['BLOQUEADO'] == 'S' && $row_folheto_ofertas['STATUS'] == 'N' && $row_folheto_ofertas['PRIMEIRO_ACESSO'] == 'N') { 
                    $css_aviso  =  $css_red;
                    $text_aviso =  "Excluido/Vencido";
                  } else if ($row_folheto_ofertas['BLOQUEADO'] == 'N' && $row_folheto_ofertas['DATA_EXPIRACAO'] >= $hoje) { 
                    $css_aviso  =  $css_green;
                    $text_aviso =  "Ativo";
                  } else if ($row_folheto_ofertas['BLOQUEADO'] == 'N' && $row_folheto_ofertas['DATA_EXPIRACAO'] <= $hoje) { 
                    $css_aviso  =  $css_red;
                    $text_aviso =  "Vencido";
                  } else if ($row_folheto_ofertas['STATUS'] == 'S') { 
                    $css_aviso  =  $css_red;
                    $text_aviso =  "Excluido";
                  } else { 
                    $css_aviso  =  $css_red;
                    $text_aviso =  "FORA DO PADR??O, EXCLUIR";
          } ?>
        <li>
          <a class="block hover:bg-gray-50">
            <div class="px-4 py-4 sm:px-6">
              <div class="flex items-center justify-between">
                <p class="xl:truncate text-sm font-medium text-blue-600 hover:text-blue-800 transition-all"><?= $row_folheto_ofertas['DESCR'] ?></p>
                <div class="ml-2 flex flex-shrink-0">
                  <?php if($text_aviso != 'Excluido/Vencido'){ ?>
                  <a onClick="confirmar(<?=$row_folheto_ofertas['REGISTRO']?>)" class="cursor-pointer inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">Confirmar</a>
                  <a data-modal-toggle="defaultModal" OnClick="exibe_modal_altera('<?=$row_folheto_ofertas['REGISTRO']?>', '<?= $row_folheto_ofertas['DESCR'] ?>', '<?= $row_folheto_ofertas['URL'] ?>', '<?= $row_folheto_ofertas['DATA_EXIBICAO'] ?>', '<?= $row_folheto_ofertas['DATA_EXPIRACAO'] ?>')" class="mx-2 inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">Editar</a>
                  <a onClick="excluir(<?=$row_folheto_ofertas['REGISTRO']?>)" class="cursor-pointer inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">Excluir</a>
                  <?php } else { ?>
                    <a>&nbsp;</a>
                  <?php } ?>
                </div>
              </div>
              <div class="mt-2 sm:flex sm:justify-between">
                <div class="flex flex-wrap">
                  <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                    <!-- Heroicon name: mini/map-pin -->
                    <svg class="w-5 h-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    <?php if($row_folheto_ofertas['URL'] != null){ ?>
                      <a target="_blank" href="<?= $row_folheto_ofertas['URL'] ?>"><?= $row_folheto_ofertas['URL'] ?></a>
                    <?php }?>
                  </p>
                  <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 ml-2 sm:ml-6">
                    <a class='inline-flex rounded-full px-2 text-xs font-semibold leading-5 <?= $css_aviso ?>' style='cursor: default '><?= $text_aviso ?></a>
                  </p>
                </div>
                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                  <!-- Heroicon name: mini/calendar -->
                  <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
                  </svg>
                  <p>
                    <time><?= date('d/m/Y', strtotime($row_folheto_ofertas['DATA_EXIBICAO'])) ?> ?? <?= date('d/m/Y', strtotime($row_folheto_ofertas['DATA_EXPIRACAO'])) ?></time>
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
        <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { if($filter != null){ echo "?filter=".$filter."&pageno=".($pageno + 1); } else { echo "?pageno=".($pageno + 1); } } ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Pr??xima </a>
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
              <span class="sr-only">Pr??xima</span>

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
                      Cadastrar Folheto de Ofertas
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="defaultModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Fechar</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <form id="form_cad">
                      <div class="space-y-6 sm:space-y-5">
                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4">
                          <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Descri????o</label>
                          <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <div class="flex max-w-lg rounded-md shadow-sm">
                              <input type="text" name="descr" id="descr" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                          </div>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-4">
                          <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">URL</label>
                          <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <div class="flex max-w-lg rounded-md shadow-sm">
                              <input type="text" name="url" id="url" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                          </div>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-4">
                          <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Data de exibi????o</label>
                          <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <div class="flex max-w-lg rounded-md shadow-sm">
                              <input type="date" name="data_exibicao" id="data_exibicao" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                          </div>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-4">
                          <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Data de expira????o</label>
                          <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <div class="flex max-w-lg rounded-md shadow-sm">
                              <input type="date" name="data_expiracao" id="data_expiracao" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                          </div>
                        </div>
                      </div>
                      <input name="registro" id="registro" hidden>
                      <input name="acao" id="acao" value="folheto_cadastro" hidden>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                    <button data-modal-toggle="defaultModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Fechar</button>
                    <input type="button" value="Salvar" onClick="envia_form()" class="cursor-pointer ml-3 inline-flex justify-center rounded-md border border-transparent bg-green-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-100 absolute z-50" style="min-height: 320px">
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
  
  <script src="js/dashboard_folheto_ofertas.js"></script>