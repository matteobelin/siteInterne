<?php
require('../config/config.php');
check_auth();//verifie si la personne est connecté


if(!empty($_POST['id'])){
  $_SESSION['salarie']['id']= $_POST['id'];
  unset($_POST);// vide la post
  header('Location: profil.php');
  }

$connexion=connect_db();//connexion a la bas de donné
$statement = $connexion->query('SELECT count(id) FROM demande WHERE resolu=0');// compte le nombre de demande non résolu
$results=$statement->fetch(PDO::FETCH_ASSOC);
$count=0;
foreach($results as $result){
  $count=$result;
}

?>
<?php if(isset($_FILES['fichier']))
    { 
      if($_SESSION['admin']['accesInsert']==0){//si pas acces erreur
        $_SESSION['salarie']['error'] = 'Vous n\'avez pas accès à l\'insertion de fichier'; 
      }
      else{
        $_FILES['fichier']['name']=$_SESSION['nom'].$_FILES['fichier']['name'];
        $nomFichier=pathinfo($_FILES['fichier']['name'],PATHINFO_FILENAME).' '.date('d-m-y');//recupere le nom du fichier et y ajoute la date du jour a la fin
        $info=new SplFileInfo($_FILES['fichier']['name']);// recupere l'extention du fichier ex:php
        $_FILES['fichier']['name']= $nomFichier.'.'.($info->getExtension());//reassemble le fichier cad le nom et l extension
        $dossier = '../documentExcel/';
        if(file_exists($dossier.basename($_FILES['fichier']['name']))){//verfie si le dossier existe deja sinon l'enregistre
            $_SESSION['salarie']['error']= 'un fichier porte deja ce nom';
        }
        else{
            $fichier = basename($_FILES['fichier']['name']);
            $_SESSION['salarie']['error']='';
        
            if(!move_uploaded_file($_FILES['fichier']['tmp_name'], $dossier . $fichier)) 
            {
              $_SESSION['salarie']['error']= 'Echec de l\'upload !';
            }
        }
      }
       
    }?>
<?php if(!empty($_FILES['fichier']['name'])){
    header('Location: profil.php');
    return;
    }?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil</title>
  <link rel="icon" type="image/png" href="../../image/LogoECCS.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<!-- Insertion de fichier -->


<?php
if(!empty($_POST['fichier'])):?>
    <script>
        window.location.href="/repertoireAdmin/AdminLTE/page/documentExcel/<?php echo $_POST['fichier']?>"//envoie vers le fichier selectionné
    </script>
