<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <?php include('includes/sections/section_head.php'); ?>
   <link rel="stylesheet" type="text/css" href="css/fale_conosco.css<?= '?'.bin2hex(random_bytes(50))?>">
   <title>Fale conosco | Lojas Deltasul</title>
</head>
<?php include('includes/sections/section_header.php'); ?>
    <body class="home-header">
      <main class="main" id="main">
         <section class="breadcrumb">
            <div class="av-container">
               <div class="av-row">
                  <div class="av-col-xs-24">
                     <div class="bread-crumb">
                        <div class="draft-article" style="display: flex; flex-wrap: wrap;">
                           <div class="bloco-faleconosco">
                              <div class="card">
                                 <div style="color: white; width: 100%; text-align: center; font-size: 22px;">
                                    <p type="text">Fale Conosco</p>
                                 </div>
                              </div>
                              <div class="card" style="background: #083b76">
                                 <div style="color: white; width: 100%; text-align: center; font-size: 22px;">
                                    <b style="margin: 8px 0px">Atendimento</b>
                                    <p style="margin: 8px 0px">Capitais e regiões metropolitanas</p>
                                    <p style="margin: 8px 0px">Atendimento de segunda a sábado, das 9h às 21h.</p>
                                    <p style="margin: 8px 0px"><a href="tel:+5508005131500" style="color: white; cursor: pointer">0800 513 1500</a></p>
                                    <br>
                                    <b style="margin: 8px 0px">Assistência Técnica</b>
                                    <p style="margin: 8px 0px"><a OnClick="abre_modal('sac.assistencia@deltasul.com.br')" style="color: white; cursor: pointer">sac.assistencia@deltasul.com.br</a></p>
                                    <br>
                                    <b style="margin: 8px 0px">Crédito e Cobrança</b>
                                    <p style="margin: 8px 0px"><a OnClick="abre_modal('sac.cobranca@deltasul.com.br')" style="color: white; cursor: pointer">sac.cobranca@deltasul.com.br</a></p>
                                    <br>
                                    <b style="margin: 8px 0px">Entrega e Montagem</b>
                                    <p style="margin: 8px 0px"><a OnClick="abre_modal('sac.operacoes@deltasul.com.br')" style="color: white; cursor: pointer">sac.operacoes@deltasul.com.br</a></p>
                                    <br>
                                    <b style="margin: 8px 0px">Sugestões e Reclamações</b>
                                    <p style="margin: 8px 0px"><a OnClick="abre_modal('sac@deltasul.com.br')" style="color: white; cursor: pointer">sac@deltasul.com.br</a></p>
                                    <br>
                                    <b style="margin: 8px 0px">Seja nosso fornecedor</b>
                                    <p style="margin: 8px 0px"><a OnClick="abre_modal('sac.fornecedor@deltasul.com.br')" style="color: white; cursor: pointer">sac.fornecedor@deltasul.com.br</a></p>
                                 </div>
                              </div>
                           </div>
                           <div class="bloco-faleconosco">
                              <div class="card">
                                 <div style="color: white; width: 100%; text-align: center; font-size: 22px;">
                                    <p type="text">Reclame Aqui</p>
                                 </div>
                              </div>
                              <div class="card" style="background: #083b76">
                                 <div style="color: white; width: 100%; text-align: center; font-size: 22px;">
                                    <b style="margin: 8px 0px">Notificações de reclamação</b>
                                    <p style="margin: 8px 0px"><a OnClick="abre_modal('sac.reclameaqui@deltasul.com.br')" style="color: white; cursor: pointer">sac.reclameaqui@deltasul.com.br</a></p>
                                 </div>
                              </div>
                              <div class="card">
                                 <div style="color: white; width: 100%; text-align: center; font-size: 22px;">
                                    <p type="text">Consumidor.gov.br</p>
                                 </div>
                              </div>
                              <div class="card" style="background: #083b76">
                                 <div style="color: white; width: 100%; text-align: center; font-size: 22px;">
                                    <b style="margin: 8px 0px">Institucional / Notificações / Fale conosco / Público geral</b>
                                    <p style="margin: 8px 0px"><a OnClick="abre_modal('sac.consumidor.gov.br@deltasul.com.br')" style="color: white; cursor: pointer">sac.consumidor.gov.br@deltasul.com.br</a></p>
                                 </div>
                              </div>
                           </div>
                        </div>
                    </div>
                  </div>
               </div>
            </div>
         </section>


         <div id="modal" class="modal-window">
            <div>
               <a href="#" title="Close" class="modal-close" OnClick="fecha_modal()">X</a>
               <h1 id="modal_titulo"></h1>
               <form id="form_fale_conosco">
                  <div class="modal-section-input">
                     <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">* Nome:</label>
                     <input type="text" name="nome" id="nome" aria-label="Nome" required class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">  
                  </div>
                  <div class="modal-section-input">
                     <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">* E-mail:</label>
                     <input type="text" name="email" id="email" aria-label="E-mail" required class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">  
                  </div>
                  <div class="modal-section-input">
                     <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">* Telefone de contato:</label>
                     <input type="text" name="telefone" id="telefone" aria-label="Telefone" required class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">  
                  </div>
                  <div class="modal-section-input">
                     <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">* Assunto:</label>
                     <input type="text" name="assunto" id="assunto" aria-label="Assunto" required class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">  
                  </div>
                  <div class="modal-section-input">
                     <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">* Mensagem:</label>
                     <textarea type="text" name="mensagem" id="mensagem" aria-label="Mensagem" required class="block w-full min-w-0 flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                  </div>
                  <input hidden name="cidade" id="cidade">
                  <input hidden name="estado" id="estado">
                  <input hidden name="pais" id="pais">
                  <input hidden name="email_contato_deltasul" id="email_contato_deltasul">
                  <input hidden name="acao" value="enviar_email_contato">
               </form>
               <div class="modal-section-input">
                  <input type="submit" class="button_enviar" OnClick="envia_form()" value="Enviar"></input>
               </div>
            </div>




    </main>
    <?php include('includes/sections/section_footer.php'); ?>
    <script src="js/fale_conosco.js<?= '?'.bin2hex(random_bytes(50))?>"></script>
</body>
</html>