// version 0.9.9a

// 0 < Round < 4
var round = -1;
// test ?
var test = true;

// score max = 30
var score = 0;
// errors max = 95
var errors = 0;

// junk progress bar
var junk = false;
// waited before playing
var wbplay = false;
// waiting time
var wait = false;

var solution = [];
var answers  = [];

/**
  * getUrlVars() - Get GET vars
  *
  */
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

var debug = getUrlVars()["debug"];
var getv = getUrlVars()["av"];

var button = document.getElementById("start");
var instructions = document.getElementById("instructions");
var newInstructions = "Le test de mémoire peut désormais commencer.<br />Vous aurez 5 grilles différentes à remplir. <b>Pour la validité de votre score, merci de bien vouloir toujours rester concentré(e) sur le jeu.</b><br /><br/><br/>Cliquez sur « Démarrer le jeu » dès que vous êtes prêt(e).";

/**
  *  refreshBugWorkaround()
  *  réactive le boutton "start" si l'utilisateur a fait un refresh de la page alors que
  *  le boutton "Valider" était caché (ie. pdt un chargement, ou avant de pouoir clicker)
  */
function refreshBugWorkaround() {
	if (button.childNodes[0].nodeValue == "Démarrer le jeu" && button.style.visibility == "hidden") 
		button.style.visibility = "visible";

}
refreshBugWorkaround();

/**
  *  randomize()
  *	 lotto-Anwendung
  *  setzt junk, wbplay und wait
  */
function randomize() {
	if (debug) {
		wait = prompt("Temps d'attente désiré = ?\nValeurs possibles: 0, 5, 10 ou 15", "");
		wait = wait * 1000;
		wbplay = prompt("Attendre avant (true) ou pendant la partie (false)?\ntrue ou false", "");
		junk = prompt("Voir les pubs ?\ntrue ou false", "");
		
		// Get real values
		if (wbplay === "true")
			wbplay = true;
		else
			wbplay = false;
		if (junk === "true")
			junk = true;
		else
			junk = false;
		if (wait === "0") {
			junk = false;
			wbplay = false;
		}
	} else {
		junk  = Math.floor(Math.random() * 2);
		wbplay = Math.floor(Math.random() * 2);
		wait   = Math.floor(Math.random() * 3);
		wait++;
		// Get real values
		if (junk == 1)
			junk = true;
		else
			junk = false;
		if (wbplay == 1)
			wbplay = true;
		else
			wbplay = false;
		switch (wait) {
			case 0: junk = false;
					wbplay = false;
					break;
			case 1: wait = 5000;
					break;
			case 2: wait = 10000;
					break;
			case 3: wait = 15000;
					break;
		}
	}

	document.getElementById("wbplay").value = wbplay;
	document.getElementById("junk").value = junk;

	document.getElementById("wperiod").value = wait/1000;

	// DEBUG
	// junk = false;
	// wbplay = false;
	// wait = 20000;
	if (getv)
		alert ("junk: " + junk + "\nwbplay: " + wbplay + "\nwait: " + wait/1000 + "s");
}
randomize();

/**
  *  initialization()
  *  lance la machine. Go purée..!
  */
function initialization() {
	if (test) {
		document.getElementById("grid").style.display = "block";
		move(); // fillGrid() is called by moveGrid() 
	} else {
		instructions.style.display = "none";
		clear();
		fillGrid();
	}
	document.getElementById("game").style.visibility = "visible";
	document.getElementById("loading").style.visibility = "hidden";
	//button.disabled = true;
	button.style.visibility = "hidden";
	button.innerHTML = 'Valider';
	button.onclick = gameEnded;	
	button.style.marginTop = "150px";
	button.style.marginLeft = "100px";
}

/**
  *  fillGrid()
  *  remplit la grille 
  */
function fillGrid() {
	var i = 0;
	var j = 0;
	var elementNb = 0;
	var gameCell;
	var gameArray = ['one', 'two', 'three', 'four', 'five'];
	
	while (elementNb < 6) {
		i = Math.floor(Math.random() * 5);
		j = Math.floor(Math.random() * 5);
		i = gameArray[i];
		j = gameArray[j];	
		gameCell = i + "-" + j;
		if (document.getElementById(gameCell).hasChildNodes() == false) {
			document.getElementById(gameCell).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
			solution[elementNb] = gameCell;
			elementNb++;
		}
	}
	displayRoundNb();	
	setTimeout("hideElements()",3000);
}