<?php endif?>    
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../../image/LogoECCS.png" alt="Logo ECCS" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../demande/utilisateur/demandeForm.php" class="nav-link">Déposer une demande</a>
      </li>
      <?php if($_SESSION['admin']['accesDemande']==1):?><!-- affiche seulement si acces  -->
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../demande/admin/listeDemande.php" class="nav-link">Voir les demandes</a>
      </li>
      <?php endif?>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
          </a>
          <div class="dropdown-divider"></div>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <?php if($_SESSION['admin']['accesDemande']==1):?><!-- affiche seulement si acces -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge"><?php echo $count?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><?php echo $count?> Notifications</span>
          <div class="dropdown-divider"></div>
          <div class="dropdown-divider"></div>
          <a href="../demande/admin/listeDemande.php" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> <?php echo $count?> nouvelles demandes
          </a>
        </div>
      </li>
      <?php endif?>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <i class="brand-link">
      <img src="../../image/LogoECCS.png" alt="ECCS Logo"  style="width : 100px;height : 65px;">
    </i>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        
          <span style="color:black; border:solid black; background-color:aliceblue;padding-left:0.4em;padding-right:0.4em;border-radius:50%;text-align:center;font-size: 20px;border-width:2px;text-transform: uppercase; "><?php echo $_SESSION['admin']['utilisateur'][0] ?></span>
        
        <div class="info" >
          <a href="../admin/modifProfil/modifProfil.php?page=<?php echo $_SERVER['REQUEST_URI'] ?>" class="d-block"><?php echo $_SESSION['admin']['utilisateur'] ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->


          <li class="nav-item">
            <a href="#" class="nav-link">
            <img style="width: 1.5em;margin-left:1em;margin-right:0.8em" src="../../image/liste.png" alt="liste">
              <p>
                Liste
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../liste/listeOrdinateur.php?page=<?php echo $_SERVER['REQUEST_URI'] ?>" class="nav-link"><!-- $_SERVER['REQUEST_URI'] recupere l'url de la page-->
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ordinateur</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../liste/listeTelephone.php?page=<?php echo $_SERVER['REQUEST_URI'] ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Telephone</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../liste/listeTablette.php?page=<?php echo $_SERVER['REQUEST_URI'] ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tablette</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../liste/listeimprimante.php?page=<?php echo $_SERVER['REQUEST_URI'] ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Imprimante</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../liste/listeSalarie.php?page=<?php echo $_SERVER['REQUEST_URI'] ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Salarié</p>
                </a>
              </li>
            </ul>
          </li>
          <?php if($_SESSION['admin']['accesOrdi']==1):?><!-- affiche seulement si acces -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <img style="width: 3em; padding-left:-1em; margin-left:0.2em" src="../../image/ordinateur.png" alt="ordinateur">
              <p>
                Ordinateur
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="ordinateur/ajout/ajoutOrdinateur.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="ordinateur/selection/selectionOrdinateurForm.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sélectionner</p>
                </a>
              </li>
            </ul>
          </li>
          <?php endif?>
          <?php if($_SESSION['admin']['accesTelephone']==1):?><!-- affiche seulement si acces -->
          <li  class="nav-item">
            <a href="#" class="nav-link">
            <img style="margin-left:0.9em;width: 1.5em;margin-right:0.7em;" src="../../image/telephone.png" alt="telephone">
              <p>
                Telephone
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="telephone/ajout/ajoutTelephone.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="telephone/selection/selectionTelephoneForm.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sélectionner</p>
                </a>
              </li>
            </ul>
          </li> 
          <?php endif?> 

          <?php if($_SESSION['admin']['accesLogiciel']==1):?><!-- affiche seulement si acces -->
          <li class="nav-item">
            <a href="#" class="nav-link">
            <img style="margin-left:0.2em;width: 3em;" src="../../image/logiciel.png" alt="logiciel">
              <p>
                Logiciel
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="logiciel/selectionner/selectionLogicielForm.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sélectionner</p>
                </a>
              </li>
            </ul>
          </li>
          <?php endif?>
          <?php if($_SESSION['admin']['accesTablette']==1):?><!-- affiche seulement si acces -->
          <li class="nav-item">
            <a href="#" class="nav-link">
            <img style="margin-left:0.5em;width: 2em;margin-right:0.6em" src="../../image/tablette.png" alt="tablette">
              <p>
                Tablette
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="tablette/ajout/ajoutTablette.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="tablette/selection/selectionTabletteForm.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sélectionner</p>
                </a>
              </li>
            </ul>
          </li>
          <?php endif?>
          <?php if($_SESSION['admin']['accesImprimante']==1):?><!-- affiche seulement si acces -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <img style="margin-left:0.2em;width: 2.5em;margin-right:0.5em" src="../../image/imprimante.png" alt="imprimante">
              <p>
                Imprimante
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="imprimante/ajout/ajoutImprimante.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="imprimante/selection/selectionImprimanteForm.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sélectionner</p>
                </a>
              </li>
            </ul>
          </li>
          <?php endif?>
          <li class="nav-item">
            <a href="../config/pdf/FeuillePhp.php" class="nav-link">
            <img style="margin-left:0.6em;width: 2em;margin-right:0.6em" src="../../image/pdf.png" alt="pdf">
              <p>
                Recap
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../../index.php" class="nav-link">
            <img style="width: 1.8em;margin-left:0.7em;margin-right:0.7em" src="../../image/fleche.png" alt="liste">
              <p>
                Page précédente
              </p>
            </a>
          </li>
          <li class="nav-item">
                <a href="../admin/decoAdmin.php" class="nav-link">            
                  <img  style=" margin-left:0.7em;width:2em;" src="../../image/deco.png" alt="deconnexion">
                </a>
          </li>
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</div>

    <!-- /.content-header -->
     <!-- /.content -->
<?php
    


$statement = $connexion->query('SELECT salarie.nom as nom ,salarie.prenom as prenom,licence.nom as licence FROM salarie left join licence on licence.id=salarie.id_licence where salarie.id='.$_SESSION['salarie']['id']);
//recupere le nom,le pernom,la licence du salarié
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

?>
<?php foreach($results as $salarie): ?>
            <tr>
            <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
                <?php echo $salarie['nom']?>
                <?php echo $salarie['prenom']?>                  
            </h1>
          </div><!-- /.col -->
          
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <?php $nom=$salarie['nom'].$salarie['prenom']?>
    <?php $_SESSION['nom']=$nom?>
                            
<?php endforeach ?>
<section class="content" style="margin-top: 3em;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" style="font-weight: bold;">Licence</h3>
                        </div>          <!-- /.card-header -->
                    <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nom de la licence</th>
                            </tr>
                        </thead>
                        <tr>
                          <td>
                            MICROSOFT : <?php echo $salarie['licence']?>
                          </td>
                        </tr>
                    </table>
                </div>
            </div>
      </div>
</section>
<?php

