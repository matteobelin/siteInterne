<?php 
require('../config/config.php');
check_auth();

if(empty($_POST['nom']))
{
    $errors['errors_nom'] = 'Le nom est obligatoire';
}
if(empty($_POST['prenom']))
{
    $errors['errors_prenom'] = 'Le prenom est obligatoire';
}

if (! empty($errors)){
    header('Location: modifSalarie.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

$connexion= connect_db();

$query=$connexion->prepare('UPDATE salarie SET nom = :nom,prenom = :prenom , id_agence=:id_agence, id_licence=:id_licence where id='.$_SESSION['modif']['id']);
$values=[
    'nom'=> $_POST['nom'],
    'prenom'=> $_POST['prenom'],
    'id_agence' => $_POST['agence'],
    'id_licence'=>$_POST['licence'],
];
$query->execute($values);
header('Location: ../../index.php');