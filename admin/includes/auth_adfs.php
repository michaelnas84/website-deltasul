<?php
session_start(); session_destroy();
session_start();
$ldap_password = 'Y]G,SGI>ky';
$ldap_username = 'php-service@deltasul.com.br';
$server        = "ldaps://adfs.deltasul.com.br:636";
$dominio       = '@deltasul.com.br';
$ldap_base_dn  = 'OU=deltasul,DC=deltasul,DC=com,DC=br';

$user            = trim(str_replace($dominio,'',strtolower($_POST['user']))); //$_POST['user'];
$pass            = $_POST['pass'];
$search_filter   = "(&(samaccountname=$user))";
putenv('LDAPTLS_REQCERT=never');
putenv('LDAPTLS_CIPHER_SUITE=NORMAL:!VERS-TLS1.2');
$ldap_connection = @ldap_connect($server); // Your domain or domain server

ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3); // Recommended for AD

// Now try to authenticate with credentials provided by user
if (! @ldap_bind($ldap_connection, $user.$dominio, $pass)) {
    header('Location: ../index.php?usu=1');
    exit;
} else {

 ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
 ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.
 
    if (TRUE === @ldap_bind($ldap_connection, $ldap_username, $ldap_password)) {

        //Your domains DN to query
       // $ldap_base_dn = 'OU=deltasul,DC=deltasul,DC=com,DC=br';

        //Connect to LDAP
        $result = @ldap_search($ldap_connection, $ldap_base_dn, $search_filter);

        if (FALSE !== $result) {
            $entries = @ldap_get_entries($ldap_connection, $result);
            //print_r($entries);
            for ($x = 0; $x < $entries['count']; $x++) {

                 if (!empty($entries[$x]['samaccountname'][0])) {
                         $ldap_user = $entries[$x]['samaccountname'][0];
                        if ($ldap_user == "NULL") {
                            $ldap_user = "";
                        }
                    } 
                 if (!empty($entries[$x]['department'][0])) {
                        $ldap_loja = $entries[$x]['department'][0];
                        if ($ldap_loja == "NULL") {
                            $ldap_loja = "";
                        }
                    }
                if (!empty($entries[$x]['title'][0])) {
                         $ldap_perfil = $entries[$x]['title'][0];
                        if ($ldap_perfil == "NULL") {
                            $ldap_perfil = "";
                        }
                } 
                 if (!empty($entries[$x]['employeeid'][0])) {
                         $ldap_cpf = $entries[$x]['employeeid'][0];
                        if ($ldap_cpf == "NULL") {
                            $ldap_cpf = "";
                        }
                }
                 if (!empty($entries[$x]['sn'][0])) {
                    $ldap_nome     =  $entries[$x]['givenname'][0].' '. $entries[$x]['sn'][0];
                     $ldap_apelido =  $entries[$x]['givenname'][0];
                    if ($ldap_nome == "NULL") {
                        $ldap_nome = "";
                    }
                 }
                 if (!empty($entries[$x]['cn'][0])) {
                    $ldap_nome_completo =  $entries[$x]['cn'][0];
                    if ($ldap_nome_completo == "NULL") {
                        $ldap_nome_completo = "";
                    }
                 }
            }

            include_once('connections.php');

            $sql = "
            SELECT
                A.id_permissao                  AS ID_PERMISSAO,
                A.admin                         AS ADMIN
            FROM
                in_sistema_acesso A,
                in_sistema_perfil B
            WHERE
                B.descricao = '".trim($ldap_perfil)."'
            AND
            	A.registro_perfil = B.registro
    
            ";
            // echo '<pre>' . $sql . '</pre>'; exit;
            $stmt = $pdo->query($sql);
            while($row_usuario = $stmt->fetch()){
                $_SESSION['permissoes'][]       = $row_usuario['ID_PERMISSAO'];
                $_SESSION['admin']              = $row_usuario['ADMIN'];
            }

            if(!$_SESSION['permissoes']){
                header('Location: ../index.php?usu=5');
                exit;
            }
            
            $_SESSION['user']               = $ldap_user;
            $_SESSION['nome']               = $ldap_nome;
            $_SESSION['apelido']            = $ldap_apelido;
            $_SESSION['department']         = str_pad(preg_replace("/[^0-9]/", "", $ldap_loja), 3, "0", STR_PAD_LEFT);
            $_SESSION['cpf']                = preg_replace("/[^0-9]/", "", $ldap_cpf);
            $_SESSION['nome_usuario']       = $ldap_nome_completo;
        
            header("Location: ../dashboard_index.php");

        }
    }
}
?>