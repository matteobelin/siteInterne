<?php 
require('../../../config/config.php');
check_auth();//verfie que la personne est bien authentifié
if(empty($_POST['nom'])){
    $errors['errors_nom'] = 'Le nom est obligatoire';
    header('Location: selectionTelephoneForm.php?'.http_build_query(array_merge($errors,$_POST)));
    return;
}

    
$connexion= connect_db();//connexion base

$statement = $connexion->query('SELECT nom FROM logiciel');//recup tout les noms de logiciel
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $total=0;
        foreach($results as $logiciel){
            if($_POST['nom']==$logiciel['nom']){
                $total+=1;
            }
        }
        if($total==0){
            $errors['errors_nom'] = 'Le logiciel n existe pas';
        }
        if($total==1){
            $statement=$connexion->query('SELECT id from logiciel where nom="'.$_POST['nom'].'"');//recup id du logiciel quand le nom correspond au nom transmit
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as $result){
                $id_logiciel=$result['id'];
            }
            $statement=$connexion->query('SELECT id_logiciel,id_salarie from salarie_logiciel where id_salarie="'.$_SESSION['salarie']['id'].'" and id_logiciel="'.$id_logiciel.'" ');
            //verifie si le logiciel n'est pas deja attribué a la personne
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($results)){
                $errors['errors_nom'] = 'Le logiciel est deja attribué';
            }

        }
        
        if (! empty($errors)){
            header('Location: selectionLogicielForm.php?'.http_build_query(array_merge($errors,$_POST)));
            return;
        }


$query=$connexion->prepare('INSERT into salarie_logiciel (id_salarie,id_logiciel) values (:id_salarie,:id_logiciel) ');//ajoute le logiciel au salarie
$values=[
    'id_salarie'=>$_SESSION['salarie']['id'],
    'id_logiciel'=>$id_logiciel,
];
$query->execute($values);
header('Location: ../../profil.php'); 