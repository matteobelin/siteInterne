

<?php 

session_start();
$link = mysqli_connect('localhost','root','','distribution'); // connection a la base de donnée
if(!empty($_GET['id'])){
    $_SESSION['salarie']['id']= $_GET['id'];
}


$requete =('SELECT salarie.nom as nom ,prenom,licence.nom as licence FROM salarie  left join licence on licence.id=salarie.id_licence  where salarie.id='.$_SESSION['salarie']['id']);
$result = mysqli_query($link, $requete);
$salarie = mysqli_fetch_array($result);
mysqli_free_result($result);
require("../../fpdf.php");

class PDF extends FPDF {
    // Footer
    function Footer() {
      // Positionnement à 1,5 cm du bas
      $this->SetY(-15);
      // Police Arial italique 9
      $this->SetFont('Helvetica','I',9);
      // Numéro de page, centré (C)
      $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
  }
  // On active la classe une fois pour toutes les pages suivantes
  // Format portrait (>P) ou paysage (>L), en mm (ou en points > pts), A4 (ou A5, etc.)
  $pdf = new PDF('P','mm','A4');
  
  // Nouvelle page A4 (incluant ici logo, titre et pied de page)
  $pdf->AddPage();
  // Polices par défaut : Helvetica taille 9
  $pdf->SetFont('Helvetica','',9);
  // Couleur par défaut : noir
  $pdf->SetTextColor(0);
  $pdf->setFillColor(255,255,255);
  // Compteur de pages {nb}
  $pdf->AliasNbPages();
  // Sous-titre calées à gauche, texte gras (Bold), police de caractère 11
  $pdf->SetFont('Helvetica','B',20);
  $pdf->Ln(20);
  $pdf->Cell(75,6,$salarie['nom'].' '.$salarie['prenom'],0,0,'L',1);
  $pdf->Image('../../../image/LogoECCS.png',10,6,30);
  $pdf->Ln(17); // saut de ligne 35mm
  $pdf->SetFont('Helvetica','B',11);
  $pdf->Cell(75,6,'Microsoft : '.$salarie['licence'],0,1,'L',1);
  $pdf->Ln(8);
  $pdf->SetFont('Helvetica','B',11);
  $pdf->Cell(75,6,'Ordinateur',0,1,'L',1);
  

