<?php 
require('../../../config/config.php');
check_auth();//verifie que la personne est bien authentifié
$connexion= connect_db();//connexion a la base de donnée
if(empty($_POST['numero_serie']))//verifie que la valeur transmise n'est pas vide
{
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
}

if(empty($_POST['marque']))
{
    $errors['errors_marque'] = 'La marque de la tablette est obligatoire';
}
if(empty($_POST['modele']))
{
    $errors['errors_type'] = 'Le type de la tablette est obligatoire';
}

if(empty($_POST['ip']))
{
    $errors['errors_ip'] = 'L\'adresse ip est obligatoire';
}


$query=$connexion->prepare('SELECT ip from imprimante where numero_serie!=:numero_serie ');//recupere l'ip des imprimantes qui n'ont pas le meme numero de serie que celle transmise
$values=[
    'numero_serie'=>$_POST['numero_serie'],
];
$query->execute($values);
$results=$query->fetchAll(PDO::FETCH_ASSOC);

if(!empty($results)){
    foreach($results as $resultat){    
        if($resultat['ip']==$_POST['ip']){//verifie si elle est deja attribué ou non 
            $errors['errors_ip'] = 'L\'adresse ip est deja utilisé';
        }
        
    }
}

if (! empty($errors)){
    header('Location: modifierImprimanteForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

$query=$connexion->prepare('UPDATE imprimante SET  ip=:ip, marque=:marque, modele=:modele where numero_serie="'.$_POST['numero_serie'].'"');
//Modifie l'imprimante qui possede le numero de serie transmi avec la nouvelle ip,marque et modele
$values=[
    'ip'=>$_POST['ip'],
    'marque'=>$_POST['marque'],
    'modele'=>$_POST['modele'],
];
$query->execute($values);
header('Location: ../../../../index.php');
