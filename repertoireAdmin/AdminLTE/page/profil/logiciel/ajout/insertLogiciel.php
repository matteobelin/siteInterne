<?php 
require('../../../config/config.php');
check_auth();//verifie l'authentification de l'utilisateur
$connexion= connect_db();//connexion a la base de donnée
$statement = $connexion->query('SELECT nom FROM logiciel');//selectionne le nom des logiciels
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

if(empty($_POST['nom']))//nom vide
{
    $errors['errors_nom'] = 'Le nom du logiciel est obligatoire';
}


foreach($results as $logiciel){
    if ($_POST['nom']==$logiciel['nom']){//logiciel existe
        $errors['errors_nom'] = 'Le logiciel est deja enregistré';
    }
}
if (! empty($errors)){
    header('Location: ajoutLogiciel.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}


$query=$connexion->prepare('INSERT INTO logiciel (nom) values (:nom)');// Insert un nouveau logiciel
$values=[
    'nom'=> $_POST['nom'],
];
$query->execute($values);
header('Location: ../../../../index.php');