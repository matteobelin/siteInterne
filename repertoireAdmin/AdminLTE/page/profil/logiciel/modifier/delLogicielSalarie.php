<?php

require('../../../config/config.php');
check_auth();//verifie authentification
if($_SESSION['admin']['accesLogiciel']==0)://execute si pas acces?>
    <script>alert("Vous n'avez pas acces a la modification de logiciel")</script>
    <script>
        function RedirectionJavascript(){
          document.location.href="http://localhost/repertoireAdmin2/AdminLTE/index.php"; 
        }
    </script> 
    <body onLoad="setTimeout('RedirectionJavascript()', 750)"><!-- execute dans 750sec la fonction redirectionJavascript -->
                <p style="font-size :30px; text-align : center ; font-weight: bold;margin-top : 12em">redirection ...</p>
    </body>
<?php else:?>
<?php
$connexion= connect_db();//connexion a la base de donnée

$statement = $connexion->query('SELECT id FROM logiciel where nom="'.$_GET['logiciel'].'" ');//recup l'id du logiciel
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $result){
    $id_logiciel=$result['id'];
}


$statement = $connexion->query('DELETE FROM salarie_logiciel where id_salarie="'.$_SESSION['salarie']['id'].'" and id_logiciel="'.$id_logiciel.'"');//supprime le logiciel du salarié
header('Location: ../../profil.php');
endif;