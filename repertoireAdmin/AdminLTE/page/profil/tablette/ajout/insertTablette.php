<?php 
require('../../../config/config.php');
check_auth();//verif auth
$connexion= connect_db();
$statement = $connexion->query('SELECT numero_serie as tablette_numero_serie FROM tablette');//recup les numero de serie des tablettes
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

if(empty($_POST['numero_serie']))//regarde si post est vide 
{
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
}
if(empty($_POST['type']))
{
    $errors['errors_type'] = 'Le type est obligatoire';
}
if(empty($_POST['marque']))
{
    $errors['errors_marque'] = 'La marque est obligatoire';
}


foreach($results as $tablette){
    if ($_POST['numero_serie']==$tablette['tablette_numero_serie']){//verif si numero de serie existe
        $errors['errors_numero_serie'] = 'Le numero de serie est deja utilisé';
    }
}
if (! empty($errors)){
    header('Location: ajoutTablette.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

$annee= date('Y');//recup l'annee
if($_POST['code_compta']==1){
    $prefixe='TA';
}
else{
    header('Location: ajoutTablette.php');
}

$prefixe=$prefixe.$annee;
$query = $connexion->prepare('SELECT code_compta FROM tablette where code_compta like :code_compta ORDER by code_compta DESC LIMIT 1;');//recup le dernier code compta enregistrer

$query->execute([
    'code_compta'=>$prefixe.'%',
]);

$results = $query->fetchAll(PDO::FETCH_ASSOC);
if(empty($results))
{
    $code=$prefixe.'-001';
}
else{
    foreach($results as $resultat){
        $code=$resultat['code_compta'];
    }
    
    $code=substr($code,-3);
    $code+=1;
    $code=$prefixe.'-'.sprintf("%03d",$code);//%03d permet que exemple : 1 -> 001 , 10->010
}


$query=$connexion->prepare('INSERT INTO tablette (numero_serie,type,marque,id_salarie,code_compta)values (:numero_serie,:type,:marque,:id_salarie,:code_compta)');
//insert dans tablette la nouvelle tablette
$values=[
    'numero_serie'=> $_POST['numero_serie'],
    'type'=> $_POST['type'],
    'marque' => $_POST['marque'],
    'id_salarie'=>$_SESSION['salarie']['id'],
    'code_compta'=>$code,
    
    
    

];
$query->execute($values);
header('Location: ../../profil.php');