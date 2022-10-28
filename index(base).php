<?php
    header("Content-type: text/html; charset=utf-8");
    $pagina = "index";

    include_once('admin/includes/connections.php');

    if($_COOKIE["visto_ultimo"]) {
      $qtd_visto_ultimo              = 0; 
      $arr_dados_base                = json_decode($_COOKIE["visto_ultimo"]); 
      $arr_dados_base                = array_reverse($arr_dados_base); 
      $visto_ultimo                  = array_slice($arr_dados_base, 0, 5); 

      foreach ($visto_ultimo as $dados_visto_ultimo) {
        $itens_visto_ultimo .= (end($visto_ultimo) == $dados_visto_ultimo) ? "'".$dados_visto_ultimo."'" : "'".$dados_visto_ultimo . "', ";
      }

      $sql = "
      SELECT
        A.referencia                       as referencia,
        A.descricaoweb                     as descricaoweb,
        (SELECT url FROM web_produtoimagem WHERE A.referencia = referencia AND status = 'S' AND sequencia = 1) AS url_imagem_1,
        (SELECT url FROM web_produtoimagem WHERE A.referencia = referencia AND status = 'S' AND sequencia = 2) AS url_imagem_2
      FROM
        web_produto A
      WHERE
        (A.bloqueado = 'N' AND A.status = 'S')
      AND 
        A.referencia IN($itens_visto_ultimo)
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);
      $tot_itens = 0;
      while($row_produtos = $stmt->fetch()){
          $arr_dados_vistos_ultimo['ref'][$row_produtos['referencia']]                      = $row_produtos['referencia'];
          $arr_dados_vistos_ultimo['nome'][$row_produtos['referencia']]                     = $row_produtos['descricaoweb'];
          $arr_dados_vistos_ultimo['URL_ARQ'][$row_produtos['referencia']][0]               = $row_produtos['url_imagem_1'];
          $arr_dados_vistos_ultimo['URL_ARQ'][$row_produtos['referencia']][1]               = $row_produtos['url_imagem_2'];
      }
    }

    $sql = "
    SELECT
      A.referencia                       as referencia,
      A.descricaoweb                     as descricaoweb,
      (SELECT url FROM web_produtoimagem WHERE A.referencia = referencia AND status = 'S' AND sequencia = 1) AS url_imagem_1,
      (SELECT url FROM web_produtoimagem WHERE A.referencia = referencia AND status = 'S' AND sequencia = 2) AS url_imagem_2
    FROM
      web_produto A
    WHERE
      (A.bloqueado = 'N' AND A.status = 'S')
    ORDER BY
      A.cadastro desc
    LIMIT
      5
    ";
    // echo '<pre>' . $sql . '</pre>'; exit;
    $tot_itens = 0;
    $xx = 0;
    
    $stmt = $pdo->query($sql);
    while($row_produtos = $stmt->fetch()){
        $arr_dados['ref'][$tot_itens]                                                 = $row_produtos['referencia'];
        $arr_dados['nome'][$tot_itens]                                                = $row_produtos['descricaoweb'];
        $arr_dados['URL_ARQ'][$row_produtos['referencia']][0]                         = $row_produtos['url_imagem_1'];
        $arr_dados['URL_ARQ'][$row_produtos['referencia']][1]                         = $row_produtos['url_imagem_2'];
        $tot_itens++;
    }


    $sql_tec = "
    SELECT
      A.referencia                       as referencia,
      A.descricaoweb                     as descricaoweb,
      (SELECT url FROM web_produtoimagem WHERE A.referencia = referencia AND status = 'S' AND sequencia = 1) AS url_imagem_1,
      (SELECT url FROM web_produtoimagem WHERE A.referencia = referencia AND status = 'S' AND sequencia = 2) AS url_imagem_2
    FROM
      web_produto A
    WHERE
      (A.bloqueado = 'N' AND A.status = 'S' AND A.subcategoria IN (1560, 1601, 1650))
    ORDER BY
      A.cadastro desc
    LIMIT
      5
    ";
    // echo '<pre>' . $sql_tec . '</pre>'; exit;
    $tot_itens_tec = 0;
    $xx = 0;
    
    $stmt_tec = $pdo->query($sql_tec);
    while($row_produtos_tec = $stmt_tec->fetch()){
        $arr_dados_tec['ref'][$tot_itens_tec]                                                 = $row_produtos_tec['referencia'];
        $arr_dados_tec['nome'][$tot_itens_tec]                                                = $row_produtos_tec['descricaoweb'];
        $arr_dados_tec['URL_ARQ'][$row_produtos_tec['referencia']][0]                         = $row_produtos_tec['url_imagem_1'];
        $arr_dados_tec['URL_ARQ'][$row_produtos_tec['referencia']][1]                         = $row_produtos_tec['url_imagem_2'];
        $tot_itens_tec++;
    }


