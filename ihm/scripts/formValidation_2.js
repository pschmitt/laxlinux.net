function validateForm() {
	valid = true;
	errorMsg = "Veuillez répondre à cette dernière question, svp !";
	
    if ((document.getElementById("score_satisfOne").checked == false) &&
		(document.getElementById("score_satisfTwo").checked == false) &&
		(document.getElementById("score_satisfThree").checked == false) &&
		(document.getElementById("score_satisfFour").checked == false) &&
		(document.getElementById("score_satisfFive").checked == false) &&
		(document.getElementById("score_satisfSix").checked == false) &&
		(document.getElementById("score_satisfSeven").checked == false)) {
        valid = false;
    }
	if (!valid)
		alert(errorMsg);
    return valid;
}
