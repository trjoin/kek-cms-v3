<?php
	/*** LAPOZÓ ***/
	function foot_linkek($link, $tomb_szama, $oldalankenti_db, $kezdes, $act_oldal)
	{
		$kimenet = "";
		$szam = 0;

		if( ($kezdes + $oldalankenti_db) > $tomb_szama)
		{
			$max = $tomb_szama;
		}
		else
		{
			$max = ($kezdes + $oldalankenti_db)-1;
		}

		$kimenet .= '<nav>
						<ul class="pagination">';
				if ($tomb_szama > $oldalankenti_db)
				{
					$k = $tomb_szama;
					 for ($k; $k > 0; $k=$k-$oldalankenti_db)
					 {
						$szam=$szam+1;
						if($szam == $act_oldal)
						{
							$kimenet .='<li><a href="#" class="page-link" disabled>'.$szam.'</a></li>';
						}
						else
						{
							$kimenet .= '<li><a href="'.$link.'o/'.$szam.'" class="page-link">'.$szam.' </a></li>';
						}
					}
				}
				$kimenet .= '</ul>
							</nav>';

		return $kimenet;
	}
	/*** NAPOK TÖMB ***/
	$napok=array("HIBA","Hétfő","Kedd","Szerda","Csütörtök","Péntek","Szombat","Vasárnap");
	/*** GENERATOR ***/
	function generateRandomString($length)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	/*** CSERE TÖMBÖK ***/
	$mirol=array("í","é","á","ű","ú","ő","ó","ü","ö","Í","É","Á","Ű","Ú","Ő","Ó","Ü","Ö","§","\"","_","+",":","%",",","?","=","*","(",")","<",">","[","]","{","}","&","#","@","<",">","$","'","!","/",";"," ");
	$mire=array("i","e","a","u","u","o","o","u","o","i","e","a","u","u","o","o","u","o","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-");
	/*** KARAKTERCSERÉLŐ ***/
	function to_linknew($str) {
		$mit = array (".", " ", ",", "?", "!", ";", ":", "+", "&", "%", "(", ")", "\\", "/", "*", "á", "é", "ű", "ú", "ő", "ó", "ü", "ö", "í", "Á", "É", "Ű", "Ú", "Ő", "Ó", "Ü", "Ö", "Í");
		$mire = array ("", "-",	"", "", "", "", "", "", "", "", "-", "", "", "", "", "a", "e", "u", "u", "o", "o", "u", "o", "i", "a", "e", "u", "u", "o", "o", "u", "o", "i");
		$str = str_replace($mit, $mire, strtolower($str));
		$str = str_replace('---', '-', $str);
		$str = str_replace('--', '-', $str);
		return $str;
	}
	/*** RÓMAI SZÁM GENERÁTOR ***/
	function romai($szam){
		$r="";
		if($szam==1): $r="I"; elseif($szam==2): $r="II"; elseif($szam==3): $r="III"; elseif($szam==4): $r="IV"; elseif($szam==5): $r="V"; elseif($szam==6): $r="VI"; elseif($szam==7): $r="VII"; elseif($szam==8): $r="VIII"; elseif($szam==9): $r="IX"; elseif($szam==10): $r="X"; elseif($szam==11): $r="XI";	elseif($szam==12): $r="XII"; elseif($szam==13): $r="XIII"; elseif($szam==14): $r="XIV"; elseif($szam==15): $r="XV"; elseif($szam==16): $r="XVI"; 	elseif($szam==17): $r="XVII"; elseif($szam==18): $r="XVIII"; elseif($szam==19): $r="XIX"; elseif($szam==20): $r="XX"; elseif($szam==21): $r="XXI"; elseif($szam==22): $r="XXII"; elseif($szam==23): $r="XXIII"; endif;
		return $r;
	}
	/*** TOKEN GENERATOR ***/
	function tokengen()
	{
		mt_srand((double)microtime()*10000);
		$length=mt_rand(10,30);
		for($i=0;$i<$length;$i++) 
		{
			$x = mt_rand(1,3);
			$str .= (($x == 1) ? chr(mt_rand(48,57)) : (($x == 2) ? chr(mt_rand(65,90)) : chr(mt_rand(97,122))));
		}
		return md5($str);
	}