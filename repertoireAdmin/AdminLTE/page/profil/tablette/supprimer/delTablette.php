
<?php
require('../../../config/config.php');
check_auth();//verif auth
$connexion= connect_db();
if(empty($_POST['numero_serie']))
{
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
}
$compteur=0;

$statement = $connexion->query('SELECT numero_serie FROM tablette ');//numero de serie des tablettes
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $tablette){
    if($tablette['numero_serie']==$_POST['numero_serie']){//verif si elle existe
        $compteur+=1;
    }
}
if($compteur==0){
        $errors['errors_numero_serie'] = 'Le numero de serie n existe pas';
}

if (! empty($errors)){
    header('Location: deltabletteForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

else{
    $statement = $connexion->query('DELETE FROM tablette where numero_serie="'.$_POST['numero_serie'].'"');//supprime la tablette
header('Location: ../../../../index.php');
}