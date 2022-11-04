<?php

      if (!isset($_SESSION)) session_start();
      if (!isset($_SESSION['nome_usuario'])) { session_destroy(); }
      if($_SESSION['nome_usuario'] == null || $_SESSION['permissoes'] == null || (!$_SESSION['admin'] == 'S')){
        $URL_ATUAL = explode("admin/", $_SERVER["REQUEST_URI"]);
        header("Location: index.php?redir=".$URL_ATUAL[1]."");
        die();
      }


      header("Content-type: text/html; charset=utf-8");

      include_once('includes/connections.php');

      $perfil_usuario = trim($_SESSION['tipo_perfil']);

      $sql = "
        SELECT
          registro        AS REGISTRO, 
          descricao       AS DESCRICAO
        FROM
          in_sistema_perfil
        WHERE 
          descricao <> '$perfil_usuario'
        AND 
          status = 'S'
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $result_perfis = $pdo->query($sql);

      $sql = "
        SELECT
          registro        AS REGISTRO,
          permissao       AS DESCRICAO
        FROM
          in_sistema_perm
        ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $result_permissoes = $pdo->query($sql); 

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
   <title>Dashboard Liberar Acessos | Lojas Deltasul</title>
   <link rel="stylesheet" type="text/css" href="css/styles.css<?= '?'.bin2hex(random_bytes(50))?>">
</head>
<body class="flex background-white dark:bg-gray-800">

<?php include('includes/dropdown_menu.php') ?>

<div class="w-full ml-auto flex flex-wrap mt-14 xl:mt-0 xl:w-10/12 xl:min-h-screen content-start">

<?php $header_page_1 = 'Liberar Acessos';
      $header_page_1_url = 'dashboard_liberar_acessos.php';
      $header_page_name = 'Dashboard Liberar Acessos';
      include('includes/header_page.php');
    ?>

<div class="w-full p-4 flex flex-wrap">
      <div class="w-full">
      <form class="form divide-y divide-gray-300">
         <div class="divide-y divide-gray-300">
            <div>
              <div>
                  <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-8">
                    <div class="sm:col-span-8">
                        <div class="mt-1">

                          <input type="text" onChange="autocomplete_perm(this)" list="item_perfil_nameOption" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" name="item_perfil_name" id="item_perfil_id" autocomplete = "off" placeholder="Selecione o Perfil" >
                            <datalist id="item_perfil_nameOption" autocomplete = "off">
                              <?php while($row_itens_perfis = $result_perfis->fetch()){ ?>
                              <option autocomplete="off" value="<?= trim($row_itens_perfis['REGISTRO']) ?>"><?= trim($row_itens_perfis['DESCRICAO']) ?></option>
                              <?php } ?>
                            </datalist>
                            <input type="hidden" name="item_perfil_name" id="item_perfil_id_hidden">
                            <script>
                              document.querySelector('#item_perfil_id').addEventListener('input', function(e) {
                                  var input = e.target,	
                                      list = input.getAttribute('list'),
                                      options = document.querySelectorAll('#' + list + ' option[value="'+input.value+'"]'),
                                      hiddenInput = document.getElementById(input.getAttribute('id') + '_hidden');
                              
                                  if (options.length > 0) {
                                      hiddenInput.value = input.value;
                                    input.value = options[0].innerText;
                                    }
                              
                              });
                                  
                              $(document).on('input', '#item_perfil_id', function() {
                                  $('#ck_item_perfil_name').prop("checked", false);

                              });
                                  
                                  keepDatalistOptions('#item_perfil_id');
                              
                                  function keepDatalistOptions(selector='') {
                                    // select all input fields by datalist attribute or by class/id
                                    selector = !selector ? "input[list]" : selector;
                                    let datalistInputs = document.querySelectorAll(selector);
                                    //if (datalistInputs.length) {
                                      for (let i = 0; i < datalistInputs.length; i++) {
                                        let input = datalistInputs[i];
                                        input.addEventListener("change", function (e) {
                                          e.target.setAttribute("value", e.target.value);
                                        });
                                        input.addEventListener("focus", function (e) {
                                          e.target.setAttribute("value", e.target.value);
                                          e.target.value = "";
                                        });
                                        // input("blur", function (e) {
                                        //   e.target.value = e.target.getAttribute("value");
                                        // });
                              
                                      }
                                    //}
                                  }
                            </script>

                        </div>
                    </div>
                  </div>
               </div>

               <div class="py-8">
                  <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-8">
                     <div class="sm:col-span-2">
                        <div class="mt-1">
                          <div class="relative flex items-start">
                            <div>
                              <label class="text-base font-medium text-gray-900">Administrador</label>
                              <p class="text-sm leading-5 text-gray-500">Este perfil vai ter nível administrador?</p>
                              <fieldset class="mt-4">
                                <div class="space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
                                  <div class="flex items-center">
                                    <input name="admin_permissao" value="N" type="radio" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label class="ml-3 block text-sm font-medium text-gray-700"> Não </label>
                                  </div>

                                  <div class="flex items-center">
                                    <input name="admin_permissao" value="S" type="radio" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label class="ml-3 block text-sm font-medium text-gray-700"> Sim </label>
                                  </div>
                                </div>
                              </fieldset>
                            </div>
                          </div>
                        </div>
                     </div>
                     <div class="sm:col-span-5"></div>
                     <div class="sm:col-span-1">
                      <a class="cursor-pointer bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition all" onClick="todas_perm();">Todas Permissões</a>
                     </div>
                  </div>
               </div>


               <div class="py-8">
                  <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-8">
                  <?php while($row_itens_permissoes = $result_permissoes->fetch()){ ?>
                     <div class="sm:col-span-2">
                        <div class="mt-1">
                          <div class="relative flex items-start">
                              <div class="flex items-center h-5">
                                  <input name="itens_permissao[]" value="<?= $row_itens_permissoes['REGISTRO'] ?>" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                              </div>
                              <div class="ml-3 text-sm">
                                  <label for="<?= $row_itens_permissoes['DESCRICAO'] ?>" class="font-medium text-gray-700"> <?= $row_itens_permissoes['DESCRICAO'] ?> </label>
                              </div>
                          </div>
                        </div>
                     </div>
                     <?php  $xx++; } ?>
                  </div>
               </div>

            </div>
            <input hidden name="acao" value="cadastro_permissoes">
            <div class="pt-4 pb-4">
               <div class="flex">
                  <button type="reset" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition all" onClick="window.location.reload()">Limpar</button>
                  <a class="cursor-pointer ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition all text-center" onClick="envia_form();">Enviar</a>
               </div>
            </div>
      </form>
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

   <script src="js/dashboard_liberar_acessos.js"></script>

</html>