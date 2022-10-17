<?php
    include_once('connections.php');
    
    if (!isset($_SESSION)) session_start();

    $PROD_COD                       = trim($_POST['prod_cod']);
    $acao                           = $_POST["acao"];
    $registro                       = $_POST["registro"];
    $produto                        = $_POST["produto"];
    $referencia                     = $_POST["referencia"];
    $referenciaagregado             = $_POST["referenciaagregado"];
    $oferta_ref                     = $_POST["oferta_ref"];
    $cat                            = $_POST["cat"];
    $sub_cat                        = $_POST["sub_cat"];
    $json                           = $_POST["json"];
    $atualiza_ficha_id              = $_POST["atualiza_ficha_id"];
    $atualiza_ficha_descr           = $_POST["atualiza_ficha_descr"];
    $sequencia                      = $_POST["sequencia"];
    $cat_subcat_cad                 = $_POST["item_ficha_name"];
    $itens_exibe_cadastro_ficha     = $_POST["itens_exibe_cadastro_ficha"];
    $nome                           = $_POST["nome"];
    $data_exibicao                  = $_POST["data_exibicao"];
    $data_expiracao                 = $_POST["data_expiracao"];
    $descr                          = $_POST["descr"];
    $url                            = $_POST["url"];
    $contato_cliente                = $_POST["contato_cliente"];
    $agora                          = date('d/m/Y - H:i:s');
    $agoraref                       = date('dmYHis');
    $agora_dia                      = date('w');
    $usu_logado                     = $_SESSION['user'];

    if($acao=="produtos_exportar_csv"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $caminho = $_SERVER['DOCUMENT_ROOT'] . "/csv/lista_produtos/".date('d_m_Y')."/";

            if(!is_dir($caminho)){ mkdir($caminho); }

            $csv = ''.$caminho.date('H_i_s').'_lista_produtos.csv';

            $headers = ['REGISTRO', 'REFERENCIA', 'CATEGORIA', 'SUBCATEGORIA', 'DESCRICAO', 'DESCRICAO_WEB', 'MARCA', 'CADASTRO', 'USUARIO'];

            $file_pointer = fopen($csv, 'w');
            fputcsv($file_pointer , $headers);

            $sql = "
            SELECT 
                A.registro                AS REGISTRO,
                A.referencia              AS REFERENCIA,
                B.descricao               AS CATEGORIA,
                C.descricao            	  AS SUBCATEGORIA,
                A.descricao               AS DESCRICAO,
                A.descricaoweb            AS DESCRICAOWEB,
                A.marca                   AS MARCA,
                A.cadastro                AS CADASTRO,
                A.usuario                 AS USUARIO
            FROM
                web_produto A,
                web_categoria B,
                web_subcategoria C
            WHERE
            	(A.categoria = B.registro AND A.subcategoria = C.registro)
            AND
                (A.primeiroacesso = 'N' AND A.bloqueado = 'N' AND A.status = 'S')
            AND
                (B.status = 'S' AND C.status = 'S')
            ";
            // echo '<pre>' . $sql . '</pre>'; exit;
            $stmt = $pdo->query($sql);
            $i = 0;
            foreach ($stmt as $avaliacoes_clientes){
                $json_base[$i][] = $avaliacoes_clientes['REGISTRO'];
                $json_base[$i][] = $avaliacoes_clientes['REFERENCIA'];
                $json_base[$i][] = $avaliacoes_clientes['CATEGORIA'];
                $json_base[$i][] = $avaliacoes_clientes['SUBCATEGORIA'];
                $json_base[$i][] = $avaliacoes_clientes['DESCRICAO'];
                $json_base[$i][] = $avaliacoes_clientes['DESCRICAOWEB'];
                $json_base[$i][] = $avaliacoes_clientes['MARCA'];
                $json_base[$i][] = date('d/m/Y', strtotime($avaliacoes_clientes['CADASTRO']));
                $json_base[$i][] = $avaliacoes_clientes['USUARIO'];
                $i++;
            }

            foreach ($json_base as $row) { fputcsv($file_pointer, $row); }

            fclose($file_pointer);

            echo ($csv);
    }


    if($acao=="slider_cadastro"){
        $file = $_FILES["arquivo"]; 
        if(empty($descr) || empty($data_exibicao) || empty($data_expiracao) || empty($file)){
            echo ("error_no_data");
            exit;
        } else if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $accept = ['image/jpeg', 'image/png', 'image/webp'];
            $ext = strtolower($_FILES['arquivo']['type']);
                if (in_array($ext, $accept)) {
                    if ($_FILES["arquivo"]["size"] < 1000000){

                        $caminho = $_SERVER['DOCUMENT_ROOT'] . "/img_base/slider";

                        if (move_uploaded_file($file["tmp_name"], "$caminho/".date('dmYHis').'_'.$file["name"])) { 

                            $sql = "
                            INSERT INTO web_slideshow(
                                descricao,
                                urlarq,
                                url,
                                dataexibe,
                                dataexpira,
                                primeiroacesso,
                                bloqueado,
                                status,
                                usuario,
                                data
                                )

                            VALUES (
                                '$descr',
                                '".date('dmYHis').'_'.$file["name"]."',
                                '$url',
                                '$data_exibicao',
                                '$data_expiracao',
                                'S',
                                'N',
                                'S',
                                '$usu_logado',
                                NOW()
                            )";

                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();

                                echo "success"; 
                        } 
                        else { 
                            echo "error"; 
                        }  
                    } else {
                        echo ('error_size');
                    }
                } else {
                    echo ('error_format');
            }
    }

    if($acao == 'slider_confirmar'){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $sql = "
            UPDATE
                web_slideshow
            SET
                primeiroacesso  = 'N',
                bloqueado       = 'N',
                status          = 'S',
                usuarioalt      = '$usu_logado',
                dataalt         = NOW()
            WHERE
                REGISTRO        = $registro
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
        echo ("success");
    }

    if($acao == 'slider_excluir'){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $sql = "
            UPDATE
                web_slideshow
            SET
                primeiroacesso  = 'N',
                bloqueado       = 'S',
                status          = 'S',
                usuarioalt      = '$usu_logado',
                dataalt         = NOW()
            WHERE
                REGISTRO        = $registro
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
        echo ("success");
    }

    if($acao == 'slider_alterar'){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $sql = "
            UPDATE
                web_slideshow
            SET
                descricao       = '$descr',
                url             = '$url',
                dataexibe       = '$data_exibicao',
                dataexpira      = '$data_expiracao',
                primeiroacesso  = 'S',
                bloqueado       = 'N',
                status          = 'S',
                usuarioalt      = '$usu_logado',
                dataalt         = NOW()
            WHERE
                REGISTRO        = $registro
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
      
        echo ("success");
    }

    if($acao == 'avaliacoes_clientes_excluir'){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $sql = "
            UPDATE
                web_avaliacaocliente
            SET
                primeiroacesso  = 'N',
                bloqueado       = 'S',
                excluido        = 'S',
                usuarioalt      = '$usu_logado',
                dataalt         = NOW()
            WHERE
                REGISTRO        = $registro
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

        echo ("success");
    }

                    
    if($acao == 'avaliacoes_clientes_confirmar'){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $sql = "
            UPDATE
                web_avaliacaocliente
            SET
                primeiroacesso = 'N',
                bloqueado      = 'N',
                excluido       = 'N',
                usuarioalt     = '$usu_logado',
                dataalt        = NOW()
            WHERE
                REGISTRO        = $registro
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

        echo ("success");
    }

    if($acao=="avaliacoes_clientes_exportar_csv"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $caminho = $_SERVER['DOCUMENT_ROOT'] . "/csv/avaliacoes_clientes/".date('d_m_Y')."/";

            if(!is_dir($caminho)){ mkdir($caminho); }

            $csv = ''.$caminho.date('H_i_s').'_avaliacoes_clientes.csv';

            $headers = ['REGISTRO', 'NOME', 'DESCRICAO', 'ESTRELAS', 'PROD_REGISTRO', 'DATA'];

            $file_pointer = fopen($csv, 'w');
            fputcsv($file_pointer , $headers);

            $sql = "
            SELECT
                registro                    AS REGISTRO,
                nome                        AS NOME,
                descricao                   AS DESCR,
                stars                       AS STARS,
                referencia                  AS PROD_REGISTRO,
                data                        AS DATA
            FROM
                web_avaliacaocliente
            ORDER BY
                registro
            ";
            // echo '<pre>' . $sql . '</pre>'; exit;
            $stmt = $pdo->query($sql);
            $i = 0;
            foreach ($stmt as $avaliacoes_clientes){
                $json_base[$i][] = $avaliacoes_clientes['REGISTRO'];
                $json_base[$i][] = $avaliacoes_clientes['NOME'];
                $json_base[$i][] = $avaliacoes_clientes['DESCR'];
                $json_base[$i][] = $avaliacoes_clientes['STARS'];
                $json_base[$i][] = $avaliacoes_clientes['PROD_REGISTRO'];
                $json_base[$i][] = date('d/m/Y', strtotime($avaliacoes_clientes['DATA']));
                $i++;
            }

            foreach ($json_base as $row) { fputcsv($file_pointer, $row); }

            fclose($file_pointer);

            echo ($csv);
    }


    if($acao=="click_interesse_prod_exportar_csv"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $caminho = $_SERVER['DOCUMENT_ROOT'] . "/csv/click_interesse_prod/".date('d_m_Y')."/";

            if(!is_dir($caminho)){ mkdir($caminho); }

            $csv = ''.$caminho.date('H_i_s').'_click_interesse_prod.csv';

            $headers = ['REGISTRO', 'REFERENCIA', 'CIDADE', 'ESTADO', 'CKLOJA', 'CEP', 'DATA'];

            $file_pointer = fopen($csv, 'w');
            fputcsv($file_pointer , $headers);

            $sql = "
            SELECT 
                registro            AS REGISTRO,
                referencia          AS REFERENCIA,
                cidade              AS CIDADE,
                estado              AS ESTADO,
                ckloja              AS CKLOJA,
                cep                 AS CEP,
                data                AS DATA
            FROM 
                web_buscaproduto
            ";
            // echo '<pre>' . $sql . '</pre>'; exit;
            $stmt = $pdo->query($sql);
            $i = 0;
            foreach ($stmt as $click_interesse_prod){
                $json_base[$i][] = $click_interesse_prod['REGISTRO'];
                $json_base[$i][] = $click_interesse_prod['REFERENCIA'];
                $json_base[$i][] = $click_interesse_prod['CIDADE'];
                $json_base[$i][] = $click_interesse_prod['ESTADO'];
                $json_base[$i][] = $click_interesse_prod['CKLOJA'];
                $json_base[$i][] = $click_interesse_prod['CEP'];
                $json_base[$i][] = date('d/m/Y', strtotime($click_interesse_prod['DATA']));
                $i++;
            }

            foreach ($json_base as $row) { fputcsv($file_pointer, $row); }

            fclose($file_pointer);

            echo ($csv);
    }

    if($acao=="localizacao_acesso_exportar_csv"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $caminho = $_SERVER['DOCUMENT_ROOT'] . "/csv/localizacao_acesso/".date('d_m_Y')."/";

            if(!is_dir($caminho)){ mkdir($caminho); }

            $csv = ''.$caminho.date('H_i_s').'_localizacao_acesso.csv';

            $headers = ['REGISTRO', 'LOCALIZACAO', 'BAIRRO', 'CIDADE', 'ESTADO', 'PAIS', 'PLACE_NAME', 'DATA'];

            $file_pointer = fopen($csv, 'w');
            fputcsv($file_pointer , $headers);

            $sql = "
            SELECT
                registro                    AS REGISTRO,
                localizacao                 AS LOCALIZACAO,
                bairro                      AS BAIRRO,
                cidade                      AS CIDADE,
                estado                      AS ESTADO,
                pais                        AS PAIS,
                placename                   AS PLACE_NAME,
                data                        AS DATA
            FROM
                web_localacesso
            ORDER BY
                registro
            ";
            // echo '<pre>' . $sql . '</pre>'; exit;
            $stmt = $pdo->query($sql);
            $i = 0;
            foreach ($stmt as $avaliacoes_clientes){
                $json_base[$i][] = $avaliacoes_clientes['REGISTRO'];
                $json_base[$i][] = $avaliacoes_clientes['LOCALIZACAO'];
                $json_base[$i][] = $avaliacoes_clientes['BAIRRO'];
                $json_base[$i][] = $avaliacoes_clientes['CIDADE'];
                $json_base[$i][] = $avaliacoes_clientes['ESTADO'];
                $json_base[$i][] = $avaliacoes_clientes['PAIS'];
                $json_base[$i][] = $avaliacoes_clientes['PLACE_NAME'];
                $json_base[$i][] = date('d/m/Y', strtotime($avaliacoes_clientes['DATA']));
                $i++;
            }

            foreach ($json_base as $row) { fputcsv($file_pointer, $row); }

            fclose($file_pointer);

            echo ($csv);
    }

    if($acao=="contato_cliente_cadastro"){
        if(empty($contato_cliente)){
            echo ("Preencha todos os campos!");
            exit;
        }
            $sql = "
            INSERT INTO web_contatocliente(
                contatocliente,
                primeiroacesso,
                bloqueado,
                excluido,
                usuario,
                data
                )
            VALUES (
                '$contato_cliente',
                'S',
                'N',
                'N',
                'ROOT',
                NOW()
            )";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

        echo ("Recebemos seu contato com sucessso!");
    };

    if($acao=="contato_cliente_exportar_csv"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $caminho = $_SERVER['DOCUMENT_ROOT'] . "/csv/contato_cliente/".date('d_m_Y')."/";

            if(!is_dir($caminho)){ mkdir($caminho); }

            $csv = ''.$caminho.date('H_i_s').'_contatos_site_deltasul.csv';

            $headers = ['REGISTRO', 'CONTATO', 'DATA_CADASTRO'];

            $file_pointer = fopen($csv, 'w');
            fputcsv($file_pointer , $headers);

            $sql = "
            SELECT
                registro                    AS REGISTRO,
                contatocliente              AS CONTATO_CLIENTE,
                data                        AS DATA
            FROM
                web_contatocliente
            ORDER BY
                registro
            ";
            // echo '<pre>' . $sql . '</pre>'; exit;
            $stmt = $pdo->query($sql);
            $i = 0;
            foreach ($stmt as $row_contato_cliente){
                $json_base[$i][] = $row_contato_cliente['REGISTRO'];
                $json_base[$i][] = $row_contato_cliente['CONTATO_CLIENTE'];
                $json_base[$i][] = date('d/m/Y', strtotime($row_contato_cliente['DATA']));
                $i++;
            }

            foreach ($json_base as $row) { fputcsv($file_pointer, $row); }

            fclose($file_pointer);

            echo ($csv);
    }
                                    
                                    
    if($acao=="avaliacoes_clientes_cadastro"){
        if(empty($oferta_registro) || empty($nome) || empty($descr) || empty($stars)){
            echo ("error_no_data");
            exit;
        } else if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $stars                          = $_POST["stars"];
            $sql = "
            INSERT INTO web_avaliacaocliente(
                referencia,
                produto,
                nome,
                descricao,
                stars,
                primeiroacesso,
                bloqueado,
                excluido,
                usuario,
                data
                )
    
            VALUES (
                '$registro',
                '$produto',
                '$nome',
                '$descr',
                '$stars',
                'S',
                'N',
                'N',
                '$usu_logado',
                NOW()
            )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();

        echo ("success");
    };
                                    
                                    
                                    
    if($acao=="click_interesse_prod_cadastro"){

        $cep_pesquisa                   = $_POST["cep_pesquisa"];
        $ckloja                         = $_POST["ckloja"];
        $cidade_pesquisa                = $_POST["cidade_pesquisa"];

        $sql = "
        INSERT INTO web_buscaproduto(
            referencia,
            produto,
            cidade,
            ckloja,
            cep,
            data,
            diasemana
            )

        VALUES (
            '$produto',
            '$registro',
            '$cidade_pesquisa',
            '$ckloja',
            '$cep_pesquisa',
            NOW(),
            '$agora_dia'
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
                                    
                                    
    if($acao=="localizacao_acesso_cadastrar"){
        $localizacao                    = $_POST["localizacao"];
        $bairro                         = $_POST["bairro"];
        $cidade                         = $_POST["cidade"];
        $estado                         = $_POST["estado"];
        $pais                           = $_POST["pais"];
        $place_name                     = $_POST["place_name"];
        $pagina                         = $_POST["pagina"];

        if(empty($localizacao)){
            exit;
        }
        
            $sql = "
            INSERT INTO web_localacesso(
                localizacao,
                bairro,
                cidade,
                estado,
                pais,
                placename,
                pagina,
                data,
                hora,
                diasemana
                )

            VALUES (
                '$localizacao',
                '$bairro',
                '$cidade',
                '$estado',
                '$pais',
                '$place_name',
                '$pagina',
                current_date(),
                current_time(),
                '$agora_dia'
            )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        
    };

    if($acao == "pesq_cod"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
        
        $sql = "
        SELECT 
            registro                          AS TOT_REGISTRO
        FROM 
            web_produtoitemfichatecnica
        WHERE
            referencia = '$PROD_COD'
        ";
        // echo "<pre>" . $sql . "</pre>";exit;
        $verifica_itens = $pdo->query($sql);
        if($verifica_itens->rowCount() != null){
            $sql = "
            SELECT 
                A.registro                          AS REGISTRO,
                A.descricao                         AS NOME,
                A.marca                             AS MARCA,
                A.descricaoweb                      AS DESCRICAO_WEB,
                A.status                            AS STATUS,
                (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND sequencia = 1 AND status = 'S' AND primeiroacesso = 'N' AND bloqueado = 'N') AS IMG_1,
                (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND sequencia = 2 AND status = 'S' AND primeiroacesso = 'N' AND bloqueado = 'N') AS IMG_2,
                (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND sequencia = 3 AND status = 'S' AND primeiroacesso = 'N' AND bloqueado = 'N') AS IMG_3,
                (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND sequencia = 4 AND status = 'S' AND primeiroacesso = 'N' AND bloqueado = 'N') AS IMG_4,
                (SELECT url FROM web_produtoimagem WHERE referencia = A.referencia AND sequencia = 5 AND status = 'S' AND primeiroacesso = 'N' AND bloqueado = 'N') AS IMG_5
            FROM 
                web_produto A
            WHERE
                A.referencia = '$PROD_COD'
            ";
            // echo "<pre>" . $sql . "</pre>";exit;
            $stmt = $pdo->query($sql);
            while($row_pesq_cod = $stmt->fetch()){
                $array['item'][0] 			                                = $row_pesq_cod['NOME'];
                $array['item'][1] 			                                = $row_pesq_cod['MARCA'];
                $array['item'][2] 			                                = $row_pesq_cod['REGISTRO'];
                $array['item'][3] 			                                = $row_pesq_cod['DESCRICAO_WEB'];
                $array['status'][0] 			                            = 'S';
                $array['status_banco'][0] 			                        = $row_pesq_cod['STATUS'];
                $array['imagens']['url'][1]                                 = $row_pesq_cod['IMG_1'];
                $array['imagens']['url'][2]                                 = $row_pesq_cod['IMG_2'];
                $array['imagens']['url'][3]                                 = $row_pesq_cod['IMG_3'];
                $array['imagens']['url'][4]                                 = $row_pesq_cod['IMG_4'];
                $array['imagens']['url'][5]                                 = $row_pesq_cod['IMG_5'];
            }

            $sql = "
            SELECT 
                A.descricao							AS DESCR_FICHA,
                A.itemfichatecnica                  AS ITEM_FICHA,
                B.descricao                         AS NOME_ITEM_FICHA
            FROM 
                web_produtoitemfichatecnica A,
                web_itemfichatecnica B
            WHERE
                A.referencia = '$PROD_COD'
            AND
                A.itemfichatecnica = B.registro
            ";
            // echo "<pre>" . $sql . "</pre>";exit;
            $xx=0;
            $stmt = $pdo->query($sql);
            while($row_pesq_cod = $stmt->fetch()){
                $array['itens_ficha']['descr_ficha'][$xx] 			        = $row_pesq_cod['DESCR_FICHA'];
                $array['itens_ficha']['ref'][$xx] 			                = $row_pesq_cod['ITEM_FICHA'];
                $array['itens_ficha']['nome'][$xx] 			                = $row_pesq_cod['NOME_ITEM_FICHA'];
                $xx++;
            }

            echo(json_encode($array));
            
        } else {
            $sql = "
            SELECT 
                A.registro                          AS REGISTRO,
                A.descricao                         AS NOME,
                A.marca                             AS MARCA,
                B.itemfichatecnica                  AS ITEM_FICHA,
                C.descricao                         AS NOME_ITEM_FICHA
            FROM 
                web_produto A,
                web_subcategoriaitemfichatecnica B,
                web_itemfichatecnica C
            WHERE
                A.referencia = '$PROD_COD'
            AND
                A.subcategoria = B.subcategoria
            AND
                B.itemfichatecnica = C.registro
            ";
            // echo "<pre>" . $sql . "</pre>";exit;
            $xx=0;
            $stmt = $pdo->query($sql);
            if ($stmt->rowCount() == null){ echo "no_result"; exit;}
            while($row_pesq_cod = $stmt->fetch()){
                $array['item'][0] 			                                = $row_pesq_cod['NOME'];
                $array['item'][1] 			                                = $row_pesq_cod['MARCA'];
                $array['item'][2] 			                                = $row_pesq_cod['REGISTRO'];
                $array['status'][0] 			                            = 'N';
                $array['itens_ficha']['ref'][$xx] 			                = $row_pesq_cod['ITEM_FICHA'];
                $array['itens_ficha']['nome'][$xx] 			                = $row_pesq_cod['NOME_ITEM_FICHA'];
                $xx++;
            }
            echo(json_encode($array));
        }
      
    }

    if($acao=="cadastro_item_ficha_categoria_sub"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
        
        $cat_subcat = explode(" - ", $cat_subcat_cad);

        $sql = "
        DELETE FROM web_subcategoriaitemfichatecnica WHERE subcategoria = '$cat_subcat[1]'
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        

        $sql = "
        INSERT INTO web_subcategoriaitemfichatecnica(
            subcategoria,
            itemfichatecnica,
            status
            )

        VALUES
        ";
        for($xx=0; $xx < count($itens_exibe_cadastro_ficha); $xx++){
        if($xx>0){ $sql .= " , "; }
        $sql .= "
        (
            '$cat_subcat[1]',
            '$itens_exibe_cadastro_ficha[$xx]',
            'S'
        )
        ";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        echo 'success';
        
    }

    if($acao=="cadastro_permissoes"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
        
            $perfil_permissao               = $_POST["item_perfil_name"];
            $itens_permissao                = $_POST["itens_permissao"];
            $admin_permissao                = $_POST["admin_permissao"];

        $sql = "
        DELETE FROM in_sistema_acesso WHERE registro_perfil = '$perfil_permissao'
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        

        $sql = "
        INSERT INTO in_sistema_acesso(
            id_permissao,
            registro_perfil,
            admin,
            data_cad,
            usu_cad
            )

        VALUES
        ";
        for($xx=0; $xx < count($itens_permissao); $xx++){
        if($xx>0){ $sql .= " , "; }
        $sql .= "
        (
            '$itens_permissao[$xx]',
            '$perfil_permissao',
            '$admin_permissao',
            NOW(),
            '$usu_logado'
        )
        ";
        }


        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        echo 'success';
        
    }

    if($acao=="cadastro_permissoes_view"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }

            $perfil_permissao               = $_POST["item_perfil_name"];       

            $sql = "
            SELECT
                A.id_permissao            AS ID_PERMISSAO
            FROM
                in_sistema_acesso A,
                in_sistema_perfil B
            WHERE
                A.registro_perfil = B.registro
            AND 
                B.descricao = '$perfil_permissao'
            ";
            //echo "<pre>" . $sql . "</pre>";exit;
            $stmt = $pdo->query($sql);
            $xx=0;
            while($row_permissoes = $stmt->fetch()){
                $array[$xx] 			                                = $row_permissoes['ID_PERMISSAO'];
                $xx++;
            }

            echo(json_encode($array));

    }

    if($acao=="cadastro_fotos_ficha_tecnica"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
        $accept = ['image/jpeg', 'image/png', 'image/webp'];
        $ext = strtolower($_FILES['upload']['type']);
        $tmpFilePath = $_FILES['upload']['tmp_name'];
        if ($tmpFilePath != ""){
            if (in_array($ext, $accept)) {
                if ($_FILES["upload"]["size"] < 1000000){
                    $caminho = $_SERVER['DOCUMENT_ROOT'] . "/img_base/produtos/" . preg_replace('/[^0-9]/', '', $referencia);
                    if(!is_dir($caminho)){
                        mkdir($caminho);
                    }
                    $newFilePath = $_SERVER['DOCUMENT_ROOT'] . "/img_base/produtos/" . preg_replace('/[^0-9]/', '', $referencia) . "/" . $agoraref . $_FILES['upload']['name'];
                    $nome_arquivo = $agoraref . $_FILES['upload']['name'];
                        if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                            $verifica_itens = $pdo->query("select * from web_produtoimagem WHERE referencia = '$referencia' AND sequencia = '$sequencia'");
                            if($verifica_itens->rowCount() != null){
                                $sql = "
                                UPDATE 
                                    web_produtoimagem
                                SET
                                    status = 'N',
                                    primeiroacesso = 'S',
                                    bloqueado= 'S'
                                WHERE 
                                    referencia = '$referencia'
                                AND 
                                    sequencia = '$sequencia'
                                ";       

                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                            }

                                $sql = "
                                INSERT INTO web_produtoimagem(
                                    referencia,
                                    produto,
                                    status,
                                    primeiroacesso,
                                    bloqueado,
                                    url,
                                    sequencia
                                    )
                        
                                VALUES
                                    (
                                    '$referencia',
                                    '$produto',
                                    'S',
                                    'N',
                                    'N',
                                    '$nome_arquivo',
                                    '$sequencia'
                                    )
                                ";                                

                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();

                                echo 'success';
                        }
                    } else {
                        echo ('error_size');
                    }
                } else {
                    echo ('error_format');
                }
            }
    }

    if($acao=="cadastro_ficha_tecnica"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }

        $json           = json_decode($json);
        $referencia     = explode(":", $json[0]);
        $produto        = explode(":", $json[1]);
        $descr_web      = explode(":", $json[2]);
        $descr          = explode(":", $json[3]);

        $pdo->beginTransaction();
        
        $sql_update = "
        UPDATE 
            web_produto
        SET 
            descricaoweb    = '$descr_web[1]',
            primeiroacesso  = 'N',
            bloqueado       = 'N',
            status          = 'N',
            cadastro        = NOW(),
            usuario         = '$usu_logado'
        WHERE 
            referencia      = '$referencia[1]'
        ";
        $stmt = $pdo->query($sql_update);
        if (!$stmt) {
            $pdo->rollBack();
            die('Erro ao lancar movimento');
        }

        $sql = "
        INSERT INTO web_produtoitemfichatecnica(
            produto,
            referencia,
            itemfichatecnica,
            descricao,
            status,
            cadastro,
            usuario
            )

        VALUES
            (
            '$produto[1]',
            '$referencia[1]',
            '$descr[0]',
            '$descr[1]',
            'S',
            NOW(),
            '$usu_logado'
            )
        ";

        for($xx=4; $xx < count($json); $xx++){
        $item = explode(":", $json[$xx]);
        if($item[1] == null){
           echo('error_no_data');
           exit;
        } else if($item[1] != null){
            $sql .= " ,
            (
                '$produto[1]',
                '$referencia[1]',
                '$item[0]',
                '$item[1]',
                'S',
                NOW(),
                '$usu_logado'
            )
            ";
        }
        }
                    
        $stmt = $pdo->query($sql);
        if (!$stmt) {
            $pdo->rollBack();
            die('Erro ao lancar movimento');
        }
        $pdo->commit();

        echo 'success';
    
    }

    if($acao=="atualizar_ficha_tecnica"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            if($atualiza_ficha_id == 'nome_web'){
                $sql_update = "
                UPDATE 
                    web_produto
                SET 
                    descricaoweb        = '$atualiza_ficha_descr',
                    cadastro            = NOW(),
                    usuario             = '$usu_logado'
                WHERE 
                    registro            = '$registro'
                ";
                $stmt = $pdo->prepare($sql_update);
                $stmt->execute();

                echo 'success';
            } else {
                $verifica_itens = $pdo->query("select * from web_produtoitemfichatecnica where produto = '$registro' AND itemfichatecnica = '$atualiza_ficha_id'");
 
                if($verifica_itens->rowCount() != null){
                    $sql_update = "
                    UPDATE 
                        web_produtoitemfichatecnica
                    SET 
                        descricao           = '$atualiza_ficha_descr',
                        cadastro            = NOW(),
                        usuario             = '$usu_logado'
                    WHERE 
                        produto             = '$registro'
                    AND
                        itemfichatecnica    = '$atualiza_ficha_id'
                    ";
                    $stmt = $pdo->prepare($sql_update);
                    $stmt->execute();
                } else {
                    $sql = "
                    INSERT INTO web_produtoitemfichatecnica(
                        produto,
                        referencia,
                        itemfichatecnica,
                        descricao,
                        status,
                        cadastro,
                        usuario
                        )
            
                    VALUES
                        (
                        '$registro',
                        '$referencia',
                        '$atualiza_ficha_id',
                        '$atualiza_ficha_descr',
                        'S',
                        NOW(),
                        '$usu_logado'
                        )
                    ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                }

                echo 'success';
            }
    }

    if($acao=="atualizar_ficha_tecnica_remove"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $sql_update = "
            DELETE FROM 
                web_produtoitemfichatecnica
            WHERE 
                produto             = '$registro'
            AND
                itemfichatecnica    = '$atualiza_ficha_id'
            ";

            $stmt = $pdo->prepare($sql_update);
            $stmt->execute();

        echo 'success';
    }

    if($acao=="atualizar_ficha_tecnica_remove_foto"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $sql_update = "
            UPDATE 
                web_produtoimagem
            SET 
                status              = 'N',
                primeiroacesso      = 'S',
                bloqueado           = 'S'
            WHERE 
                produto             = '$registro'
            AND
                sequencia           = '$sequencia'
            ";

            $stmt = $pdo->prepare($sql_update);
            $stmt->execute();

        echo 'success!';
    }

    if($acao=="cadastro_itens_agregados"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $sql = "
            INSERT INTO web_produtoagregado(
                produto,
                referencia,
                referenciaagregado,
                status,
                cadastro,
                usuario
                )
    
            VALUES
                (
                '$produto',
                '$referencia',
                '$referenciaagregado',
                'S',
                NOW(),
                '$usu_logado'
                )
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();

        echo 'success';
    }

    if($acao=="confirma_produto_site"){
        if(empty($usu_logado)) {
            echo ("error_user");
            exit;
        }
            $sql = "
            UPDATE
                web_produto
            SET
                status          = 'S',
                usuario         = '$usu_logado'
            WHERE
                referencia      = '$PROD_COD'
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
        echo ("success");
    }

?>