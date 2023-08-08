<?php 
require('../../../config/config.php');
check_auth();//verif auth

if($_SESSION['admin']['accesTelephone']==0)://si pas acces?>
    <script>alert("Vous n'avez pas acces a la modification de téléphone")</script>
    <script>
        function RedirectionJavascript(){
            document.location.href="http://localhost/repertoireAdmin2/AdminLTE/index.php"; 
        }
    </script> 
    <body onLoad="setTimeout('RedirectionJavascript()', 750)">
                <p style="font-size :30px; text-align : center ; font-weight: bold;margin-top : 12em">redirection ...</p>
    </body>
<?php else:?>
<?php
$connexion= connect_db();
$statement=$connexion->query('UPDATE telephone SET id_salarie = 8 WHERE numero_serie = "'.$_GET['numero_serie'].'"');//attribut le telephone au stock

header('Location: ../../../../index.php');
endif;