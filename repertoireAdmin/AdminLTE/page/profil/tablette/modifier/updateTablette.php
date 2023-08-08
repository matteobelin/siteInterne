<?php 
require('../../../config/config.php');
check_auth();//verif auth
$connexion= connect_db();
if(empty($_POST['numero_serie']))//verif si vide
{
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
}

if(empty($_POST['marque']))
{
    $errors['errors_marque'] = 'La marque de la tablette est obligatoire';
}
if(empty($_POST['type']))
{
    $errors['errors_type'] = 'Le type de la tablette est obligatoire';
}



$query=$connexion->prepare('SELECT code_compta from tablette where numero_serie!=:numero_serie ');//compte compta des tablettes != a celle transmise par le form 
$values=[
    'numero_serie'=>$_POST['numero_serie'],
];
$query->execute($values);
$results=$query->fetchAll(PDO::FETCH_ASSOC);

if(!empty($results)){
    foreach($results as $resultat){    
        if($resultat['code_compta']==$_POST['code_compta']){
            $errors['errors_code_compta'] = 'Le code compta de la tablette est deja utilisé';
        }
        
    }
}

if (! empty($errors)){
    header('Location: modifierTabletteForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

$query=$connexion->prepare('UPDATE tablette SET  code_compta=:code_compta, marque=:marque, type=:type where numero_serie="'.$_POST['numero_serie'].'"');
//met ajour les informations de la tablette
$values=[
    'code_compta'=>$_POST['code_compta'],
    'marque'=>$_POST['marque'],
    'type'=>$_POST['type'],
];
$query->execute($values);
header('Location: ../../../../index.php');