$statement = $connexion->query('SELECT ordinateur.numero_serie as ordinateur_numero_serie,ordinateur.nom as ordinateur_nom, ordinateur.code_compta as ordinateur_code_compta,ordinateur.designation as ordinateur_designation,ordinateur.marque as ordinateur_marque, ordinateur.status as ordinateur_status FROM ordinateur where id_salarie='.$_SESSION['salarie']['id']);
//recupere le numero de serie,le nom, le code compta,la designation,le status,la marque de l'ordinateur
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
    ?>
 
 <section class="content" style="margin-top: 3em;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" style="font-weight: bold;">Ordinateur</h3>
                        </div>          <!-- /.card-header -->
                    <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Numero de serie</th>
                                <th>Nom</th>
                                <th>Code compta</th>
                                <th>Designation</th>
                                <th>Marque</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <?php foreach($results as $salarie):?>
                        <tr>
                            <td><?php echo $salarie['ordinateur_numero_serie']?></td>
                            <td><?php echo $salarie['ordinateur_nom']?></td>
                            <td><?php echo $salarie['ordinateur_code_compta']?></td>
                            <td><?php echo $salarie['ordinateur_designation']?></td>
                            <td><?php echo $salarie['ordinateur_marque']?></td>
                            <?php if($salarie['ordinateur_status']==1){
                                 $salarie['ordinateur_status']='EN SERVICE';
                            }elseif($salarie['ordinateur_status']==0){
                                 $salarie['ordinateur_status']='HORS SERVICE';
                            }
                            else{
                                 $salarie['ordinateur_status']='';
                            }?>
                            <td><?php echo $salarie['ordinateur_status']?></td>
                        </tr>
                        <?php endforeach;?>
                    </table>
                </div>
            </div>
      </div>
</section> 
<?php
$statement = $connexion->query('SELECT telephone.numero_serie as telephone_numero_serie, telephone.code_compta as telephone_code_compta,telephone.type as telephone_type, telephone.marque as telephone_marque, telephone.ntelephone as telephone_ntelephone,telephone.ncarte as telephone_ncarte FROM telephone where id_salarie='.$_SESSION['salarie']['id']);
//recupere le numero de serie,le code compta, le type, la marque, le ncarte,le ntelephone du telephone
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="content" style="margin-top: 3em;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" style="font-weight: bold;">Telephone</h3>
                        </div>          <!-- /.card-header -->
                    <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Numero de serie</th>
                                <th>Type</th>
                                <th>Code compta</th>
                                <th>Marque</th>
                                <th>Numero de telephone</th>
                                <th>Numero de carte</th>
                            </tr>
                        </thead>
                        <?php foreach($results as $salarie):?>
                        <tr>
                            <td><?php echo $salarie['telephone_numero_serie']?></td>
                            <td><?php echo $salarie['telephone_type']?></td>
                            <td><?php echo $salarie['telephone_code_compta']?></td>
                            <td><?php echo $salarie['telephone_marque']?></td>
                            <td><?php if(!empty($salarie['telephone_ntelephone'])){
                                    echo 0 . $salarie['telephone_ntelephone'];
                                    }?>
                            </td>
                            <td><?php echo $salarie['telephone_ncarte']?></td>
                        </tr>
                        <?php endforeach;?>
                    </table>
                </div>
            </div>
      </div>
</section> 
<?php 
 $statement = $connexion->query('SELECT logiciel.nom as nom from logiciel join salarie_logiciel on logiciel.id=salarie_logiciel.id_logiciel join salarie on salarie.id=salarie_logiciel.id_salarie where id_salarie='.$_SESSION['salarie']['id']);
 //recupere le nom du logiciel
 $results = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="content" style="margin-top: 3em;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" style="font-weight: bold;">Logiciel</h3>
                        </div>          <!-- /.card-header -->
                    <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nom du logicel</th>
                            </tr>
                        </thead>
                        <?php foreach($results as $salarie):?>
                        <tr>
                            <td><?php echo $salarie['nom']?></td>
                            <td><a class = " btn btn-danger" href="logiciel/modifier/delLogicielSalarie.php?logiciel=<?php echo $salarie['nom']?>">retirer le logiciel</a></td>
                        </tr>
                        <?php endforeach;?>
                    </table>
                </div>
            </div>
      </div>
</section>   

<?php
$statement = $connexion->query('SELECT tablette.code_compta as tablette_code_compta, tablette.marque as tablette_marque,tablette.type as tablette_type,tablette.numero_serie as tablette_numero_serie from tablette where id_salarie='.$_SESSION['salarie']['id']);
//recupere le code compta,le numero de serie,le type et la marque de la tablette
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="content" style="margin-top: 3em;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" style="font-weight: bold;">Tablette</h3>
                        </div>          <!-- /.card-header -->
                    <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Numero de serie</th>
                                <th>Type</th>
                                <th>Code compta</th>
                                <th>Marque</th>
                            </tr>
                        </thead>
                        <?php foreach($results as $salarie):?>
                        <tr>
                            <td><?php echo $salarie['tablette_numero_serie']?></td>
                            <td><?php echo $salarie['tablette_type']?></td>
                            <td><?php echo $salarie['tablette_code_compta']?></td>
                            <td><?php echo $salarie['tablette_marque']?></td>
                        </tr>
                        <?php endforeach;?>
                    </table>
                </div>
            </div>
      </div>
