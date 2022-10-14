<?php
  header("Content-type: text/html; charset=utf-8");

  function deixarNumero($string) {
    return preg_replace("/[^0-9]/", "", $string);
  }

  include_once('admin/includes/connections.php');

  $cat                = $_GET['cat'];
  $sub_cat            = $_GET['sub_cat'];
  $marca              = $_GET['marca'];
  $busca              = trim($_GET['result_txtBusca']);

  $sql = "
      SELECT
        A.referencia                                                                                                          as referencia,
        A.registro                                                                                                            as registro,
        A.categoria                                                                                                           as categoria,
        A.subcategoria                                                                                                        as subcategoria,
        A.descricaoweb                                                                                                        as descricaoweb,
        A.marca                                                                                                               as marca,
        (SELECT url FROM web_produtoimagem WHERE sequencia = 1 AND produto = A.registro AND bloqueado = 'N')                  as url_imagem_capa,
        (SELECT url FROM web_produtoimagem WHERE sequencia = 2 AND produto = A.registro AND bloqueado = 'N')                  as url_imagem_contra_capa
      FROM
        web_produto A
      WHERE
        A.status = 'S'
      ";

  if ($busca != null) {
    $sql .= "
        AND
          A.descricaoweb LIKE '%$busca%'
        ";
  }

  if ($cat != null) {
    $sql .= "
        AND
          A.categoria = '$cat'
        ";
  }

  if ($sub_cat != null) {
    $sql .= "
        AND
          A.subcategoria = '$sub_cat'
        ";
  }

  $sql .= "
      ORDER BY
        A.cadastro desc

      ";

  // echo '<pre>' . $sql . '</pre>'; exit;  
  $xx = 0;
  $tot_itens = 0;
  $stmt = $pdo->query($sql);
  while ($row_produtos = $stmt->fetch()) {
    if ($row_produtos['registro'] != $registro) {
      $arr_dados['ref'][$tot_itens]                                                 = $row_produtos['referencia'];
      $arr_dados['categoria'][$tot_itens]                                           = $row_produtos['categoria'];
      $arr_dados['sub_categoria'][$tot_itens]                                       = $row_produtos['subcategoria'];
      $arr_dados['nome'][$tot_itens]                                                = $row_produtos['descricaoweb'];
      $arr_dados['marca'][$tot_itens]                                               = $row_produtos['marca'];
      $tot_itens++;
      $xx = 0;
    }
    $registro                                                                         = $row_produtos['registro'];
    $arr_dados['URL_ARQ'][$row_produtos['referencia']][$xx]                           = $row_produtos['url_imagem_capa'];
    $arr_dados['URL_ARQ'][$row_produtos['referencia']][$xx+1]                           = $row_produtos['url_imagem_contra_capa'];
  }
  $ref_unit = $arr_dados_unit['ref'];

  if($arr_dados['marca']){
    $arr_dados['marca_unico'] = array_unique($arr_dados['marca']);
    $arr_dados['marca_unico'] = array_values($arr_dados['marca_unico']);
  }

  $content_prods = json_encode(array('produtos' => $arr_dados['ref']));

  include_once('includes/api_precos.php');

  // $valores_limpos = 
  //     array_map(
  //         function($str) { return deixarNumero(substr($str,  0, -3)); },
  //         $arr_dados['preco_novo']
  //     );
  // $max_value = max($valores_limpos);
  // $min_value = min($valores_limpos);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <?php include('includes/sections/section_head.php'); ?>

  <title>Busca | Lojas Deltasul</title>
  <link rel="stylesheet" type="text/css" href="css/home.css<?= '?' . bin2hex(random_bytes(50)) ?>">
  <link rel="stylesheet" type="text/css" href="css/categorias.css<?= '?' . bin2hex(random_bytes(50)) ?>">
</head>
<?php include('includes/sections/section_header.php'); ?>

