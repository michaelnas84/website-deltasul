<?php
      if (!isset($_SESSION)) session_start();
      if (!isset($_SESSION['nome_usuario'])) { session_destroy(); }
      if($_SESSION['nome_usuario'] != null && $_SESSION['permissoes'] != null && (in_array("6", $_SESSION['permissoes']))){


      header("Content-type: text/html; charset=utf-8");
      $pagina = "admin";
      $hoje = date('Y-m-d');

      include_once('includes/connections.php');

      $sql = "
      SELECT 
         cidade               AS CIDADE,
         COUNT(cidade)        AS CIDADE_QTD
      FROM
        web_localacesso
      WHERE 
        pagina = 'index.php'
      GROUP BY 
         cidade
      ORDER BY 
         COUNT(cidade) DESC
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      while($row_cidades_interesse = $stmt->fetch()){
        $cidade_mais_interesse[]                    = $row_cidades_interesse['CIDADE'];
        $qtd_cidade_mais_interesse[]                = $row_cidades_interesse['CIDADE_QTD'];
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
  <title>Cidades Recorrentes | Lojas Deltasul</title>
  <link rel="stylesheet" type="text/css" href="css/styles.css<?= '?'.bin2hex(random_bytes(50))?>">
</head>
<body class="flex background-white dark:bg-gray-800">

<?php include('includes/dropdown_menu.php') ?>

<div class="w-full ml-auto flex flex-wrap mt-14 xl:mt-0 xl:w-10/12 xl:min-h-screen content-start">

<?php $header_page_1 = 'Cidades Recorrentes';
      $header_page_1_url = 'dashboard_cidades_recorrentes.php';
      $header_page_name = 'Dashboard Cidades Recorrentes';
      $header_page_buttons = '<button onClick="exportar_csv()" class="bg-transparent transition-all hover:bg-green-700 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-700 hover:border-transparent rounded mx-1">Exportar</button>';
      include('includes/header_page.php');
    ?>

<div class="w-full p-4 flex flex-wrap">
<div class="w-full overflow-hidden bg-white shadow sm:rounded-md">
  <ul role="list" class="divide-y divide-gray-200">
  <?php for($xy=0; $xy < count($cidade_mais_interesse); $xy++){ ?>
    <li>
      <a class="block hover:bg-gray-50">
        <div class="px-4 py-4 sm:px-6">
          <div class="mt-2 sm:flex sm:justify-between">
            <p class="truncate text-sm font-medium text-blue-600 hover:text-blue-800 transition-all"><?= $cidade_mais_interesse[$xy]; ?></p>
            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
            <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
              <p>
                <time><?= $qtd_cidade_mais_interesse[$xy]; ?></time>
              </p>
            </div>
          </div>
        </div>
      </a>
    </li>
  <?php } ?>
  </ul>
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

<script src="js/dashboard_cidades_recorrentes.js"></script>

<?php } else { 
    $URL_ATUAL = explode("admin/", $_SERVER["REQUEST_URI"]);
    header("Location: index.php?redir=".$URL_ATUAL[1]."");
    die(); } ?>