<?php
    include_once('admin/includes/connections.php');

    $sql = "
    SELECT
        A.registro                        as registro_cat,
        A.descricao                       as descricao_cat,
        A.exibemenu                       as exibe_menu,
        B.registro					              as registro_subcat,
        B.descricao						            as descricao_subcat
    FROM
        web_categoria A,
        web_subcategoria B
    WHERE
    	A.registro = B.categoria
    AND 
        A.status = 'S'
    ORDER BY
        A.registro
    ";
    // echo '<pre>' . $sql . '</pre>'; exit; 
    $xy=0;
    $xx=0;
    $xz=0;

    $stmt = $pdo->query($sql);
    while($row_categoria = $stmt->fetch()){
      if($row_categoria['registro_cat'] != $categorias_cat){
      $categorias_cat                                = $row_categoria['registro_cat'];
          
          if($row_categoria['exibe_menu'] == 'S'){
          $categorias_ref_exibe[$xz]                     = $row_categoria['registro_cat'];
          $categorias_descr_exibe[$xz]                   = $row_categoria['descricao_cat'];
          $xz++;
          }
          
      $categorias_ref[$xx]                     = $row_categoria['registro_cat'];
      $categorias_descr[$xx]                   = $row_categoria['descricao_cat'];
      $xx++;
      } 
      
      if($row_categoria['registro_cat'] != $sub_categorias_cat){ $xy = 0; }
      $sub_categorias_cat                                                                    = $row_categoria['registro_cat'];
      $sub_categorias_registro[$row_categoria['registro_cat']][$xy]                      = $row_categoria['registro_subcat'];
      $sub_categorias_descr[$row_categoria['registro_cat']][$xy]                         = $row_categoria['descricao_subcat'];
      $xy++;
    }

    


    // $sql = "
    //   SELECT
    //       REF                         AS REF
    //   FROM
    //       PRODUTOS_OFERTA_DIA
    //   WHERE
    //       DATA_EXIBICAO = CURDATE()
    //    AND
    //       PRIMEIRO_ACESSO = 'N'
    //    AND 
    //       BLOQUEADO = 'N'
    //    AND 
    //       EXCLUIDO = 'N'
    //   ";
    //   // echo '<pre>' . $sql . '</pre>'; exit;
    //   $result_produtos_oferta_dia = $conn->query($sql);
    //   while($row_produtos_oferta_dia = $result_produtos_oferta_dia->fetch_assoc()) {
    //     $oferta_da_semana            = $row_produtos_oferta_dia['REF'];
    //   }


    $hoje = date('d/m/Y');

    $icons = array('chair', 'coffee_maker', 'ac_unit', 'tv', 'smartphone', 'kitchen', 'computer', 'construction');
?>