//    $content_prods = json_encode(array('produtos' => $arr_dados['ref']));
//
//    include_once('includes/api_precos.php');


?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <?php include('includes/sections/section_head.php'); ?>
    <link rel="stylesheet" type="text/css" href="css/home.css<?= '?'.bin2hex(random_bytes(50))?>">
    <title>Home | Lojas Deltasul</title>
  </head>
  <?php include('includes/sections/section_header.php'); ?>
    <body class="home-header">
      <main class="main" id="main">
      <!-- START: Banner full -->
      <?php include('includes/sections/section_slider.php'); ?>
      <!-- END: Banner full -->
      <!-- START: Banner -->
      <div class="home-banner home-banner-1" id="home-banner-1">
        <div class="av-container">
          <div class="av-row">
            <div class="av-col-xs-24">
              <div class="slick-frame has--lazyload is--lazyloaded">
                <div class="box-banner">
                  <a>
                    <img width="580" height="258" src="img_base/home/brinquedos-home.png">
                  </a>
                </div>
                <div class="box-banner">
                  <a href="categorias.php?sub_cat=4">
                    <img width="580" height="258" src="img_base/home/cozinhas-moduladas.png">
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END: Banner -->
      <!-- START: Shelf 0 -->
      <?php if($_COOKIE["visto_ultimo"]) {  ?>
      <div class="shelf-carousel home-shelf home-shelf-1">
        <div class="av-container">
          <div class="av-row">
            <div class="av-col-xs-24">
              <div class="home-shelf__content">
                <div class="main-shelf n5colunas">
                  <h2>Vistos por último</h2>
                  <ul class="slick-initialized slick-slider">
                    <div class="slick-list draggable">
                      <div class="slick-track">
                        <?php for($xx=0; $xx < count($arr_dados_vistos_ultimo['ref']); $xx++){ ?>
                          <div class="slick-slide slick-active">
                            <div class="shelf-item shef-item--binded second-image">
                              <div class="shelf-item__img">
                                <a class="shelf-item__img-link">
                                  <div class="shelf-item__image has--lazyload is--lazyloaded">
                                    <img src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $arr_dados_vistos_ultimo['ref'][$visto_ultimo[$xx]]) ?>/<?= $arr_dados_vistos_ultimo['URL_ARQ'][$visto_ultimo[$xx]][0] ?>"  style="max-height: 204px">
                                  </div>
                                  <figure class="shelf-item__image__second">
                                    <a href="item.php?ref=<?= $arr_dados_vistos_ultimo['ref'][$visto_ultimo[$xx]] ?>"><img  style="max-height: 204px" src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $arr_dados_vistos_ultimo['ref'][$visto_ultimo[$xx]]) ?>/<?= $arr_dados_vistos_ultimo['URL_ARQ'][$visto_ultimo[$xx]][1] ?>"></a>
                                  </figure>
                                </a>
                              </div>
                              <div class="yv-review-quickreview"></div>
                              <div class="shelf-item__info">
                              <ul class="shelf-item__title" id="ulItens">
                                  <li class="shelf-item__title-link"><?= $arr_dados_vistos_ultimo['nome'][$visto_ultimo[$xx]] ?></li>
                              </ul>
                                <div class="shelf-item__buy-info">
                                  <div class="shelf-item__price">
                                    <div class="shelf-item__list-price">R$<?= $array_precos[$arr_dados_vistos_ultimo['ref'][$xx]]['preco']['de'] ?></div>
                                    <div class="shelf-item__best-price">R$<?= $array_precos[$arr_dados_vistos_ultimo['ref'][$xx]]['preco']['por'] ?></div>
                                    <div class="shelf-item__installments">ou em <?= $arr_dados_vistos_ultimo['qtd_vezes'][$xx] ?>x de R$<span class="shelf-item__installments__value"><?= $arr_dados_vistos_ultimo['valor_parcelado'][$xx] ?></span>
                                    </div>
                                  </div>
                                  <div class="shelf-item__btns" id="shelf-item__btns">
                                    <a href="item.php?ref=<?= $arr_dados_vistos_ultimo['ref'][$visto_ultimo[$xx]] ?>" class="shelf-item__btn-buy">VER MAIS</a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
      <!-- END: Shelf 0 -->
      <!-- START: Shelf 1 -->
        <div class="shelf-carousel home-shelf home-shelf-1">
        <div class="av-container">
          <div class="av-row">
            <div class="av-col-xs-24">
              <div class="home-shelf__content">
                <div class="main-shelf n5colunas">
                  <h2>Veja só o que chegou agora. Não perca!</h2>
                  <ul class="slick-initialized slick-slider">
                    <div class="slick-list draggable">
                      <div class="slick-track">
                        <?php for($item=0; $item<$tot_itens; $item++){ ?>
                        <div class="slick-slide slick-active">
                              <div class="shelf-item shef-item--binded second-image">
                                <div class="shelf-item__img">
                                  <a class="shelf-item__img-link">
                                    <div class="shelf-item__image has--lazyload is--lazyloaded">
                                      <img src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $arr_dados['ref'][$item]) ?>/<?= $arr_dados['URL_ARQ'][$arr_dados['ref'][$item]][0] ?>"  style="max-height: 204px">
                                    </div>
                                    <figure class="shelf-item__image__second">
                                      <a href="item.php?ref=<?= $arr_dados['ref'][$item] ?>"><img  style="max-height: 204px" src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $arr_dados['ref'][$item]) ?>/<?= $arr_dados['URL_ARQ'][$arr_dados['ref'][$item]][1] ?>"></a>
                                    </figure>
                                  </a>
                                </div>
                                <div class="yv-review-quickreview"></div>
                                <div class="shelf-item__info">
                                <ul class="shelf-item__title" id="ulItens">
                                    <li class="shelf-item__title-link"><?= $arr_dados['nome'][$item] ?></li>
                                </ul>
                                  <div class="shelf-item__buy-info">
                                    <div class="shelf-item__price">
                                      <div class="shelf-item__list-price">R$<?= $array_precos[$arr_dados['ref'][$item]]['preco']['de'] ?></div>
                                      <div class="shelf-item__best-price">R$<?= $array_precos[$arr_dados['ref'][$item]]['preco']['por'] ?></div>
                                      <div class="shelf-item__installments">ou em <?= $arr_dados['qtd_vezes'][$item] ?>x de R$<span class="shelf-item__installments__value"><?= $arr_dados['valor_parcelado'][$item] ?></span>
                                      </div>
                                    </div>
                                    <div class="shelf-item__btns" id="shelf-item__btns">
                                      <a href="item.php?ref=<?= $arr_dados['ref'][$item] ?>" class="shelf-item__btn-buy">VER MAIS</a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END: Shelf 1 -->
      <!-- START: Banner 2 -->
      <div class="home-banner home-banner-2">
        <div class="av-container">
          <div class="av-row" style="display: flex; justify-content: center;">
            <div class="av-col-md-8 av-hidden-xs av-hidden-sm has--lazyload is--lazyloaded">
              <div class="box-banner">
                <a href="categorias.php?cat=120">
                  <img width="380" height="470" alt="Telefonia" src="img_base/home/MOSAICO-telefonia.png">
                </a>
              </div>
            </div>
            <div class="av-col-xs-24 av-col-md-16">
              <div class="av-row slick-frame has--lazyload is--lazyloaded">
                <div class="box-banner">
                  <a href="categorias.php?cat=1560">
                    <img width="380" height="225" src="img_base/home/MOSAICO-informatica.png">
                  </a>
                </div>
                <div class="box-banner">
                  <a href="categorias.php?cat=1871">
                    <img width="380" height="225" src="img_base/home/MOSAICO-ferramentas.png">
                  </a>
                </div>
                <div class="box-banner">
                  <a href="categorias.php?cat=1601">
                    <img width="380" height="225" src="img_base/home/MOSAICO-audio-e-video.png">
                  </a>
                </div>
                <div class="box-banner">
                  <a href="categorias.php?cat=107">
                    <img width="380" height="225" src="img_base/home/MOSAICO-eletrodomesticos.png">
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END: Banner 2 -->
      <!-- START: Shelf 2 -->
      <div class="shelf-carousel home-shelf home-shelf-2">
        <div class="av-container">
          <div class="av-row">
            <div class="av-col-xs-24">
              <div class="home-shelf__content">
                <div class="main-shelf n5colunas">
                  <h2>Tecnologia, som e imagem pra levar agora!</h2>
                  <ul class="slick-initialized slick-slider">
                    <div class="slick-list draggable">
                      <div class="slick-track">
                      <?php for($item=0; $item<$tot_itens_tec; $item++){ ?>
                        <div class="slick-slide slick-active">
                          <div class="shelf-item shef-item--binded second-image">
                            <div class="shelf-item__img">
                              <a class="shelf-item__img-link">
                                <div class="shelf-item__image has--lazyload is--lazyloaded">
                                  <img src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $arr_dados_tec['ref'][$item]) ?>/<?= $arr_dados_tec['URL_ARQ'][$arr_dados_tec['ref'][$item]][0] ?>"  style="max-height: 204px">
                                </div>
                                <figure class="shelf-item__image__second">
                                  <a href="item.php?ref=<?= $arr_dados_tec['ref'][$item] ?>"><img  style="max-height: 204px" src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $arr_dados_tec['ref'][$item]) ?>/<?= $arr_dados_tec['URL_ARQ'][$arr_dados_tec['ref'][$item]][1] ?>"></a>
                                </figure>
                              </a>
                            </div>
                            <div class="yv-review-quickreview"></div>
                            <div class="shelf-item__info">
                              <ul class="shelf-item__title" id="ulItens">
                                <li class="shelf-item__title-link"><?= $arr_dados_tec['nome'][$item] ?></li>
                              </ul>
                              <div class="shelf-item__buy-info">
                                <div class="shelf-item__price">
                                  <div class="shelf-item__list-price">R$<?= $array_precos[$arr_dados_tec['ref'][$item]]['preco']['de'] ?></div>
                                  <div class="shelf-item__best-price">R$<?= $array_precos[$arr_dados_tec['ref'][$item]]['preco']['por'] ?></div>
                                  <div class="shelf-item__installments">ou em <?= $arr_dados_tec['qtd_vezes'][$item] ?>x de R$<span class="shelf-item__installments__value"><?= $arr_dados_tec['valor_parcelado'][$item] ?></span>
                                  </div>
                                </div>
                                <div class="shelf-item__btns" id="shelf-item__btns">
                                  <a href="item.php?ref=<?= $arr_dados_tec['ref'][$item] ?>" class="shelf-item__btn-buy">VER MAIS</a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php } ?>
                      </div>
                    </div>
                  </ul>
                </div>
              </div>
      <!-- END: Shelf 2 -->
    </main>
    <!-- END - Main -->
    <?php include('includes/sections/section_footer.php'); ?>
  </body>
</html>