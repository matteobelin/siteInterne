<?php 
require('../../config/config.php');
check_auth();// verification connection
$connexion= connect_db();

if(empty($_POST['nom']))
{
    $errors['errors_nom'] = 'Votre nom est obligatoire';
}
if(empty($_POST['type']))
{
    $errors['errors_type'] = 'Le type est obligatoire';
}
if(empty($_POST['commentaire']))
{
    $errors['errors_commentaire'] = 'Un commentaire explicatif est obligatoire';
}
if(empty($_POST['prenom']))
{
    $errors['errors_prenom'] = 'Votre prenom est obligatoire';
}



if (! empty($errors)){
    header('Location: demandeForm.php?'.http_build_query(array_merge($errors,$_POST)));//retourne sur la page demandeForm avec les erreurs et les valeurs envoyé
    return;
}

$query=$connexion->prepare('INSERT INTO demande (nom,prenom,type,commentaire,resolu) values (:nom,:prenom,:type,:commentaire,0)');//insert dans la table demande la demande du formulaire avec nom prenom type commentaire et resulu=0 car non resolu
$values=[
    'nom'=> $_POST['nom'],
    'prenom' => $_POST['prenom'],
    'type'=> $_POST['type'],
    'commentaire' => $_POST['commentaire'],
        


];
$query->execute($values);//execute les valeurs dans la requete au prealablement preparer avec prepare
header('Location: ../../../index.php');