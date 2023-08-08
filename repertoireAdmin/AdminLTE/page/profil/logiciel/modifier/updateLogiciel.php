<?php 
require('../../../config/config.php');
check_auth();//verifie l'authentification
$connexion= connect_db();//connexion a la base de donnée
if(empty($_POST['nom']))
{
    $errors['errors_nom'] = 'Le nom du logiciel est obligatoire';
}

if(empty($_POST['nouveauNom']))
{
    $errors['errors_nouveauNom'] = 'Le nouveau nom est obligatoire';
}



$query=$connexion->prepare('SELECT nom from logiciel where nom=:nom and nom!=:ancien'); //recupere le nom des logiciel qui possede le nouveau nom si != a l'ancien
$values=[
    'nom'=>$_POST['nouveauNom'],
    'ancien'=>$_POST['nom'],
];
$query->execute($values);
$results=$query->fetchAll(PDO::FETCH_ASSOC);

if(!empty($results)){
    $errors['errors_nouveauNom'] = 'Le nom est deja utilisé';
}

if (! empty($errors)){
    header('Location: modifierLogicielForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

$query=$connexion->prepare('UPDATE logiciel SET  nom=:nom where nom=:ancien');//met a jour le nom avec le nouveau nom
$values=[
    'nom'=>$_POST['nouveauNom'],
    'ancien'=>$_POST['nom'],
];
$query->execute($values);
header('Location: ../../../../index.php');
