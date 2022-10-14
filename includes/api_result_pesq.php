<?php 
    include_once('../admin/includes/connections.php');

    $txtBusca = $_POST['txtBusca'];

    $sql = "
    SELECT
      referencia                              AS REF,
      descricaoweb                       AS NOME_EXIBE
    FROM
      web_produto
    WHERE
        (descricaoweb LIKE '%$txtBusca%' AND status = 'S')
    OR
      (descricao LIKE '%$txtBusca%' AND status = 'S')
    OR
      (marca LIKE '%$txtBusca%' AND status = 'S')
    OR
      (referencia LIKE '%$txtBusca%' AND status = 'S')
    LIMIT
        10
      ";
    // echo '<pre>' . $sql . '</pre>'; exit;
    $stmt = $pdo->query($sql);
    while($row_produtos = $stmt->fetch()){
        echo '<option value="'.$row_produtos["NOME_EXIBE"].'">'.$row_produtos["REF"].'</option>';
    }    
?>