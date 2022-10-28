<?php
      include_once('admin/includes/connections.php');

      $sql = "
      SELECT
        descricao                       AS DESCR,
        urlarq                          AS URL_ARQ,
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
          $slider['descr'][$tot_sliders]          = $row_slider['DESCR'];
          $slider['url_arq'][$tot_sliders]        = $row_slider['URL_ARQ'];
          $slider['url'][$tot_sliders]            = $row_slider['URL'];
          $tot_sliders++;
      }
?>