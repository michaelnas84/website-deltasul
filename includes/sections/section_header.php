<head>
    <link rel="stylesheet" type="text/css" href="css/base.css<?= '?'.bin2hex(random_bytes(50))?>">
    <link rel="stylesheet" type="text/css" href="css/header.css<?= '?'.bin2hex(random_bytes(50))?>">
    <link rel="stylesheet" href="css/style.css<?= '?'.bin2hex(random_bytes(50))?>">
</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0CGV6VFGRE"></script> <script>   window.dataLayer = window.dataLayer || [];   function gtag(){dataLayer.push(arguments);}   gtag('js', new Date());   gtag('config', 'G-0CGV6VFGRE'); </script>

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
            <div class="header__content--item header__account av-hidden-sm av-hidden-xs header__link--account-box">
              <a class="header__link_prov">Estamos de cara nova. Em breve, mais novidades!</a>
            </div>
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
          <nav class="nav-menu" style="display: flex; justify-content: center; flex-wrap: wrap;">
             <div class="close-nav-menu">
                <img src="img_base/sprites/close.svg" alt="close">
             </div>
             <ul class="menu">
                <li class="menu-item menu-item-has-children">
                   <a href="localizar_lojas.php" data-toggle="sub-menu">Nossas Lojas<span class="material-icons" style="margin-left: 5px;">location_on</span></a>
                </li>
                <li class="menu-item menu-item-has-children">
                   <a href="https://www3.deltasul.com.br/boleto/" data-toggle="sub-menu">Pague seu carnê<span class="material-icons" style="margin-left: 5px;">paid</span></a>
                </li>
                <!-- <li class="menu-item menu-item-has-children">
                   <a href="blog.php" data-toggle="sub-menu">Blog<span class="material-icons" style="margin-left: 5px;">forum</span></a>
                </li> -->
              </ul>
           </nav>
          </div>
        </div>
      </div>
    </div>
  </header>

  <script src="js/menu_mobile.js"></script>
  <script src="js/header.js"></script>