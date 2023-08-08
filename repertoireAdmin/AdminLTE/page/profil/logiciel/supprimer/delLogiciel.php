
<?php
require('../../../config/config.php');
check_auth();// verification de l'authentification
$connexion= connect_db();// connexion a la base de donnée
if(empty($_POST['nom']))// si le nom est vide
{
    $errors['errors_nnom'] = 'Le nom est obligatoire';
}
$compteur=0;

$statement = $connexion->query('SELECT nom FROM logiciel ');//recup le nom des logiciel
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $logiciel){
    if($logiciel['nom']==$_POST['nom']){
        $compteur+=1;
    }
}
if($compteur==0){
        $errors['errors_nom'] = 'Le nom n\' existe pas';
}

if (! empty($errors)){
    header('Location: delIlogicielForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

else{
    $statement = $connexion->query('DELETE FROM logiciel where nom="'.$_POST['nom'].'"');//supprime le logiciel
header('Location: ../../../../index.php');
}