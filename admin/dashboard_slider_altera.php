<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['nome_usuario'])) { session_destroy(); }
    if($_SESSION['nome_usuario'] != null && $_SESSION['permissoes'] != null && (in_array("3", $_SESSION['permissoes']))){


      header("Content-type: text/html; charset=utf-8");
	  $pagina = "admin";

	  include_once('includes/connections.php');


	  $REF                = $_GET['ref']; 

	  $sql = "
	    SELECT
			REGISTRO                      AS REGISTRO,
			descricao                     AS DESCR,
			urlarq                        AS URL_ARQ,
			url                           AS URL,
			dataexibe                     AS DATA_EXIBICAO,
			dataexpira                    AS DATA_EXPIRACAO,
			primeiroacesso                AS PRIMEIRO_ACESSO,
			bloqueado                     AS BLOQUEADO,
			status                        AS STATUS
		FROM
			web_slideshow
		WHERE
	  	  REGISTRO = $REF
    AND
      status = 'S'
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);

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

<?php $header_page_1 = 'Slider'; $header_page_2 = 'Editar Slider'; $header_page_3 = 'Slider '.$REF; $header_page_name = 'Editor';  include('includes/header_page.php') ?>

<div class="2xl:w-full xl:w-2/4 px-8 flex flex-wrap">
<form class="w-full form">
<input type="text" name="nome" autocomplete="off" id="nome" maxlength="255" value="<?= $user_ad ?>" class="hidden">
  <div>
    <div>
	  <?php foreach ($stmt as $row_slider){ ?>
      <div class="mt-6 space-y-6 sm:mt-5 sm:space-y-5">
        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:pt-4">
          <label for="username" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Registro</label>
          <div class="mt-1 sm:col-span-2 sm:mt-0">
            <div class="flex max-w-lg rounded-md shadow-sm">
				<input readonly type="text" name="registro" id="registro" value="<?= $row_slider['REGISTRO'] ?>" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
          </div>
        </div>

        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-4">
          <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Descrição</label>
          <div class="mt-1 sm:col-span-2 sm:mt-0">
		  	<div class="flex max-w-lg rounded-md shadow-sm">
				<input type="text" name="descr" id="descr" value="<?= $row_slider['DESCR'] ?>" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
			</div>
		  </div>
        </div>

		<div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-4">
          <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">URL</label>
          <div class="mt-1 sm:col-span-2 sm:mt-0">
		  	<div class="flex max-w-lg rounded-md shadow-sm">
				<input type="text" name="url" id="url" value="<?= $row_slider['URL'] ?>" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
			</div>
		  </div>
        </div>

		<div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-4">
          <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Data de exibição</label>
          <div class="mt-1 sm:col-span-2 sm:mt-0">
		  	<div class="flex max-w-lg rounded-md shadow-sm">
				<input type="date" name="data_exibicao" id="data_exibicao" value="<?= $row_slider['DATA_EXIBICAO'] ?>" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
			</div>
		  </div>
        </div>

		<div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-4">
          <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Data de expiração</label>
          <div class="mt-1 sm:col-span-2 sm:mt-0">
		  	<div class="flex max-w-lg rounded-md shadow-sm">
				<input type="date" name="data_expiracao" id="data_expiracao" value="<?= $row_slider['DATA_EXPIRACAO'] ?>" class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
			</div>
		  </div>
        </div>

        <div class="sm:grid sm:grid-cols-3 sm:items-center sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-4">
          <label for="photo" class="block text-sm font-medium text-gray-700">Imagem</label>
          <div class="mt-1 sm:col-span-3 sm:mt-0">
            <div class="px-8 flex items-center">
			  	    <img src="../img_base/slider/<?=$row_slider['URL_ARQ'] ?>" class="max-w-full h-auto"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <input class="hidden" name="acao" value="slider_alterar">
<?php } ?>
  <div class="pt-4">
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

<script src="js/dashboard_slider_altera.js"></script>

</html>
<?php } else { 
    $URL_ATUAL = explode("admin/", $_SERVER["REQUEST_URI"]);
    header("Location: index.php?redir=".$URL_ATUAL[1]."");
    die(); } ?>