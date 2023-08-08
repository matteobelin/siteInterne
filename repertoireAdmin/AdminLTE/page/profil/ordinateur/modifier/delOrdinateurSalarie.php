<?php 
require('../../../config/config.php');
check_auth();//verification de l'authentification
if($_SESSION['admin']['accesOrdi']==0)://execute si as acces?>
    <script>alert("Vous n'avez pas acces a la modification d'ordinateur")</script>
    <script>
        function RedirectionJavascript(){
          document.location.href="http://localhost/repertoireAdmin2/AdminLTE/index.php";
        }
    </script> 
    <body onLoad="setTimeout('RedirectionJavascript()', 750)"><!-- execute la fonction apres 750 sec-->
                <p style="font-size :30px; text-align : center ; font-weight: bold;margin-top : 12em">redirection ...</p>
    </body>
<?php else:?>
<?php
$connexion= connect_db();//connexion a la base
$statement=$connexion->query('UPDATE ordinateur SET id_salarie = 8 WHERE numero_serie = "'.$_GET['numero_serie'].'"');//attribut l'oridnateur au stock

header('Location: ../../../../index.php');
endif;