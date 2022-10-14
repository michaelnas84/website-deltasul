<?php
   session_start(); session_destroy();
   
   if($_GET["redir"] != null){
   $redirect = '?redir='.$_GET["redir"];
   }
   ?>
<!doctype html>
<html>
   <head>
      <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <link rel="stylesheet" href="css/all.min.css">
      <link rel="stylesheet" href="css/tailwind.css" />
      <script src="js/jquery-3.1.0.js"></script>
      <script src="js/sweetalert2.all.min.js"></script>
      <link rel="shortcut icon" href="img/ICON_Deltasul_laranja.ico">
      <title>Login</title>
   </head>

   <body onload="load_error()">
      <!-- <img src="imagens/fluid.svg" class="fixed hidden lg:block inset-0 h-full" style="z-index: -1; transform: rotate(180deg); opacity: 0.15" /> -->
      <div class="w-screen h-screen flex flex-col justify-center items-center" >
         <form action="includes/auth_adfs.php<?= $redirect ?>" name="form1" method="post" class="form1 flex flex-col justify-center items-center md:w-1/2 p-8 rounded">
            <img src="img/Logo_Deltasul_02.png" class="w-64" />
            <div class="relative mt-8 w-full">
               <i id="user_icon" class="fa fa-user absolute text-primarycolor text-xl py-2 px-4 transition-all duration-500"></i>
               <input type="text" placeholder="Insira seu usuário" name="user" id="user" class="py-2 bg-primarycolor2 rounded-full w-full pl-12 border-b-2 font-display focus:outline-none focus:border-primarycolor transition-all duration-500" />
            </div>
            <div class="relative mt-8 w-full">
               <i id="pass_icon" class="fa fa-lock absolute text-primarycolor text-xl py-2 px-4 transition-all duration-500"></i>
               <input type="password" placeholder="Insira sua senha" name="pass" id="pass" class="py-2 bg-primarycolor2 rounded-full w-full pl-12 border-b-2 font-display focus:outline-none focus:border-primarycolor transition-all duration-500" />
            </div>
            <a onclick="validaLogin()" id="logar" class="cursor-pointer py-3 px-20 bg-primarycolor rounded-full text-white font-bold uppercase text-lg mt-4 transform hover:bg-secondarycolor transition-all duration-500">Login</a>
         </form>
      </div>
   </body>

</html>
<script type="text/javascript">
  var url_string = window.location.href;
  var url = new URL(url_string);
  var url_data = url.searchParams.get("usu");
  var url_data_redir = url.searchParams.get("redir");

  function validaLogin(){
    if(document.getElementById('user').value == ''){
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })

      Toast.fire({
        icon: 'error',
        title: 'Insira o seu usuário!'
      })
       document.getElementById('user_icon').classList.add("text-red-700");
       document.getElementById('user_icon').classList.remove("text-primarycolor");
       return;
   }else{
     document.getElementById('user_icon').classList.add("text-primarycolor");
     document.getElementById('user_icon').classList.remove("text-red-700");
   } if(document.getElementById('pass').value == ''){
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      Toast.fire({
        icon: 'error',
        title: 'Insira a sua senha!'
      })       
       document.getElementById('pass_icon').classList.add("text-red-700");
       document.getElementById('pass_icon').classList.remove("text-primarycolor");
       return;
        }else{
       document.getElementById('pass_icon').classList.add("text-primarycolor");
       document.getElementById('pass_icon').classList.remove("text-red-700");
        }
     
     if(document.getElementById('user').value != '' && document.getElementById('pass').value != ''){
        document.form1.submit();
    }
}
    jQuery(document.body).on('keypress', function (e) {
    if (e.keyCode === 13) {
        e.preventDefault();
        $("#logar").trigger("click");
    }
    });

    function load_error(){
    if(url_data == '1'){
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      Toast.fire({
        icon: 'error',
        title: 'Login ou senha incorretos!'
      })       
      return;
      } else if(url_data == '2'){
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      Toast.fire({
        icon: 'error',
        title: 'Seu login está inativo! Contate o administrador'
      })       
      return;
      } else if(url_data == '3'){
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      Toast.fire({
        icon: 'error',
        title: 'Email não registrado no nosso servidor!'
      })       
      return;
      } else if(url_data == '4'){
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      Toast.fire({
        icon: 'error',
        title: 'Faça login com sua nova senha para garantir sua segurança'
      })       
      return;
      } else if(url_data == '5'){
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      Toast.fire({
        icon: 'error',
        title: 'Seu perfil não tem permissão de acessso!'
      })       
      return;
      } else if(url_data_redir != null){
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      Toast.fire({
        icon: 'error',
        title: 'Faça login para continuar'
      })       
      return;
      }
    }
</script>