<?php 
session_start();
$link = mysqli_connect('localhost','root','','distribution'); // connection a la base de donnée



$requete =('SELECT tablette.marque as marque,tablette.code_compta as code_compta,tablette.numero_serie as numero_serie,tablette.type as type,salarie.nom as nom, salarie.prenom as prenom FROM tablette left join salarie on salarie.id=tablette.id_salarie order by nom ');
$result = mysqli_query($link, $requete);

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
  $pdf->Image('../../../image/LogoECCS.png',10,6,30);
  $pdf->Cell(75,6,'Liste tablette',0,0,'L',1);    
  $pdf->Ln(10); // saut de ligne 35mm
  $pdf->SetFont('Helvetica','B',11);
  

  
$pdf->setFillColor(255,255,255);
$pdf->Ln(20);
$pdf->SetFont('Helvetica','B',11);
function entete_table($position_entete) {
    global $pdf;
    $pdf->SetDrawColor(183); // Couleur du fond RVB
    $pdf->SetFillColor(221); // Couleur des filets RVB
    $pdf->SetTextColor(0); // Couleur du texte 
    $pdf->SetY($position_entete);
    // position de colonne 1 (10mm à gauche)  
    $pdf->SetX(5);
    $pdf->Cell(30,8,'Salarie',1,0,'C',1); 
    $pdf->SetX(35);
    $pdf->Cell(30,8,'Numero de serie',1,0,'C',1);  // 40 >largeur colonne, 8 >hauteur colonne
   
    $pdf->SetX(65); 
    $pdf->Cell(40,8,'Marque',1,0,'C',1);
   
    $pdf->SetX(105); 
    $pdf->Cell(40,8,'Code compta',1,0,'C',1);
    $pdf->SetX(145); 
    $pdf->Cell(20,8,'Type',1,0,'C',1);
    $pdf->Ln(); // Retour à la ligne
  }
  $position_entete = 50;
// police des caractères
    $pdf->SetFont('Helvetica','',9);
    $pdf->SetTextColor(0);
// on affiche les en-têtes du tableau
    entete_table($position_entete);
    $position_detail = 58;
    while ($tablette = mysqli_fetch_array($result)){
        $pdf->SetY($position_detail);
        $pdf->SetX(5);
        $pdf->MultiCell(30,8,$tablette['nom'].' '.$tablette['prenom'],1,'C'); // 40 -> x, 8 -> y, C = centré # multicell -> Autant de cellules que nécessaire sont générées, les unes en dessous des autres.
        $pdf->SetY($position_detail);
        $pdf->SetX(35);
        $pdf->MultiCell(30,8,$tablette['numero_serie'],1,'C'); 
        $pdf->SetY($position_detail);
        $pdf->SetX(65); 
        $pdf->MultiCell(40,8,$tablette['marque'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(105); 
        $pdf->MultiCell(40,8,$tablette['code_compta'],1,'C');
        $pdf->SetY($position_detail);
        $pdf->SetX(145); 
        $pdf->MultiCell(20,8,$tablette['type'],1,'C');
        $pdf->SetY($position_detail);
      

        
        $position_detail += 8;
    }
mysqli_free_result($result);
    $pdf->Output('Tablette.pdf','I');?>