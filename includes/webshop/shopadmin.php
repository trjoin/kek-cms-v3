Boltom dashboard




<?php
//session_start();
//if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
//{
//?>
<!--<script>
function fokepCsere(f)
{
	if(document.termekmodform.fkepetis.checked == true)
	{
		document.termekmodform.t_fkep.disabled=false;
	}
	else
	{
		document.termekmodform.t_fkep.disabled=true;
	}
}

function tobbikepCsere(f)
{
	if(document.termekmodform.tobbkepetis.checked == true)
	{
		document.getElementById('t_kepek[]').disabled=false;
	}
	else
	{
		document.getElementById('t_kepek[]').disabled=true;
	}
}

function PDFetis(f)
{
	if(document.ujtermek.pdfis.checked == true)
	{
		document.getElementById('t_pdf').disabled=false;
	}
	else
	{
		document.getElementById('t_pdf').disabled=true;
	}
}

function PDFetismod(f)
{
	if(document.termekmodform.pdfis.checked == true)
	{
		document.getElementById('t_pdf').disabled=false;
	}
	else
	{
		document.getElementById('t_pdf').disabled=true;
	}
}
</script>-->
//<?php
///*** TÖRLÉSEK VÉGREHAJTÁSA ***/
//	//termék törlése
//	
//	

//	//ügyfél törlése
//	elseif(isset($_GET["uftorol"]))
//	{
//		$torles=$pdo->query("delete from ".$elotag."_shop_vasarlok where vkod=".$_GET["uftorol"]);
//		if($torles)
//		{
//			echo "<h3 style='color:#00FF00;'>Sikeres ügyfél törlés!</h3>";
//			echo "<script>
//					function atiranyit()
//					{
//						location.href = 'index.php?lng=hun&page=shop&ugyfelek=y';
//					}
//					ID = window.setTimeout('atiranyit();', 1*300);
//				</script>";
//		}
//		else
//		{
//			echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a gyártó törlése!</h3>";
//			echo "<script>
//					function atiranyit()
//					{
//						location.href = 'index.php?lng=hun&page=shop&ugyfelek=y';
//					}
//					ID = window.setTimeout('atiranyit();', 1*300);
//				</script>";
//		}
//	}
//	
///*** ADATFELVÉTELEK VÉGREHAJTÁSA ***/
//	//ügyfél aktiválása
//	elseif(isset($_GET["ufakt"]))
//	{
//		$aktival=$pdo->query("update ".$elotag."_shop_vasarlok set vaktiv='igen' where vkod='".$_GET["ufakt"]."'");
//		if($aktival)
//		{
//			$hovamegy=$pdo->query("select * from ".$elotag."_shop_vasarlok where vkod='".$_GET["ufakt"]."'");
//			$idemegy=$hovamegy->fetch();
//			echo "<h3 style='color:#00FF00;'>Sikeres aktiválás!</h3>";
//				$reggelonek=$idemegy["vmail"];
//				$targy2="Vásárlói regisztráció aktiválás";
//				$headers  = "MIME-Version: 1.0" . "\r\n";    
//				$headers .= "Content-type:text/html;charset=iso-8859-2" . "\r\n";   
//				$headers .= "From: <webshop@turanium.com>" . "\r\n";    
//				$ido = ("Küldve: ".date("Y.m.d. H:i:s", time())."\r\n\r\n");
//				mail ($reggelonek, $targy2, "<font face='verdana' size='2' color='#00AEEF'><b>Welcome!</b><br /><br />Thank you for registration at the www.turanium.com . Your account has been accepted and activated.<br /><br /><b>Your Login datas:</b><br />Name: ".$idemegy["vnev"]."<br />E-mail: ".$idemegy["vmail"]."<br />Phone number: ".$idemegy["vtelefon"]."<br />Address: ".$idemegy["vlakcim"]."<br /><br />" .$ido. "</font><br />",$headers);
//			echo "<script>
//					function atiranyit()
//					{
//						location.href = 'index.php?lng=hun&page=shop&ugyfelek=y';
//					}
//					ID = window.setTimeout('atiranyit();', 1*300);
//				</script>";
//		}
//		else
//		{
//			echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült az aktiválás - SQL hiba!</h3>";
//			echo "<script>
//					function atiranyit()
//					{
//						location.href = 'index.php?lng=hun&page=shop&ugyfelek=y';
//					}
//					ID = window.setTimeout('atiranyit();', 1*500);
//				</script>";
//		}
//	}
//	//termék hozzáadása végrehajtása
//	

