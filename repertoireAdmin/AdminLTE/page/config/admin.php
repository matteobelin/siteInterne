<?php 
function check_auth(){ // verifie si la personne est connecté sinon renvoie vers la page pour se connecter
    if (empty($_SESSION['admin'])){
            header('Location:/repertoireAdmin2/AdminLTE/page/admin/adminForm.php');
            return;
    }
}