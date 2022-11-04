<?php
    include_once('admin/includes/connections.php');

    $sql = "
      SELECT
         registro       AS REGISTRO,
         titulo         AS TITULO,
         img            AS IMG
      FROM
         web_blog
      WHERE
         primeiroacesso	= 'N'
      AND 
         bloqueado = 'N'
      AND 
         status = 'S'
    ";
    // echo '<pre>' . $sql . '</pre>'; exit;   
    $stmt = $pdo->query($sql);
    while($row_blog = $stmt->fetch()){
      $REGISTRO[]                                           = $row_blog['REGISTRO'];
      $TITULO[$row_blog['REGISTRO']]                        = $row_blog['TITULO'];     
      $IMG[$row_blog['REGISTRO']]                           = $row_blog['IMG'];
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <?php include('includes/sections/section_head.php'); ?>
   <link rel="stylesheet" type="text/css" href="css/blog.css<?= '?'.bin2hex(random_bytes(50))?>">
  <script src="js/jquery.min.js<?= '?'.bin2hex(random_bytes(50))?>"></script>
   <title>Blog | Lojas Deltasul</title>
   </head>
<?php include('includes/sections/section_header.php'); ?>
    <body class="home-header">
      <main class="main" id="main">
         <section class="breadcrumb">
            <div class="av-container">
               <div class="av-row">
                  <div class="av-col-xs-24">
                     <div class="blog-section">

                        <?php $xx=0; while($xx < count($REGISTRO)){ ?>
                           <a class="blog-item" href="blog_item.php?ref=<?= $REGISTRO[$xx] ?>">
                              <div class="blog-item-img">
                              <?= $IMG[$REGISTRO[$xx]] ?>
                              </div>
                              <h3><?= $TITULO[$REGISTRO[$xx]] ?></h3>
                           </a>
                        <?php $xx++; } ?>


                     </div>
                  </div>
               </div>
            </div>
         </section>
    </main>
    <?php include('includes/sections/section_footer.php'); ?>
</body>
</html>