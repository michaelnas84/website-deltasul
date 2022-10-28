    <link rel="stylesheet" type="text/css" href="css/base.css<?= '?'.bin2hex(random_bytes(50))?>">
    <link rel="stylesheet" type="text/css" href="css/footer.css<?= '?'.bin2hex(random_bytes(50))?>">
<!-- START - Subtemplate Footer -->
    <footer class="footer">
      <!-- START: Footer Content -->
      <div class="footer-content">
        <div class="footer-newsletter">
          <div class="av-container">
            <div class="av-row">
              <div class="footer-content__wrap footer-content__wrap--newsletter">
                <div class="footer-newsletter__text">Receba <b> ofertas exclusivas</b>
                </div>
                <div class="footer-newsletter__form">
                  <form class="newsletter__form form" style="display: flex; width: 100%; align-items: center;">
                    <div class="newsletter__field">
                      <input type="text" class="default-input newsletter__input" name="contato_cliente" placeholder="usuario@email.com ou (00) 00000-0000" required="true">
                      <input type="text" hidden name="pagina" value="index">
                    </div>
                    <div class="newsletter__button">
                    <button type="button" id="enviar" onClick="enviar_dados()" class="newsletter__submit">Enviar</button>
		                <input type="hidden" name="acao" value="contato_cliente_cadastro">
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="av-container">
          <div class="av-row" style="display: flex;">
            <div class="footer-col footer-col-institucional footer-col-accordion">
              <div class="footer-content__wrap">
                <h4 class="footer-content__title footer-content__title--arrow">Institucional</h4>
                <ul class="footer-content__list">
                  <li class="footer-content__item">
                    <a href="sobre_nos.php" class="footer-content__link">Sobre nós</a>
                  </li>
                  <li class="footer-content__item">
                    <a href="localizar_lojas.php" class="footer-content__link">Nossas Lojas</a>
                  </li>
                  <li class="footer-content__item">
                    <a href="fale_conosco.php" class="footer-content__link">Fale Conosco</a>
                  </li>
                  <li class="footer-content__item">
                    <a href="politica_de_privacidade.php" class="footer-content__link">Política de Privacidade</a>
                  </li>
                  <li class="footer-content__item">
                    <a href="https://deltasul.gupy.io/#section-jobs" class="footer-content__link">Trabalhe Conosco</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="footer-col footer-col-atendimento">
              <div class="footer-content__wrap">
                <h4 class="footer-content__title">Atendimento</h4>
                <ul class="footer-content__list">
                  <li class="footer-content__item">
                    <a href="tel:+555533593500" class="footer-content__link footer-content__link--phone">(51) 3713-7239 </a>
                  </li>
                  <li class="footer-content__item">
                    <p class="footer-content__text">De 2 <sup>a</sup> à 6 <sup>a</sup> feira, <br> das 08h15min às 12h e <br> 13h30min às 17h45min </p>
                    <p class="footer-content__text">Aos Sábados, <br> das 8h às 12h </p>
                    <p class="footer-content__text">ou <a href="fale_conosco.php">Fale Conosco</a>
                    </p>
                  </li>
                </ul>
              </div>
            </div>
            <div class="footer-col footer-col-pagamento">
              <div class="footer-content__wrap">
                <h4 class="footer-content__title">Formas de pagamento</h4>
                <ul class="footer-content__list footer-content__list--gateways">
                <div class="cards-payment">
                    <span class="icon-cartao-visa" title="Visa"></span>
                    <span class="icon-cartao-mastercard" title="Master card"></span>
                    <span class="icon-cartao-american-express" title="American Express"></span>
                    <span class="icon-cartao-dinners" title="Dinners Club"></span>
                    <span class="icon-cartao-elo" title="Elo"></span>
                    <span class="icon-cartao-hiper" title="Hiper"></span>
                    <span class="icon-boleto-bancario" title="Boleto Bancário"></span>
                    <span class="icon-pix" title="Pix"></span>
                    <span class="icon-cartao-hipercard" title="Hipercard"></span>
                </div>
                </ul>
              </div>
            </div>
            <div class="footer-col footer-col-pagamento">
              <div class="footer-content__wrap">
                <h4 class="footer-content__title">Redes Sociais</h4>
                <ul class="footer-content__list footer-content__list--gateways">
                <div class="cards-payment">
                  <div OnClick="window.open(`https://pt-br.facebook.com/LojasDeltasul/`)" class="icon-fb" title="Facebook"></div>
                  <div OnClick="window.open(`https://www.instagram.com/lojasdeltasul/`)" class="icon-instagram" title="Instagram"></div>
                </div>
                </ul>
              </div>
            </div>
            <div class="footer-col footer-col-pagamento">
              <div class="footer-content__wrap">
                <h4 class="footer-content__title">Certificados</h4>
                <ul class="footer-content__list footer-content__list--gateways">
                </ul>
              </div>
            </div>
            <div class="footer-col footer-col-pagamento">
              <div class="footer-content__wrap">
                <h4 class="footer-content__title">Navegação</h4>
                <ul class="footer-content__list footer-content__list--gateways">
                  <a href="#header">Ir para o topo</a>
                </ul>
              </div>
            </div>
        </div>
              <!-- END: Footer Content -->
      <!-- START: Footer Bottom -->
      <div class="footer-bottom">
        <div class="av-container">
          <div class="av-row">
            <div class="footer-bottom-content">
              <div class="footer-bottom__text">
              <p>"Apenas aos pedidos efetivamente formulados e aceitos não se aplicarão eventuais alterações posteriores de preço."</p><p> LOJAS DELTASUL - CNPJ 98.102.924/0001-01 - PRESIDENTE CASTELO BRANCO - DISTR. INDUSTRIAL - SANTA CRUZ DO SUL - RS - CEP 96835-666</p></div></div></div></div></div>
              <!-- END: Footer Bottom -->
    </footer>

    <script src="js/footer.js"></script>
    <script src="js/save_localizacao.js"></script>