/**
  *  displayRoundNb()
  *  fait afficher le numéro de la grille
  */
function displayRoundNb() {
	var roundNbTxt = document.getElementById("roundNb");
	if (test) {
		roundNbTxt.innerHTML = "TEST";
		roundNbTxt.style.visibility = "visible";	
	} else
		roundNbTxt.innerHTML = "# " + (round + 1) + " / 5";
}

/**
  * move()
  * Bouge la grille, fait disparaitre la consigne et apparaitre le numéro de la grille
  */
function move() {
	instructions.style.display = "none";
	moveGrid(300, 10);
}

var margin = 0;
/**
  *  moveGrid(pxNb, refreshRate)
  *  Bouge "game" de pxNb vers la gauche, avec un effet d'animation
  */
function moveGrid(pxNb, refreshRate) {
 	if (margin <= pxNb) {
    	document.getElementById("game").style.marginRight = margin + "px";
    	setTimeout("moveGrid("+pxNb+","+refreshRate+");", refreshRate);
    	margin += 2;   
	} else {
		setTimeout("fillGrid();",200);
	}
}

/**
  *  hideElements()
  *  caches les images de la grille, en fait toute les img sont cachées maintenant
  */
function hideElements() {
	var images = document.getElementsByTagName("img");
	for (var i = 0; i < images.length; i++) {
		images[i].style.visibility = "hidden";
		// DEBUG
		// images[i].parentNode.style.backgroundColor = "red";
	}
	button.style.visibility = "visible";
	click();
	if (!wbplay && !test && wait != 0)
		loading(wait);
}

/**
  *  showHidden()
  *	 Montre les images cachées
  *  id est donne la solution
  */
function showHidden() {
	for (var i = 0; i < document.getElementsByTagName("img").length; i++) {
		var images = document.getElementsByTagName("img")[i];
		if (images.style.visibility == 'hidden') {
			images.src = "./images/redstar.png";
			images.style.visibility = "visible";
		}
	}
}

/**
 *  Cross-browser implementation of element.addEventListener()
 *  Source: http://javascript.about.com/library/bllisten.htm
 */
/*var addEvent, removeEvent;
if (window.addEventListener) {
	addEvent = function(obj, type, fn ) {
    	obj.addEventListener(type, fn, false );
  	}
  	removeEvent = function(obj, type, fn ) {
  		obj.removeEventListener(type, fn, false );
  	}
} else if (document.attachEvent) {  // IE - BUT DOES NOT WORK !!!
  	addEvent = function(obj, type, fn) {
  		var eProp = type + fn;
    	obj['e'+eProp] = fn;
    	obj[eProp] = function(){obj['e'+eProp](window.event);}
    	obj.attachEvent('on'+type, obj[eProp] );
  	}
  	removeEvent = function(obj, type, fn) {
  		var eProp = type + fn;
    	obj.detachEvent('on'+type, obj[eProp]);
    	obj[eProp] = null;
    	obj["e"+eProp] = null;
  	}
}*/

var clickNb = -1;
/**
  *  listener
  *  à éxécuter lorsque l'utilisateur clique
  */
var listener = function(event) {
	event = event || window.event;
	eventTarget = event.target || event.srcElement;
	//alert(event.srcElement.tagName);
	if ((event.button == 0) || (event.button == 1)) {
		if ((eventTarget == "[object HTMLTableCellElement]") || (eventTarget == "[object HTMLTableDataCellElement]") || (eventTarget.tagName == "TD")) {
			// Le problème de compatibilité avec IE était là !!! Le srcElement est pour IE8
			// Pour compatibilité avec IE8 voir http://stackoverflow.com/questions/2642095/access-event-target-in-ie8-unobstrusive-javascript
			var spot = eventTarget.id;
			if (!answers.contains(spot))
				answers[++clickNb] = spot;
			eventTarget.innerHTML = '<img class="guess" src="./images/star.png" alt="render fail" />';
		} else if (eventTarget == "[object HTMLImageElement]" || eventTarget.tagName == "IMG") {
			//alert(eventTarget.parentNode);
			var rmIndex = answers.indexOf(eventTarget.parentNode.id);
			answers.remove(rmIndex);
			clickNb--;
			eventTarget.parentNode.innerHTML = '';
			//alert(solution.join(", ") + "\n\n" + answers.join(", ") + "\tlength: " + answers.length);
		}
	}
}

