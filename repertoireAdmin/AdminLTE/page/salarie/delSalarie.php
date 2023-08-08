<?php
require('../config/config.php');
check_auth();
if($_SESSION['admin']['accesSalarie']==0):?>
    <script>alert("Vous n'avez pas acces a la suppression de la salarie ")</script>
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
    $statement = $connexion->query('SELECT ordinateur.numero_serie as ordinateur_numero_serie, telephone.numero_serie as telephone_numero_serie, tablette.numero_serie as tablette_numero_serie, imprimante.numero_serie as imprimante_numero_serie FROM salarie left join ordinateur on ordinateur.id_salarie=salarie.id left join telephone on telephone.id_salarie=salarie.id left join imprimante on imprimante.id_salarie=salarie.id left join tablette on tablette.id_salarie=salarie.id where salarie.id='.$_POST['id']);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach($results as $result){
        if($result['telephone_numero_serie'] == '' and $result['ordinateur_numero_serie'] == '' and $result['tablette_numero_serie'] == '' and $result['imprimante_numero_serie'] == ''){
            goto del;
        }
        else{
            if($result['ordinateur_numero_serie'] != ''){
                $query=$connexion->prepare('UPDATE ordinateur set id_salarie = 8 where numero_serie=:numero_serie');
                $values=[
                    'numero_serie'=>$result['ordinateur_numero_serie'],
                ];
                $query->execute($values);
            }
            if($result['telephone_numero_serie'] != ''){
                $query=$connexion->prepare('UPDATE telephone set id_salarie = 8 where numero_serie=:numero_serie');
                $values=[
                    'numero_serie'=>$result['telephone_numero_serie'],
                ];
                $query->execute($values);
            }
            
            if($result['tablette_numero_serie'] != ''){
                $query=$connexion->prepare('UPDATE tablette set id_salarie = 8 where numero_serie=:numero_serie');
                $values=[
                    'numero_serie'=>$result['tablette_numero_serie'],
                ];
                $query->execute($values);
            }
            if($result['imprimante_numero_serie'] != ''){
                $query=$connexion->prepare('UPDATE imprimante set id_salarie = 8  where numero_serie=:numero_serie');
                $values=[
                    'numero_serie'=>$result['imprimante_numero_serie'],
                ];
                $query->execute($values);
            }
        }
        }
        
        
    del :
    $statement = $connexion->query('DELETE FROM salarie where id='.$_POST['id']);

header('Location: ../../index.php');


endif;