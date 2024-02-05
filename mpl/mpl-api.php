<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css" />
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
		max-width: 125px !important;
	}
</style>
<script type="text/javascript">
	$.extend( true, $.fn.dataTable.defaults, {
		dom: '<"top"B>r<t><"bottom"lip><"clear">',
		responsive: false,
		paging:   true,
		aLengthMenu: [
			[25, 50, 100, 200, -1],
			[25, 50, 100, 200, "Összes"]
		],
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
</script>
<?php
/*** MENÜNEK MEGFELELŐ TÁBLA BETÖLTÉSE ***/
			echo "<h3>MEGRENDELÉSEK LISTA</h3>";
			
			if(isset($_REQUEST["szamladel"]))
			{
				// RÉGEBBEN A SZÁMLA KÉSZITÉS UTÁN TÖRÖLTÜK A SZÁMLÁT, DE MOSTMÁR NEM FOGJUK!
				//unlink("../szamlak/".$_REQUEST["szamladel"].".pdf");
				echo "<script>
						function atiranyit()
						{
							location.href = 'index.php?lng=hun&page=shop&rendelesek=y';
						}
						ID = window.setTimeout('atiranyit();', 1*1);
					</script>";
			}
			if(isset($_REQUEST["ragszam"]))
			{
				if($_REQUEST["ragszam"]=="1")
				{
					//biztonság kedvéért kiütjük a tokent és inkább kérünk újat, ne fussunk hibára, csak mert valaki balfasz!
					unset($_SESSION["access_token"]);
					
					echo '<p><big><b>MPL csomagszám (RAG szám) igénylése</b></big></p>';
					echo '<p><small>Az alábbi megrendelések várnak csomagszámra, címkére és kiszállításra.<br><b>FONTOS! A RAG szám igénylés <u>NEM</u> rendeli meg a futárt, ahhoz logisztikai zárást kell csinálni a futár érkezése előtt!</b></small></p>';
					echo '<hr><p><b><big>Csomagszám nélküli, még be nem küldött rendelések:</big></b></p><hr>';
					$i=1;
					$csomagok=$pdo->query("select * from ".$elotag."_shop_rendelesek where szallitasimod='Házhozszállítás' and ragszam=''");
					if($csomagok->rowCount()>0)
					{
						while($cs=$csomagok->fetch())
						{
							echo '<p>'.$i.': Azonosító: '.$cs["rendelesazon"].' | Megrendelő: '.$cs["megrendelonev"].' | Dátum: '.str_replace("-",".",$cs["datum"]).'. | Fizetendő: '.number_format($cs["fizetendo"],0,",",".").' Ft</p><br>';
							$i++;
						}
						echo '<p><a href="index.php?lng=hun&page=shop&rendelesek=y&ragszam=ok" class="btn btn-secondary">RAG szám kérése a fenti csomagokra</a> ';
					}
					else
					{
						echo '<p>Jelenleg nincs olyan csomag amit nem adtatok még fel.<br><br>';
					}
						echo '<a href="index.php?lng=hun&page=shop&rendelesek=y" class="btn btn-default">&laquo; vissza a megrendelések listához</a></p>';
				}
				else
				{
					//beküldte RAG szám kérésre
					echo '<p><big><b>MPL csomagszám (RAG szám) igénylése folyamatban...</b></big></p>';
					//alap API URL
					$mplapiurl="https://core.api.posta.hu/";
					/* DEMO 
					$mplapiurl="https://sandbox.api.posta.hu/";
					*/
					//API USER DATA
					$client_id="LB6Qz2CjRQAM28udAChwcJJDAEQxic34";
					$client_secret="HQfHC4hXdxtP6VGA";
					
					/* DEMO 
					$client_id="CnRqYlCK6v2FIYubTrYptIBoTqKJwL5H";
					$client_secret="WutcbzRFpqBLYXWn";
					*/
					
					//login és token kérés elsőnek
					if(!isset($_SESSION["access_token"]) OR $_SESSION["access_token"]=="")
					{
						//API URL
						$tokenurl=$mplapiurl."/oauth2/token";
						
						//32digit GENERATOR
						$requestid1=generateRandomId(8); //PL.: 827f3343-2cfd-4e46-a646-065a0a7268c4
						$requestid2=generateRandomId(4);
						$requestid3=generateRandomId(4);
						$requestid4=generateRandomId(4);
						$requestid5=generateRandomId(12);
						$fullrequestid=$requestid1."-".$requestid2."-".$requestid3."-".$requestid4."-".$requestid5;

						//base64 encoded login data, az API doksi szerint
						$logindata=base64_encode($client_id.":".$client_secret);

						//HEADER
						$headers[] = 'Authorization: Basic '.$logindata;
						$headers[] = 'X-Accounting-Code: 0020192305';
						$headers[] = 'X-Request-ID: '.$fullrequestid;
						$headers[] = 'Content-Type: application/x-www-form-urlencoded';
						//CURL START
						$tokench = curl_init($tokenurl);
						curl_setopt($tokench, CURLOPT_URL, $tokenurl);
						curl_setopt($tokench, CURLOPT_CUSTOMREQUEST, 'POST');
						curl_setopt($tokench, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($tokench, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
						curl_setopt($tokench, CURLOPT_RETURNTRANSFER, true);

						$result = curl_exec($tokench);
						$tokencode = curl_getinfo($tokench, CURLINFO_HTTP_CODE);
						curl_close($tokench);

						$result = json_decode($result, true);


						// Token lekérés vizsgálat
						if($tokencode == 200)
						{
							// Auth token
							$_SESSION["access_token"] = $result['access_token'];
							echo '<p><i>ACCESS TOKEN</i> kérése sikeres. ('.$_SESSION["access_token"].'), továbbítás...</p>';
							echo "<script>
								function atiranyit()
								{
									location.href = 'index.php?lng=hun&page=shop&rendelesek=y&ragszam=ok';
								}
								ID = window.setTimeout('atiranyit();', 1*1000);
							</script>";
						}
						else
						{
							echo '<p>Valami szar van a palacsintában TOKEN kéréskor...! Lásd alább:</p>';
							print_r($result);
						}
					}
					else
					{
						//RAG szám kérés, a már meglévő access_token segitségével.
						$ragurl=$mplapiurl."v2/mplapi/shipments";
						
						//művelet inditása
						$webshopid=0;
						$csomagok=$pdo->query("select * from ".$elotag."_shop_rendelesek where szallitasimod='Házhozszállítás' and ragszam=''");
						while($cs=$csomagok->fetch())
						{
							//32digit GENERATOR
							$requestid1=generateRandomId(8); //PL.: 827f3343-2cfd-4e46-a646-065a0a7268c4
							$requestid2=generateRandomId(4);
							$requestid3=generateRandomId(4);
							$requestid4=generateRandomId(4);
							$requestid5=generateRandomId(12);
							$fullrequestid=$requestid1."-".$requestid2."-".$requestid3."-".$requestid4."-".$requestid5;
							
							$dataj = array (array (
								'developer' => 'szepseg-forras.hu', 
								'webshopId' => ''.$webshopid.'',
								'labelType' => 'A5',
								'labelFormat' => 'PDF',
								'sender' => array(
									'agreement' => '55314928',
									'accountNo' => '117450042452254200000000',
									'contact' => array(
										'name' => 'Szépség-Forrás Elite Szalon',
										'email' => 'info@szepseg-forras.hu',
										'phone' => '+36707842739',
										'organization' => 'Pekprod Kft.'
									),
									'address' => array(
										'postCode' => '5000',
										'city' => 'Szolnok',
										'address' => 'Szántó körút 16/B',
										'remark' => 'FIFO feletti helység az emeleten'
									),
								),
								'paymentMode' => 'UV_AT',
								'recipient' => array(
									'contact' => array(
										'name' => ''.$cs["megrendelonev"].'',
										'email' => ''.str_replace(" ", "", $cs["megrendeloemail"]).'',
										'phone' => ''.$cs["megrendelotelszam"].''
									),
									'address' => array(
										'postCode' => ''.$cs["megrendeloszlairszam"].'',
										'city' => ''.$cs["megrendeloszlavaros"].'',
										'address' => ''.$cs["megrendeloszlacim"].'',
										'remark' => ''
									),
								),
								'item' => array(
									array(
										'customData1' => 'Megrendelésazonosító: '.$cs["rendelesazon"].'',
										'weight' => array(
											'value' => 250,
											'unit' => 'G'
										),
										'services' => array(
											'basic' => 'A_175_UZL',
											'extra' => [''.($cs["fizetesimod"]=='utánvét' ? 'K_UVT, K_ENY' : 'K_ENY').''],
											'cod' => ''.($cs["fizetesimod"]=='utánvét' ? $cs["fizetendo"] : '').'',
											'value' => $cs["fizetendo"],
											'deliveryMode' => 'HA',
										),
									),
								),
								'packageRetention' => 5
							));
							
							$data_json = json_encode($dataj);

							//HEADER
							$headers[] = 'Content-Type: application/json; charset=utf-8 ';
							$headers[] = 'Authorization: Bearer '.$_SESSION["access_token"];
							$headers[] = 'X-Accounting-Code: 0020192305';
							$headers[] = 'X-Request-ID: '.$fullrequestid;
							//CURL START
							$ragch = curl_init($ragurl);
							curl_setopt($ragch, CURLOPT_URL, $ragurl);
							curl_setopt($ragch, CURLOPT_CUSTOMREQUEST, 'POST');
							curl_setopt($ragch, CURLOPT_HTTPHEADER, $headers);
							curl_setopt($ragch, CURLOPT_POSTFIELDS, $data_json);
							curl_setopt($ragch, CURLOPT_RETURNTRANSFER, true);

							$result = curl_exec($ragch);
							$ragcode = curl_getinfo($ragch, CURLINFO_HTTP_CODE);
							curl_close($ragch);
							
							$result = json_decode($result, true);
							
							// RAG szám lekérés vizsgálat
							if($ragcode == 200)
							{
								// RAG szám lett
								$ragszamlett = $result[0]['trackingNumber']; //SQL: ragszam
								$pdflabel = $result[0]['label']; //SQL: ragpdf
								$ragsave=$pdo->query("update ".$elotag."_shop_rendelesek set ragszam='".$ragszamlett."',ragpdf='".$pdflabel."' where m_id='".$cs["m_id"]."'");
								/*** RÉGI PDF LABEL GENERÁLÓ
								$pdf_decoded = base64_decode ($pdflabel);
								$pdf = fopen ('cimkek/'.$ragszamlett.'.pdf','w');
								fwrite ($pdf,$pdf_decoded);
								fclose ($pdf);
								***/
								//echo '<p>A(z) '.$ragszamlett.' azonosítóval a csomagszám kérése sikeres, címke itt elérhető: <a href="cimkek/'.$ragszamlett.'.pdf" target="_blank">[ LABEL ] &raquo;</a></p><br>';
								echo '<p>A '.$ragszamlett.' azonosítóval a csomagszám kérése sikeres, címkét a megrendelések kezelésénél az aktuális megrendelési sor végén találhatod.</p><br>';
							}
							else
							{
								echo '<p>Valami szar van a palacsintában a '.$webshopid.'. számú RAG szám kéréssel...! Lásd alább:</p>';
								print_r($result);
								echo '<br>';
							}
							
							$webshopid++;
							//üritjük a tárolókat a tutiság kedvéért!
							$dataj="";
							$data_json="";
						}
						
						echo '<br><br><p><a href="index.php?lng=hun&page=shop&rendelesek=y" class="btn btn-default">&laquo; vissza a megrendelések listához</a></p>';
					}
				}
			}
			elseif(isset($_REQUEST["logzar"]))
			{
				echo '<p><b>LOGISZTIKAI ZÁRÁS</b></p>';
				if(!isset($_POST["csomag"]))
				{
					echo '<p>Kérlek válaszd ki a már kész és összerakott csomagokat az alábbi listából (össze vannak csomagolva, rajtuk van a csomagcímke, le vannak zárva, jöhet érte az MPL futár.</p><br>';
					$csomagok=$pdo->query("select * from ".$elotag."_shop_rendelesek where ragszam!='' and logzar=''");
					if($csomagok->rowCount()>0)
					{
						echo '<form method="POST" name="logzar">';
						while($cs=$csomagok->fetch())
						{
							echo '<input type="checkbox" name="csomag[]" value="'.$cs["m_id"].'"> <b>Rendelés szám:</b> '.$cs["rendelesazon"].' | <b>Név:</b> '.$cs["megrendelonev"].' | <b>Megrendelés dátuma:</b> '.str_replace("-",".",$cs["datum"]).'.<br>';
						}
						echo '<br><input type="submit" value="INDÍTÁS" class="btn btn-secondary">
							</form>';
					}
					else
					{
						echo '<p><b>Nincs lezárandó csomagod már. Ügyesen dolgoztál. :)</b></p>';
						echo '<p><a href="index.php?lng=hun&page=shop&rendelesek=y" class="btn btn-default">&laquo; vissza a megrendelések listához</a></p>';
					}
				}
				else
				{
					$_SESSION["access_token"]="";
					
					$mplapiurl="https://core.api.posta.hu/";
					$tokenurl=$mplapiurl."/oauth2/token";
					$logzurl=$mplapiurl."/v2/mplapi/shipments/close";
					
					$client_id="LB6Qz2CjRQAM28udAChwcJJDAEQxic34";
					$client_secret="HQfHC4hXdxtP6VGA";
					
					//32digit GENERATOR
					$requestid1=generateRandomId(8); //PL.: 827f3343-2cfd-4e46-a646-065a0a7268c4
					$requestid2=generateRandomId(4);
					$requestid3=generateRandomId(4);
					$requestid4=generateRandomId(4);
					$requestid5=generateRandomId(12);
					$fullrequestid=$requestid1."-".$requestid2."-".$requestid3."-".$requestid4."-".$requestid5;

					//base64 encoded login data, az API doksi szerint
					$logindata=base64_encode($client_id.":".$client_secret);

					//HEADER
					$headers[] = 'Authorization: Basic '.$logindata;
					$headers[] = 'X-Accounting-Code: 0020192305';
					$headers[] = 'X-Request-ID: '.$fullrequestid;
					$headers[] = 'Content-Type: application/x-www-form-urlencoded';
					//CURL START
					$tokench = curl_init($tokenurl);
					curl_setopt($tokench, CURLOPT_URL, $tokenurl);
					curl_setopt($tokench, CURLOPT_CUSTOMREQUEST, 'POST');
					curl_setopt($tokench, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($tokench, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
					curl_setopt($tokench, CURLOPT_RETURNTRANSFER, true);

					$result = curl_exec($tokench);
					$tokencode = curl_getinfo($tokench, CURLINFO_HTTP_CODE);
					curl_close($tokench);

					$result = json_decode($result, true);


					// Token lekérés vizsgálat
					if($tokencode == 200)
					{
						// Auth token
						$_SESSION["access_token"] = $result['access_token'];

						$ma=date("Y-m-d");
					
						//tracking numbers slice
						$trnumbers=$_POST["csomag"];
						$numberek="";
						foreach($trnumbers as $k => $v)
						{
							if($v!="")
							{
								$raglekeres=$pdo->query("select * from ".$elotag."_shop_rendelesek where m_id='".$v."'");
								$rl=$raglekeres->fetch();
								$numberek=$rl["ragszam"];
								
								/*** zárás CURL kérés ***/
								//32digit GENERATOR
								$requestid1=generateRandomId(8); //PL.: 827f3343-2cfd-4e46-a646-065a0a7268c4
								$requestid2=generateRandomId(4);
								$requestid3=generateRandomId(4);
								$requestid4=generateRandomId(4);
								$requestid5=generateRandomId(12);
								$fullrequestidlog=$requestid1."-".$requestid2."-".$requestid3."-".$requestid4."-".$requestid5;
								
								$datajz = array (
									'trackingNumbers' => array(
										''.$numberek.''
									),
									'checkList' => false,
									'checkListWithPrice' => false,
									'summaryList' => false,
									'singleFile' => false
								);
								
								$data_jsonz = json_encode($datajz);

								$headersz[] = 'Content-Type: application/json; charset=utf-8';
								$headersz[] = 'Authorization: Bearer '.$_SESSION["access_token"];
								$headersz[] = 'X-Accounting-Code: 0020192305';
								$headersz[] = 'X-Request-ID: '.$fullrequestidlog;
								//CURL START
								$logch = curl_init($logzurl);
								curl_setopt($logch, CURLOPT_URL, $logzurl);
								curl_setopt($logch, CURLOPT_CUSTOMREQUEST, 'POST');
								curl_setopt($logch, CURLOPT_HTTPHEADER, $headersz);
								curl_setopt($logch, CURLOPT_POSTFIELDS, $data_jsonz);
								curl_setopt($logch, CURLOPT_RETURNTRANSFER, true);

								$resultz = curl_exec($logch);
								$logcode = curl_getinfo($logch, CURLINFO_HTTP_CODE);
								curl_close($logch);
								
								$resultz = json_decode($resultz, true);
								
								// LOGZÁR lekérés vizsgálat
								if($logcode == 200)
								{
									foreach($trnumbers as $k => $v)
									{
										if($v!="")
										{
											$logzarsave=$pdo->query("update ".$elotag."_shop_rendelesek set logzar='1' where m_id='".$v."'");
										}
									}
									//lezárt logisztikai zárás ellenőrzése: https://posta.hu/ugyfelszolgalat/nyomkovetes?ids=TRACKING_NUMBER
									echo '<p>Sikeres logisztikai zárás a '.$numberek.' RAG számú csomagnál.</p><br>';
								}
								else
								{
									echo '<p>Valami szar van a palacsintában a logisztikai zárás beküldésekor, a '.$numberek.' RAG számú csomagnál...! Lásd alább:</p>';
									print_r($resultz);
									echo '<br>';
								}
							}
						}
						echo '<br><p><a href="index.php?lng=hun&page=shop&rendelesek=y" class="btn btn-default">&laquo; vissza a megrendelések listához</a></p>';
					}
					else
					{
						echo '<p>Valami szar van a palacsintában TOKEN kéréskor...! Lásd alább:</p>';
						print_r($result);
					}
				}
			}
			else
			{
				$osszes=$pdo->query("select * from ".$elotag."_shop_rendelesek");
				echo "<a href='index.php?lng=hun&page=shop&rendelesek=y&ragszam=1' class='btn'>&raquo; RAG szám kérés &laquo;</a> 
						<a href='index.php?lng=hun&page=shop&rendelesek=y&logzar=1' class='btn'><i class='fa fa-lock'></i> Logisztikai zárás</a> 
						<br /><br />
						<table id='datatables' class='display'>
							<thead>
								<tr>
									<th>Megrendelés dátuma</th>
									<th>Azonosító<br>Megrendelés száma</th>
									<th>Megrendelő<br>adatok</th>
									<th>Fizetés és Szállítás<br>Fizetendő</th>
									<th>Művelet</th>
								</tr>
								<tr>
									<th>Megrendelés dátuma</th>
									<th>Azonosító<br>Megrendelés száma</th>
									<th>Megrendelő<br>adatok</th>
									<th>Fizetés és Szállítás<br>Fizetendő</th>
									<th>Művelet</th>
								</tr>
							</thead><tbody>";
				while($row = $osszes->fetch())
				{
					echo "<tr>
								<td>".str_replace("-",".",$row['datum']).".</td>
								<td>".$row['paymentazon']."<br>".$row['rendelesazon']."</td>
								<td>".$row['megrendelonev']."<br>".$row['megrendeloemail']."</td>
								<td>".$row['szallitasimod'].", ".$row['fizetesimod']."<br>".$row['fizetendo']." Ft</td>
								<td>
									<a href='index.php?lng=".$webaktlang."&page=shop&rendelles=".$row["m_id"]."' title='Megrendelés megtekintése' class='btn btn-sm btn-default'><i class='fa fa-eye'></i></a> ";
									//fizetve/számlázás ikon
									if($row['fizetesimod']!="barion" AND $row['fizetve']!="1")
									{
										echo "<a href='index.php?lng=".$webaktlang."&page=shop&szamlaz=".$row["m_id"]."' title='Számlázás' class='btn btn-sm btn-default'><i class='fa fa-money'></i></a> ";
									}
									else
									{
										echo "<a href='#' disabled class='btn btn-sm btn-default'><i class='fa fa-thumbs-up'></i></a>";
									}
									//RAG szám cimke nyomtatás ikon
									if($row['ragszam']!="")
									{
										echo "<a href='cimke.php?ragszam=".$row["ragszam"]."' title='LABEL cimke megtekintése' target='_blank' class='btn btn-sm btn-default'><i class='fa fa-file-pdf-o'></i></a> ";
									}
								if($_SESSION["jogkor"]==0)
								{
									echo "<a href='index.php?lng=".$webaktlang."&page=shop&rendtorol=".$row["m_id"]."' title='Adat törlése' onclick='return confirm(\"Biztosan törlöd ezt a megrendelést?\")' class='btn btn-sm btn-default'><i class='fa fa-trash'></i></a>";
								}
							echo "</td>
						   </tr>";
				}
				echo "</tbody>
					</table>";
			}
?>