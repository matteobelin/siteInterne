<?php 
require('../../../config/config.php');
check_auth();//verifie si la personne est authentifié 
if($_SESSION['admin']['accesImprimante']==0): //verifie si la personne a acces a l'imprimante?>
    <script>alert("Vous n'avez pas acces a la modification d'imprimante")</script>
    <script>
        function RedirectionJavascript(){
          document.location.href="http://localhost/repertoireAdmin2/AdminLTE/index.php"; 
        }
    </script> 
    <body onLoad="setTimeout('RedirectionJavascript()', 750)"><!-- 750sec avant que la fonction se lance-->
                <p style="font-size :30px; text-align : center ; font-weight: bold;margin-top : 12em">redirection ...</p>
    </body>
<?php else:?>
<?php
$connexion= connect_db();// connexion a la base de donnée
$statement=$connexion->query('UPDATE imprimante SET id_salarie = 8 WHERE numero_serie = "'.$_GET['numero_serie'].'"');
//attribut l'imprimante au stock
header('Location: ../../../../index.php');
endif;