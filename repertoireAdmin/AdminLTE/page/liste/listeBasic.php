<?php
require('../config/config.php');
check_auth();//verifie si connecté
$connexion=connect_db();
$statement = $connexion->query('SELECT count(id) FROM demande WHERE resolu=0');//renvoie le nombre de demande non résolu
$results=$statement->fetch(PDO::FETCH_ASSOC);
$count=0;
foreach($results as $result){
  $count=$result;
}

if(!empty($_GET['page'])){
    $_SESSION['page']=$_GET['page'];
}
if(!empty($_POST['search'])){
    $query = $connexion->prepare('SELECT salarie.nom as nom,salarie.prenom as prenom,agence.ville as ville, licence.nom as licence FROM salarie join agence on agence.id=salarie.id_agence left join licence on licence.id=salarie.id_licence where licence.nom="Basic" and (salarie.nom like :salarie_nom or salarie.prenom like :salarie_prenom or agence.ville like :ville) order by ville');        
   // selectionne le nom du salarie,son prenom, l'agence a qui il appartient,la licence microsoft qu'il possede lorsque le nom,le prenom ou la ville correspond a la valeur renvoyé par le formulaire et que la licence est basic  
    $query->execute([
        'salarie_nom'=>$_POST['search'].'%' ,
        'salarie_prenom'=>$_POST['search'].'%' ,
        'ville'=>$_POST['search'].'%' ,
    ]);


  $results=$query->fetchAll(PDO::FETCH_ASSOC);
    
        
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Liste Salarié Basic</title>
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
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline" method="post"><!-- formulaire qui renvoie sur cette page et permet de faire une recherche-->
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
                <a href="listeOrdinateur.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link"><!-- $_SERVER['REQUEST_URI'] recupère l'url de la page -->
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ordinateur</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="listeTelephone.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Telephone</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="listeTablette.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tablette</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="listeimprimante.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Imprimante</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="listeSalarie.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Salarié</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="../config/pdf/SalarieBasic.php" class="nav-link">
            <img style="width: 2em;margin-right:0.6em" src="../../image/pdf.png" alt="pdf">
              <p>
                Recap
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $_SESSION['page']?>" class="nav-link">
            <img style="width: 1.8em;margin-left:0.1em;margin-right:0.8em" src="../../image/fleche.png" alt="fleche">
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
            <h1 class="m-0">Salarié Basic</h1>
          </div><!-- /.col -->
    <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <?php    
                  $statement = $connexion->query('SELECT licence.nom as licence FROM licence');// Selectionne tout les noms de licence
                  $resultat = $statement->fetchAll(PDO::FETCH_ASSOC);
              
                ?>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
           
              </div>
              <div class="card-body">
                <div id="actions" class="row">
                  <div class="col-lg-6">
                    <div class="btn-group w-100">
                                  
                  <form style="margin-left: 3em; " method="POST" action="listeSalarie.php" enctype="multipart/form-data"><!-- Formulaire qui permet de choisir comment on veut trié les salarié en fonction de leur licence-->
                    
                          <select class="form-control select2bs4 m-4" style="width: 100%;" name="licence" id="licence">
                            <option value="liste<?php echo 'Basic'?>.php"><?php echo 'Basic'?></option>
                            <option value="liste<?php echo 'Salarie'?>.php"><?php echo 'Salarié'?></option>
                          <?php foreach($resultat as $result):?>
                            <?php if($result['licence']!='Basic'):?>
                              <option value="liste<?php echo $result['licence']?>.php"><?php echo $result['licence']?></option>
                          <?php endif; endforeach?>
                      
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
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <?php
                if(empty($_POST['search'])){// si aucune recherche n'a été faite cette requette est lancé
                    $statement = $connexion->query('SELECT salarie.nom as nom,salarie.prenom as prenom,agence.ville as ville,licence.nom as licence FROM salarie join agence on agence.id=salarie.id_agence left join licence on licence.id=salarie.id_licence where licence.nom="Basic" order by ville');
                   //recupere le nom du salarié,son prenom, son agence,sa licence lorsque la personne possede une licence basic
                    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                }
                
                ?>
 
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                        <thead style="font-weight: bold;">
                            <tr>
                                <td >Nom</td>
                                <td>Prenom</td>
                                <td>Ville</td>
                                <td>Licence</td>
                            </tr>
                        </thead>
                        <?php foreach($results as $salarie): ?>
                        <tr>
                            <td scope="col" class="col-3"><?php echo $salarie['nom']?></td>
                            <td scope="col" class="col-3"><?php echo $salarie['prenom']?></td>
                            <td scope="col" class="col-3"><?php echo $salarie['ville']?></td>
                            <td scope="col" class="col-3"><?php echo $salarie['licence']?></td>
                        </tr>
                    <?php endforeach ?>
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