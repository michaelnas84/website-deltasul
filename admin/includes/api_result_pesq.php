<?php 

    include_once('connections.php');

    $txtBusca = $_POST['txtBusca'];

    $sql = "
    SELECT
      registro                              AS REF,
      descricao                       AS NOME_EXIBE
    FROM
        web_itemfichatecnica
    WHERE
        descricao LIKE '%$txtBusca%'
    AND 
       registro NOT IN ('10','9','8','7','6','5','4','3','2','1','11')
    LIMIT
        10
      ";
    // echo '<pre>' . $sql . '</pre>'; exit;
    $tot_itens=0;
    $stmt = $pdo->query($sql);
    while($row_produtos = $stmt->fetch()){
        echo '<option value="'.$row_produtos["REF"].'">'.$row_produtos["NOME_EXIBE"].'</option>';
      $tot_itens++;
    }

    
?>