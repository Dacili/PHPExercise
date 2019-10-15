
<style>
/*da bi radilo svugdje moram imati border i na td*/
tr, td {
	border:1px solid black; border-collapse: collapse;
	text-align: center;

}

td{
	width:100px;
}

.kolonadugme{
	border:none;

}
</style>

<?php
$imee=$prezimee=$brojj=$redniBrojKogaEditujem="";
$akcijaa="Dodavanje";

/*
ovaj dio skripte ispod, mi je bio napisan poslije html-a, i imala sam problem da iako u skripti poslije tacno postavim vrijednosti varijabli $imee, $prezimee i $brojj, ne bi se nista desilo na formi, jer je ona vec ispisana

zato je VEOOMAAA BITNOOO GDJE STE POSTAVILI SKRIPTU A GDJE HTML KODD!!!
ako u skripti mijenjas neke stvari koje ce se printati na html formi, php ide prijeeee html-a

*/

	//dodavanje broja u imenik
if(isset($_REQUEST["submitBtn"]) && $_REQUEST["akcija"]=="Dodavanje" && isset($_REQUEST["ime"]) && isset($_REQUEST["prezime"]) && isset($_REQUEST["broj"])){
	//fopen vraca sadrzaj fajla u string, za razliku od file() koji vraca niz ciji su elementi redovi fajla
	$fajl=fopen("brojevi.csv", "a");
		//moramo staviti "a" mode, za append, ako hocu dole da pozovem FILE_APPEND u suprotnom mi ne radiiii
		$dodajNoviRed=$_REQUEST["ime"].",". $_REQUEST["prezime"].",". $_REQUEST["broj"].PHP_EOL;
		//php end of line,  i omogucava cross platform da podrzi, radi i na linuxu i na windowsu..\n ne radi fino,
		//LOCK EX da ne bi vise ljudi istovremeno mijenjali fajl
		file_put_contents("brojevi.csv", $dodajNoviRed, FILE_APPEND | LOCK_EX); //NADODAJ NA POSTOJECE (NA KRAJ) -> APPEND
		echo "<p style='color:green;'>Uspješno dodana osoba!</p>"."<br>";
		fclose($fajl);
	}

	//brisanje
	else{
		$redovi=file("brojevi.csv");
		$jeLObrisaoIsta=false;
		$noviSadrzajDatoteke="";
		for($j=0; $j<count($redovi); $j++){
		//moramo staviti gore ona dugmad u <form> tag, jer inace nece submit raditi
			//provjerava da li je kliknut ijedan obrisi button
			if(isset($_REQUEST["obrisi".$j])){
	 	//ne dodaj ga, ne radi nista
				$jeLObrisaoIsta=true;
			}
			else $noviSadrzajDatoteke.=$redovi[$j];
		}
		file_put_contents("brojevi.csv", $noviSadrzajDatoteke);
		if($jeLObrisaoIsta)echo "<p style='color:green;'>Uspješno obrisana osoba!"."<br>";
	}

	//editovanje
	//prvo popuni formu sa podacima
	
	$redovi=file("brojevi.csv");
	for($j=0; $j<count($redovi); $j++){
		//moramo staviti gore ona dugmad u <form> tag, jer inace nece submit raditi
		//provjerava da li je kliknut ijedan edit button
		if(isset($_REQUEST["edituj".$j])){
	 	//ne dodaj ga, ne radi nista
			$razbi=explode(",",$redovi[$j]);
			$imee=$razbi[0];
			$prezimee=$razbi[1];
			$brojj=$razbi[2];
			$redniBrojKogaEditujem=$j;
			$akcijaa="Editovanje";
	 	//print_r( $razbi);
	 	//echo "<br>"."Editujem redni broj:"."\n".$redniBrojKogaEditujem;
			break;
		}

	}
//, i onda nakon klika na formi, spasi izmjene
	if(isset($_REQUEST['submitBtn']) && $_REQUEST['akcija']=="Editovanje" && isset($_REQUEST['kogaEditujem'])){
		
		
		$rbroj=$_REQUEST['kogaEditujem'];
		$redovi=file("brojevi.csv");
		$noviSadrzajDatoteke="";
		for($j=0; $j<count($redovi); $j++){

			if($j==$rbroj){
				$editovaniRed=$_REQUEST['ime'].",".$_REQUEST['prezime'].",".$_REQUEST['broj'].PHP_EOL;
				$noviSadrzajDatoteke.=$editovaniRed;
			}	

			else $noviSadrzajDatoteke.=$redovi[$j];
		}

		file_put_contents("brojevi.csv", $noviSadrzajDatoteke);
	//$akcijaa="Dodavanje";
		echo "Uspješno editovana osoba!"."<br>";
//$imee=$prezimee=$brojj=$redniBrojKogaEditujem="";



	}

	
	

	



	
	?>





	<!DOCTYPE>
	<html>
	<head>
		<title>Fajlovi </title>
	</head>
	<body>
		<h1>Dobro došli u imenik!</h1>
		
		<h2>Stanje u imeniku: </h2>
		<table >
			<tr >
				<td >Ime</td><td>Prezime</td><td>Broj</td>
			</tr>
			<?php
			//ispis sadrzaja tabele
			$redovi=file("brojevi.csv");
			//echo "br redova u fajlu ".count($redovi);
			for($i=0; $i<count($redovi); $i++){
				$dijelovi=explode(",",$redovi[$i]);
				//nije radilo brisanje jer mi ovo nije bilo u <form> tagu, submit ne radi tad
				echo "<form action='VjezbamFajlove.php' method='POST'><tr class='kolonadugme'><td>".$dijelovi[0]."</td><td>".$dijelovi[1]."</td><td>".$dijelovi[2]."</td><td class='kolonadugme'><input type='submit' value='Obrisi' name='obrisi".$i."'></td><td class='kolonadugme'><input type='submit' value='Edituj'name='edituj".$i."'></td></tr></form>";

			}

			?>


		</table>
		<br><br><br><br>
		<h3><?php echo $akcijaa." osobe"; ?> </h3>
		<form action="VjezbamFajlove.php" method="POST">
			<input type="hidden" name="kogaEditujem" value="<?=$redniBrojKogaEditujem?>">
			<input type="hidden" name="akcija" value="<?=$akcijaa?>">
			Ime: <input type="text" name="ime" value="<?=$imee?>">
			<br>
			Prezime: <input type="text" name="prezime" value="<?=$prezimee?>" >
			<br>
			Broj: <input type="text" name="broj" value="<?=$brojj?>" >
			<br><br>
			<input type="submit" name="submitBtn" value="OK">
		</form>
	</body>
	</html>

	<script>
//blokira mi resubmission kad se refresha stranica, moze se i ajax al neka sad za sad ovo
//tj. bio je problem, kad se uradi refresh, opet mi doda isti red u fajl koji je zadnji put bio unesen u formu
if ( window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
</script>

