<?php
    header("Content-type: text/html; charset=utf-8");

    include_once('connections.php');

    $cidade = $_POST['cidade'].' (RS)';

    $sql = "
    SELECT 
      descricao           as descricao,
      cidade              as cidade,
      cep                 as cep,
      endereco            as endereco,
      numeroender         as numeroender,
      telefone            as telefone,
      ddd                 as ddd,
      logra               as logra,
      razao               as razao,
      bairro              as bairro
    FROM 
      web_cidade
    WHERE 
      cidade = '$cidade'
    ";
    // echo '<pre>' . $sql . '</pre>'; exit;
    $yy=0;
    $stmt = $pdo->query($sql);
    while($row_cidades = $stmt->fetch()){
      if($numeroender != $row_cidades['numeroender']){
        $yy++;
      }
      $numeroender                       = $row_cidades['numeroender'];
      $array[$yy]['DESCR']            = $row_cidades['descricao'];
      $array[$yy]['CIDADE']           = $row_cidades['cidade'];
      $array[$yy]['CEP']              = $row_cidades['cep'];
      $array[$yy]['ENDERECO']         = $row_cidades['endereco'];
      $array[$yy]['NUM_END']          = $row_cidades['numeroender'];
      $array[$yy]['TELEFONES']        = $row_cidades['telefone'];
      $array[$yy]['DDD']              = $row_cidades['ddd'];
      $array[$yy]['LOGRA']            = $row_cidades['logra'];
      $array[$yy]['RAZAO']            = $row_cidades['razao'];
      $array[$yy]['BAIRRO']           = $row_cidades['bairro'];
    }

    echo(json_encode($array));
?>