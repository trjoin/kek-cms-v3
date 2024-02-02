<?php

if (isset($_GET["rendtorol"])) {
    $torles = $pdo->query("delete from " . $elotag . "_shop_rendelesek where m_id=" . $_GET["rendtorol"]);
    if ($torles) {
        echo "<h3 style='color:#00FF00;'>Sikeres adattörlés!</h3>";
        echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop&rendelesek=y';
					}
					ID = window.setTimeout('atiranyit();', 1*300);
				</script>";
    }
    else {
        echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a megrendelési adat törlése!</h3>";
        echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop&rendelesek=y';
					}
					ID = window.setTimeout('atiranyit();', 1*300);
				</script>";
    }
}
if (isset($_GET["rendelesek"])) { //megrendelések
    $osszes = $pdo->query("select * from " . $elotag . "_shop_rendelesek");
    echo "<h3>MEGRENDELÉSEK LISTA</h3>
					<br />
					<table id='datatables' class='display'>
						<thead>
							<tr>
								<th>Megrendelés ID</th>
								<th>Megrendelés dátuma</th>
								<th>Művelet</th>
							</tr>
							<tr>
								<th>Megrendelés ID</th>
								<th>Megrendelés dátuma</th>
								<th>Művelet</th>
							</tr>
						</thead><tbody>";
    while ($row = $osszes->fetch()) {
        echo "<tr>
							<td>" . $row['m_id'] . "</td>
							<td>" . $row['datum'] . "</td>
							<td><a href='index.php?lng=" . $webaktlang . "&page=shop&rendelles=" . $row["m_id"] . "' title='Megrendelés megtekintése'><b>MEGTEKINT</b></a><br /><a href='index.php?lng=" . $webaktlang . "&page=shop&rendtorol=" . $row["m_id"] . "' title='Adat törlése' onclick='return confirm(\"Biztosan törlöd ezt a megrendelést?\")'><b>TÖRÖL</b></a></td>
					   </tr>";
    }
    echo "</tbody>
				</table>";
}

if (isset($_GET["rendelles"])) { //megrendelések
    $osszes = $pdo->query("select * from " . $elotag . "_shop_rendelesek where m_id='" . $_GET["rendelles"] . "'");
    $eak = $osszes->fetch();
    echo "<h3>MEGRENDELÉS ADATAI</h3>
				<u><b>Megrendelő adatai:</b></u><br />" . $eak["megrendelo"] . "<br /><br />
				<u><b>Tétel adatok:</b></u><br />" . $eak["tetelek"] . "<br /><br />
				<u><b>Egyéb adatok:</b></u><br /><i>szállítás:</i> " . $eak["szallitas"] . "<br /><i>számlázás:</i> " . $eak["fizetes"] . "<br />dátum: " . $eak["datum"] . "
			";
}