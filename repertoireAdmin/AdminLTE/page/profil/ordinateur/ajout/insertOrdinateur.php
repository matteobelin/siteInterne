<?php 
require('../../../config/config.php');
check_auth();//verifie que la personne est bien authentifié
$connexion= connect_db();//connexion a la base de donnée



if(empty($_POST['numero_serie']))//regarde si post n'est pas vide
{
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
}
if(empty($_POST['nom']))
{
    $errors['errors_nom'] = 'Le nom est obligatoire';
}

if(empty($_POST['designation']))
{
    $errors['errors_designation'] = 'La designation est obligatoire';
}
if(empty($_POST['marque']))
{
    $errors['errors_marque'] = 'La marque est obligatoire';
}

$statement = $connexion->query('SELECT numero_serie as ordinateur_numero_serie, nom FROM ordinateur');//recup numero de serie, nom de l'ordi
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach($results as $numero){
    if ($_POST['numero_serie']==$numero['ordinateur_numero_serie']){//verifie pour voir si le numero de serie existe deja 
        $errors['errors_numero_serie'] = 'Le numero de serie est deja utilisé';
    }if($_POST['nom']==$numero['nom']){//verifie si le nom existe deja
        $errors['errors_nom'] = 'Le nom est deja utilisé';
    }
}
if (! empty($errors)){
    header('Location: ajoutOrdinateur.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

$annee= date('Y');//recupere l'anne
if($_POST['code_compta']==1){//regarde si il s'agit d'un pc portable ou fixe
    $prefixe='PO';
}
else{
    $prefixe='PC';
}

$prefixe=$prefixe.$annee;
$query = $connexion->prepare('SELECT code_compta FROM ordinateur where code_compta like :code_compta ORDER by code_compta DESC LIMIT 1;');//recup le dernier code compta enregistrer par rapport si c'est un portable ou un fixe

$query->execute([
    'code_compta'=>$prefixe.'%',
]);

$results = $query->fetchAll(PDO::FETCH_ASSOC);
if(empty($results))//si pas encore cette annee prend valeur de 001
{
    $code=$prefixe.'-001';
}
else{
    foreach($results as $resultat){
        $code=$resultat['code_compta'];
    }
    
    $code=substr($code,-3);
    $code+=1;
    $code=$prefixe.'-'.sprintf("%03d",$code);//03d correspond a remplir des 0 sur un emplacement de 3 chiffres si la valeur ne remplit pas les 3 ex : 1  -> 001
}
$query=$connexion->prepare('INSERT INTO ordinateur (numero_serie,nom,code_compta,designation,marque,status,id_salarie)values (:numero_serie,:nom,:code_compta,:designation,:marque,:status,:id_salarie)');
//insert dans ordinateur le numero de serie le nom le code compta qui vient d etre cree la designation la marque le status et l'id de l'utilisateur a qui il appartient
$values=[
    'numero_serie'=> $_POST['numero_serie'],
    'nom'=> $_POST['nom'],
    'code_compta' => $code,
    'designation' => $_POST['designation'],
    'marque'=>$_POST['marque'],
    'status'=>$_POST['status'],
    'id_salarie'=>$_SESSION['salarie']['id']
    
    
    

];
$query->execute($values);
header('Location: ../../profil.php');

