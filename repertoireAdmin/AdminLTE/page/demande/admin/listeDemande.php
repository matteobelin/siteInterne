<?php
require('../../config/config.php');
check_auth(); // verifie si connecté
if($_SESSION['admin']['accesDemande']==0):?><!-- si la personne n'a pas acces renvoie vers la page index.php-->
    <script>alert("Vous n'avez pas acces a l'ajout d'utilisateur")</script>
    <script>
        function RedirectionJavascript(){
          document.location.href="http://localhost/repertoireAdmin2/AdminLTE/index.php"; 
        }
    </script> 
    <body onLoad="setTimeout('RedirectionJavascript()', 750)"><!-- la fonction se lance dans 750 sec-->
                <p style="font-size :30px; text-align : center ; font-weight: bold;margin-top : 12em">redirection ...</p>
    </body>
<?php else:?>
<?php
$connexion=connect_db();
$statement = $connexion->query('SELECT count(id) FROM demande WHERE resolu=0'); // compte le nombre de demande non résolu
$results=$statement->fetch(PDO::FETCH_ASSOC);
$count=0;
foreach($results as $result){
  $count=$result;
}

if(empty($_POST['resolu'])){
    $_POST['resolu']=0;
}
$statement = $connexion->query('SELECT id,nom,prenom,type,commentaire,DATE_FORMAT(date_resolu,"%d-%m-%Y") as date_resolu FROM demande where resolu='.$_POST['resolu'].'  order by date_resolu DESC');// recupere id nom prenom type commentaire date_resolu
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
if(!empty($_POST['search'])):
    ?>
        <?php

        $query = $connexion->prepare('SELECT id,nom,prenom,type,commentaire,DATE_FORMAT(date_resolu,"%d-%m-%Y") as date_resolu  FROM demande where resolu='.$_POST['resolu'].' and (nom like :nom or prenom like :prenom or type like :type) order by date_resolu DESC');// recupere id nom prenom type commentaire date_resolu qand l'un des champ correspont a la recherche     
        $query->execute([
            'nom'=>$_POST['search'].'%' ,
            'prenom'=>$_POST['search'].'%' ,
            'type'=>$_POST['search'].'%' ,
        ]);
    
        $results=$query->fetchAll(PDO::FETCH_ASSOC);
    
        endif
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Faire une demande</title>
  <link rel="icon" type="image/png" href="../../../image/LogoECCS.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../../../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../../plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<!-- Insertion de fichier -->
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../../../image/LogoECCS.png" alt="LogoEccs" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../../index.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../demande/utilisateur/demandeForm.php" class="nav-link">Déposer une demande</a>
      </li>
      <?php if($_SESSION['admin']['accesDemande']==1):?><!-- affiche seulement quand la personne a l'acces-->
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../demande/admin/listeDemande.php" class="nav-link">Voir les demandes</a>
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
          <a href="../../demande/admin/listeDemande.php" class="dropdown-item">
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
      <img src="../../../image/LogoECCS.png" alt="ECCS Logo"  style="width : 100px;height : 65px;">
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
            <img style="width: 1.5em;margin-left:0.3em;margin-right:0.8em" src="../../../image/liste.png" alt="liste">
              <p>
                Liste
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../../liste/listeOrdinateur.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ordinateur</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../../liste/listeTelephone.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Telephone</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../../liste/listeTablette.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tablette</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../../liste/listeimprimante.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Imprimante</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../../liste/listeSalarie.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Salarié</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="../../../index.php" class="nav-link">
            <img style="width: 1.8em;margin-left:0.1em;margin-right:0.8em" src="../../../image/fleche.png" alt="liste">
              <p>
                Page précédente
              </p>
            </a>
          </li>

          <li class="nav-item">
                <a href="../../admin/decoAdmin.php" class="nav-link">
                  <img style="margin-left:0.1em;width:2em;" src="../../../image/deco.png" alt="deconnexion">
                </a>
          </li>
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Salarié</h1>
          </div><!-- /.col -->
    <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
           
              </div>
              <div class="card-body">
                <div id="actions" class="row">
                  <div class="col-lg-6">
                    <div class="btn-group w-100">
                                  
                    <form style="margin-left: 3em; " method="POST" action="listedemande.php" enctype="multipart/form-data"><!--formulaire qui renvoit sur cette page permet de choisir si on veut afficher les résolu ou non résolu-->
                    
                    <select class="form-control select2bs4 m-4" style="width: 100%;" name="resolu" id="resolu">
                      <?php if($_POST['resolu']==1):?>
                        <option value="1">Resolu</option>
                        <option value="0">Non Resolu</option>
                      <?php else:?>
                        <option value="0">Non Resolu</option>
                        <option value="1">Resolu</option>
                      <?php endif?>
                    </select>
              </div>
              </div>
              <div class="col-auto d-flex align-items-center">
                <div class="btn-group">
                  <button  type="submit" class="btn btn-secondary start">
                    <span>Filtrer</span>
                  </button>
          </form>
                    
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
</section>
    
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                         <!-- /.card-header -->
                    <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Type</th>
                                <th>Commentaire</th>
                                <?php if($_POST['resolu']==1):?>
                                  <th>Date de résolution</th>
                                <?php endif?> 
                                <th>Resolu</th>
                            </tr>
                        </thead>
                        <?php foreach($results as $salarie): ?>
                        <tr>
                            <td ><?php echo $salarie['nom']?></td>
                            <td ><?php echo $salarie['prenom']?></td>
                            <td ><?php echo $salarie['type']?></td>
                            <td ><?php echo $salarie['commentaire']?></td>
                            <?php if($_POST['resolu']==1):?>
                                  <td><?php echo $salarie['date_resolu']?></td>
                                <?php endif?> 
                            <?php if($_POST['resolu']==0):?>
                                <td><a  style="text-decoration:none; color:black" href="demandeResolu.php?id=<?php echo $salarie['id']?>"><i class="fas fa-edit"></i> </a></td><!-- envois sur la page rdemande resolu avec dans l'url l'id de la demande quand on clique dessus-->
                            <?php else:?>
                                <td>✓</td>
                            <?php endif?>
                        </tr>
                        <?php endforeach?>
                    </table>
                </div>
            </div>
      </div>
</section>  
    
</div>        <!-- /.row -->
<!-- ./wrapper -->


<!-- jQuery -->
<script src="../../../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../../../plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../../../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../../../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../../../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../../../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../../../plugins/moment/moment.min.js"></script>
<script src="../../../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../../../plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../../../dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../../../dist/js/pages/dashboard.js"></script>

</body>
</html>
<?php endif?>