<?php

//gyártó törlése
if (isset($_GET["gyartotorol"])) {
    $torles = $pdo->query("delete from " . $elotag . "_shop_gyartok where shop_gyartoid=" . $_GET["gyartotorol"]);
    if ($torles) {
        echo "<h3 style='color:#00FF00;'>Sikeres gyártótörlés!</h3>";
        echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop&gyartok=y';
					}
					ID = window.setTimeout('atiranyit();', 1*300);
				</script>";
    }
    else {
        echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a gyártó törlése!</h3>";
        echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop&gyartok=y';
					}
					ID = window.setTimeout('atiranyit();', 1*300);
				</script>";
    }
}

//gyártó hozzáadása végrehajtása
elseif (isset($_POST["ujgyarto"])) {
    $gyartoment = $pdo->query("insert into " . $elotag . "_shop_gyartok (shop_gyartonev) values('" . $_POST["shop_gyartonev"] . "')");
    if ($gyartoment) {
        echo "<h3 style='color:#00FF00;'>Sikeres gyártó felvétel!</h3>";
        echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop&gyartok=y';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
    }
    else {
        echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a gyártó mentése!</h3>";
        echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop&gyartok=y';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
    }
}

	//gyártó hozzáadás űrlap
	elseif(isset($_GET["ujgyarto"]))
	{
		echo "<h2>ÚJ GYÁRTÓ FELVÉTELE</h2>";
		echo "<form name='ujgyarto' method='POST' action='index.php?lng=".$webaktlang."&page=shop' enctype='multipart/form-data'>
					<input type='hidden' name='ujgyarto' id='ujgyarto' value='igen'>
					<b>Új gyártó neve:</b><br /><input type='text' name='shop_gyartonev' id='shop_gyartonev' style='width:200px;' required><br /><br />
					<input type='submit' id='gyartomentes' name='gyartomentes' value=' GYÁRTÓ MENTÉSE ' class='btn btn-large btn-secondary'><br />
				</form>";
	}
        	//gyártó szerkesztése űrlap
	elseif(isset($_GET["gyartomod"]))
	{
		$gyarto=$pdo->query("select * from ".$elotag."_shop_gyartok where shop_gyartoid='".$_GET["gyartomod"]."'");
		$adat=$gyarto->fetch();
		echo "<h2>GYÁRTÓ MÓDOSÍTÁSA</h2>";
		echo "<form name='gyartomod' method='POST' action='index.php?lng=".$webaktlang."&page=shop' enctype='multipart/form-data'>
					<input type='hidden' name='gyartomod' id='gyartomod' value='".$_GET["gyartomod"]."'>
					<b>Új gyártó neve:</b><br /><input type='text' name='shop_gyartonev' id='shop_gyartonev' style='width:200px;' value='".$adat["shop_gyartonev"]."' required><br /><br />
					<input type='submit' id='gyartomentes' name='gyartomentes' value=' GYÁRTÓ FRISSÍTÉSE ' class='btn btn-large btn-secondary'><br />
				</form>";
	}
        if(isset($_GET["gyartok"])) //gyártók
		{
			$osszes=$pdo->query("select * from ".$elotag."_shop_gyartok");
			echo "<h3>GYÁRTÓK LISTA</h3>
					<a href='index.php?lng=".$webaktlang."&page=shop&ujgyarto=y' class='btn'> + új gyártó felvétele &raquo;</a>
					<br /><br />
					<table id='datatables' class='display'>
						<thead>
							<tr>
								<th>Gyártó ID</th>
								<th>Gyártó név</th>
								<th>Művelet</th>
							</tr>
							<tr>
								<th>Gyártó ID</th>
								<th>Gyártó név</th>
								<th>Művelet</th>
							</tr>
						</thead><tbody>";
			while($row = $osszes->fetch())
			{
				echo "<tr>
							<td>".$row['shop_gyartoid']."</td>
							<td>".$row['shop_gyartonev']."</td>
							<td><a href='index.php?lng=".$webaktlang."&page=shop&gyartomod=".$row["shop_gyartoid"]."' title='Gyártó módosítása'><b>MÓDOSÍT</b></a><br /><a href='index.php?lng=".$webaktlang."&page=shop&gyartotorol=".$row["shop_gyartoid"]."' title='Gyártó törlése' onclick='return confirm(\"Biztosan törlöd ezt a gyártót?\")'><b>TÖRÖL</b></a></td>
					   </tr>";
			}
			echo "</tbody>
				</table>";
		}
        
        