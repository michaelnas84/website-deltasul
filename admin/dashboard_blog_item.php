<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['nome_usuario'])) { session_destroy(); }
    if($_SESSION['nome_usuario'] == null || $_SESSION['permissoes'] == null || (!in_array("13", $_SESSION['permissoes']))){
      $URL_ATUAL = explode("admin/", $_SERVER["REQUEST_URI"]);
      header("Location: index.php?redir=".$URL_ATUAL[1]."");
      die();
    }

      include_once('includes/connections.php');

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
      <title>Dashboard Blog Item | Lojas Deltasul</title>
      <link rel="stylesheet" type="text/css" href="css/styles.css<?= '?'.bin2hex(random_bytes(50))?>">
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/text-variant-tune@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/footnotes@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/raw@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/underline@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/attaches@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/link-autocomplete@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/personality@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/nested-list@latest"></script>
      <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script>
      <!-- <script src="https://cdn.jsdelivr.net/npm/@vietlongn/editorjs-carousel"></script> -->
      <script src="js/edjsHTML.js"></script>
    </head>
    <body class="flex background-white dark:bg-gray-800">

    <?php include('includes/dropdown_menu.php') ?>

    <div class="w-full ml-auto flex flex-wrap mt-14 xl:mt-0 xl:w-10/12 xl:min-h-screen content-start">

    <?php $header_page_1 = 'Blog';
          $header_page_1_url = 'dashboard_blog.php';
          $header_page_2 = 'Blog Novo Item';
          $header_page_2_url = 'dashboard_blog_item.php';
          $header_page_name = 'Dashboard Blog Novo Item';
          include('includes/header_page.php');
        ?>

  <div class="2xl:w-full xl:w-2/4 px-8 flex flex-wrap">

		  <div class="sm:grid sm:items-start sm:gap-4 sm:border-gray-200 w-full">
      <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2 w-full">Título</label>
        <div class="mt-1 sm:mt-0 w-full">
          <div class="flex rounded-md shadow-sm w-full">
            <input type="text" name="titulo" id="titulo" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
          </div>
        </div>
      </div>

      <div class="container w-full">
          <div id="editorjs"></div>
      </div>
      <script src="js/dashboard_blog_editor.js"></script>


    <div class="pt-4 w-full">
      <div class="flex justify-end">
        <input type="button" value="Voltar" onclick="history.back()" class="cursor-pointer rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <input type="button" value="Salvar" onClick="envia_form()" class="cursor-pointer ml-3 inline-flex justify-center rounded-md border border-transparent bg-green-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
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

  <script src="js/dashboard_blog.js"></script>