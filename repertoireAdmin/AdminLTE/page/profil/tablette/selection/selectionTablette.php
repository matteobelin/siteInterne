<?php 
require('../../../config/config.php');
check_auth();//verif auth
if(empty($_POST['numero_serie'])){
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
    header('Location: selectionTabletteForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

    
$connexion= connect_db();
$statement = $connexion->query('SELECT numero_serie FROM tablette');//numero de serie des tablettes
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
            
            $statement = $connexion->query('SELECT id_salarie FROM tablette where numero_serie="'.$_POST['numero_serie'].'"');//regarde si elle possede deja un utilisateur
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as $result){
                if($result['id_salarie']!=''  and $result['id_salarie']!=8){//regarde si elle est deja attribué si oui si elle est au stock
                    $total+=1;
                }
            }
        }
        if($total==2){
            $errors['errors_numero_serie'] = 'la tablette est deja attribué a quelqu un ';
        }
        
        if (! empty($errors)){
            header('Location: selectionTabletteForm.php?'.http_build_query(array_merge($errors,$_POST)));
            return;
        }
$query=$connexion->prepare('UPDATE tablette SET id_salarie = :id_salarie WHERE numero_serie = "'.$_POST['numero_serie'].'"');
//attribut la tablette a un salarié
$values=[
    'id_salarie'=>$_SESSION['salarie']['id'],
];
$query->execute($values);
header('Location: ../../profil.php'); 