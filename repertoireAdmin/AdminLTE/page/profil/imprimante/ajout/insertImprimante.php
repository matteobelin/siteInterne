<?php 
require('../../../config/config.php');
check_auth();// verifie si la personne est authentifié
$connexion= connect_db();//connexion a la base de donnée
$statement = $connexion->query('SELECT numero_serie as imprimante_numero_serie,ip FROM imprimante');// recupere la numero de serie et l'ip de l'imprimante
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

if(empty($_POST['numero_serie']))//si la personne n'a pas rempli le champs
{
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
}
if(empty($_POST['modele']))//si la personne n'a pas rempli le champs
{
    $errors['errors_modele'] = 'Le modele est obligatoire';
}
if(empty($_POST['marque']))//si la personne n'a pas rempli le champs
{
    $errors['errors_marque'] = 'La marque est obligatoire';
}
if(empty($_POST['ip']))//si la personne n'a pas rempli le champs
{
    $errors['errors_ip'] = 'L\'ip est obligatoire';
}


foreach($results as $imprimante){
    if ($_POST['numero_serie']==$imprimante['imprimante_numero_serie']){
        $errors['errors_numero_serie'] = 'Le numero de serie est deja utilisé';
    }
    if ($_POST['ip']==$imprimante['ip']){
        $errors['errors_ip'] = 'L\'ip est deja utilisé';
    }
}
if (! empty($errors)){
    header('Location: ajoutImprimante.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}



$query=$connexion->prepare('INSERT INTO imprimante (numero_serie,modele,marque,id_salarie,ip)values (:numero_serie,:modele,:marque,:id_salarie,:ip)');
//insert a imprimante les valeurs du form pour le numero de serie,le modele,la marque,et l'ip
$values=[
    'numero_serie'=> $_POST['numero_serie'],
    'modele'=> $_POST['modele'],
    'marque' => $_POST['marque'],
    'id_salarie'=>$_SESSION['salarie']['id'],
    'ip'=>$_POST['ip'],
    
    
    

];
$query->execute($values);
header('Location: ../../profil.php');