/**
  *  click()
  *  Ajoute le bon "event"
  */
function click() {
	if (document.addEventListener)
	  	document.addEventListener('click', listener, false);
	else
		document.attachEvent('onclick', listener);
}

/**
  * unclick()
  * Remove click event
  */
function unclick() {
	if (document.removeEventListener) // Si notre élément possède la méthode addEventListener()
    	document.removeEventListener('click', listener, false);
	else  // Si notre élément ne possède pas la méthode addEventListener()
	    document.detachEvent('onclick', listener);
}

/**
  *  gameEnded()
  *  Continue ?!
  */
function gameEnded() {
	button.style.visibility = "hidden";
	clickNb = 0;
	
	unclick();
	compute();
	if (test) {
		instructions.innerHTML = newInstructions;
		instructions.style.display = "block";
		button.innerHTML = "Démarrer le jeu";
		button.onclick = initialization;
		//button.disabled = false;
		button.style.visibility = "visible";
		button.style.marginTop = "15px";
		button.style.marginLeft = "75px";
	}
	else if (round == 0) {
		button.onclick = gameEnded;
	}
	if (round < 4) {
		sendData();
		clear();
		round++;
		if (!test && wbplay) {
			loading(wait);
		}
		if (!test && !wbplay)
			initialization();
		test = false;
		return false;
	} else {
		sendData();
		document.getElementById("score").value = score;
		document.getElementById("errors").value = errors;
		document.getElementById("hiddenform").submit();
		return true;
	}
}

/**
  *  compute()
  *  calcule le nombre d'erreurs et le score 
  */
function compute() {
	for (i = 0; i < answers.length; i++) {
		if (solution.contains(answers[i]))
			score++;
		else
			errors++;
	}
	if (test) {
		alert("Votre score (sur 6) est de: " + score + "\nNombre d'erreurs commises: " + errors);
		score = 0;
		errors = 0;
	} // DEBUG 
	// else
	// 	 alert("score: " + score + "\nerrors: " + errors);

}

/**
  *  clear()
  */
function clear() {
	var images = document.getElementsByTagName('img');
	while(images.length > 0)
	   	images[0].parentNode.removeChild(images[0]);
	clickNb = -1;

	// reset arrays
	answers.length = 0;
	solution.length = 0;
	// DEBUG
	// var td = document.getElementsByTagName("td");
	// for (i = 0; i < td.length; i++)
	// 		td[i].style.backgroundColor = "blue";*/
}

/**
  *  getElementByClass(className)
  *  get all elements matching a certain class
  *  NOT WORKING YET !!
  */
function getElementByClass(className) {
	var allElems = document.getElementsByTagName('*');
	var elements = "";
	for (var i = 0; i < allElems.length; i++) {
		var thisElem = allElems[i];
		if (thisElem.className && thisElem.className == className) {
			allElems[i].parentNode.removeChild(allElems[i]);
		}
	 }
}

/**
  *  loading()
  *	 Charge la page d'attente
  */
function loading(time) {
	if (wtime == 0)
		initialization();
	else {
		var lb = document.getElementById("loading");
		lb.style.visibility = "visible";
		var wtime = time / 200;
		document.getElementById("game").style.visibility = "hidden";
		// DEBUG
		// timedProg(0, false);
		if (wtime != 0)
			timedProg(wtime, junk);
		else if (wbplay) 
			initialization();
		if (junk && wtime != 0) {
			// DISPLAY ADS
			/*document.getElementById("adOne").style.visibility = "visible";
			document.getElementById("adTwo").style.visibility = "visible";
			document.getElementById("your_adOne").style.visibility = "visible";
			document.getElementById("your_adTwo").style.visibility = "visible";*/
		} else {
			/*document.getElementById("adOne").style.visibility = "hidden";
			document.getElementById("adTwo").style.visibility = "hidden";*/
			lb.style.marginTop = "130px";
			document.getElementById("additionnal_loading_txt").style.visibility = "hidden";
			document.getElementById("progress_bar_text").style.visibility = "hidden";
			document.getElementById("progress_bar_bg").style.visibility = "hidden";
		}
	}
}

