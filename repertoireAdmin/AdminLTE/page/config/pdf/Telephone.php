<?php 
session_start();
$link = mysqli_connect('localhost','root','','distribution'); // connection a la base de donnée



$requete =('SELECT telephone.marque as marque,telephone.ncarte as ncarte,telephone.ntelephone as ntelephone,telephone.code_compta as code_compta,telephone.numero_serie as numero_serie,telephone.type as type,salarie.nom as nom, salarie.prenom as prenom FROM telephone left join salarie on salarie.id=telephone.id_salarie order by nom ');
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
  $pdf->Cell(75,6,'Liste telephone',0,0,'L',1);    
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
    $pdf->Cell(30,8,'Salarie ',1,0,'C',1);
    $pdf->SetX(35); 
    $pdf->Cell(30,8,'Numero de serie',1,0,'C',1);  // 40 >largeur colonne, 8 >hauteur colonne
    // position de la colonne 2 (50 = 10+40)
    $pdf->SetX(65); 
    $pdf->Cell(30,8,'Numero de carte',1,0,'C',1);
    // position de la colonne 3 (90 = 50+40)
    $pdf->SetX(95); 
    $pdf->Cell(40,8,'Numero de telephone',1,0,'C',1);
    $pdf->SetX(135); 
    $pdf->Cell(20,8,'Type',1,0,'C',1);
    $pdf->SetX(155); 
    $pdf->Cell(20,8,'Marque',1,0,'C',1);
    $pdf->SetX(175); 
    $pdf->Cell(30,8,'Code compta',1,0,'C',1);
    $pdf->Ln(); // Retour à la ligne
  }
  $position_entete = 50;
// police des caractères
    $pdf->SetFont('Helvetica','',9);
    $pdf->SetTextColor(0);
// on affiche les en-têtes du tableau
    entete_table($position_entete);
    $position_detail = 58;
  while ($telephone = mysqli_fetch_array($result)){
    $pdf->SetY($position_detail);
    $pdf->SetX(5);
    $pdf->MultiCell(30,8,$telephone['nom'].' '.$telephone['prenom'],1,'C'); // 40 -> x, 8 -> y,1 = border ,C = centré # multicell -> Autant de cellules que nécessaire sont générées, les unes en dessous des autres.
    $pdf->SetY($position_detail);
    $pdf->SetX(35);
    $pdf->MultiCell(30,8,$telephone['numero_serie'],1,'C'); 
    $pdf->SetY($position_detail);
    $pdf->SetX(65); 
    $pdf->MultiCell(30,8,$telephone['ncarte'],1,'C');
    $pdf->SetY($position_detail);
    $pdf->SetX(95); 
    $pdf->MultiCell(40,8,$telephone['ntelephone'],1,'C');
    $pdf->SetY($position_detail);
    $pdf->SetX(135); 
    $pdf->MultiCell(20,8,$telephone['type'],1,'C');
    $pdf->SetY($position_detail);
    $pdf->SetX(155); 
    $pdf->MultiCell(20,8,$telephone['marque'],1,'C');
    $pdf->SetY($position_detail);
    $pdf->SetX(175); 
    $pdf->MultiCell(30,8,$telephone['code_compta'],1,'C');
    
    $position_detail += 8;
}
mysqli_free_result($result);
    $pdf->Output('Telephone.pdf','I');?>