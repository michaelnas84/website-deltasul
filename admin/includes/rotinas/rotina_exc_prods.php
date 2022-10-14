<?php
   $sql = "
            SELECT 
              registro
            FROM 
              web_logcontrole
            WHERE
              servico = 'ROTINA EXC PRODS OBSOLETOS'
            AND
              date(data) = CURDATE()
            ";
   // echo '<pre>' . $sql . '</pre>'; exit;
   $stmt = $pdo->query($sql);
   
   if ($stmt->rowCount() == null)
   {
       $sql = "
            SELECT
               A.referencia                         as referencia,
      		   D.url                                as url_arq
            FROM
               web_produto A,
               web_produtoimagem D
            WHERE 
               A.bloqueado = 'S'
            AND 
               A.status = 'N'
            AND 
               D.referencia = A.referencia
            ";
       // echo '<pre>' . $sql . '</pre>'; exit;
       $stmt = $pdo->query($sql);
   
       if ($stmt->rowCount() != null)
       {
      
           while ($row_produtos = $stmt->fetch())
           {
               $ref[] = $row_produtos['referencia'];
               $folder_arq[] = preg_replace('/[^0-9]/', '', $row_produtos['referencia']);
               $file_pointer[] = $row_produtos['url_arq'];
           }
           for ($xx = 0;$xx < count($file_pointer);$xx++)
           {
               if (!unlink("../img_base/produtos/" . $folder_arq[$xx] . "/" . $file_pointer[$xx]))
               {
               }
               else
               {
                   $sql = "
                  UPDATE 
                     web_produtoimagem
                  SET 
                    primeiroacesso      = 'S',
                    bloqueado           = 'S',
                    status              = 'N'
                  WHERE
                     referencia = '$ref[$xx]'
                  AND 
                     url = '$file_pointer[$xx]'
                  ";
                   $stmt = $pdo->prepare($sql);
                   $stmt->execute();
               
                   $sql = "
                     INSERT INTO web_logcontrole(
                        servico,
                        acao,
                        status,
                        usuario,
                        data
                        )
                     
                     VALUES (
                        'ROTINA EXC PRODS OBSOLETOS',
                        'EXCLUI FOTO PROD - $ref[$xx] - $file_pointer[$xx]',
                        'S',
                        'ROOT',
                        NOW()
                     )";
                     
                   $stmt = $pdo->prepare($sql);
                   $stmt->execute();
               }
           }
       }
    
       $sql2 = "
            SELECT
               referencia                         as referencia,
      		   url                                as url_arq
            FROM
               web_produtoimagem
            WHERE 
               status = 'N'
            AND 
               bloqueado = 'S'
            AND 
               primeiroacesso = 'S'
            ";
       // echo '<pre>' . $sql . '</pre>'; exit;
       $stmt2 = $pdo->query($sql2);
    
       if ($stmt2->rowCount() != null)
       {
           while ($row_produtos = $stmt2->fetch())
           {
               $ref[] = $row_produtos['referencia'];
               $folder_arq[] = preg_replace('/[^0-9]/', '', $row_produtos['referencia']);
               $file_pointer[] = $row_produtos['url_arq'];
           }
           for ($xx = 0;$xx < count($file_pointer);$xx++)
           {
               if (!unlink("../img_base/produtos/" . $folder_arq[$xx] . "/" . $file_pointer[$xx]))
               {
               }
               else
               {
                   $sql = "
                   INSERT INTO web_logcontrole(
                      servico,
                      acao,
                      status,
                      usuario,
                      data
                      )
                  
                   VALUES (
                      'ROTINA EXC PRODS OBSOLETOS',
                      'EXCLUI FOTO PROD - $ref[$xx] - $file_pointer[$xx]',
                      'S',
                      'ROOT',
                      NOW()
                   )";
                  
                   $stmt = $pdo->prepare($sql);
                   $stmt->execute();
               }
           }
       }
   }
?>