<?php 
require('../../../config/config.php');
check_auth();//verif auth
$connexion= connect_db();
$statement = $connexion->query('SELECT numero_serie as telephone_numero_serie, ntelephone,ncarte FROM telephone');//recup numero serie,ntelephone,ncarte
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

if(empty($_POST['numero_serie']))
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
if(empty($_POST['numero_telephone']))
{
    $errors['errors_numero_telephone'] = 'Le numero de telephone est obligatoire';
}
if(empty($_POST['numero_carte']))
{
    $errors['errors_numero_carte'] = 'Le numero de carte est obligatoire';
}

foreach($results as $numero){
    if ($_POST['numero_serie']==$numero['telephone_numero_serie']){//verif si existe deja
        $errors['errors_numero_serie'] = 'Le numero de serie est deja utilisé';
    }if($_POST['numero_telephone']==$numero['ntelephone']){//verif si existe deja
        $errors['errors_numero_telephone'] = 'Le numero de telephone est deja utilisé';
    }if($_POST['numero_carte']==$numero['ncarte']){//verif si existe deja
        $errors['numero_errors_numero_carte'] = 'Le numero de carte est deja utilisé';
    }
}
if (! empty($errors)){
    header('Location: ajoutTelephone.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

$annee= date('Y');//recup l'annee
if($_POST['code_compta']==1){
    $prefixe='TEL';
}
else{
    header('Location: ajoutTelephone.php');
}

$prefixe=$prefixe.$annee;
$query = $connexion->prepare('SELECT code_compta FROM telephone where code_compta like :code_compta ORDER by code_compta DESC LIMIT 1;');//recup le dernier code compta enregistrer

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
    $code=$prefixe.'-'.sprintf("%03d",$code);//%03d -> (1->001),(11->011)
}


$query=$connexion->prepare('INSERT INTO telephone (numero_serie,type,marque,ntelephone,ncarte,id_salarie,code_compta)values (:numero_serie,:type,:marque,:ntelephone,:ncarte,:id_salarie,:code_compta)');
//insert un nouveau telephone avec les informations envoyé par le form
$values=[
    'numero_serie'=> $_POST['numero_serie'],
    'type'=> $_POST['type'],
    'marque' => $_POST['marque'],
    'ntelephone' => $_POST['numero_telephone'],
    'ncarte'=>$_POST['numero_carte'],
    'id_salarie'=>$_SESSION['salarie']['id'],
    'code_compta'=>$code,
    
    
    

];
$query->execute($values);
header('Location: ../../profil.php');