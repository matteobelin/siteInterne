<?php 
require('../../../config/config.php');
check_auth();//verifie si la personne est bien authentifié
if(empty($_POST['numero_serie'])){
    $errors['errors_numero_serie'] = 'Le numero de serie est obligatoire';
    header('Location: selectionTelephoneForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
    
}

    
$connexion= connect_db();//connexion a la base de donnée

$statement = $connexion->query('SELECT numero_serie FROM ordinateur');//selectionne numero de serie ordi
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $total=0;
        foreach($results as $numero){
            if($_POST['numero_serie']==$numero['numero_serie']){//verifie si il existe
                $total+=1;
            }
        }
        if($total==0){
            $errors['errors_numero_serie'] = 'Le numero de serie n existe pas';
        }
        if($total==1){
            
            $statement = $connexion->query('SELECT id_salarie FROM ordinateur where numero_serie="'.$_POST['numero_serie'].'"');//recup si un utilisateur possede deja l'ordinateur 
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as $result){
                if($result['id_salarie']!=''  and $result['id_salarie']!=8){//si il appartient a quelqu'un dautre que le stock
                    $total+=1;
                }
            }
        }
        if($total==2){
            $errors['errors_numero_serie'] = 'l ordinateur est deja attribué a quelqu un ';
        }
        
        if (! empty($errors)){
            header('Location: selectionOrdinateurForm.php?'.http_build_query(array_merge($errors,$_POST)));
            return;
        }

$query=$connexion->prepare('UPDATE ordinateur SET id_salarie = :id_salarie WHERE numero_serie = "'.$_POST['numero_serie'].'"');
//attribut a l'utilisateur l ordinateur
$values=[
    'id_salarie'=>$_SESSION['salarie']['id'],
];
$query->execute($values);
header('Location: ../../profil.php'); 