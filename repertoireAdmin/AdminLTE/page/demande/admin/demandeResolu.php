<?php
require('../../config/config.php');
check_auth();
$connexion= connect_db();// connexion a la base de donnée

$statement = $connexion->query('UPDATE demande set resolu=1 , date_resolu=Date(Now()) where id='.$_GET['id']);// passe en résolu avec la date du jour la demande transmise par l'url


header('Location: listeDemande.php');// renvoie sur la liste des demandes