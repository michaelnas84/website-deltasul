<?php include('includes/api_header_menu.php'); ?>
<head>
    <link rel="stylesheet" type="text/css" href="css/base.css<?= '?'.bin2hex(random_bytes(50))?>">
    <link rel="stylesheet" type="text/css" href="css/header.css<?= '?'.bin2hex(random_bytes(50))?>">
    <link rel="stylesheet" href="css/style.css<?= '?'.bin2hex(random_bytes(50))?>">
</head>

    <!-- início do preloader -->
    

<div id="preloader">
        <div class="inner">
           <div class="bolas">
              <div></div>
              <div></div>
              <div></div>                    
           </div>
        </div>
    </div>

    <!-- fim do preloader --> 
<header class="header" id="header">
    <div class="open-nav-menu">
      <span></span>
      <b style="color: white; margin-left: 5px">Menu</b>
    </div>
    <div class="header__content">
      <div class="av-container">
        <div class="av-row">
          <div class="av-col-xs-24 av-col-static">
            <div class="header__content--item header__logo">
              <a href="/" class="logo__link">
                <img src="img_base/sprites/Logo_Deltasul_01.1.png" style="max-width: 200px" class="logo__link">
              </a>
            </div>
            <div class="header__content--item header__search">
              <span class="header__link--register av-hidden-xs av-hidden-sm" title="Registrar">Pague seu carnê sem sair de casa com <a href="https://www3.deltasul.com.br/boleto/" target="_blank">BOLETO ou PIX</a>
              </span>
              <a class="header__link--store-locator av-hidden-xs av-hidden-sm" href="localizar_lojas.php" title="Localizar nossas lojas">Localizar nossas lojas</a>
              <form method="get" name="form" action="categorias.php" class="search__form">
                <input type="text" name="result_txtBusca" list="result_busca" id="txtBusca" onKeyDown="$(this).html('')" onKeyUp="list_pesquisa()" class="default-input search__input" value="<?= $busca ?>" placeholder="Pesquise aqui"  autocomplete = "off"/>
                <datalist id="result_busca" autocomplete="off">
                </datalist>
                <a type="submit" onClick="envia_pesq()" value="Submit" class="search__submit material-icons" style="cursor: pointer"></a>
              </form>
            </div>
            <!-- END: Header Content - Search -->
            <!-- START: Header Content - Folheto -->
            <div onclick="window.location.href='folheto_de_ofertas.php';" class="header__content--item header__account av-hidden-sm av-hidden-xs header__link--account-box">
              <a href="folheto_de_ofertas.php" class="header__link">Folheto de ofertas</a>
              <a class="material-icons header__link--account">menu_book</a>
            </div>
            <!-- END: Header Content - Folheto -->
            <!-- START: Header Content - Help -->
            <?php if ($oferta_da_semana != null) { ?>
            <div onclick="window.location.href='item.php?ref=<?= $oferta_da_semana ?>';" class="header__content--item header__account av-hidden-sm av-hidden-xs header__link--account-box">
              <a href="item.php?ref=<?= $oferta_da_semana ?>" class="header__link">Oferta do dia</a>
              <a class="material-icons header__link--account">local_offer</a>
            </div>
            <?php } else { ?>
            <div class="header__content--item header__account av-hidden-sm av-hidden-xs header__link--account-box">
              <a class="header__link">Procurando</a>
              <a class="material-icons header__link--account">local_offer</a>
            </div>
            <?php } ?>
            <!-- END: Header Content - Account -->
          </div>
        </div>
      </div>
    </div>
    <!-- END: Header Content -->
    <!-- START: Header Menu Wrapper -->
    <div class="header__menu-wrapper">
      <div class="av-container">
        <div class="av-row">
          <div class="av-col-xs-24 class-mobile">
          <div class="menu-overlay">
           </div>
          <nav class="nav-menu" style="display: flex;justify-content: center;">
             <div class="close-nav-menu">
                <img src="img_base/sprites/close.svg" alt="close">
             </div>
             <ul class="menu">
                <li class="menu-item menu-item-has-children">
                   <a href="#" data-toggle="sub-menu">Todas as categorias</a>
                   <ul class="sub-menu">
                        <?php for($xx=0; $xx < count($categorias_ref); $xx++){ ?>
                            <li class="menu-item"><a href="categorias.php?cat=<?= $categorias_ref[$xx] ?>"><?= ucfirst(strtolower($categorias_descr[$xx])) ?></a></li>
                        <?php } ?>
                   </ul>
                </li>
                <?php for($xx=0; $xx < count($categorias_ref_exibe); $xx++){ ?>
                 <li class="menu-item menu-item-has-children">
                   <a href="#" data-toggle="sub-menu"><?= ucfirst(strtolower($categorias_descr_exibe[$xx])); ?><span class="material-icons" style="margin-left: 5px;"><?= $icons[$xx] ?></span></a>
                   <ul class="sub-menu">
                        <?php for($xy=0; $xy < count($sub_categorias_descr[$categorias_ref[$xx]]); $xy++) { ?>
                            <li class="menu-item"><a href="categorias.php?sub_cat=<?= $sub_categorias_registro[$categorias_ref[$xx]][$xy] ?>"><?= ucfirst(strtolower($sub_categorias_descr[$categorias_ref[$xx]][$xy])) ?></a></li>
                        <?php } ?>
                   </ul>
                </li>
                <?php } ?>
              </ul>
           </nav>
          </div>
        </div>
      </div>
    </div>
  </header>

  <script src="js/menu_mobile.js"></script>
  <script src="js/header.js"></script>