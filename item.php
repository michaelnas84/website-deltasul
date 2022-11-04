<?php
      $PROD_REF = $_GET['ref'];
      header("Content-type: text/html; charset=utf-8");
      
      include_once('admin/includes/connections.php');

      // $content_prods = json_encode(array('produtos' => array($PROD_REF)));
      
      // include_once('includes/api_precos.php');
      
      $sql = "
      SELECT
         registro
      FROM
         web_produto
      WHERE 
         referencia = '$PROD_REF'
      AND 
         status = 'S'
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;    
      $stmt_produto = $pdo->query($sql);

      if(!$_POST['post'] == 'admin'){ if(!$stmt_produto->fetch()){ header("Location: ./"); die(); } }

      $sql = "
      SELECT
         A.categoria                         AS ref_categoria,
         A.subcategoria                      AS ref_sub_categoria,
         B.descricao                      	AS categoria,
         C.descricao                   		AS sub_categoria,
         A.registro                          AS ref,
         A.descricaoweb                      AS nome_exibe,
         A.marca                             AS marca,
         A.status                            AS status,
         (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND status = 'S' AND sequencia = 1)  AS URL_ARQ_01,
         (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND status = 'S' AND sequencia = 2)  AS URL_ARQ_02,
         (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND status = 'S' AND sequencia = 3)  AS URL_ARQ_03,
         (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND status = 'S' AND sequencia = 4)  AS URL_ARQ_04
      FROM
         web_produto A,
         web_categoria B,
         web_subcategoria C
      WHERE 
         A.referencia = '$PROD_REF'
      AND
         B.registro = A.categoria
      AND
         C.registro = A.subcategoria
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;    
      $stmt_produto = $pdo->query($sql);


      $sql = "
      SELECT 
         DISTINCT(A.referencia)			                                                                              AS REF,
         B.descricaoweb                                                                                              AS NOME_EXIBE,
         (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND status = 'S' AND sequencia = 1)      AS URL_ARQ_01,
         (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND status = 'S' AND sequencia = 2)      AS URL_ARQ_02
      FROM 
         web_buscaproduto A,
         web_produto B
      WHERE
         (A.referencia = B.referencia AND B.referencia <> '$PROD_REF')
      ORDER BY
         B.registro desc
      LIMIT
         4
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;      
      $stmt_produtos_rodape = $pdo->query($sql);

     $sql = "
      SELECT 
         A.descricao                AS DESCRICAO_ITEM_FICHA_TECNICA,
         B.descricao                AS NOME_ITEM_FICHA_TECNICA
      FROM 
         web_produtoitemfichatecnica A,
         web_itemfichatecnica B
      WHERE 
         A.referencia = '$PROD_REF'
      AND 
         A.status = 'S'
      AND
         A.itemfichatecnica = B.registro
      AND
         A.itemfichatecnica <> '11'
     ";
     // echo '<pre>' . $sql . '</pre>'; exit;
     $result_ficha_tecnica = $pdo->query($sql);


     $sql = "
     SELECT 
         A.descricao                AS SOBRE_O_PRODUTO,
         B.url					         AS URL
      FROM 
         web_produtoitemfichatecnica A
      LEFT JOIN
         web_produtoimagem B
      ON
         (B.produto = A.produto AND B.sequencia = 5 AND B.bloqueado = 'N')
      WHERE 
         (A.referencia = '$PROD_REF' AND A.itemfichatecnica = '11' AND A.status = 'S')
    ";
    // echo '<pre>' . $sql . '</pre>'; exit;
    $sobre_o_produto = $pdo->query($sql);


     $sql = "
     SELECT
         nome                        as nome,
         descricao                   as descricao,
         stars                       as stars,
         data                        as data
     FROM
         web_avaliacaocliente
     WHERE
         referencia = '$PROD_REF'
     AND 
         primeiroacesso = 'N'
     AND 
         bloqueado = 'N'
     AND 
         excluido = 'N'
     ORDER BY
         registro desc 
     LIMIT
         4
     ";
     // echo '<pre>' . $sql . '</pre>'; exit;
     $avaliacoes_clientes = $pdo->query($sql);

     $sql = "
      SELECT 
         A.descricaoweb                AS nome_exibe,
         A.referencia                  AS referencia,
         B.referenciaagregado          AS referenciaagregado,
         (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND status = 'S' AND sequencia = 1) AS url_arq
      FROM 
         web_produto A,
         web_produtoagregado B
      WHERE 
         B.referencia = '$PROD_REF'
      AND 
         A.referencia = B.referenciaagregado
      AND 
         B.status = 'S'
    ";
    // echo '<pre>' . $sql . '</pre>'; exit;
    $produtoagregado = $pdo->query($sql);


    $PROD_REF_LIKE = explode(".", $PROD_REF);
    $sql = "
    SELECT
      A.referencia            AS referencia_semelhante,
      A.descricaoweb          AS descricaoweb_semelhante,
      B.url                   AS url_semelhante,
      C.descricao             AS cor_semelhante
    FROM 
      web_produto A,
      web_produtoimagem B,
      web_produtoitemfichatecnica C
    WHERE
      (A.referencia <> '$PROD_REF' AND A.referencia like '%$PROD_REF_LIKE[0]%' AND A.primeiroacesso = 'N' AND A.bloqueado = 'N' AND A.status = 'S')
    AND 
      (B.referencia = A.referencia AND B.sequencia = '1')
    AND 
      (C.referencia = A.referencia AND C.itemfichatecnica = '2')
   ";
   // echo '<pre>' . $sql . '</pre>'; exit;
   $produtosemelhante = $pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="pt-BR">
   <head>
      <?php include('includes/sections/section_head.php'); ?>
      <link rel="stylesheet" type="text/css" href="css/item.css<?= '?'.bin2hex(random_bytes(50))?>">
      <link rel="stylesheet" type="text/css" href="css/magiczoomplus.css<?= '?'.bin2hex(random_bytes(50))?>">
      <script src="js/magiczoomplus.js<?= '?'.bin2hex(random_bytes(50))?>"></script>
      <script src="admin/js/jquery.redirect.js<?= '?'.bin2hex(random_bytes(50))?>"></script>
      <title>Produto | Lojas Deltasul</title>
   </head>
   <?php include('includes/sections/section_header.php'); ?>
   <body class="home-header">
      <main class="main" id="main">
         <!-- stars: Breadcrumb -->
         <?php while($row_produtos = $stmt_produto->fetch()){ $img_base_prod = $row_produtos['URL_ARQ_01']; ?>
         <section class="breadcrumb">
            <div class="av-container">
               <div class="av-row">
                  <div class="av-col-xs-24">
                     <div class="bread-crumb">
                        <ul>
                           <li itemprop="itemListElement">
                              <a href="index.php" itemprop="item"><span itemprop="name">Deltasul</span></a>
                              <meta itemprop="position" content="1">
                           </li>
                           <li itemprop="itemListElement">
                              <a href="categorias.php?cat=<?= $row_produtos['ref_categoria'] ?>" itemprop="item"><span itemprop="name"><?= $row_produtos['categoria'] ?></span></a>
                              <meta itemprop="position" content="2">
                           </li>
                           <li class="last" itemprop="itemListElement">
                              <a href="categorias.php?sub_cat=<?= $row_produtos['ref_sub_categoria'] ?>" itemprop="item"><span itemprop="name"><?= $row_produtos['sub_categoria'] ?></span></a>
                              <meta itemprop="position" content="3">
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- END: Breadcrumb -->
         <div class="product-info">
            <div class="av-container">
               <div class="av-row">
                  <div class="product-images">
                     <!-- ZOOM MODIFIED -->
                     <div class="apresentacao">
                        <div id="show">
                           <div id="include">
                              <div id="image">
                                 <div class="zoomPad">
                                    <div class="img-zoom-container">
                                    <?php for($xx=1; $xx <= 4; $xx++){ if($xx > 1) { $display = 'display: none'; } $nome_var = "URL_ARQ_0$xx";
                                       if($row_produtos[$nome_var]){ ?>
                                       <div style="<?= $display ?>" id="img_<?= $xx ?>" style="max-width: 430px;" href="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $PROD_REF) ?>/<?= $row_produtos[$nome_var] ?>" data-options="zoomMode:magnifier;hint:off;" class="MagicZoom">
                                       <img src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $PROD_REF) ?>/<?= $row_produtos[$nome_var] ?>" width="300" height="240" />
                                       </div>
                                    <?php } } ?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <ul class="thumbs slick-initialized slick-slider slick-vertical">
                              <div class="slick-list draggable">
                                 <div class="slick-track">
                                 <?php for($xx=1; $xx <= 4; $xx++){ $nome_var = "URL_ARQ_0$xx";
                                    if($row_produtos[$nome_var]){ ?>
                                    <div class="slick-slide slick-active" style="width: 80px;">
                                       <div>
                                          <li style="width: 100%; display: inline-block;">
                                             <a onclick='changeImage("img_<?= $xx ?>")' url="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $PROD_REF) ?>/<?= $row_produtos[$nome_var] ?>">
                                             <img src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $PROD_REF) ?>/<?= $row_produtos[$nome_var] ?>">
                                             </a>
                                          </li>
                                       </div>
                                    </div>
                                 <?php } } ?>
                                 </div>
                              </div>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="product-buy-info">
                     <div class="product-name av-hidden-xs av-hidden-sm">
                        <div class="fn productName"><?= $row_produtos['nome_exibe'] ?></div>
                     </div>
                     <div class="product-reference av-hidden-xs av-hidden-sm">
                        REF:
                        <div class="productReference 11572288"><?= $PROD_REF ?></div>
                     </div>
                     <!-- Inicio Yourviews --><!-- Âncora na página do produto (De Preferência sempre manter próximo ao nome do produto)  -->
                     <div id="yv-review-quickreview"></div>
                     <!-- Final Yourviews -->
                     <!-- <?php // if($arr_dados_unit['voltagem'] != null){ ?>
                     <div class="product-sku">
                        <div class="sku-selector-container sku-selector-container-0">
                           <ul class="topic Voltagem productid-491650 numopt-1">
                              <li class="specification">Voltagem</li>
                              <li class="select skuList item-dimension-Voltagem">
                                 <span class="group_0">
                                    <label class="checked sku-picked"><?= $arr_dados_unit['voltagem'] ?> <?php if($arr_dados_unit['voltagem'] == '110' || $arr_dados_unit['voltagem'] == '220'){ echo('volts'); }?></label>
                                 </span>
                              </li>
                           </ul>
                        </div>
                     </div> -->
                     <?php  $tot_itens_oferta = 0; ?>
                     <div class="product-buy-box">
                        <div class="product-price">
                           <div class="plugin-preco">


                           <?php while($row_produtosemelhante = $produtosemelhante->fetch()){ ?>
                              <div class="box_semelhantes" title="<?= $row_produtosemelhante['cor_semelhante'] ?>" onClick="window.location.href='item.php?ref=<?= $row_produtosemelhante['referencia_semelhante'] ?>'">
                                 <img src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $row_produtosemelhante['referencia_semelhante']) ?>/<?= $row_produtosemelhante['url_semelhante'] ?>"/>
                              </div>
                           <?php } ?>


                              <div class="productPrice">
                                 <p class="descricao-preco">
                                    <em class="valor-de price-list-price" style="display: block;"><strong class="skuListPrice">R$ <?= $array_precos['preco']['de'] ?></strong></em>
                                    <em class="valor-por price-best-price" style="display: flex;">
                                       <strong class="skuBestPrice">por R$<?php if($valor_item_oferta != null){ echo($valor_item_oferta); } else { echo($array_precos['preco']['por']); } ?></strong>
                                 <p class="preco-a-vista price-cash" style="display: flex;">
                                 <em>
                                    <span class="valorAvista"> à vista no boleto bancário ( 3% de desconto) </span>
                                    <img src="img_base/sprites/boleto-icone-lb.png" alt="Ícone boleto" title="Ícone boleto">
                                 </em>
                              </p>
                           </em>
                           <em class="valor-dividido price-installments" style="display: block;">ou <?php if($valor_item_oferta_parcelas != null){ echo($valor_item_oferta_parcelas); } else {echo ($arr_dados_unit['qtd_vezes']); } ?><span class="x">x</span> de <b>R$ <?php if($valor_item_oferta_valor_parcela != null){ echo($valor_item_oferta_valor_parcela); } else {echo ($arr_dados_unit['valor_parcelado']); } ?></b> s/ juros no cartão
                           </em>
                           </p>
                              </div>
                           </div>
                        </div>
                        <div class="product-shipping">
                           <span class="product-shipping-text">
                           Ver loja&nbsp;
                           <br>
                           <b>mais perto de você</b>
                           </span>
                           <div id="calculoFrete">
                              <div class="contentWrapper">
                                 <div id="ctl00_Conteudo_upnlContent">
                                    <div class="content">
                                       <fieldset>
                                          <form class="form" style="display: flex;align-items: center;">
                                             <input class="fitext freight-zip-box" name="cep" type="text" id="cep" value="" size="10" maxlength="9" placeholder="Digite seu CEP aqui"
                                             onblur="pesquisacep(this.value)" />
                                             <input class="fitext freight-zip-box" name="cidade" type="text" id="cidade" value="" style="display: none"/>
                                             <input type="button" class="bt freight-btn" title="OK" id="btnFreteSimulacao" value="OK" name="btnFreteSimulacao">
                                          </form>
                                          </span>
                                          <span class="cep-busca">
                                             <a title="Não sei meu CEP" class="bt lnkExterno" target="_blank" href="http://www.buscacep.correios.com.br/sistemas/buscacep/">Não sei meu CEP</a>
                                          </span>
                                       </fieldset>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="local_sem_loja" id="local_sem_loja" style="display: none; justify-content: space-around; align-items: center;">
                           <span class="material-icons material-icons-bad">sentiment_very_dissatisfied</span>
                           <div class="text-local-sem-loja"><a style="color: white; text-decoration: none">Infelizmente ainda não temos uma loja na sua cidade. Mas você pode ver qual a loja mais perto </a><a style="color: white" href="localizar_lojas.php">clicando aqui!</a></div>
                        </div>
                        <div id="cidade_proxima" style="display: none; flex-wrap: wrap">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <?php } ?>
         <!-- stars: Product Description -->
         <section class="product-details">
            <div class="av-container">
               <div class="av-row">
                  <div class="av-col-xs-24">
                     <div class="product-details-tabs">
                        <?php while($row_sobre_o_produto = $sobre_o_produto->fetch()) { ?>
                        <div class="box-content-details">
                           <h3 class="product-details-tab description selected trigger">Sobre o Produto<p class="material-icons">expand_more</p></h3>
                           <div class="product-details-contents selected toggle" style="display: none">
                              <div class="product-details-content description ">
                                 <div class="productDescription">
                                    <?= $row_sobre_o_produto['SOBRE_O_PRODUTO'] ?>
                                 </div>
                                 <?php if($row_sobre_o_produto['URL']){ ?>
                                 <div class="productDescription" style="display: flex; justify-content: center; padding: 25px;">
                                    <img src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $PROD_REF) ?>/<?= $row_sobre_o_produto['URL'] ?>"/>
                                 </div>
                                 <?php } ?>
                              </div>
                           </div>
                        </div>
                        <?php } ?>
                        <div class="box-content-details">
                           <h3 class="product-details-tab specifications trigger">Ficha Técnica<p class="material-icons">expand_more</p></h3>
                           <div class="product-details-contents toggle" style="display: none">
                              <div class="product-details-content specifications">
                                 <div id="caracteristicas">
                                    <h4 class="group Especificacoes-do-Produto">Especificações do Produto</h4>
                                    <table class="group Especificacoes-do-Produto" cellspacing="0">
                                       <tbody>
                                          <?php while($row_ficha_tecnica = $result_ficha_tecnica->fetch()){ ?>
                                             <tr>
                                                <th class="name-field"><?= $row_ficha_tecnica['NOME_ITEM_FICHA_TECNICA'] ?></th>
                                                <td class="value-field"><?= $row_ficha_tecnica['DESCRICAO_ITEM_FICHA_TECNICA'] ?></td>
                                             </tr>
                                          <?php } ?>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>   
                           </div>
                        </div>
                        <?php if($produtoagregado->rowCount() != null ){ ?>
                        <div class="box-content-details">
                           <h3 class="product-details-tab description selected trigger" style="cursor: context-menu;">Ou você pode levar esses dois juntos</h3>
                           <div class="product-details-contents selected">
                              <div class="product-details-content description">
                                 <div class="productDescription">

                                  <ul class="secao_itens_conjunto">
                                    <li class="secao_itens_conjunto_item">
                                    <div style="padding: 10px" href="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $PROD_REF) ?>/<?= $row_produtos['URL_ARQ_01'] ?>"><img onClick="window.location.href='item.php?ref=<?= $PROD_REF ?>'" style="height: 240px !important; object-fit: contain; cursor: pointer" src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $PROD_REF) ?>/<?= $img_base_prod ?>"/></div>
                                       <div style="padding: 10px; width: 100%;"><a href="item.php?ref=<?= $PROD_REF ?>"><?= $row_produtos['nome_exibe'] ?></a></div>
                                       <div class="shelf-item__buy-info">
                                          <div class="shelf-item__price">
                                             <div class="shelf-item__list-price">
                                                R$ <?= $arr_dados_unit['preco_anterior'] ?>
                                             </div>
                                             <div class="shelf-item__best-price">R$ <?= $arr_dados_unit['preco_novo'] ?></div>
                                             <div class="shelf-item__installments">
                                                ou em <?= $arr_dados_unit['qtd_vezes'] ?>x de <span class="shelf-item__installments__value">R$ <?= $arr_dados_unit['valor_parcelado'] ?></span>
                                             </div>
                                          </div>
                                       </div>

                                    </li>
                                    <li class="secao_itens_conjunto_juncao">
                                       <b style="font-size:8vw;">+</b>
                                    </li>
                                    <?php while($row_produtoagregado = $produtoagregado->fetch()){ ?>
                                    <li class="secao_itens_conjunto_item">
                                       <div style="padding: 10px" href="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $row_produtoagregado['referenciaagregado']) ?>/<?= $row_produtoagregado['url_arq'] ?>"><img onClick="window.location.href='item.php?ref=<?= $row_produtoagregado['referenciaagregado'] ?>'" style="height: 240px !important; object-fit: contain; cursor: pointer" src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $row_produtoagregado['referenciaagregado']) ?>/<?= $row_produtoagregado['url_arq'] ?>"/></div>
                                       <div style="padding: 10px; width: 100%;"><a href="item.php?ref=<?= $row_produtoagregado['referenciaagregado'] ?>"><?= $row_produtoagregado['nome_exibe'] ?></a></div>
                                       <div class="shelf-item__buy-info">
                                          <div class="shelf-item__price">
                                             <div class="shelf-item__list-price">
                                                R$ <?= $row_produtoagregado['preco_anterior'] ?>
                                             </div>
                                             <div class="shelf-item__best-price">R$ <?= $row_produtoagregado['preco_novo'] ?></div>
                                             <div class="shelf-item__installments">
                                                ou em <?= $row_produtoagregado['qtd_vezes'] ?>x de <span class="shelf-item__installments__value">R$ <?= $row_produtoagregado['valor_parcelado'] ?></span>
                                             </div>
                                          </div>
                                       </div>
                                    </li>
                                    <?php } ?>
                                    <li class="secao_itens_conjunto_juncao">
                                       <b style="font-size:8vw;">=</b>
                                    </li>
                                    <li class="secao_itens_conjunto_item">
                                       <div class="shelf-item__info">
                                          <div class="product-price">
                                             <div class="plugin-preco">
                                                <div class="productPrice">
                                                   <p class="descricao-preco">
                                                      <em class="valor-por price-best-price" style="display: flex;">
                                                         <strong class="skuBestPrice">por R$</strong>
                                                   <p class="preco-a-vista price-cash" style="display: flex;">
                                                   <em>
                                                      <span class="valorAvista"> à vista no boleto bancário ( 3% de desconto) </span>
                                                      <img src="img_base/sprites/boleto-icone-lb.png" alt="Ícone boleto" title="Ícone boleto">
                                                   </em>
                                                </p>
                                             </em>
                                             <em class="valor-dividido price-installments" style="display: block;">ou <?php if($valor_item_oferta_parcelas != null){ echo($valor_item_oferta_parcelas); } else {echo ($arr_dados_unit['qtd_vezes']); } ?><span class="x">x</span> de <b>R$ <?php if($valor_item_oferta_valor_parcela != null){ echo($valor_item_oferta_valor_parcela); } else {echo ($arr_dados_unit['valor_parcelado']); } ?></b> s/ juros no cartão
                                             </em>
                                             </p>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </li>
                                    
                                   </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <?php } if($avaliacoes_clientes->rowCount() != null ){ ?>
                        <div class="box-content-details">
                           <h3 class="product-details-tab" style="cursor: context-menu;">Últimas avaliações de Clientes</p></h3>
                           <div class="product-details-contents selected">
                              <div class="product-details-content description ">
                                 <div class="productDescription">
                                    <div class="product-shelf" style="margin: 20px 0px">
                                    <div class="av-container">
                                       <div class="av-row">
                                          <div class="shelf-default">
                                             <div class="slick-default slick-default--overflowY">
                                                <div class="main-shelf n4colunas">
                                                   <ul>
                                                   <?php while($row_avaliacoes_clientes = $avaliacoes_clientes->fetch()){ ?>
                                                      <li>
                                                         <div class="shelf-item shef-item--binded second-image" style="padding: 0px">
                                                            <div class="shelf-item__img" style="display: flex; justify-content: center; color: gold">
                                                            <?php for($item_coment_stars=0; $item_coment_stars<5; $item_coment_stars++){ if ($item_coment_stars<$row_avaliacoes_clientes['stars']){ echo('<spam class="material-icons" style="color: #f2c832; pointer-events: none;">star</spam>');} else { echo('<spam class="material-icons" style="color: #ccc; pointer-events: none;">star</spam>');}} ?>
                                                            </div>
                                                            <div class="shelf-item__info">
                                                               <div class="shelf-item__buy-info">
                                                                  <div class="shelf-item__price" style="padding-bottom: 7px">
                                                                     <spam><?= $row_avaliacoes_clientes['descricao'] ?></spam>
                                                                  </div>
                                                                  <div class="shelf-item__price" style="padding-bottom: 7px">
                                                                     <spam><b><?= $row_avaliacoes_clientes['nome'] ?></b></spam>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </li>
                                                      <?php } ?>
                                                   </ul>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <?php } ?>
                        <div class="box-content-details">
                           <h3 class="product-details-tab" style="cursor: context-menu;">Outras pessoas também observaram</p></h3>
                           <div class="product-details-contents selected">
                              <div class="product-details-content description ">
                                 <div class="productDescription">
                                    <div class="product-shelf" style="margin: 20px 0px">
                                    <div class="av-container">
                                       <div class="av-row">
                                          <div class="shelf-default">
                                             <div class="slick-default slick-default--overflowY">
                                                <div class="main-shelf n4colunas">
                                                   <ul>
                                                      <?php while($row_produtos_rodape = $stmt_produtos_rodape->fetch()){ ?>
                                                      <li>
                                                         <div class="shelf-item shef-item--binded second-image">
                                                            <div class="shelf-item__img">
                                                               <a class="shelf-item__img-link">
                                                                  <div class="shelf-item__image js--lazyload has--lazyload" data-noscript="">
                                                                     <img src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $row_produtos_rodape['REF']) ?>/<?= $row_produtos_rodape['URL_ARQ_01'] ?>" style="max-height: 204px"/>
                                                                  </div>
                                                                  <figure class="shelf-item__image__second"><a href="item.php?ref=<?= $row_produtos_rodape['REF'] ?>"><img src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $row_produtos_rodape['REF']) ?>/<?= $row_produtos_rodape['URL_ARQ_02'] ?>" style="max-height: 204px"></a></figure>
                                                               </a>
                                                            </div>
                                                            <div class="shelf-item__info">
                                                               <h3 class="shelf-item__title" id="shelf-item__title">
                                                                  <a class="shelf-item__title-link"><?= $row_produtos_rodape['NOME_EXIBE'] ?></a>
                                                               </h3>
                                                               <div class="shelf-item__buy-info">
                                                                  <div class="shelf-item__price">
                                                                     <div class="shelf-item__list-price">
                                                                        R$ <?= $arr_dados_unit_rodape['preco_anterior'][$item_rodape] ?>
                                                                     </div>
                                                                     <div class="shelf-item__best-price">R$ <?= $arr_dados_unit_rodape['preco_novo'][$item_rodape] ?></div>
                                                                     <div class="shelf-item__installments">
                                                                        ou em <?= $arr_dados_unit_rodape['qtd_vezes'][$item_rodape] ?>x de <span class="shelf-item__installments__value">R$ <?= $arr_dados_unit_rodape['valor_parcelado'][$item_rodape] ?></span>
                                                                     </div>
                                                                  </div>
                                                                  <div class="shelf-item__btns" id="shelf-item__btns">
                                                                     <a href="item.php?ref=<?= $row_produtos_rodape['REF'] ?>" class="shelf-item__btn-buy">VER MAIS</a>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </li>
                                                      <?php } ?>
                                                   </ul>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>

         <?php if($_POST['post'] == 'admin'){ ?>
         <div style="position:fixed; bottom:0px; right: 0px; width: 360px; height: 100px; margin-right: 50px; display: flex; justify-content: space-between; align-items: center;">
            <button id="button_adiciona_item_site" class="shelf-item__btn-buy" style="border: none">Confirmar produto no site</button>
            <button id="button_voltar" class="shelf-item__btn-buy" style="border: none; background-color: #17264d; margin-left: 10px">Voltar</button>
         </div>
         <?php } ?>

      </main>

      <script src="js/item.js<?= '?'.bin2hex(random_bytes(50))?>"></script>

      <input hidden type="text" id="registro_produto" value="<?= $PROD_REF ?>">

      <?php // if($exibir_contador == 'S'){ include('includes/scripts/section_oferta_da_semana.php'); } ?>
      
      <?php include('includes/sections/section_footer.php'); ?>
    </body>
</html>