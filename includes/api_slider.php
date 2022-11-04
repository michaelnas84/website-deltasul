<?php
      include_once('../admin/includes/connections.php');

      $sql = "
      SELECT
        urlarq                          AS URL_ARQ,
        urlarq_mobile                   AS URL_ARQ_MOBILE,
        url                             AS URL
      FROM
        web_slideshow
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
      ORDER BY
        registro DESC
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $tot_sliders = 0;

      $stmt = $pdo->query($sql);
        while($row_slider = $stmt->fetch()){
          $slider[$tot_sliders]['URL_ARQ']          = $row_slider['URL_ARQ'];
          $slider[$tot_sliders]['URL_ARQ_MOBILE']   = $row_slider['URL_ARQ_MOBILE'];
          $slider[$tot_sliders]['URL']              = $row_slider['URL'];
          $tot_sliders++;
      }

      echo json_encode($slider);
?>