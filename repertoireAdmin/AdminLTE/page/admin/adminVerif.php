<?php

function utilisateur($ldapConn,$dn,$samAccountName){ //fonction qui permet de récuperer le DN d'une personne grace a son nom d'utilisateur ex : m.belin
    $searchFilter = "(samAccountName=$samAccountName)";
    $attributes = array('dn', 'samAccountName');
    $searchResult = ldap_search($ldapConn, $dn, $searchFilter, $attributes); // recherche dans le ldap a l'emplacement du dn (exemple : Siege) le samAccoutName (nom d'utilisateur) et récupère les attribut dn del'utilisateur et son sameAccoutName

if ($searchResult) {
    $entries = ldap_get_entries($ldapConn, $searchResult);

    if ($entries['count'] > 0) {
        // L'utilisateur a été trouvé
        $userDN = $entries[0]['dn'];
        return $userDN;
    }
} else {
    header('Location: adminForm.php?' . http_build_query([
        'error' =>'Impossible de se connecter avec ces informations',
        'utilisateur' => $_POST['utilisateur'],
    ]));// renvois sur la page admin form avec le message d'erreur et le nom de l'utilsateur
    return;
}
}




require('../config/config.php');

$ldapServer = '';//ip du domaine
$ldapPort = 389; // Port par défaut LDAP
$ldapUser = ''; // Remplacez par le DN de l'utilisateur
$ldapPassword = $_POST['mdp'];
$ldapConn = ldap_connect($ldapServer, $ldapPort);

if (!$ldapConn) {
    // Gérer l'erreur de connexion
    header('Location: adminForm.php?' . http_build_query([
            'error' =>'Impossible de se connecter avec ces informations',
            'utilisateur' => $_POST['utilisateur'],
        ]));
        return;
    }

// Authentification auprès du serveur LDAP
$ldapBind = ldap_bind($ldapConn, $ldapUser, $ldapPassword);

if (!$ldapBind) {
    // Gérer l'erreur d'authentification
    header('Location: adminForm.php?' . http_build_query([
        'error' =>'Impossible de se connecter avec ces informations',
        'utilisateur' => $_POST['utilisateur'],
    ]));
    return;
}



$tab=new ArrayObject(array());


$samAccountName = $_POST['utilisateur'];

$ldapBaseDN = 'ou=Siege,dc=eccs-siege,dc=fr';
$userDN=utilisateur($ldapConn,$ldapBaseDN,$samAccountName);
if(empty($userDN)){
    $dn=""; // si l'utilisateur n'est pas dans siege on le test dans les autres
    utilisateur($ldapConn,$ldapBaseDN,$samAccountName);
}
if(empty($userDN)){
$dn="";
$userDN=utilisateur($ldapConn,$ldapBaseDN,$samAccountName);
}
if(empty($userDN)){
$dn="";
$userDN=utilisateur($ldapConn,$ldapBaseDN,$samAccountName);
}
if(empty($userDN)){
$dn="";
$userDN=utilisateur($ldapConn,$ldapBaseDN,$samAccountName);
}
if(empty($userDN)){
$dn="";
$userDN=utilisateur($ldapConn,$ldapBaseDN,$samAccountName);
}
if(empty($userDN)){
$dn="";
$userDN=utilisateur($ldapConn,$ldapBaseDN,$samAccountName);
}
if(empty($userDN)){
$dn="";
$userDN=utilisateur($ldapConn,$ldapBaseDN,$samAccountName);
}
if(empty($userDN)){ // si on ne trouve pas l'utilisateur on crée une erreur
    header('Location: adminForm.php?' . http_build_query([
        'error' =>'Impossible de se connecter avec ces informations',
        'utilisateur' => $_POST['utilisateur'],
    ]));
    return;
}





$dn="";
$searchFilter = "(member=$userDN)"; 
$attributes = array('cn');

$searchResult = ldap_search($ldapConn, $dn, $searchFilter, $attributes);// on recherche le groupe de l'utilisateur grace a son dn (userDN)

if ($searchResult) {
    $entries = ldap_get_entries($ldapConn, $searchResult);

    if ($entries['count'] > 0) {
        for ($i = 0; $i < $entries['count']; $i++) {
            $groupName = $entries[$i]['cn'][0];
            $tab->append($groupName);//on rajoute le groupe a la fin du tableau pour pouvoir ensuite comparer les noms de groupe
        }
    }
} else { // gestion des erreurs
    header('Location: adminForm.php?' . http_build_query([
        'error' =>'Impossible de se connecter avec ces informations',
        'utilisateur' => $_POST['utilisateur'],
    ]));
    return;
}
$utilisateur=strstr($userDN, ',',true);// permet de garder CN=(NOM de la personne)
$utilisateur = substr($utilisateur, '3');// garde seulement NOM de la personne

$_SESSION['admin']['utilisateur'] = utf8_encode($utilisateur);

$valeur=0;

for($i=0;$i<count($tab);$i++)
{
    if($tab[$i]=='Administrateurs de l\'entreprise'){// compare si l'utilisateur est un admin pour lui mettre les droits
        $valeur=1;
    }
}

$_SESSION['admin']['accesOrdi'] = $valeur;
$_SESSION['admin']['accesTelephone'] = $valeur;
$_SESSION['admin']['accesAjout'] = $valeur;
$_SESSION['admin']['accesSalarie'] = $valeur;
$_SESSION['admin']['accesTablette'] = $valeur;
$_SESSION['admin']['accesImprimante'] = $valeur;
$_SESSION['admin']['accesDemande'] = $valeur;
$_SESSION['admin']['accesLogiciel'] = $valeur;
$_SESSION['admin']['accesInsert'] = $valeur;
        
header('Location:../../index.php');


ldap_close($ldapConn);// ferme la connexion ldap

?>


