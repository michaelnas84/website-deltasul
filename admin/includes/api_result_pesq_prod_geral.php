<?php 

    include_once('connections.php');

    $txtBusca = $_POST['txtBusca'];

    $sql = "
    SELECT
      registro                              AS registro,
      referencia                            AS referencia,
      descricao                             AS descricao,
      descricaoweb                          AS descricaoweb,
      marca                                 AS marca
    FROM
      web_produto
    WHERE
      descricao LIKE '%$txtBusca%'
    OR
      referencia LIKE '%$txtBusca%'
    LIMIT
        10
      ";
    // echo '<pre>' . $sql . '</pre>'; exit;
    $tot_itens=0;
    $stmt = $pdo->query($sql);
    while($row_produtos = $stmt->fetch()){
        echo '<option value="'.$row_produtos["referencia"].'">'.$row_produtos["descricao"].'</option>';
      $tot_itens++;
    }

    
?>