<?php
    include_once('admin/includes/connections.php');

    $REGISTRO = $_GET["ref"];

    $sql = "
      SELECT
         titulo         AS TITULO,
         texto          AS TEXTO
      FROM
         web_blog
      WHERE
         primeiroacesso	= 'N'
      AND 
         bloqueado = 'N'
      AND 
         status = 'S'
      AND 
         registro = $REGISTRO
    ";
    // echo '<pre>' . $sql . '</pre>'; exit;   
    $stmt = $pdo->query($sql);
    $RETORNO = $stmt->fetch();
    $ITEM = json_decode($RETORNO['TEXTO']);

    $xx=0;
    $zz=0;
    $yy=0;

    while($xx < count($ITEM)){

       if(str_contains($ITEM[$xx], '<img src=')){
         $ITENS_SLIDER[$zz] = $ITEM[$xx];
         $zz++;
       } else {
         $ITENS_TEXTO[$yy] = '<div class="blog-section-item-text">' . $ITEM[$xx] . '</div>';
         $yy++;
       }

       $xx++;
    }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <?php include('includes/sections/section_head.php'); ?>
   <link rel="stylesheet" type="text/css" href="css/blog.css<?= '?'.bin2hex(random_bytes(50))?>">
  <script src="js/jquery.min.js<?= '?'.bin2hex(random_bytes(50))?>"></script>
   <title><?= $RETORNO['TITULO'] ?> | Lojas Deltasul</title>
   </head>
<?php include('includes/sections/section_header.php'); ?>
    <body class="home-header">
      <main class="main" id="main">
         <section class="breadcrumb">
            <div class="av-container">
               <div class="av-row">
                  <div class="av-col-xs-24">
                     <div class="blog-section-item">

                     <div class="blog-item-title">
                        <h3><?= $RETORNO['TITULO'] ?></h3>
                     </div>
                     
                        
                     <?php if($ITENS_SLIDER) { ?>
                        <div class="slider">
                           <?php for($xx=0; $xx < count($ITENS_SLIDER); $xx++) { ?>
                              <div class="slide">
                              <?= $ITENS_SLIDER[$xx]; ?>
                              </div>
                           <?php } ?>
                           <button class="btn btn-next">></button>
                           <button class="btn btn-prev"><</button>
                        </div>
                     <?php } ?>

                     <?php if($ITENS_TEXTO) { for($xx=0; $xx < count($ITENS_TEXTO); $xx++) { echo $ITENS_TEXTO[$xx]; } } ?>


                        <script src="js/slider_blog.js<?= '?'.bin2hex(random_bytes(50))?>"></script>

                     </div>
                  </div>
               </div>
            </div>
         </section>
    </main>
    <?php include('includes/sections/section_footer.php'); ?>
</body>
</html>