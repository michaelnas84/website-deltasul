<?php
      
      $sql = "
      SELECT 
        registro
      FROM 
        web_logcontrole
      WHERE
        servico = 'ROTINA EXC SLIDES OBSOLETOS'
      AND
        date(data) = CURDATE()
      ";
      // echo '<pre>' . $sql . '</pre>'; exit;
      $stmt = $pdo->query($sql);

      if ($stmt->rowCount() == null){
        
        $sql = "
        SELECT
          urlarq                        AS URL_ARQ
        FROM
          web_slideshow
        WHERE
          dataexibe < CURDATE()
        AND 
          dataexpira < CURDATE()
        ";
        // echo '<pre>' . $sql . '</pre>'; exit;
        $stmt = $pdo->query($sql);

        if ($stmt->rowCount() != null){
          foreach ($stmt as $row_slider){
            $file_pointer[] = $row_slider['URL_ARQ']; 
          }
          for($xx=0; $xx <count($file_pointer); $xx++){
            if (!unlink("../img_base/slider/".$file_pointer[$xx])) { 
            } 
            else { 
                $sql = "
                INSERT INTO web_logcontrole(
                  servico,
                  acao,
                  status,
                  usuario,
                  data
                  )

                VALUES (
                  'ROTINA EXC SLIDES OBSOLETOS',
                  'EXCLUI FOTO SLIDE - $file_pointer[$xx]',
                  'S',
                  'ROOT',
                  NOW()
                )";

                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            } 
          }
        }
        $sql = "
        UPDATE 
          web_slideshow
        SET 
          urlarq              = NULL,
          primeiroacesso      = 'N',
          bloqueado           = 'S',
          status              = 'N',
          usuarioalt          = 'ROOT',
          dataalt             =  NOW()
        
        WHERE
          dataexibe < CURDATE()
        AND 
          dataexpira < CURDATE()
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
      }
?> 