<body class="home-header">
  <main class="main" id="main">
    <!-- START: Shelf 1 -->
    <div class="shelf-carousel home-shelf home-shelf-1">
      <div class="av-container">
        <div class="av-row">
          <div class="av-col-xs-24" style="display: flex">
            <div class="menu-filtros">
              <button class="trigger"><a>Marcas</a>
                <p class="material-icons">expand_more</p>
              </button>
              <ul class="toggle" style="display: none">
                <p href="#" class="btn" data-cad="todos" style="cursor: pointer">Todos</p>
                <?php if($arr_dados['marca_unico']){ for ($item = 0; $item < count($arr_dados['marca_unico']); $item++) { ?>
                  <p style="cursor: pointer" class="btn" data-cad="<?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca_unico'][$item])) ?>"><?= ucfirst(strtolower($arr_dados['marca_unico'][$item])) ?></p>
                <?php } } ?>
              </ul>
              <button class="trigger"><a>Faixa de Preço</a>
                <p class="material-icons">expand_more</p>
              </button>
              <ul class="toggle" style="display: none">
                <p>R$<span id="valor_filtro"></span></p>
                <input style="width: 100%" type="range" min="<?= $min_value ?>" max="<?= $max_value ?>" class="myRange" id="myRange">
              </ul>
            </div>

            <div class="home-shelf__content">
              <div class="main-shelf n5colunas">
                <ul class="slick-initialized slick-slider">
                  <div class="slick-list draggable">
                    <div class="slick-track">
                      <?php if ($busca != null && $tot_itens == null) { ?>
                        <div class="slick-slide" style="padding: 50px">
                          A sua pesquisa não retornou resultado, tente novamente mudando o texto : )
                        </div>
                      <?php } else if ($busca == null && $tot_itens == null) { ?>
                        <div class="slick-slide" style="padding: 50px">
                          Estamos adicionando itens nesta categoria
                        </div>
                      <?php } ?>
                      <?php for ($item = 0; $item < $tot_itens; $item++) { ?>
                        <div class="slick-slide <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">
                          <div class="shelf-item shef-item--binded second-image <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">
                            <div class="shelf-item__img <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">
                              <a class="shelf-item__img-link <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">
                                <div class="shelf-item__image has--lazyload is--lazyloaded <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">
                                  <img src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $arr_dados['ref'][$item]) ?>/<?= $arr_dados['URL_ARQ'][$arr_dados['ref'][$item]][0] ?>" width="204" height="204">
                                </div>
                                <figure class="shelf-item__image__second <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">
                                  <a href="item.php?ref=<?= $arr_dados['ref'][$item] ?>"><img width="204" height="204" src="img_base/produtos/<?= preg_replace('/[^0-9]/', '', $arr_dados['ref'][$item]) ?>/<?= $arr_dados['URL_ARQ'][$arr_dados['ref'][$item]][1] ?>"></a>
                                </figure>
                              </a>
                            </div>
                            <div class="yv-review-quickreview <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>"></div>
                            <div class="shelf-item__info <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">
                              <ul class="shelf-item__title <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>" id="ulItens">
                                <li class="shelf-item__title-link <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>"><?= $arr_dados['nome'][$item] ?></li>
                              </ul>
                              <div class="shelf-item__buy-info <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">
                                <div class="shelf-item__price <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">
                                  <div class="shelf-item__list-price <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">R$<?= $array_precos[$arr_dados['ref'][$item]]['preco']['de'] ?></div>
                                  <div id="shelf-item__best-price" value="<?= $array_precos[$arr_dados['ref'][$item]]['preco']['por'] ?>" class="shelf-item__best-price <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">R$<?= $array_precos[$arr_dados['ref'][$item]]['preco']['por'] ?></div>
                                  <div class="shelf-item__installments <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">ou em <?= $arr_dados['qtd_vezes'][$item] ?>x de R$<span class="shelf-item__installments__value"><?= $arr_dados['valor_parcelado'][$item] ?></span>
                                  </div>
                                </div>
                                <div id="shelf-item__btns" class="shelf-item__btns <?= iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($arr_dados['marca'][$item])) ?>">
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
  </main>

  <script src="js/categorias.js"></script>

  <!-- END - Main -->
  <?php include('includes/sections/section_footer.php'); ?>
</body>

</html>