<?php 

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'bibliotecas/PHPMailer-master/src/Exception.php';
    require 'bibliotecas/PHPMailer-master/src/PHPMailer.php';
    require 'bibliotecas/PHPMailer-master/src/SMTP.php';

    $acao = $_POST["acao"];

    function verifica_celular($telefone){
        $telefone= trim(str_replace('/', '', str_replace(' ', '', str_replace('-', '', str_replace(')', '', str_replace('(', '', $telefone))))));
     
        $regexTelefone = "^[0-9]{11}$";
     
        $regexCel = '/[0-9]{2}[6789][0-9]{3,4}[0-9]{4}/'; // Regex para validar somente celular
        if (preg_match($regexCel, $telefone)) {
            return true;
        } else {
            return false;
        }
     }

    if($acao=="enviar_email_contato"){
            $nome                           = $_POST["nome"];
            $email                          = $_POST["email"];
            $cidade                         = $_POST["cidade"];
            $estado                         = $_POST["estado"];
            $pais                           = $_POST["pais"];
            $telefone                       = $_POST["telefone"];
            $assunto                        = $_POST["assunto"];
            $mensagem                       = $_POST["mensagem"];
            $email_contato_deltasul         = $_POST["email_contato_deltasul"];


            if(empty($nome) || empty($email) || empty($cidade) || empty($estado) || empty($pais) || empty($telefone) || empty($assunto) || empty($mensagem) || empty($email_contato_deltasul)){
                echo ("error_no_data");
                exit;
            }
            if((!verifica_celular($telefone))){
                echo ("Telefone Inválido!");
                exit;
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo ("E-mail Inválido!");
                exit;
            }

            $mail = new PHPMailer(true);

            try {
                $mail->CharSet = 'UTF-8'; 
                $mail->isSMTP();  
                $mail->Host         = 'smtp.office365.com';
                $mail->Port         = 587; 
                $mail->SMTPSecure   = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth     = true;  

                $mail->Username     = 'naoresponda@deltasulutilidades.onmicrosoft.com';
                $mail->Password     = 'nEwWnCYp8R';

                $mail->SetFrom('naoresponda@deltasulutilidades.onmicrosoft.com', 'Website Deltasul'); 
                $mail->addAddress($email_contato_deltasul, $assunto);
                $mail->Subject = $assunto;
                $mail->isHTML(true); 
                $mail->Body = "</p>". $nome ."</p><br></p>". $telefone . " - " . $email . "</p><br></p>". $cidade." - " . $estado." - ". $pais ."</p><br></p>". $mensagem ."</p>";

                $mail->send();

                echo "success";
                
            }  catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }


    }