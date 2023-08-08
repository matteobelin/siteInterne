<?php
require('../../../config/config.php');
check_auth();//verification de l'authentification
$connexion= connect_db();//connexion a la base de donné
if(empty($_POST['numero_serie']))
{
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
}
$compteur=0;

$statement = $connexion->query('SELECT numero_serie FROM ordinateur ');//recup num serie ordi
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $ordinateur){
    if($ordinateur['numero_serie']==$_POST['numero_serie']){//verdie si le numero existe bien
        $compteur+=1;
    }
}
if($compteur==0){
        $errors['errors_numero_serie'] = 'Le numero de serie n existe pas';
}

if (! empty($errors)){
    header('Location: delOrdinateurForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}
else{
    $statement = $connexion->query('DELETE FROM ordinateur where numero_serie="'.$_POST['numero_serie'].'"');
    //supprime l'ordinateur
    header('Location: ../../../../index.php');
}