//	
///*** ADAT SZERKESZTÉSEK VÉGREHAJTÁSAI ***/
//	elseif(isset($_POST["gyartomod"]))
//	{
//		$gyartofrissit=$pdo->query("update ".$elotag."_shop_gyartok set shop_gyartonev='".$_POST["shop_gyartonev"]."' where shop_gyartoid='".$_POST["gyartomod"]."'");
//		if($gyartofrissit)
//		{
//			echo "<h3 style='color:#00FF00;'>Sikeres gyártó frissítés!</h3>";
//			echo "<script>
//					function atiranyit()
//					{
//						location.href = 'index.php?lng=hun&page=shop&gyartok=y';
//					}
//					ID = window.setTimeout('atiranyit();', 1*100);
//				</script>";
//		}
//		else
//		{
//			echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a gyártó frissítése!</h3>";
//			echo "<script>
//					function atiranyit()
//					{
//						location.href = 'index.php?lng=hun&page=shop&gyartok=y';
//					}
//					ID = window.setTimeout('atiranyit();', 1*100);
//				</script>";
//		}
//	}
//	
//	
///*** ADATFELVÉTELEK ŰRLAPJAI ***/
//	//termék hozzáadás űrlap
//	

//	//kategória hozzáadás űrlap
//	
///*** SZERKESZTÉSEK ŰRLAPJAI ***/

//	
//	//termék szerkesztése űrlap
//	
///*** FŐ RENDSZERTÖLTŐ SCRIPT ***/
//	else
//	{
//		//jQ_Datatables script
//?>
<!--	<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/1.10.20/css/dataTables.jqueryui.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/select/1.3.1/css/select.jqueryui.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/searchpanes/1.0.1/css/searchPanes.jqueryui.min.css" rel="stylesheet" type="text/css" />
	<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/dataTables.jqueryui.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>	
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.2/js/dataTables.responsive.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.2/js/responsive.semanticui.min.js"></script>
	<style>
		input[type="text"] {
			max-width: 150px !important;
		}
	</style>
	<script type="text/javascript">
		$.extend( true, $.fn.dataTable.defaults, {
			dom: '<"top"B>r<t><"bottom"lip><"clear">',
			responsive: false,
			paging:   true,
			pageLength: 10,
			order: [[ 0, "desc" ]],
			buttons: [
				{
					extend: 'copy',
					text: 'Másolás',
					exportOptions: {
						modifier: {
							page: 'current'
						}
					}
				},
				{
					extend: 'pdfHtml5',
					text: 'PDF',
					exportOptions: {
						modifier: {
							page: 'current'
						}
					}
				},
				{
					extend: 'excel',
					text: 'Excel (XLS)',
					exportOptions: {
						modifier: {
							page: 'current'
						}
					}
				},
				{
					extend: 'csv',
					text: 'Excel (CSV)',
					exportOptions: {
						modifier: {
							page: 'current'
						}
					}
				},
				{
					extend: 'print',
					text: 'Nyomtatás',
					exportOptions: {
						modifier: {
							page: 'current'
						}
					}
				}
			],
			"language": {
				"lengthMenu": "_MENU_ sor / lap",
				"zeroRecords": "Nem találtam semmit, bocsi... :)",
				"info": "Adatok: _START_ - _END_ | Összesen: _TOTAL_",
				"infoEmpty": "Nincs megfelelő adat",
				"sProcessing": "Feldolgozás...",
				"sLoadingRecords": "Betöltés...",
				"sSearch": "Keresés:",
				"infoFiltered": "(szűrve a _MAX_ sorból összesen)",
				"oPaginate": {
					   "sFirst":    "Első",
					   "sPrevious": "Előző",
					   "sNext":     "Következő",
					   "sLast":     "Utolsó"
				   }
			}
		});
		$(document).ready(function() {
			$('#datatables thead tr:eq(1) th').each( function () {
				var title = $(this).text();
				$(this).html( '<input type="text" placeholder="Keresés '+title+'" class="column_search" />' );
			} );

			var table = $('#datatables').DataTable({
				orderCellsTop: true,
				fixedHeader: true,
			});

			$('#datatables thead').on( 'keyup', ".column_search",function () {
				table
					.column( $(this).parent().index() )
					.search( this.value )
					.draw();
			});
		});
	</script>-->
//<?php
///*** MENÜNEK MEGFELELŐ TÁBLA BETÖLTÉSE ***/
//		
//	
//		
//		else //termékek
//		{
//			
//		}
//	}
//}
//else
//{
//	echo "<br /><br /><center><b>Nem vagy bejelentkezve!</b></center><br />";
//}
?>