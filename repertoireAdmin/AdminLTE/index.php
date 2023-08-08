<?php
require('page/config/config.php');
check_auth();//verif auth
if(!empty($_SESSION['salarie']['error'])){
  $_SESSION['salarie']['error']='';
}

$connexion=connect_db();
$statement = $connexion->query('SELECT count(id) FROM demande WHERE resolu=0');//nb de demande non resolu
$results=$statement->fetch(PDO::FETCH_ASSOC);
$count=0;
foreach($results as $result){
  $count=$result;
}

if(!empty($_POST['search'])){
  $query = $connexion->prepare('SELECT salarie.nom as nom,salarie.prenom as prenom ,salarie.id as id,agence.id as agence_id, agence.ville as ville,salarie.id_licence as id_licence,licence.nom as licence FROM salarie join agence on agence.id=salarie.id_agence left join licence on licence.id=salarie.id_licence where salarie.nom like :salarie_nom or salarie.prenom like :salarie_prenom  order by ville');        
  //renvoie le nom,le prenom, l agence l'id de l agence,l'id de la licence,la licence du Salarie par rapport a la recherche faite
  $query->execute([
      'salarie_nom'=>$_POST['search'].'%' ,
      'salarie_prenom'=>$_POST['search'].'%' ,
  ]);
  

  $resultat=$query->fetchAll(PDO::FETCH_ASSOC);
    
        
}

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accueil</title>
  <link rel="icon" type="image/png" href="image/LogoECCS.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="image/LogoECCS.png" alt="Logo ECCS" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="page/demande/utilisateur/demandeForm.php" class="nav-link">Déposer une demande</a>
      </li>
      <?php if($_SESSION['admin']['accesDemande']==1):?>
      <li  class="nav-item d-none d-sm-inline-block">
        <a  href="page/demande/admin/listeDemande.php" class="nav-link">Voir les demandes</a>
      </li>
      <?php endif?>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline" method="post">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" name='search' id="search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
          </a>
          <div class="dropdown-divider"></div>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <?php if($_SESSION['admin']['accesDemande']==1):?>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge"><?php echo $count?></span>
        </a>
        <div  class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><?php echo $count?> Notifications</span>
          <div class="dropdown-divider"></div>
          <div class="dropdown-divider"></div>
          <a href="page/demande/admin/listeDemande.php" class="dropdown-item">
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
    <!-- Brand Logo -->
    
    <i class="brand-link">
      <img src="image/LogoECCS.png" alt="ECCS Logo"  style="width : 100px;height : 65px;">
    </i>

    <!-- Sidebar -->

    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        
          <span style="color:black; border:solid black; background-color:aliceblue;padding-left:0.4em;padding-right:0.4em;border-radius:50%;text-align:center;font-size: 20px;border-width:2px;text-transform: uppercase; "><?php echo $_SESSION['admin']['utilisateur'][0] ?></span>
        
        <div class="info" >
          <a href=" " class="d-block"><?php echo $_SESSION['admin']['utilisateur'] ?></a>
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
              <img style="width: 1.5em;margin-left:0.3em;margin-right:0.8em" src="image/liste.png" alt="liste">
              <p>
                Liste
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="page/liste/listeOrdinateur.php?page=<?php echo $_SERVER['REQUEST_URI'] ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ordinateur</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="page/liste/listeTelephone.php?page=<?php echo $_SERVER['REQUEST_URI'] ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Telephone</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="page/liste/listeTablette.php?page=<?php echo $_SERVER['REQUEST_URI'] ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tablette</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="page/liste/listeimprimante.php?page=<?php echo $_SERVER['REQUEST_URI'] ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Imprimante</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="page/liste/listeSalarie.php?page=<?php echo $_SERVER['REQUEST_URI'] ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Salarié</p>
                </a>
              </li>
            </ul>
          </li>
          <?php if( $_SESSION['admin']['accesSalarie'] == 1 or $_SESSION['admin']['accesLogiciel'] == 1  )://affiche si acces?>
          <li 
            class="nav-item">
            <a href="#" class="nav-link">
              <img style="width: 2em;margin-right:0.6em" src="image/ajouter.png" alt="ajouter">
              <p>
                Ajouter
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if($_SESSION['admin']['accesSalarie'] == 1)://affiche si acces?>
              <li class="nav-item">
                <a href="page/salarie/ajoutSalarie.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Salarié</p>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['admin']['accesLogiciel'] == 1)://affiche si acces?>
              <li class="nav-item">
                <a href="page/profil/logiciel/ajout/ajoutLogiciel.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Logiciel</p>
                </a>
              </li>
              <?php endif?>
            </ul>
          </li>
          <?php endif?>
          <?php if($_SESSION['admin']['accesOrdi'] == 1 or $_SESSION['admin']['accesOrdi'] == 1 or $_SESSION['admin']['accesTelephone'] == 1 or $_SESSION['admin']['accesImprimante'] == 1 or $_SESSION['admin']['accesLogiciel'] == 1)://affiche si acces?>

          <li
            class="nav-item">
            <a href="#" class="nav-link">
              <img style="margin-left:0.3em;width: 1.5em;margin-right:0.6em" src="image/modifier.png" alt="modifier">
              <p>
                Modifier
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if($_SESSION['admin']['accesOrdi'] == 1)://affiche si acces?>
              <li class="nav-item">
                <a href="page/profil/ordinateur/modifier/modifierOrdinateur.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ordinateur</p>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['admin']['accesTelephone'] == 1)://affiche si acces?>
              <li class="nav-item">
                <a href="page/profil/telephone/modifier/modifierTelephone.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Telephone</p>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['admin']['accesTablette'] == 1)://affiche si acces?>
              <li  class="nav-item">
                <a href="page/profil/tablette/modifier/modifierTablette.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tablette</p>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['admin']['accesImprimante'] == 1)://affiche si acces?>
              <li  class="nav-item">
                <a href="page/profil/imprimante/modifier/modifierImprimante.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Imprimante</p>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['admin']['accesLogiciel'] == 1)://affiche si acces?>
              <li  class="nav-item">
                <a href="page/profil/logiciel/modifier/modifierLogiciel.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Logiciel</p>
                </a>
              </li>
              <?php endif?>
            </ul>
          </li>
          <?php endif?>

          <?php if($_SESSION['admin']['accesOrdi'] == 1 or $_SESSION['admin']['accesTablette'] == 1  or $_SESSION['admin']['accesTelephone'] == 1 or $_SESSION['admin']['accesImprimante'] == 1 or $_SESSION['admin']['accesLogiciel'] == 1 ):?>
          <li class="nav-item">    
            <a href="#" class="nav-link">
              <img style="margin-left:0.2em;width: 1.5em;margin-right:0.6em" src="image/supprimer.png" alt="supprimer">
              <p>
                Supprimer
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if($_SESSION['admin']['accesOrdi'] == 1):?>
              <li  class="nav-item">
                <a href="page/profil/ordinateur/supprimer/delOrdinateurForm.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ordinateur</p>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['admin']['accesTelephone'] == 1):?>
              <li  class="nav-item">
                <a href="page/profil/telephone/supprimer/delTelephoneForm.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Telephone</p>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['admin']['accesTablette'] == 1):?>
              <li class="nav-item">
                <a href="page/profil/tablette/supprimer/delTabletteForm.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tablette</p>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['admin']['accesImprimante'] == 1):?>
              <li  class="nav-item">
                <a href="page/profil/imprimante/supprimer/delImprimanteForm.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Imprimante</p>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['admin']['accesLogiciel'] == 1):?>
              <li class="nav-item">
                <a href="page/profil/logiciel/supprimer/delLogicielForm.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Logiciel</p>
                </a>
              </li>
              <?php endif?>
            </ul>
            
          </li>
          <?php endif?>
          <li class="nav-item">
                <a href="page/admin/decoAdmin.php" class="nav-link">
                  <img style="width:2em;margin-left:0.2em" src="image/deco.png" alt="deconnexion">
                </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</div>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"> Salarié</h1>
          </div><!-- /.col -->
          <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
