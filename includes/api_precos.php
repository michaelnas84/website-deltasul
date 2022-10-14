<?php
    if($GLOBALS['api_preco_key'] == null){
        $curl = curl_init('https://api.deltasul.com.br/token');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, '{"username":"PHPDEVEL","password":"2pu^H2R2Uu5!"}');
        $json_response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($json_response, true);
        $GLOBALS['api_preco_key'] = $response['access_token'];
    }

    $curl = curl_init('https://api.deltasul.com.br/produtos/');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("accept:application/json", "Authorization:Bearer ".$GLOBALS['api_preco_key']."", "Content-Type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content_prods);
    $json_response = curl_exec($curl);
        if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == '401' || curl_getinfo($curl, CURLINFO_HTTP_CODE) == '403'){
            $curl = curl_init('https://api.deltasul.com.br/token');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Content-type: application/json"));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, '{"username":"PHPDEVEL","password":"2pu^H2R2Uu5!"}');
            $json_response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($json_response, true);
            $GLOBALS['api_preco_key'] = $response['access_token'];

            $curl = curl_init('https://api.deltasul.com.br/produtos/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("accept:application/json", "Authorization:Bearer ".$GLOBALS['api_preco_key']."", "Content-Type: application/json"));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content_prods);
            $json_response = curl_exec($curl);
        }

    curl_close($curl);

    $array_precos = json_decode($json_response, true);
?>