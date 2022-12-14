<?php 
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['nome_usuario'])) { session_destroy(); }
    if($_SESSION['nome_usuario'] == null || $_SESSION['permissoes'] == null || (!in_array("10", $_SESSION['permissoes']))){
      $URL_ATUAL = explode("admin/", $_SERVER["REQUEST_URI"]);
      header("Location: index.php?redir=".$URL_ATUAL[1]."");
      die();
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
   <title>Inserir Produto Agregado | Lojas Deltasul</title>
   <link rel="stylesheet" type="text/css" href="css/styles.css<?= '?'.bin2hex(random_bytes(50))?>">
</head>
<body class="flex background-white dark:bg-gray-800">

<?php include('includes/dropdown_menu.php') ?>

<div class="w-full ml-auto flex flex-wrap mt-14 xl:mt-0 xl:w-10/12 xl:min-h-screen content-start">

<?php $header_page_1 = 'Agregar Itens';
      $header_page_1_url = 'dashboard_agregar_itens.php';
      $header_page_name = 'Dashboard Agregar Itens';
      include('includes/header_page.php');
    ?>

<div class="w-full p-4 flex flex-wrap">
      <div class="w-full">
      <div class="space-y-8 divide-y divide-gray-200">
         <div class="divide-y divide-gray-200">
              <div class="py-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-8" id="itens_ficha">
                <div class="sm:col-span-8" style="display: flex; flex-wrap: wrap">
                <label for="ref" class="block text-sm font-medium text-gray-700" style="width: 100%">REF</label>
                  <div style="width: 90%">
                    <input type="text" name="registro" id="registro" autocomplete="off" class="py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">
                  </div>
                  <div style="width: 10%; display: flex; justify-content: center; align-items: center; cursor: pointer">
                    <i class='material-icons cursor-pointer text-3xl' onclick="pesq_cod()">search</i>
                  </div>
                </div>
                <div id="nome_marca" style="display: none">
                  <div class="sm:col-span-4">
                  <label class="block text-sm font-medium text-gray-700">NOME ITEM</label>
                    <div class="mt-1">
                      <input type="text" name="nome_item" id="nome_item" class="py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" disabled>
                    </div>
                  </div>
                  <div class="sm:col-span-4">
                  <label class="block text-sm font-medium text-gray-700">MARCA ITEM</label>
                    <div class="mt-1">
                      <input type="text" name="marca_item" id="marca_item" class="py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" disabled>
                    </div>
                  </div>
                  <form name="form" id="form" class="form sm:col-span-8" style="display: contents" method="post" enctype="multipart/form-data">
                    <input hidden name="acao" value="cadastro_itens_agregados">
                    <input type="text" hidden name="referencia" id="referencia">
                    <input type="text" hidden name="produto" id="produto">
                    <div class="sm:col-span-4">
                      <label class="block text-sm font-medium text-gray-700">PRODUTO A VINCULAR</label>
                        <div class="mt-1">
                          <input type="text" list="result_busca" id="txtBusca" name="referenciaagregado" onkeyup="list_pesquisa()" class="py-2 px-4 inline-flex w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md" placeholder="Digite o produto" autocomplete="off"/>
                          <datalist id="result_busca" autocomplete="off">
                          </datalist>
                        </div>
                    </div>
                  </form>
                </div>
              </div>
            <div class="pt-4" id="footer" style="display: none">
               <div class="flex">
                <div class="flex" style="width: 50%">
                  <button type="reset" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition all" onClick="window.location.reload()">Limpar</button>
                  <a style="cursor: pointer" class="cursor-pointer ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" onClick="envia_form();">Salvar Agregados</a>
                </div>
              </div>
            </div>
      </div>
      </div>
      </div>
   </body>


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

    <script src="js/dashboard_agregar_itens.js<?= '?'.bin2hex(random_bytes(50))?>"></script>

</html>