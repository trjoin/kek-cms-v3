<?php
	include("../connect.php");
	$csomagok=$pdo->query("select * from ".$elotag."_shop_rendelesek where ragszam='".$_REQUEST["ragszam"]."'");
	$cs=$csomagok->fetch();
	$pdf_decoded = base64_decode($cs["ragpdf"]);
	$pdffile = 'cimkek/'.$cs["ragszam"].'.pdf';
	$pdf = fopen ($pdffile,'w');
	fwrite ($pdf,$pdf_decoded);
	fclose ($pdf);
	header('Cache-Control: public'); 
	header('Content-Type: application/pdf');
	header('Content-Disposition: attachment; filename="'.$pdffile.'"');

	readfile($pdffile);
	
	unlink($pdffile);
?>