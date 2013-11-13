function validateForm() {
	valid = true;
	errorMsg = "Veuillez remplir le questionnaire en entier.\nElements manquants:\n";
	
	if(document.getElementById("gender").value == "default") {
		errorMsg += "\t- votre sexe\n";
        valid = false;
	}
	if(document.getElementById("byear").value == "default") {
		errorMsg += "\t- votre année de naissance\n";
        valid = false;
	}
	if(document.getElementById("lang").value == "default") {
		errorMsg += "\t- votre langue maternelle\n";
        valid = false;
	}
	if(document.getElementById("wperiod_estimation").value == "default") {
		errorMsg += "\t- estimation du temps d'attente\n";
        valid = false;
	}
    if ((document.getElementById("technoOne").checked == false) &&
		(document.getElementById("technoTwo").checked == false) &&
		(document.getElementById("technoThree").checked == false) &&
		(document.getElementById("technoFour").checked == false) &&
		(document.getElementById("technoFive").checked == false) &&
		(document.getElementById("technoSix").checked == false) &&
		(document.getElementById("technoSeven").checked == false)) {
    	errorMsg += "\t- techologie\n";
        valid = false;
    }
	if ((document.getElementById("focOne").checked == false) &&
		(document.getElementById("focTwo").checked == false) &&
		(document.getElementById("focThree").checked == false) &&
		(document.getElementById("focFour").checked == false) &&
		(document.getElementById("focFive").checked == false) &&
		(document.getElementById("focSix").checked == false) &&
		(document.getElementById("focSeven").checked == false)) {
		errorMsg += "\t- focalisation sur le temps d'attente\n";
        valid = false;
    }
	if ((document.getElementById("okOne").checked == false) &&
		(document.getElementById("okTwo").checked == false) &&
		(document.getElementById("okThree").checked == false) &&
		(document.getElementById("okFour").checked == false) &&
		(document.getElementById("okFive").checked == false) &&
		(document.getElementById("okSix").checked == false) &&
		(document.getElementById("okSeven").checked == false)) {
    	errorMsg += "\t- sdsd\n";
        valid = false;
    }
	if ((document.getElementById("satisfOne").checked == false) &&
		(document.getElementById("satisfTwo").checked == false) &&
		(document.getElementById("satisfThree").checked == false) &&
		(document.getElementById("satisfFour").checked == false) &&
		(document.getElementById("satisfFive").checked == false) &&
		(document.getElementById("satisfSix").checked == false) &&
		(document.getElementById("satisfSeven").checked == false)) {
    	errorMsg += "\t- satisfaction\n";
        valid = false;
    }
	if ((document.getElementById("justOne").checked == false) &&
		(document.getElementById("justTwo").checked == false) &&
		(document.getElementById("justThree").checked == false) &&
		(document.getElementById("justFour").checked == false) &&
		(document.getElementById("justFive").checked == false) &&
		(document.getElementById("justSix").checked == false) &&
		(document.getElementById("justSeven").checked == false)) {
    	errorMsg += "\t- just\n";
        valid = false;
    }
	if ((document.getElementById("stimulOne").checked == false) &&
		(document.getElementById("stimulTwo").checked == false) &&
		(document.getElementById("stimulThree").checked == false) &&
		(document.getElementById("stimulFour").checked == false) &&
		(document.getElementById("stimulFive").checked == false) &&
		(document.getElementById("stimulSix").checked == false) &&
		(document.getElementById("stimulSeven").checked == false)) {
    	errorMsg += "\t- stimulation\n";
        valid = false;
    }
	if (!valid)
		alert(errorMsg);
    return valid;
}
