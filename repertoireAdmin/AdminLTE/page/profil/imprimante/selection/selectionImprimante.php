<?php 
require('../../../config/config.php');
check_auth();//verifie si personne authentifié
if(empty($_POST['numero_serie'])){//execute si le numero de serie est vide
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
    header('Location: selectionImprimanteForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

    
$connexion= connect_db();//connexion a la base de donnée
$statement = $connexion->query('SELECT numero_serie FROM imprimante');//selectionne le numero de serie de l'imprimante
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $total=0;
        foreach($results as $numero){
            if($_POST['numero_serie']==$numero['numero_serie']){
                $total+=1;
            }
        }
        if($total==0){
            $errors['errors_numero_serie'] = 'Le numero de serie n existe pas';
        }
        if($total==1){//total=1 signifie qu'elle existe
            
            $statement = $connexion->query('SELECT id_salarie FROM imprimante where numero_serie="'.$_POST['numero_serie'].'"');//selectionne l'id de la personne a qui est attribué l'imprimante
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as $result){
                if($result['id_salarie']!='' and $result['id_salarie']!=8){//si l'imprimante est attribué a quelqu'un qui n'est pas le stock
                    $total+=1;
                }
            }
        }
        if($total==2){
            $errors['errors_numero_serie'] = 'l\' imprimante est deja attribué a quelqu un ';
        }
        
        if (! empty($errors)){
            header('Location: selectionImprimanteForm.php?'.http_build_query(array_merge($errors,$_POST)));
            return;
        }
$query=$connexion->prepare('UPDATE imprimante SET id_salarie = :id_salarie WHERE numero_serie = "'.$_POST['numero_serie'].'"');
//attribue l'imprimante a la personne qui vient de la selectionner
$values=[
    'id_salarie'=>$_SESSION['salarie']['id'],
];
$query->execute($values);
header('Location: ../../profil.php'); 