</section>
<?php
$statement = $connexion->query('SELECT imprimante.ip as imprimante_ip,imprimante.marque as imprimante_marque,imprimante.modele as imprimante_modele,imprimante.numero_serie as imprimante_numero_serie from imprimante where id_salarie='.$_SESSION['salarie']['id']);
//recupere la marque,l'ip,le modele,le numero de serie de l'imprimante
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="content" style="margin-top: 3em;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" style="font-weight: bold;">Imprimante</h3>
                        </div>          <!-- /.card-header -->
                    <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Numero de serie</th>
                                <th>Marque</th>
                                <th>Modèle</th>
                                <th>Adresse ip</th>
                            </tr>
                        </thead>
                        <?php foreach($results as $salarie):?>
                        <tr>
                            <td><?php echo $salarie['imprimante_numero_serie']?></td>
                            <td><?php echo $salarie['imprimante_marque']?></td>
                            <td><?php echo $salarie['imprimante_modele']?></td>
                            <td><?php echo $salarie['imprimante_ip']?></td>
                        </tr>
                        <?php endforeach;?>
                    </table>
                </div>
            </div>
      </div>
</section>
<?php if($_SESSION['admin']['accesInsert']==1):?><!-- s'affiche si la personne a acces-->
<section class="content " style="margin-top: 3em;">
<div class="container-fluid">
  <div class="row">
          <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title" style="font-weight: bold;">Ajouter un document</h3>
              </div>
              <div class="card-body">
                <div id="actions" class="row">
                  <div class="col-lg-6">
                    <div class="btn-group w-100">
                    
                <form style="margin-left: 3em; " method="POST" action="profil.php" enctype="multipart/form-data">
                  <label for="fichier">
                    <span class="btn btn-success col fileinput-button">
                      <i class="fas fa-plus"></i>
                      <span>Add files</span>
                    </span>
                  </label>
                <input  style="display:none" id="fichier" type="file" name="fichier">
                </div>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                      <div class="btn-group">
                        <button  type="submit" class="btn btn-primary start">
                          <i class="fas fa-upload"></i>
                          <span>Start</span>
                        </button>
                        
                </form>
                
                      </div>
                    </div> 
                   
                      <?php if(!empty($_SESSION['salarie']['error'])){

                      echo $_SESSION['salarie']['error'];
                      }
                      ?>
                  </div>
                </div>
              </div>
          </div>
</section>
<?php endif?>
<section class="content" style="margin-top: 3em;">
<div class="container-fluid">
  <div class="row">
          <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title" style="font-weight: bold;">Télécharger un document</h3>
              </div>
              <div class="card-body">
                <div id="actions" class="row">
                  <div class="col-lg-6">
                    <div class="btn-group w-100">
                    <?php
                  $PATH = '../documentExcel/';// repertoire dans lequel il va regarder
                  if ($dir = opendir($PATH)) {// ouverture du dossier 
                    while($file = readdir($dir)) { // lecture d'une entrée 
                    //création d'un tableau à 2 colonnes : nom + date fichiers 
                      $tab[] =$file;  
                    }
                    closedir($dir); 
                  }

                  $i=0;
                  $rest = substr($tab[$i], 0,strlen($nom));
                  ?>
              
                    
                <form style="margin-left: 3em; " method="POST" action="profil.php" enctype="multipart/form-data">
                    
                          <select class="form-control select2bs4 m-4" style="width: 100%;" name="fichier" id="fichier">
                      <?php while($i<= (count($tab)-1)) :?>
                      <?php
                          $rest = substr($tab[$i], 0,strlen($nom));
                          if (strcmp($nom, $rest) === 0)://strcmp compare les deux chaine?>
                          <option value="<?php echo $tab[$i]?>"><?php echo $tab[$i]?></option>
                        <?php endif?>
                    <?php
                        $i++;
                  endwhile?>
                </select>
                    </div>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                      <div class="btn-group">
                        <button  type="submit" class="btn btn-primary start">
                          <i class="fas fa-upload"></i>
                          <span>Télecharger</span>
                        </button>
                </form>
                    
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
</section>
</div> 
        <!-- /.row -->
<!-- ./wrapper -->


<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../../plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../../plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../../dist/js/pages/dashboard.js"></script>

</body>
</html>
