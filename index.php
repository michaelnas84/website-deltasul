<?php
    include_once('admin/includes/connections.php');

    $sql = "
    SELECT
      url                             AS URL
    FROM
      web_folhetooferta
    WHERE
      dataexibe <= CURDATE()
    AND 
      dataexpira >= CURDATE()
    AND 
      primeiroacesso	= 'N'
    AND 
      bloqueado = 'N'
    AND 
      status = 'S'
    ";
    // echo '<pre>' . $sql . '</pre>'; exit;   
    $stmt = $pdo->query($sql);
    $URL_FOLHETO = $stmt->fetch();
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
      <?php include('includes/sections/section_slider.php'); ?>


      <?php if($URL_FOLHETO['URL']){ ?>
      <div class="shelf-carousel home-shelf home-shelf-1 section_home_prov">
        <div class="av-container">
          <div class="av-row">
            <div class="av-col-xs-24">
              <div class="home-shelf__content">
                <div class="main-shelf n5colunas">


                  <h2>Veja nosso folheto de ofertas!</h2>
                    

                    <div class="shelf-carousel home-shelf home-shelf-1 iframe">
                        <iframe style="border:none; width: 100%;" id="meuiFrame" src="<?= $URL_FOLHETO['URL'] ?>"></iframe>
                    </div>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>  
      <?php } ?>
      
      </main>
    <?php include('includes/sections/section_footer.php'); ?>
  </body>
</html>