<!DOCTYPE html>
<html>
<head>
	<title>Medi Digitron JavaScript</title>
</head>

<script>

//ne treba mi var u parametru fje
function digitron(x){
	var trenutnoStanjeIspisa=document.getElementById("ispis").value;	
	
	//je li x operacija 
	var operacija=(x=='zarez' || x=='mnozi' || x=='saberi' || x=='dijeli' || x=='oduzmi');

	if(trenutnoStanjeIspisa!="") 
		var posljednjiUnos=trenutnoStanjeIspisa[trenutnoStanjeIspisa.length-1];

	//za DEL 
	if(x=='obrisiZadnji' && trenutnoStanjeIspisa!=""){
		document.getElementById("ispis").value=trenutnoStanjeIspisa.substr(0,trenutnoStanjeIspisa.length-1);
	}
	
	//da ne bi prvo unio operaciju, a nemamo prvog operanda, moze unijeti -, poslije dole samo izmijenim 2. el niza da bude negativan, a prvi el niza elemetni tj. - obrisem
	else if ( trenutnoStanjeIspisa=="" && (x=='zarez' || x=='mnozi' || x=='saberi' || x=='dijeli' )) {
		
	}


	//da ne bi unio vise istih operacija zaredom
	else if(trenutnoStanjeIspisa!="" && operacija==true && (posljednjiUnos=='/' || posljednjiUnos=='x' || posljednjiUnos=='+' || posljednjiUnos=='-' || posljednjiUnos=='.')){
		
	}


	

	else {
		var postavi="";
		switch(x){
			case 7: postavi="7";
			break;
			case 8: postavi="8";
			break;
			case 9: postavi="9";
			break;
			case 4: postavi="4";
			break;
			case 5: postavi="5";
			break;
			case 6: postavi="6";
			break;
			case 0: postavi="0";
			break;
			case 1: postavi="1";
			break;
			case 2: postavi="2";
			break;
			case 3: postavi="3";
			break;
			case 'zarez': postavi=".";

			break;
			case 'dijeli': postavi="/";
			break;
			case 'mnozi': postavi="x";
			break;
			case 'saberi': postavi="+";
			break;
			case 'oduzmi': postavi="-";
			break;

			case 'ponisti': document.getElementById("ispis").value="";

			break;
			case 'jednako': 

			var unos=trenutnoStanjeIspisa;
			//da li je zadnji unos bio operacija, dakle fali jedan operand, obrisat cemo samo tu posljednju operaciju
			if(unos[unos.length-1]=='/' || unos[unos.length-1]=='.' || unos[unos.length-1]=='+' || unos[unos.length-1]=='-' || unos[unos.length-1]=='x') unos=unos.substr(0, unos.length-1);
			var elementi=[];
			var broj="";
			var i=0;
			if(unos[0]=='-') {broj="-"; i=1;}

			/* kreiramo niz elementi, u koji ubacujemo brojeve i operacije kao zasebne clanove niza
			*/
			for(i; i<unos.length; i++){
				//ako nije operacija detektovana u unosu, nadodaj cifru na trenutni broj
				if(unos[i]!='x' && unos[i]!='/' && unos[i]!='+' && unos[i]!='-'){
					//console.log(unos[i]+'\n');
					broj+=unos[i];
				}
				else { 
					//dosli smo do operacije u unosu, unosimo broj, i unosimo operaciju
					elementi.push(broj);
					elementi.push(unos[i]);
					broj="";
				}
			}
			elementi.push(broj);
			//ako je prvi element = -, znaci negativan je operand, samo drugi el ucinimo negativnim, prvi element (znak -) obrisemo
			if(elementi[0]=='-'){
				elementi[1]="-"+elementi[1];
				elementi.shift();
			}
			//console.log(elementi);

			//ideja je da trazi operacije s lijeva nadesno, po prioritetima, onda ce obaviti operaciju nad prethodnim i elementom poslije operacije, i to spasava u npr prethodni, dok operaciju i sljedeci brise iz niza na nacin da pomjera preostale elemente na ta obrisana mjesta, fja splice
			

			//brojac koliko ima * i / posto oni imaju prioritet
			var brojac=0;
			for(var k=0; k<elementi.length; k++)
				if(elementi[k]=='/' || elementi[k]=='x'){
					brojac++;
				}
				while(elementi.length>1){
					for(var j=0; j<elementi.length; j++){
						if(elementi[j]=='x' || elementi[j]=='/'){
							brojac--;
							if(elementi[j]=='x'){
								elementi[j-1]=parseFloat(elementi[j-1])*parseFloat(elementi[j+1]);
						
						

					}
					else {
						elementi[j-1]=parseFloat(elementi[j-1])/parseFloat(elementi[j+1]);
					}
					//obrisati operaciju, i drugi operand
					elementi.splice(j,2); 
						//bila izbacila izvan ovih ifova, i onda break-ao prije nego sto promijeni stanje prvog operanda
						break;
					}
					else if(brojac==0 && (elementi[j]=='+' || elementi[j]=='-')){
						if(elementi[j]=='+'){
							elementi[j-1]=parseFloat(elementi[j-1])+parseFloat(elementi[j+1]);
						}
						else {
							elementi[j-1]=parseFloat(elementi[j-1])-parseFloat(elementi[j+1]);

						}
						//obrisati operaciju, i drugi operand
						elementi.splice(j,2);
						break;
					}
					

					

				}


			}
			document.getElementById("ispis").value=String(elementi[0]);

			
			break;


	}	//kraj switcha

	if(postavi!=""){
		document.getElementById("ispis").value+=postavi;

	}

}


}
//direction: rtl; imam belaj kad dodaje znakove, doda ga lijevo, a treba desno