<?php 

$statement = $connexion->query('SELECT count(id)  from salarie');//nombre de salarie
$results=$statement->fetch(PDO::FETCH_ASSOC);
$count=0;
foreach($results as $result){
  $count=$result;
}

?>
    <!-- Main content -->
    <section class="content">
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Nombre de salarié</span>
                <span class="info-box-number"><?php echo $count?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
              <!-- /.card-header -->
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <?php
                if(empty($_POST['search'])){//si pas de recherche effectué
                  $statement = $connexion->query('SELECT salarie.nom as nom,salarie.prenom as prenom ,salarie.id as id,agence.id as agence_id, agence.ville as ville,salarie.id_licence as id_licence,licence.nom as licence FROM salarie join agence on agence.id=salarie.id_agence left join licence on licence.id=salarie.id_licence');
                  //le nom,prenom,agence,id agence,logiciel,id logiciel de chaque employé
                  $resultat = $statement->fetchAll(PDO::FETCH_ASSOC); 
                
               
                }
                ?>
 
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <?php foreach($resultat as $salarie):?>
                    <tr>
                        <td ><?php echo $salarie['nom']?></td>
                        <td ><?php echo $salarie['prenom']?></td>
                        <td>
                          <form action="page/profil/profil.php" method="post">
                            <input  type="hidden" name="id" id="id" value="<?php echo $salarie['id'] ?>">
                            <button class="btn btn-secondary" type="submit">Afficher</button>
                          </form>
                        </td>
                        <?php if($_SESSION['admin']['accesSalarie']==1):?>
                        <td>
                          <form action="page/salarie/modifSalarie.php" method="post">
                            <input type="hidden" id="id" value="<?php echo $salarie['id'] ?>" name="id"><!-- permet de cacher-->
                            <input type="hidden" id="nom" value="<?php echo $salarie['nom'] ?>" name="nom">
                            <input type="hidden" id="prenom" value="<?php echo $salarie['prenom'] ?>" name="prenom">
                            <input type="hidden" id="agence_id" value="<?php echo $salarie['agence_id'] ?>" name="agence_id">
                            <input type="hidden" id="ville" value="<?php echo $salarie['ville'] ?>" name="ville">
                            <input type="hidden" id="id_licence" value="<?php echo $salarie['id_licence'] ?>" name="id_licence">
                            <input type="hidden" id="licence" value="<?php echo $salarie['licence'] ?>" name="licence">
                            <button class="btn btn-primary" type="submit">Modifier salarié</button>
                          </form>
                        </td>
                        <?php endif?>
                        <?php if($_SESSION['admin']['accesSalarie']==1):?>
                        <td>
                          <form action="page/salarie/delSalarie.php" method="post">
                            <input  type="hidden" name="id" id="id" value="<?php echo $salarie['id'] ?>">
                            <button class="btn btn-danger" type="submit">Supprimer salarié</button>
                          </form></td>
                          <?php endif?>
                    </tr>
                  <?php endforeach;?>
                
                  
                  

                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
        <!-- /.row -->
<!-- ./wrapper -->


<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>

</body>
</html>
