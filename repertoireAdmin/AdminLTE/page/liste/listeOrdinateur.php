<?php
require('../config/config.php');
check_auth();//verifie si la personne est connecté
$connexion=connect_db();// connexion a la base 
$statement = $connexion->query('SELECT count(id) FROM demande WHERE resolu=0');// recupere le nom de demande résolu
$results=$statement->fetch(PDO::FETCH_ASSOC);
$count=0;
foreach($results as $result){
  $count=$result;
}

if(!empty($_GET['page'])){
    $_SESSION['page']=$_GET['page'];
}
if(!empty($_POST['search'])){
    $query = $connexion->prepare('SELECT ordinateur.numero_serie as numero_serie,ordinateur.code_compta as code_compta,ordinateur.designation as designation, ordinateur.marque as marque,ordinateur.nom as nom_ordinateur,ordinateur.status as status,salarie.nom as nom, salarie.prenom as prenom FROM ordinateur left join salarie on salarie.id=ordinateur.id_salarie where salarie.nom like :nom_salarie or salarie.prenom like :prenom_salarie or numero_serie like :numero_serie or code_compta like :code_compta or designation like :designation or marque like :marque or ordinateur.nom=:nom_ordinateur order by nom');        
    // recupere le numero de serie de l'ordinateurson code compta,sa designation,sa marque,son nom,son status et le salarié a qui il appartient(NOM,PRENOM),lorsque le nom,le prenom du salarié ou le numero de serie,le code compta,la designation ou le nom de l'ordinateur correspond a la recherche    
    $query->execute([
            'nom_salarie'=>$_POST['search'].'%' ,
            'prenom_salarie'=>$_POST['search'].'%' ,
            'numero_serie'=> $_POST['search'].'%' ,
            'code_compta'=>$_POST['search'].'%' ,
            'designation'=> $_POST['search'].'%' ,
            'marque'=> $_POST['search'].'%' ,
            'nom_ordinateur'=>$_POST['search'].'%' ,
        
        ]);
        
        $results=$query->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Liste Ordinateur</title>
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
      <?php if($_SESSION['admin']['accesDemande']==1):?><!-- affiche seulement quand la personne a acces a la consultation des demandes -->
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
          <form class="form-inline" method="post"><!-- formulaire qui permet d'effectuer des recherches-->
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
                <a href="listeOrdinateur.php?page=<?php echo $_SERVER['REQUEST_URI']?>" class="nav-link"><!-- $_SERVER['REQUEST_URI'] recupere l'url de la page-->
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
            <a href="../config/pdf/Ordinateur.php" class="nav-link">
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
            <h1 class="m-0">Ordinateur</h1>
          </div><!-- /.col -->
    <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <?php
                if(empty($_POST['search'])){// si pas de recherche effectué
                    $statement = $connexion->query('SELECT ordinateur.numero_serie as numero_serie,ordinateur.code_compta as code_compta,ordinateur.designation as designation, ordinateur.marque as marque,ordinateur.nom as nom_ordinateur,ordinateur.status as status,salarie.nom as nom, salarie.prenom as prenom FROM ordinateur left join salarie on salarie.id=ordinateur.id_salarie order by nom');
                    //recupère le numero de serie, le code compta, la designation,la marque, le nom et le status de l'ordinateur ainsi que le salairé a qui il appartient (NOM,PRENOM)
                    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                }
                
                ?>
 
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                        <thead style="font-weight: bold;">
                            <tr>
                                <td >Nom</td >
                                <td>Prenom</td>
                                <td>Numero de serie</td>
                                <td>Nom</td>
                                <td>Designation</td>
                                <td>code compta</td>
                                <td>marque</td>
                                <td>status</td>
                            </tr>
                        </thead>
                        <?php foreach($results as $ordinateur): ?>
                        <tr>
                            <td scope="col" class="col-1"><?php echo $ordinateur['nom']?></td>
                            <td scope="col" class="col-1"><?php echo $ordinateur['prenom']?></td>
                            <td scope="col" class="col-2"><?php echo $ordinateur['numero_serie']?></td>
                            <td scope="col" class="col-2"><?php echo $ordinateur['nom_ordinateur']?></td>
                            <td scope="col" class="col-3"><?php echo $ordinateur['designation']?></td>
                            <td scope="col" class="col-2"><?php echo $ordinateur['code_compta']?></td>
                            <td scope="col" class="col-1"><?php echo $ordinateur['marque']?></td>
                            <?php if($ordinateur['status']==1){
                                        $ordinateur['status']='EN SERVICE';
                                    }elseif($ordinateur['status']==0){
                                        $ordinateur['status']='HORS SERVICE';
                                    }
                                    else{
                                        $ordinateur['status']='';
                                    }?>
                            <td scope="col" class="col-1"><?php echo $ordinateur['status']?></td>
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