  // Fonction en-tête des tableaux en 3 colonnes de largeurs variables
function entete_table($position_entete) {
    global $pdf;
    $pdf->SetDrawColor(183); // Couleur du fond RVB
    $pdf->SetFillColor(221); // Couleur des filets RVB
    $pdf->SetTextColor(0); // Couleur du texte 
    $pdf->SetY($position_entete);
    // position de colonne 1 (10mm à gauche)  
    $pdf->SetX(10);
    $pdf->Cell(40,8,'numero de serie',1,0,'C',1);  // 40 >largeur colonne, 8 >hauteur colonne
    // position de la colonne 2 (50 = 10+40)
    $pdf->SetX(50); 
    $pdf->Cell(40,8,'nom',1,0,'C',1);
    // position de la colonne 3 (90 = 40+50)
    $pdf->SetX(90); 
    $pdf->Cell(30,8,'code compta',1,0,'C',1);
    $pdf->SetX(120); 
    $pdf->Cell(30,8,'designation',1,0,'C',1);
    $pdf->SetX(150); 
    $pdf->Cell(20,8,'marque',1,0,'C',1);
    $pdf->SetX(170); 
    $pdf->Cell(30,8,'status',1,0,'C',1);
  
    $pdf->Ln(); // Retour à la ligne
  }
  $position_entete = 70;
// police des caractères
    $pdf->SetFont('Helvetica','',9);
    $pdf->SetTextColor(0);
// on affiche les en-têtes du tableau
    entete_table($position_entete);
    $position_detail = 78;
    $requete2 =('SELECT numero_serie,nom,code_compta,designation,marque, status FROM ordinateur where id_salarie='.$_SESSION['salarie']['id']);
    $result2 = mysqli_query($link, $requete2);
    while ($ordinateur = mysqli_fetch_array($result2)){
        $pdf->SetY($position_detail);
        $pdf->SetX(10);
        $pdf->MultiCell(40,8,$ordinateur['numero_serie'],1,'C'); # multicell -> Autant de cellules que nécessaire sont générées, les unes en dessous des autres.
          // position abcisse de la colonne 2 (50 = 10 + 40)  
        $pdf->SetY($position_detail);
        $pdf->SetX(50); 
        $pdf->MultiCell(40,8,$ordinateur['nom'],1,'C');
        // position abcisse de la colonne 3 (90 = 50+ 40)
        $pdf->SetY($position_detail);
        $pdf->SetX(90); 
        $pdf->MultiCell(30,8,$ordinateur['code_compta'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(120); 
        $pdf->MultiCell(30,8,$ordinateur['designation'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(150); 
        $pdf->MultiCell(20,8,$ordinateur['marque'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(170); 
        if($ordinateur['status']==1){
            $pdf->MultiCell(30,8,'EN SEVICE',1,'C');
        }else{
            $pdf->MultiCell(30,8,'HORS SERVICE',1,'C');
        }
        $position_detail += 8;
        
    }
    mysqli_free_result($result2);
    $pdf->setFillColor(255,255,255);
    $pdf->Ln(20);
    $pdf->SetFont('Helvetica','B',11);
    $pdf->Cell(75,6,'Telephone',0,1,'L',1);
    function entete_table2($position_entete) {
        global $pdf;
        $pdf->SetDrawColor(183); // Couleur du fond RVB
        $pdf->SetFillColor(221); // Couleur des filets RVB
        $pdf->SetTextColor(0); // Couleur du texte 
        $pdf->SetY($position_entete);
        // position de colonne 1 (10mm à gauche)  
        $pdf->SetX(10);
        $pdf->Cell(40,8,'numero de serie',1,0,'C',1);  // 40 >largeur colonne, 8 >hauteur colonne
        // position de la colonne 2 (50 = 10+40)
        $pdf->SetX(50); 
        $pdf->Cell(40,8,'numero de carte',1,0,'C',1);
        // position de la colonne 3 (90 = 50+40)
        $pdf->SetX(90); 
        $pdf->Cell(40,8,'numero de telephone',1,0,'C',1);
        $pdf->SetX(130); 
        $pdf->Cell(20,8,'type',1,0,'C',1);
        $pdf->SetX(150); 
        $pdf->Cell(20,8,'marque',1,0,'C',1);
        $pdf->SetX(170); 
        $pdf->Cell(30,8,'code compta',1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
      }
      $position_entete = $position_detail+28;
// police des caractères
    $pdf->SetFont('Helvetica','',9);
    $pdf->SetTextColor(0);
// on affiche les en-têtes du tableau
    entete_table2($position_entete);
    $position_detail = $position_entete+8;
    $requete3 =('SELECT numero_serie,type,marque,ntelephone,ncarte,code_compta FROM telephone where id_salarie='.$_SESSION['salarie']['id']);
    $result3 = mysqli_query($link, $requete3);
    while ($telephone = mysqli_fetch_array($result3)){
        $pdf->SetY($position_detail);
        $pdf->SetX(10);
        $pdf->MultiCell(40,8,$telephone['numero_serie'],1,'C'); 
        $pdf->SetY($position_detail);
        $pdf->SetX(50); 
        $pdf->MultiCell(40,8,$telephone['ncarte'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(90); 
        $pdf->MultiCell(40,8,$telephone['ntelephone'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(130); 
        $pdf->MultiCell(20,8,$telephone['type'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(150); 
        $pdf->MultiCell(20,8,$telephone['marque'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(170); 
        $pdf->MultiCell(30,8,$telephone['code_compta'],1,'C');
        
        $position_detail += 8;
    }
    mysqli_free_result($result3);
    $pdf->setFillColor(255,255,255);
    $pdf->Ln(20);
    $pdf->SetFont('Helvetica','B',11);
    $pdf->Cell(75,6,'Tablette',0,1,'L',1);
    function entete_table3($position_entete) {
        global $pdf;
        $pdf->SetDrawColor(183); // Couleur du fond RVB
        $pdf->SetFillColor(221); // Couleur des filets RVB
        $pdf->SetTextColor(0); // Couleur du texte 
        $pdf->SetY($position_entete);
        // position de colonne 1 (10mm à gauche)  
        $pdf->SetX(10);
        $pdf->Cell(40,8,'numero de serie',1,0,'C',1);  // 40 >largeur colonne, 8 >hauteur colonne
       
        $pdf->SetX(50); 
        $pdf->Cell(40,8,'marque',1,0,'C',1);
       
        $pdf->SetX(90); 
        $pdf->Cell(40,8,'code_compta',1,0,'C',1);
        $pdf->SetX(130); 
        $pdf->Cell(20,8,'type',1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
      }
      $position_entete = $position_detail+28;
// police des caractères
    $pdf->SetFont('Helvetica','',9);
    $pdf->SetTextColor(0);
    entete_table3($position_entete);
    $position_detail = $position_entete+8;
    $requete4 =('SELECT numero_serie,type,marque,code_compta FROM tablette where id_salarie='.$_SESSION['salarie']['id']);
    $result4 = mysqli_query($link, $requete4);
    while ($tablette = mysqli_fetch_array($result4)){
        $pdf->SetY($position_detail);
        $pdf->SetX(10);
        $pdf->MultiCell(40,8,$tablette['numero_serie'],1,'C'); 
        $pdf->SetY($position_detail);
        $pdf->SetX(50); 
        $pdf->MultiCell(40,8,$tablette['marque'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(90); 
        $pdf->MultiCell(40,8,$tablette['code_compta'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(130); 
        $pdf->MultiCell(20,8,$tablette['type'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(150); 

        
        $position_detail += 8;
    }
    mysqli_free_result($result4);


    $pdf->setFillColor(255,255,255);
    $pdf->Ln(20);
    $pdf->SetFont('Helvetica','B',11);
    $pdf->Cell(75,6,'Imprimante',0,1,'L',1);
    function entete_table4($position_entete) {
        global $pdf;
        $pdf->SetDrawColor(183); // Couleur du fond RVB
        $pdf->SetFillColor(221); // Couleur des filets RVB
        $pdf->SetTextColor(0); // Couleur du texte
        $pdf->SetY($position_entete);
        // position de colonne 1 (10mm à gauche)  
        $pdf->SetX(10);
        $pdf->Cell(40,8,'numero de serie',1,0,'C',1);  
       
        $pdf->SetX(50); 
        $pdf->Cell(40,8,'marque',1,0,'C',1);
      
        $pdf->SetX(90); 
        $pdf->Cell(40,8,'adresse ip',1,0,'C',1);
        $pdf->SetX(130); 
        $pdf->Cell(20,8,'modele',1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
      }
      $position_entete = $position_detail+28;
// police des caractères
    $pdf->SetFont('Helvetica','',9);
    $pdf->SetTextColor(0);
    entete_table4($position_entete);
    $position_detail = $position_entete+8;
    $requete5 =('SELECT numero_serie,modele,marque,ip FROM imprimante where id_salarie='.$_SESSION['salarie']['id']);
    $result5 = mysqli_query($link, $requete5);
    while ($imprimante = mysqli_fetch_array($result5)){
        $pdf->SetY($position_detail);
        $pdf->SetX(10);
        $pdf->MultiCell(40,8,$imprimante['numero_serie'],1,'C'); 
        $pdf->SetY($position_detail);
        $pdf->SetX(50); 
        $pdf->MultiCell(40,8,$imprimante['marque'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(90); 
        $pdf->MultiCell(40,8,$imprimante['ip'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(130); 
        $pdf->MultiCell(20,8,$imprimante['modele'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(150); 

        
        $position_detail += 8;
    }
    mysqli_free_result($result5);

    $pdf->setFillColor(255,255,255);
    $pdf->Ln(20);
    $pdf->SetFont('Helvetica','B',11);
    $pdf->Cell(75,6,'Logiciel',0,1,'L',1);
    function entete_table5($position_entete) {
        global $pdf;
        $pdf->SetDrawColor(183); // Couleur du fond RVB
        $pdf->SetFillColor(221); // Couleur des filets RVB
        $pdf->SetTextColor(0); // Couleur du texte 
        $pdf->SetY($position_entete);
        // position de colonne 1 (10mm à gauche)  
        $pdf->SetX(10);
        $pdf->Cell(40,8,'nom du logiciel',1,0,'C',1);  
        $pdf->Ln(); // Retour à la ligne
      }
      $position_entete = $position_detail+28;
// police des caractères
     $pdf->SetFont('Helvetica','',9);
     $pdf->SetTextColor(0);
     entete_table5($position_entete);
    $position_detail = $position_entete+8;
    $requete6 =('SELECT logiciel.nom as nom FROM logiciel join salarie_logiciel on salarie_logiciel.id_logiciel=logiciel.id join salarie on salarie.id=salarie_logiciel.id_salarie where id_salarie=2');
    $result6 = mysqli_query($link, $requete6);
    while ($logiciel = mysqli_fetch_array($result6)){
        $pdf->SetY($position_detail);
        $pdf->SetX(10);
        $pdf->MultiCell(40,8,$logiciel['nom'],1,'C'); 
        $position_detail += 8;
     }
     mysqli_free_result($result6);


  
   // affichage à l'écran...
   $pdf->Output($_SESSION['nom'].'.pdf','I');?>
?>



