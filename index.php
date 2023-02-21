<?php 
session_start();
if(isset($_SESSION["isLogedIn"])){
  header("location:company/company.php");
}
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
  </head>
  <body>
    <center>
      <form id="loginForm">
        <div class="container bg-light">
        	<h2 class="text-center">Login</h2>
          <div id="show_message">
          </div>
          <label for="email" ><b>E-Mail</b></label>
          <br>
          <input type="email" placeholder="Benutzer E-Mail Adresse" name="email">
          <br>
          <label for="psw"><b>Passwort</b></label><br>
          <input type="password" placeholder="Passwort Eingeben" name="pass"><br>
         <!-- <span class="psw">Forgot <a href="#">password?</a></span> <br> -->
          <input type="submit" class="loginBtn" value="Anmelden">
        </div>
      </form>
    </center>
   <script type="text/javascript">
     const loginform = document.getElementById('loginForm');
     loginform.addEventListener('submit', function(e) {
        e.preventDefault();
        const payload = new FormData(this);
        fetch('ajax/ajax_login.php', {
          method: 'POST',
          body: payload,
        })
        .then(res => res.json())
        .then((data) => {
          console.log(data);
          if(data.status == 1){
             window.location.href="company/company.php";
          }else{
            document.getElementById("show_message").innerHTML='<p style="margin:10px 0px;color:red">'+data.message+'</p>'
          }
        })
     });
   </script>
  </body>
</html>
