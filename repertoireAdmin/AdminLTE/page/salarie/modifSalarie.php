<?php
require('../config/config.php');
check_auth();
if($_SESSION['admin']['accesSalarie']==0):?>
    <script>alert("Vous n'avez pas acces a la modification de salarié")</script>
    <script>
        function RedirectionJavascript(){
            document.location.href="http://localhost/repertoireAdmin2/AdminLTE/index.php"; 
        }
    </script> 
    <body onLoad="setTimeout('RedirectionJavascript()', 750)">
                <p style="font-size :30px; text-align : center ; font-weight: bold;margin-top : 12em">redirection ...</p>
    </body>
<?php else:?>
<?php

$connexion=connect_db();
$statement = $connexion->query('SELECT count(id) FROM demande WHERE resolu=0');
$results=$statement->fetch(PDO::FETCH_ASSOC);
$count=0;
foreach($results as $result){
  $count=$result;
}
$connexion= connect_db();
$statement = $connexion->query('SELECT * FROM agence');
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement = $connexion->query('SELECT * FROM licence');
$resultat = $statement->fetchAll(PDO::FETCH_ASSOC);
if(!empty($_POST['agence_id'])){
    $_SESSION['modif']['agende_id']=$_POST['agence_id'];
    $_SESSION['modif']['ville']=$_POST['ville'];
    $_SESSION['modif']['id']=$_POST['id'];
    $_SESSION['modif']['licence_id']=$_POST['id_licence'];
    $_SESSION['modif']['licence']=$_POST['licence'];
    $_SESSION['modif']['prenom']=$_POST['prenom'];
    $_SESSION['modif']['nom']=$_POST['nom'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier un Salarié</title>
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
      <?php if($_SESSION['admin']['accesDemande']==1):?> 
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../demande/admin/listeDemande.php" class="nav-link">Voir les demandes</a>
      </li>
      <?php endif?>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      
      <!-- Notifications Dropdown Menu -->
      <?php if($_SESSION['admin']['accesDemande']==1):?> 
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge"><?php echo $count?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><?php echo $count?> Notifications</span>
          <div class="dropdown-divider"></div>
          <div class="dropdown-divider"></div>
          <a href="../../../demande/admin/listeDemande.php" class="dropdown-item">
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
        <div class="info">
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
            <img style="width: 1.5em;margin-left:0.3em;margin-right:0.8em" src="../../image/liste.png" alt="liste">
              <p>
                Liste
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../liste/listeOrdinateur.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ordinateur</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../liste/listeTelephone.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Telephone</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../liste/listeTablette.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tablette</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../liste/listeimprimante.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Imprimante</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../liste/listeSalarie.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Salarié</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="../../index.php" class="nav-link">
            <img style="width: 1.8em;margin-left:0.1em;margin-right:0.8em" src="../../image/fleche.png" alt="liste">
              <p>
                Page précédente
              </p>
            </a>
          </li>

          <li class="nav-item">
                <a href="../admin/decoAdmin.php" class="nav-link">
                  <img style="margin-left:0.1em;width:2em;" src="../../image/deco.png" alt="deconnexion">
                </a>
          </li>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Modifier un salarié</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->



    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
             
              <!-- /.card-header -->
              <!-- form start -->
              <form action="changeSalarie.php" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="nom">Nom</label>
                    <input class="form-control" type="text" id='nom' placeholder="Nom" name='nom' value="<?=$_SESSION['modif']['nom'] ??''?>">
                    <?php if (isset($_GET['errors_nom'])): ?>
                    <div style="color : red">
                        <?php echo $_GET['errors_nom'];?>
                    </div>
                    <?php endif?>
                             
                  </div>
                  <div class="form-group">
                    <label for="prenom">Prenom</label>
                    <input class="form-control" type="text" id='prenom' placeholder="Prenom" name='prenom' value="<?=$_SESSION['modif']['prenom'] ??''?>">
                    <?php if (isset($_GET['errors_prenom'])): ?>
                    <div style="color : red">
                        <?php echo $_GET['errors_prenom'];?>
                    </div>
                    <?php endif?>
                  </div>
                  <div class="form-group">
                  <label for="agence" >Nom de l'agence</label>
                    <select class="form-control" name="agence" id="agence">
                        <option value="<?= $_SESSION['modif']['agende_id'] ?>"><?php echo $_SESSION['modif']['ville']?></option>
                        <?php foreach($results as $agence):?>
                            <?php if($agence['id']!= $_SESSION['modif']['agende_id']):?>
                                <option value="<?=$agence['id'] ?>"><?php echo $agence['ville']?></option>
                            <?php endif?>
                        <?php endforeach?>
                    </select>
                  </div>
                  <div class="form-group">
                  <label for="licence">Licence microsoft</label>
                    <select class="form-control" name="licence" id="licence">
                        <option value="<?= $_SESSION['modif']['licence_id'] ?>"><?php echo $_SESSION['modif']['licence']?></option>
                        <?php foreach($resultat as $licence):?>
                            <?php if($licence['id']!= $_SESSION['modif']['licence_id']):?>
                                <option value="<?=$licence['id'] ?>"><?php echo $licence['nom']?></option>
                            <?php endif?>
                        <?php endforeach?>
                    </select>
                  </div>
                  <button class="btn btn-primary" type="submit">Valider</button>
                </form>
        
            
    </div>


    
</div>        <!-- /.row -->
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
<?php endif?>