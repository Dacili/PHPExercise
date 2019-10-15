<!DOCTYPE html>

<html>
<head>
	<title>Medi **PHP only** (no JavaScript)</title>
</head>




<?php 
if(isset($_GET["stanjeUnos"]))
	$unos=$_GET["stanjeUnos"];
else $unos=""; //kad se prvi put pokrene skripta, da ne bi bacalo error undefined

$dugmad=["1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "+", "-", "x", "/","Zarez", "=", "CE", "DEL"];
	//pravi mi probleme operator tacka -> za zarez

foreach($dugmad as $dugme){

	if(isset($_GET["b".$dugme])){
		if($unos!="") $posljednjiZnakUnosa=substr($unos, strlen($unos)-1);

		//validacija
 		//napraviti validaciju da ne moze unijeti 2.2.25 broj

		if($unos=="" && ($dugme=='+' || $dugme=='x' || $dugme=='/' || $dugme=='Zarez' )){
			echo "Najprije unesite prvi operand, nakon toga odaberite željenu operaciju.";
			break;
		}
		//ponisti unos
		else if("bCE"=="b".$dugme){
			$unos="";
			break;
		}
		//obrisi zadnji unos
		else if("bDEL"=="b".$dugme){
			$unos=substr($unos,0,strlen($unos)-1);
			break;
		}

		else if($unos!=""){

		//da ne stavlja vise operacija  jednu za drugom
			if(strlen($unos)>0 && ($dugme=='+' || $dugme=='x' || $dugme=='/' || $dugme=='Zarez' || $dugme=='-' )&& ($posljednjiZnakUnosa=="+" || $posljednjiZnakUnosa=="x" || $posljednjiZnakUnosa=="/" || $posljednjiZnakUnosa=="." || $posljednjiZnakUnosa=="-")) {
				echo "Ne mozete unijeti operaciju za operacijom!";
				break;
			}
		}



		//kliknuo na jednako
		if("b="=="b".$dugme){


			//da li je zadnji unos bio operacija, dakle fali jedan operand, obrisat cemo samo tu posljednju operaciju
			if($posljednjiZnakUnosa=='/' || $posljednjiZnakUnosa=='.' || $posljednjiZnakUnosa=='+' || $posljednjiZnakUnosa=='-' || $posljednjiZnakUnosa=='x') 
				$unos=substr($unos,0, strlen($unos)-1);

			$elementi=[];
			$broj="";
			$i=0;

			//ako je unesen prvo minus
			if($unos[0]=='-') {$broj="-"; $i=1;}

			/* kreiramo niz elementi, u koji ubacujemo brojeve i operacije kao zasebne clanove niza
			*/
			for($i; $i<strlen($unos); $i++){
				//ako nije operacija detektovana u unosu, nadodaj cifru na trenutni broj
				if($unos[$i]!='x' && $unos[$i]!='/' && $unos[$i]!='+' && $unos[$i]!='-'){
					//console.log(unos[i]+'\n');
					$broj.=$unos[$i];
				}
				else { 
					//dosli smo do operacije u unosu, unosimo broj, i unosimo operaciju
					array_push($elementi, $broj);
					array_push($elementi, $unos[$i]);

					$broj="";
				}
			}
			array_push($elementi, $broj);
			
			//ako je prvi element = -, znaci negativan je operand, samo drugi el ucinimo negativnim, prvi element (znak -) obrisemo
			if($elementi[0]=='-'){
				$elementi[1]="-".$elementi[1];
				array_shift($elementi);
			}
			//console.log(elementi);
			//echo 'elementi su: '."\n";
			//print_r($elementi);
			//ideja je da trazi operacije s lijeva nadesno, po prioritetima, onda ce obaviti operaciju nad prethodnim i elementom poslije operacije, i to spasava u npr prethodni, dok operaciju i sljedeci brise iz niza na nacin da pomjera preostale elemente na ta obrisana mjesta, fja splice
			

			//brojac koliko ima * i / posto oni imaju prioritet
			$brojac1=0;
			for($k=0; $k<count($elementi) ; $k++)
				if($elementi[$k]=='/' || $elementi[$k]=='x'){
					$brojac1++;
				}
				while(count($elementi)>1){	
					for($j=0; $j<count($elementi); $j++){
						if($elementi[$j]=='x' || $elementi[$j]=='/'){
							$brojac1--;
							if($elementi[$j]=='x'){
								$elementi[$j-1]=$elementi[$j-1]*$elementi[$j+1];
							}
							else {
								$elementi[$j-1]=$elementi[$j-1]/$elementi[$j+1];
							}
					//obrisati operaciju, i drugi operand					
							unset($elementi[$j]);
							$elementi=array_values($elementi);
							//array values, reindeksira niz, prebacuje na prazna mjesta, nesta k'o splice
							unset($elementi[$j]);
							$elementi=array_values($elementi);
							//echo "velicina niza elementi je ".count($elementi)."\n";
					//array_splice($elementi, j, 2, []);

						//bila izbacila izvan ovih ifova, i onda break-ao prije nego sto promijeni stanje prvog operanda
							break;
						}
						else if($brojac1==0 && ($elementi[$j]=='+' || $elementi[$j]=='-')){
							if($elementi[$j]=='+'){
								$elementi[$j-1]=$elementi[$j-1]+$elementi[$j+1];
							}
							else {
								$elementi[$j-1]=$elementi[$j-1]-$elementi[$j+1];
							}
						//obrisati operaciju, i drugi operand
							unset($elementi[$j]);
							$elementi=array_values($elementi);
							unset($elementi[$j]);
							$elementi=array_values($elementi);
							//echo "velicina niza elementi je ".count($elementi)."\n";
							break;
						}
					}
				} 
				$unos=$elementi[0];
				break;
			}
			else {
				if($dugme=="Zarez") $unos.=".";
				else $unos.=$dugme;
				break;
			}
		}
	}
	?>

	<style>
	.dugme{
		margin-right: 16pt;
		font-weight: bold;
		background-color: white;
		font-size:15pt;
		border-radius: 50%;


	}
</style>
<body>
	<body >
		<div style="border: 2pt solid black;padding:10px; margin-right: 70%; background-color: #bfbfbf">
			<form action="DigitronPHP.php" method="GET">
				<input type="text" id="ispis"  name="stanjeUnos" value="<?= $unos?>" style="font-size:15pt; background-color: white;">
				<br><br>
				
				<div style="margin-left:8%">
					<input type="submit" name="b7" value="7"  class="dugme" >
					<input type="submit" name="b8" value="8" class="dugme" >
					<input type="submit" name="b9" value="9" class="dugme">
					<input type="submit" name="b/" value="/" class="dugme" >

					<br><br>
					<input type="submit" name="b4" value="4" class="dugme" >
					<input type="submit" name="b5" value="5" class="dugme" >
					<input type="submit" name="b6" value="6" class="dugme" >
					<input type="submit" name="b-" value="-" class="dugme" >

					<br><br>
					<input type="submit" name="b1" value="1" class="dugme" >
					<input type="submit" name="b2" value="2" class="dugme">
					<input type="submit" name="b3" value="3" class="dugme" >
					<input type="submit" name="bx" value="x" class="dugme">

					<br><br>
					<input type="submit" name="b0" value="0" class="dugme" >
					<input type="submit" name="bZarez" value="." class="dugme" >
					<input type="submit" name="b=" value="=" style="background-color: #ff66b3;" class="dugme">
					<input type="submit" name="b+" value="+" class="dugme" >
					<br><br>
					<input type="submit" name="bCE" value="AC " class="dugme"  style="margin-left:20pt;">
					<input type="submit" name="bDEL" value="DEL" class="dugme"  style="margin-left:15pt;">
					<br>

				</form>
			</div>
		</div>
	</body>
	</html>



</body>
</html>

