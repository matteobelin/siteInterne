
<?php
require('../../../config/config.php');
check_auth();
$connexion= connect_db();
if(empty($_POST['numero_serie']))
{
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
}
$compteur=0;

$statement = $connexion->query('SELECT numero_serie FROM imprimante ');
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $imprimante){
    if($imprimante['numero_serie']==$_POST['numero_serie']){
        $compteur+=1;
    }
}
if($compteur==0){
        $errors['errors_numero_serie'] = 'Le numero de serie n existe pas';
}

if (! empty($errors)){
    header('Location: delImprimanteForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

else{
    $statement = $connexion->query('DELETE FROM imprimante where numero_serie="'.$_POST['numero_serie'].'"');
header('Location: ../../../../index.php');
}