</script>
<body >
	<div style="border: 2pt solid black;padding:10px; margin-right: 70%; background-color: #bfbfbf">
		<form action="MediDigitronJS.php" method="POST">
			<input type="text" id="ispis" disabled="" value="" style="font-size:15pt; background-color: white;">
			<br><br>
			<style>
			.dugme{
				margin-right: 16pt;
				font-weight: bold;
				background-color: white;
				font-size:15pt;
				border-radius: 50%;


			}
		</style>
		<div style="margin-left:8%">
			<input type="button" name="btn7" value="7" width=100 class="dugme" onclick="digitron(7);">
			<input type="button" name="btn8" value="8" class="dugme" onclick="digitron(8);">
			<input type="button" name="btn9" value="9" class="dugme" onclick="digitron(9);">
			<input type="button" name="btn%" value="/  " class="dugme" onclick="digitron('dijeli');">

			<br><br>
			<input type="button" name="btn4" value="4" class="dugme" onclick="digitron(4);">
			<input type="button" name="btn5" value="5" class="dugme" onclick="digitron(5);">
			<input type="button" name="btn6" value="6" class="dugme" onclick="digitron(6);">
			<input type="button" name="btn-" value="-  " class="dugme" onclick="digitron('oduzmi');">

			<br><br>
			<input type="button" name="btn1" value="1" class="dugme" onclick="digitron(1);">
			<input type="button" name="btn2" value="2" class="dugme" onclick="digitron(2);">
			<input type="button" name="btn3" value="3" class="dugme" onclick="digitron(3);">
			<input type="button" name="btnx" value="x " class="dugme" onclick="digitron('mnozi');">

			<br><br>
			<input type="button" name="btn0" value="0" class="dugme" onclick="digitron(0);">
			<input type="button" name="btn." value=". " class="dugme" onclick="digitron('zarez');">
			<input type="button" name="btn=" value="=" style="background-color: #ff66b3;" class="dugme" onclick="digitron('jednako');">
			<input type="button" name="btn+" value="+ " class="dugme" onclick="digitron('saberi');">
			<br><br>
			<input type="button" name="btnCE" value="AC " class="dugme" onclick="digitron('ponisti');" style="margin-left:20pt;">
			<input type="button" name="btnCE" value="DEL" class="dugme" onclick="digitron('obrisiZadnji');" style="margin-left:15pt;">
			<br>

		</form>
	</div>
</div>
</body>
</html>