/**
 *  timedProg(refreshRate, showPercentage)
 *  Barre de progression
 *  @author ???
 *  http://www.journaldunet.com/developpeur/tutoriel/dht/060304-javascript-barre-de-progression.shtml
 *  modified by Philipp Schmitt (parameters + else statement)
 */
var i = 0;
function timedProg(refreshRate, showPercentage) {
 	if (i <= 200) {
		//SHOW PERCENTAGE;
	   	if (showPercentage) //&& i > 40)
				document.getElementById("progress_bar_text").innerHTML = parseInt(i/2) + "%";
    	document.getElementById("progress_bar_fg").style.width=i+"px";
    	var j = 0;		
    	while (j <= 100)
     		j++;  
    	setTimeout("timedProg("+refreshRate+","+showPercentage+");", refreshRate);
    	i++;
		if (!wbplay)
			button.style.visibility = "hidden";
    } else { // reset !
		i = 0;
		document.getElementById("progress_bar_fg").style.width = 0;
		if (wbplay) 
			initialization();
		else {
			button.style.visibility = "visible";
			document.getElementById("game").style.visibility = "visible";
			document.getElementById("loading").style.visibility = "hidden";
		}
		if (junk) {
			document.getElementById("adOne").style.visibility = "hidden";
			document.getElementById("adTwo").style.visibility = "hidden";
			document.getElementById("your_adOne").style.visibility = "hidden";
			document.getElementById("your_adTwo").style.visibility = "hidden";
			document.getElementById("progress_bar_text").innerHTML = "0%";
		}
	}
}

/**
  *  sendData()
  *	 sends solution to hidden form
  */
function sendData() {
	var arrayContentSol = solution.toString(" ");
	var arrayContentUA  = answers.toString(" ");
	switch (round) {
		case 0: document.getElementById("uaOne").value = arrayContentUA;
				document.getElementById("solOne").value = arrayContentSol;
				break;
		case 1: document.getElementById("uaTwo").value = arrayContentUA;
				document.getElementById("solTwo").value = arrayContentSol;
				break;
		case 2: document.getElementById("uaThree").value = arrayContentUA;
				document.getElementById("solThree").value = arrayContentSol;
				break;
		case 3: document.getElementById("uaFour").value = arrayContentUA;
				document.getElementById("solFour").value = arrayContentSol;
				break;
		case 4: document.getElementById("uaFive").value = arrayContentUA;
				document.getElementById("solFive").value = arrayContentSol;
				break;
	}
	// DEBUG
	//alert(arrayContentSol + "\n\n"  + arrayContentUA);
}







// Helper methods

// extend Arrays !
Array.prototype.contains = function(obj) {
	var i = this.length;
  	while (i--) {
    	if (this[i] === obj) {	// == obj ?!
      		return true;
    	}
  	}
  	return false;
}

// Source: https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/indexOf
if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function(elt /*, from*/) {
		var len = this.length >>> 0;

		var from = Number(arguments[1]) || 0;
		from = (from < 0)
			 ? Math.ceil(from)
			 : Math.floor(from);
		if (from < 0)
		  from += len;
		for (; from < len; from++) {
		  if (from in this &&
			  this[from] === elt)
				return from;
			}
					return -1;
				  };
}

// Source: http://ejohn.org/blog/javascript-array-remove/
Array.prototype.remove = function(from, to) {
	var rest = this.slice((to || from) + 1 || this.length);
  	this.length = from < 0 ? this.length + from : from;
  	return this.push.apply(this, rest);
}

// 
Array.prototype.toString = function(delimiter) {
	var arrayContent = "";
	for (i = 0; i < this.length; i++) {
	    arrayContent += this[i];
		if (i != this.length - 1)
			arrayContent += delimiter;
	}
	return arrayContent;
}
