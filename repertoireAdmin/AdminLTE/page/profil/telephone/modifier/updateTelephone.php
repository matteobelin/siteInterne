<?php 
require('../../../config/config.php');
check_auth();//verif auth
$connexion= connect_db();
if(empty($_POST['numero_serie']))
{
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
}
if(empty($_POST['ncarte']))
{
    $errors['errors_ncarte'] = 'Le numero de carte est obligatoire';
}

if(empty($_POST['ntelephone']))
{
    $errors['errors_ntelephone'] = 'Le numero de telephone est obligatoire';
}
if(empty($_POST['marque']))
{
    $errors['errors_marque'] = 'La marque du telephone est obligatoire';
}
if(empty($_POST['type']))
{
    $errors['errors_type'] = 'Le type du telephone est obligatoire';
}

$query=$connexion->prepare('SELECT code_compta, ncarte,ntelephone from telephone where numero_serie!=:numero_serie and (code_compta=:code_compta or ntelephone=:ntelephone or ncarte=:ncarte)');
//recup le code compta,le ncarte,le ntel si un des trois appartient deja a un autre telephone
$values=[
    'code_compta'=>$_POST['code_compta'],
    'ntelephone'=>$_POST['ntelephone'],
    'ncarte'=>$_POST['ncarte'],
    'numero_serie'=>$_POST['numero_serie'],
];
$query->execute($values);
$results=$query->fetchAll(PDO::FETCH_ASSOC);

if(!empty($results)){
    foreach($results as $resultat){    
        if($resultat['code_compta']==$_POST['code_compta']){//verif si est deja enregistré
            $errors['errors_code_compta'] = 'Le code compta du telephone est deja utilisé';
        }
        elseif($resultat['ntelephone']==$_POST['ntelephone']){//verif si est deja enregistré
            $errors['errors_ntelephone'] = 'Le numero de telephone est deja utilisé';
        }
        else{
            $errors['errors_ncarte'] = 'Le numero de carte est deja utilisé';
        }
        
    }
}

if (! empty($errors)){
    header('Location: modifierTelephoneForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}


$query=$connexion->prepare('UPDATE telephone SET ncarte = :ncarte , code_compta=:code_compta,ntelephone=:ntelephone,marque=:marque, type=:type where numero_serie="'.$_POST['numero_serie'].'"');
//MAJ le telephone avec les nouvelles informations
$values=[
    'ncarte'=> $_POST['ncarte'],
    'code_compta'=>$_POST['code_compta'],
    'ntelephone' => $_POST['ntelephone'],
    'marque'=>$_POST['marque'],
    'type'=>$_POST['type'],
];
$query->execute($values);
header('Location: ../../../../index.php');
