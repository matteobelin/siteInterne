<?php
require('../../../config/config.php');
check_auth();//verif auth
$connexion= connect_db();
if(empty($_POST['numero_serie']))
{
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
}
$compteur=0;

$statement = $connexion->query('SELECT numero_serie FROM telephone ');//recup numero de serie des telephones
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $telephone){
    if($telephone['numero_serie']==$_POST['numero_serie']){//verif si le numero transmi existe
        $compteur+=1;
    }
}
if($compteur==0){
        $errors['errors_numero_serie'] = 'Le numero de serie n existe pas';
}

if (! empty($errors)){
    header('Location: deltelephoneForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

else{
    $statement = $connexion->query('DELETE FROM telephone where numero_serie="'.$_POST['numero_serie'].'"');//supprime le telephone
header('Location: ../../../../index.php');
}