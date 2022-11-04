<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['nome_usuario'])) { session_destroy(); }
    if($_SESSION['nome_usuario'] == null || $_SESSION['permissoes'] == null || (!in_array("3", $_SESSION['permissoes']))){
      $URL_ATUAL = explode("admin/", $_SERVER["REQUEST_URI"]);
      header("Location: index.php?redir=".$URL_ATUAL[1]."");
      die();
    }

    header("Content-type: text/html; charset=utf-8");

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
  <title>Banners | Lojas Deltasul</title>
  <link rel="stylesheet" type="text/css" href="css/styles.css<?= '?'.bin2hex(random_bytes(50))?>">
</head>
<body class="flex background-white dark:bg-gray-800">

<?php include('includes/dropdown_menu.php') ?>

<div class="w-full ml-auto flex flex-wrap mt-14 xl:mt-0 xl:w-10/12 xl:min-h-screen content-start">

<?php $header_page_1 = 'Slider';
      $header_page_1_url = 'dashboard_slider.php';
      $header_page_2 = 'Criar Slider';
      $header_page_2_url = 'dashboard_slider_cadastro.php';
      $header_page_name = 'Editor';
      include('includes/header_page.php') ?>

<div class="2xl:w-full xl:w-2/4 px-8 flex flex-wrap">
<form id="form" class="w-full form">
<input type="text" name="nome" autocomplete="off" id="nome" maxlength="255" value="<?= $user_ad ?>" class="hidden">
  <div>
    <div>
      <div class="mt-6 space-y-6 sm:mt-5 sm:space-y-5">
        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4">
          <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Descrição</label>
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
          <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Data de exibição</label>
          <div class="mt-1 sm:col-span-2 sm:mt-0">
		  	<div class="flex max-w-lg rounded-md shadow-sm">
				<input type="date" name="data_exibicao" id="data_exibicao" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
			</div>
		  </div>
        </div>

		<div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-4">
          <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Data de expiração</label>
          <div class="mt-1 sm:col-span-2 sm:mt-0">
		  	<div class="flex max-w-lg rounded-md shadow-sm">
				<input type="date" name="data_expiracao" id="data_expiracao" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
			</div>
		  </div>
        </div>

        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-4">
          <label for="cover-photo" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Imagem Desktop</label>
          <div class="mt-1 sm:col-span-2 sm:mt-0">
            <div class="flex max-w-lg justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-4 pb-6">
              <div class="space-y-1 text-center">
                <img id="input_img_exib" class="max-w-full h-auto" style="display: none">
                <svg id="input_img_exib_svg" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex justify-center mt-8 text-sm text-gray-600">
                  <label for="arquivo" class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 hover:text-blue-500">
                    <span>Carregar</span>
                    <input id="arquivo" name="arquivo" type="file" OnChange="readURL(this)" class="sr-only">
                  </label>
                  <p class="pl-1">a imagem aqui</p>
                </div>
                <p class="text-xs text-gray-500">PNG, JPG ou WEBP até 1MB</p>
              </div>
            </div>
          </div>
        </div>

        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-4">
          <label for="cover-photo" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Imagem Mobile</label>
          <div class="mt-1 sm:col-span-2 sm:mt-0">
            <div class="flex max-w-lg justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-4 pb-6">
              <div class="space-y-1 text-center">
                <img id="input_img_exib_mobile" class="max-w-full h-auto" style="display: none">
                <svg id="input_img_exib_svg_mobile" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex justify-center mt-8 text-sm text-gray-600">
                  <label for="arquivo_mobile" class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 hover:text-blue-500">
                    <span>Carregar</span>
                    <input id="arquivo_mobile" name="arquivo_mobile" type="file" OnChange="readURL_mobile(this)" class="sr-only">
                  </label>
                  <p class="pl-1">a imagem aqui</p>
                </div>
                <p class="text-xs text-gray-500">PNG, JPG ou WEBP até 1MB</p>
              </div>
            </div>
          </div>
        </div>
        

      </div>
    </div>
  </div>
  <input class="hidden" name="acao" value="slider_cadastro">
  <div class="pt-4 pb-4">
    <div class="flex justify-end">
      <input type="button" value="Voltar" onclick="history.back()" class="cursor-pointer rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
      <input type="button" value="Salvar" onClick="enviar_dados()" class="cursor-pointer ml-3 inline-flex justify-center rounded-md border border-transparent bg-green-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
    </div>
  </div>
</form>

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
  </div>
</body>

<script src="js/dashboard_slider_cadastro.js"></script>

</html>