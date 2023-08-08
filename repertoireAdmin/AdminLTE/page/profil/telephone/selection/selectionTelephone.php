<?php 
require('../../../config/config.php');
check_auth();//verif auth
if(empty($_POST['numero_serie'])){
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
    header('Location: selectionTelephoneForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

    
$connexion= connect_db();
$statement = $connexion->query('SELECT numero_serie FROM telephone');//recup numero de serie des telephones
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $total=0;
        foreach($results as $numero){
            if($_POST['numero_serie']==$numero['numero_serie']){//verif si existe
                $total+=1;
            }
        }
        if($total==0){
            $errors['errors_numero_serie'] = 'Le numero de serie n existe pas';
        }
        if($total==1){
            
            $statement = $connexion->query('SELECT id_salarie FROM telephone where numero_serie="'.$_POST['numero_serie'].'"');//recup si un salarie possede deja le telephone
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as $result){
                if($result['id_salarie']!='' and $result['id_salarie']!=8){//verif si deja attribué si oui si il est au stock
                    $total+=1;
                }
            }
        }
        if($total==2){
            $errors['errors_numero_serie'] = 'le telephone est deja attribué a quelqu un ';
        }
        
        if (! empty($errors)){
            header('Location: selectionTelephoneForm.php?'.http_build_query(array_merge($errors,$_POST)));
            return;
        }
$query=$connexion->prepare('UPDATE telephone SET id_salarie = :id_salarie WHERE numero_serie = "'.$_POST['numero_serie'].'"');
//attribue le telephone au salarie
$values=[
    'id_salarie'=>$_SESSION['salarie']['id'],
];
$query->execute($values);
header('Location: ../../profil.php'); 