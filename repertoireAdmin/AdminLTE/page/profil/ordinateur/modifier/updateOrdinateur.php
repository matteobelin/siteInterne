<?php 
require('../../../config/config.php');
check_auth();//verifie si la personne est authentifié
$connexion= connect_db();//connexion a la base de donnée
if(empty($_POST['ordinateur_numero_serie']))
{
    $errors['errors_ordinateur_numero_serie'] = 'Le numero de serie est obligatoire';
}
if(empty($_POST['ordinateur_nom']))
{
    $errors['errors_ordinateur_nom'] = 'Le nom de l ordinateur est obligatoire';
}

if(empty($_POST['ordinateur_designation']))
{
    $errors['errors_ordinateur_designation'] = 'La designation de l ordinateur est obligatoire';
}
if(empty($_POST['ordinateur_marque']))
{
    $errors['errors_ordinateur_marque'] = 'La marque de l ordinateur est obligatoire';
}
if(empty($_POST['ordinateur_status']))
{
    $errors['errors_ordinateur_status'] = 'Le status de l ordinateur est obligatoire';
}
$query=$connexion->prepare('SELECT code_compta,nom from ordinateur where numero_serie!=:numero_serie and (code_compta=:code_compta or nom=:nom) ');
//recup code compta, nom losque numero de serie different de celui de l'ordi 
$values=[
    'code_compta'=>$_POST['ordinateur_code_compta'],
    'nom'=>$_POST['ordinateur_nom'],
    'numero_serie'=>$_POST['ordinateur_numero_serie']
];
$query->execute($values);
$results=$query->fetchAll(PDO::FETCH_ASSOC);

if(!empty($results)){
    foreach($results as $resultat){
        if($resultat==$_POST['ordinateur_code_compta']){//verifie si code compta deja attribué
            $errors['errors_ordinateur_code_compta'] = 'Le code compta de l ordinateur est deja utilisé';
            }
        else{//verifi si nom deja attribue
            $errors['errors_ordinateur_nom'] = 'Le nom de l ordinateur est deja utilisé';
        }
    }
    

    
}

if (! empty($errors)){
    header('Location: modifOrdinateurForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}


$query=$connexion->prepare('UPDATE ordinateur SET nom = :nom , designation=:designation,code_compta=:code_compta, marque=:marque, status=:status where numero_serie="'.$_POST['ordinateur_numero_serie'].'"');
//modifie l ordi et met a jour le nom la designation le code compta la marque et le status

$values=[
    'nom'=> $_POST['ordinateur_nom'],
    'designation' => $_POST['ordinateur_designation'],
    'code_compta'=>$_POST['ordinateur_code_compta'],
    'marque'=>$_POST['ordinateur_marque'],
    'status'=>$_POST['ordinateur_status'],
];
$query->execute($values);
header('Location: ../../../../index.php');
