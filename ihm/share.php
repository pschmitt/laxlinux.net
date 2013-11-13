<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="title" content="MMS Project | Jeu de mémoire" />
		<meta name="description" content="LaXLinux.net" />
		<link rel="image_src" type="image/jpeg" href="http://www.laxlinux.net/ihm/images/star.png" />
        <title>MMS Project | Jeu de mémoire</title>
		<link rel="shortcut icon" href="./images/star.png" />
        <link rel="icon" type="image/png" href="./images/star.png" />
        <link rel="stylesheet" type="text/css" href="./css/styles.css" />
		<!--[if IE]>
			<style type="text/css">
			.clear {
			  zoom: 1;
			  display: block;
			}
			</style>
        <![endif]-->
    </head>
	
    <body>
		<div id="fb-root"></div>
		<script type="text/javascript">
	    	window.fbAsyncInit = function() {
	        FB.init({
    	        appId: '262035797143739', // Your Facebook ID
        	    status: true,
            	cookie: true,
	            xfbml: true
    	    });
	    	};
	    	(function() {
    	    	var e = document.createElement('script'); e.async = true;
	    	    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
	        	document.getElementById('fb-root').appendChild(e);
	    	}());
		</script>
		<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
    	<div class="section" id="page">
            <div class="header">
                <a href="./index.html">
    	            <h1>Jeu de mémoire</h1>
				</a>
            </div>
            <div class="section" id="articles">
                <div class="line"></div>
                <div class="main">
                	<h2>Vos résultats</h2>
                    <div class="line"></div>
                    <div class="articleBody clear">
					<h3>Votre score</h3>

	<?php
	$id = $_GET['id'];
	if ($id < 0 || $id == null)
		echo "invalid ID !";
	//echo "$id";

	// HERE ARE THE PASSWORDS !!
	$passwd_file = realpath('../../.login/DB_credentials.php');
	require "$passwd_file";
	
	$db = mysqli_connect($db_host, $db_user, $db_password, $db_name);

	if (mysqli_connect_error()) {
	    die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
	}
	
	$query = "SELECT score, errors, answers , solutions FROM $table WHERE id = $id";
	//echo "$query";	
	if ($stmt = $db->prepare($query)) {
    	/* execute statement */
	    $stmt->execute();
	    /* bind result variables */
    	$stmt->bind_result($score, $errors, $answers, $solutions);
		/* fetch values */ 
	    $stmt->fetch();
	}
	
	if (is_null($answers) || is_null($solutions))
		echo "<p><br/><br/>Couldn't fetch variables ! Invalid ID maybe ?</p>";		

    /* close statement */
    $stmt->close();

	$db->close();
	//echo($answers."\n\n".$solutions);
	//$uaOne = explode("A2", $answers);
	//$uaTwo = explode("A1 A3", $answers);
	//$uaOne = preg_split("/A1: .+  /", $answers);
	$answers = explode(' | ', $answers);
	$solutions = explode(' | ', $solutions);
	/*echo "<br/><br/>";*/
	//print_r($answers);
	//echo "<br/><br/>";
	/*print_r($solutions);*/
?>


					<!-- Arrow taken from: http://www.iconfinder.com/icondetails/23491/128/arrow_glossy_redo_right_submit_icon  -->
					<a href="http://www.facebook.com/sharer.php?u=http://laxlinux.net/ihm/share.php?id=<?php echo $_GET['id']; ?>&t=MMS+Project+LaXLinux" target="_blank">
						<img class="fb_share" src="./images/fb.png" />
					</a>
					<div class="share_buttons">
						<g:plusone class="gp" size="medium"></g:plusone>
						<br />
						<fb:like class="fb" href="www.laxlinux.net/ihm/" send="false" layout="button_count" colorsheme="light" show_faces="false"></fb:like>
						<br />
						<a href="http://twitter.com/share" class="twitter-share-button" data-text="I scored <?php echo $score ; ?> and made <?php echo $errors ; ?> errors on " data-via="Attentah" data-count="horizontal">
							Tweet
						</a>
						<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					</div>
					<p>
						Score: <span id="score" class="big"></span>
						<br />
						Erreurs: <span id="errors" class="big"></span>
					</p>
					<p>
						<span class="big" id="answ">Vos réponses</span>
						<span class="big" id="sols">Les solutions</span>
					</p>
					<div class="clear"></div>
					<table class="grid left">
							<tr>
								<td id="uaOne_one-one"></td>
								<td id="uaOne_one-two"></td>
								<td id="uaOne_one-three"></td>
								<td id="uaOne_one-four"></td>
								<td id="uaOne_one-five"></td>
							</tr>
							<tr>
								<td id="uaOne_two-one"></td>
								<td id="uaOne_two-two"></td>
								<td id="uaOne_two-three"></td>
								<td id="uaOne_two-four"></td>
								<td id="uaOne_two-five"></td>
							</tr>
							<tr>
								<td id="uaOne_three-one"></td>
								<td id="uaOne_three-two"></td>
								<td id="uaOne_three-three"></td>
								<td id="uaOne_three-four"></td>
								<td id="uaOne_three-five"></td>
							</tr>
							<tr>
								<td id="uaOne_four-one"></td>
								<td id="uaOne_four-two"></td>
								<td id="uaOne_four-three"></td>
								<td id="uaOne_four-four"></td>
								<td id="uaOne_four-five"></td>
							</tr>
							<tr>
								<td id="uaOne_five-one"></td>
								<td id="uaOne_five-two"></td>
								<td id="uaOne_five-three"></td>
								<td id="uaOne_five-four"></td>
								<td id="uaOne_five-five"></td>
							</tr>
						</table>
						<p class="gridNb">Grille 1</p>
						<table class="grid right">
							<tr>
								<td id="solOne_one-five"></td>
								<td id="solOne_one-four"></td>
								<td id="solOne_one-three"></td>
								<td id="solOne_one-two"></td>
								<td id="solOne_one-one"></td>
							</tr>
							<tr>
								<td id="solOne_two-five"></td>
								<td id="solOne_two-four"></td>
								<td id="solOne_two-three"></td>
								<td id="solOne_two-two"></td>
								<td id="solOne_two-one"></td>
							</tr>
							<tr>
								<td id="solOne_three-five"></td>
								<td id="solOne_three-four"></td>
								<td id="solOne_three-three"></td>
								<td id="solOne_three-two"></td>
								<td id="solOne_three-one"></td>
							</tr>
							<tr>
								<td id="solOne_four-five"></td>
								<td id="solOne_four-four"></td>
								<td id="solOne_four-three"></td>
								<td id="solOne_four-two"></td>
								<td id="solOne_four-one"></td>
							</tr>
							<tr>
								<td id="solOne_five-five"></td>
								<td id="solOne_five-four"></td>
								<td id="solOne_five-three"></td>
								<td id="solOne_five-two"></td>
								<td id="solOne_five-one"></td>
							</tr>
						</table>
						<div class="newLine"></div>
						<table class="grid left">
							<tr>
								<td id="uaTwo_one-one"></td>
								<td id="uaTwo_one-two"></td>
								<td id="uaTwo_one-three"></td>
								<td id="uaTwo_one-four"></td>
								<td id="uaTwo_one-five"></td>
							</tr>
							<tr>
								<td id="uaTwo_two-one"></td>
								<td id="uaTwo_two-two"></td>
								<td id="uaTwo_two-three"></td>
								<td id="uaTwo_two-four"></td>
								<td id="uaTwo_two-five"></td>
							</tr>
							<tr>
								<td id="uaTwo_three-one"></td>
								<td id="uaTwo_three-two"></td>
								<td id="uaTwo_three-three"></td>
								<td id="uaTwo_three-four"></td>
								<td id="uaTwo_three-five"></td>
							</tr>
							<tr>
								<td id="uaTwo_four-one"></td>
								<td id="uaTwo_four-two"></td>
								<td id="uaTwo_four-three"></td>
								<td id="uaTwo_four-four"></td>
								<td id="uaTwo_four-five"></td>
							</tr>
							<tr>
								<td id="uaTwo_five-one"></td>
								<td id="uaTwo_five-two"></td>
								<td id="uaTwo_five-three"></td>
								<td id="uaTwo_five-four"></td>
								<td id="uaTwo_five-five"></td>
							</tr>
						</table>
						<p class="gridNb">Grille 2</p>
						<table class="grid right">
							<tr>
								<td id="solTwo_one-five"></td>
								<td id="solTwo_one-four"></td>
								<td id="solTwo_one-three"></td>
								<td id="solTwo_one-two"></td>
								<td id="solTwo_one-one"></td>
							</tr>
							<tr>
								<td id="solTwo_two-five"></td>
								<td id="solTwo_two-four"></td>
								<td id="solTwo_two-three"></td>
								<td id="solTwo_two-two"></td>
								<td id="solTwo_two-one"></td>
							</tr>
							<tr>
								<td id="solTwo_three-five"></td>
								<td id="solTwo_three-four"></td>
								<td id="solTwo_three-three"></td>
								<td id="solTwo_three-two"></td>
								<td id="solTwo_three-one"></td>
							</tr>
							<tr>
								<td id="solTwo_four-five"></td>
								<td id="solTwo_four-four"></td>
								<td id="solTwo_four-three"></td>
								<td id="solTwo_four-two"></td>
								<td id="solTwo_four-one"></td>
							</tr>
							<tr>
								<td id="solTwo_five-five"></td>
								<td id="solTwo_five-four"></td>
								<td id="solTwo_five-three"></td>
								<td id="solTwo_five-two"></td>
								<td id="solTwo_five-one"></td>
							</tr>
						</table>
						<div class="newLine"></div>
						<table class="grid left">
							<tr>
								<td id="uaThree_one-one"></td>
								<td id="uaThree_one-two"></td>
								<td id="uaThree_one-three"></td>
								<td id="uaThree_one-four"></td>
								<td id="uaThree_one-five"></td>
							</tr>
							<tr>
								<td id="uaThree_two-one"></td>
								<td id="uaThree_two-two"></td>
								<td id="uaThree_two-three"></td>
								<td id="uaThree_two-four"></td>
								<td id="uaThree_two-five"></td>
							</tr>
							<tr>
								<td id="uaThree_three-one"></td>
								<td id="uaThree_three-two"></td>
								<td id="uaThree_three-three"></td>
								<td id="uaThree_three-four"></td>
								<td id="uaThree_three-five"></td>
							</tr>
							<tr>
								<td id="uaThree_four-one"></td>
								<td id="uaThree_four-two"></td>
								<td id="uaThree_four-three"></td>
								<td id="uaThree_four-four"></td>
								<td id="uaThree_four-five"></td>
							</tr>
							<tr>
								<td id="uaThree_five-one"></td>
								<td id="uaThree_five-two"></td>
								<td id="uaThree_five-three"></td>
								<td id="uaThree_five-four"></td>
								<td id="uaThree_five-five"></td>
							</tr>
						</table>
					    <p class="gridNb">Grille 3</p>	
						<table class="grid right">
							<tr>
								<td id="solThree_one-five"></td>
								<td id="solThree_one-four"></td>
								<td id="solThree_one-three"></td>
								<td id="solThree_one-two"></td>
								<td id="solThree_one-one"></td>
							</tr>
							<tr>
								<td id="solThree_two-five"></td>
								<td id="solThree_two-four"></td>
								<td id="solThree_two-three"></td>
								<td id="solThree_two-two"></td>
								<td id="solThree_two-one"></td>
							</tr>
							<tr>
								<td id="solThree_three-five"></td>
								<td id="solThree_three-four"></td>
								<td id="solThree_three-three"></td>
								<td id="solThree_three-two"></td>
								<td id="solThree_three-one"></td>
							</tr>
							<tr>
								<td id="solThree_four-five"></td>
								<td id="solThree_four-four"></td>
								<td id="solThree_four-three"></td>
								<td id="solThree_four-two"></td>
								<td id="solThree_four-one"></td>
							</tr>
							<tr>
								<td id="solThree_five-five"></td>
								<td id="solThree_five-four"></td>
								<td id="solThree_five-three"></td>
								<td id="solThree_five-two"></td>
								<td id="solThree_five-one"></td>
							</tr>
						</table>
						<div class="newLine"></div>
						<table class="grid left">
							<tr>
								<td id="uaFour_one-one"></td>
								<td id="uaFour_one-two"></td>
								<td id="uaFour_one-three"></td>
								<td id="uaFour_one-four"></td>
								<td id="uaFour_one-five"></td>
							</tr>
							<tr>
								<td id="uaFour_two-one"></td>
								<td id="uaFour_two-two"></td>
								<td id="uaFour_two-three"></td>
								<td id="uaFour_two-four"></td>
								<td id="uaFour_two-five"></td>
							</tr>
							<tr>
								<td id="uaFour_three-one"></td>
								<td id="uaFour_three-two"></td>
								<td id="uaFour_three-three"></td>
								<td id="uaFour_three-four"></td>
								<td id="uaFour_three-five"></td>
							</tr>
							<tr>
								<td id="uaFour_four-one"></td>
								<td id="uaFour_four-two"></td>
								<td id="uaFour_four-three"></td>
								<td id="uaFour_four-four"></td>
								<td id="uaFour_four-five"></td>
							</tr>
							<tr>
								<td id="uaFour_five-one"></td>
								<td id="uaFour_five-two"></td>
								<td id="uaFour_five-three"></td>
								<td id="uaFour_five-four"></td>
								<td id="uaFour_five-five"></td>
							</tr>
						</table>
						<p class="gridNb">Grille 4</p>
						<table class="grid right">
							<tr>
								<td id="solFour_one-five"></td>
								<td id="solFour_one-four"></td>
								<td id="solFour_one-three"></td>
								<td id="solFour_one-two"></td>
								<td id="solFour_one-one"></td>
							</tr>
							<tr>
								<td id="solFour_two-five"></td>
								<td id="solFour_two-four"></td>
								<td id="solFour_two-three"></td>
								<td id="solFour_two-two"></td>
								<td id="solFour_two-one"></td>
							</tr>
							<tr>
								<td id="solFour_three-five"></td>
								<td id="solFour_three-four"></td>
								<td id="solFour_three-three"></td>
								<td id="solFour_three-two"></td>
								<td id="solFour_three-one"></td>
							</tr>
							<tr>
								<td id="solFour_four-five"></td>
								<td id="solFour_four-four"></td>
								<td id="solFour_four-three"></td>
								<td id="solFour_four-two"></td>
								<td id="solFour_four-one"></td>
							</tr>
							<tr>
								<td id="solFour_five-five"></td>
								<td id="solFour_five-four"></td>
								<td id="solFour_five-three"></td>
								<td id="solFour_five-two"></td>
								<td id="solFour_five-one"></td>
							</tr>
						</table>
						<div class="newLine"></div>
						<table class="grid left">
							<tr>
								<td id="uaFive_one-one"></td>
								<td id="uaFive_one-two"></td>
								<td id="uaFive_one-three"></td>
								<td id="uaFive_one-four"></td>
								<td id="uaFive_one-five"></td>
							</tr>
							<tr>
								<td id="uaFive_two-one"></td>
								<td id="uaFive_two-two"></td>
								<td id="uaFive_two-three"></td>
								<td id="uaFive_two-four"></td>
								<td id="uaFive_two-five"></td>
							</tr>
							<tr>
								<td id="uaFive_three-one"></td>
								<td id="uaFive_three-two"></td>
								<td id="uaFive_three-three"></td>
								<td id="uaFive_three-four"></td>
								<td id="uaFive_three-five"></td>
							</tr>
							<tr>
								<td id="uaFive_four-one"></td>
								<td id="uaFive_four-two"></td>
								<td id="uaFive_four-three"></td>
								<td id="uaFive_four-four"></td>
								<td id="uaFive_four-five"></td>
							</tr>
							<tr>
								<td id="uaFive_five-one"></td>
								<td id="uaFive_five-two"></td>
								<td id="uaFive_five-three"></td>
								<td id="uaFive_five-four"></td>
								<td id="uaFive_five-five"></td>
							</tr>
						</table>
						<p class="gridNb">Grille 5</p>
						<table class="grid right">
							<tr>
								<td id="solFive_one-five"></td>
								<td id="solFive_one-four"></td>
								<td id="solFive_one-three"></td>
								<td id="solFive_one-two"></td>
								<td id="solFive_one-one"></td>
							</tr>
							<tr>
								<td id="solFive_two-five"></td>
								<td id="solFive_two-four"></td>
								<td id="solFive_two-three"></td>
								<td id="solFive_two-two"></td>
								<td id="solFive_two-one"></td>
							</tr>
							<tr>
								<td id="solFive_three-five"></td>
								<td id="solFive_three-four"></td>
								<td id="solFive_three-three"></td>
								<td id="solFive_three-two"></td>
								<td id="solFive_three-one"></td>
							</tr>
							<tr>
								<td id="solFive_four-five"></td>
								<td id="solFive_four-four"></td>
								<td id="solFive_four-three"></td>
								<td id="solFive_four-two"></td>
								<td id="solFive_four-one"></td>
							</tr>
							<tr>
								<td id="solFive_five-five"></td>
								<td id="solFive_five-four"></td>
								<td id="solFive_five-three"></td>
								<td id="solFive_five-two"></td>
								<td id="solFive_five-one"></td>
							</tr>
						</table>
					</div>

<script type="text/javascript">
		document.getElementById("score").innerHTML = "<?php echo $score; ?>";
		document.getElementById("errors").innerHTML = "<?php echo $errors; ?>";

		var solOne   = "<?php echo $solutions[0]; ?>".split(" ");
		var solTwo   = "<?php echo $solutions[1]; ?>".split(" ");
		var solThree = "<?php echo $solutions[2]; ?>".split(" ");
		var solFour  = "<?php echo $solutions[3]; ?>".split(" ");
		var solFive  = "<?php echo $solutions[4]; ?>".split(" ");
		var uaOne   = "<?php echo $answers[0]; ?>".split(" ");
		var uaTwo   = "<?php echo $answers[1]; ?>".split(" ");
		var uaThree = "<?php echo $answers[2]; ?>".split(" ");
		var uaFour  = "<?php echo $answers[3]; ?>".split(" ");
		var uaFive  = "<?php echo $answers[4]; ?>".split(" ");

		Array.prototype.contains = function(obj) {
			var j = this.length;
		  	while (j--) {
    			if (this[j] == obj)	// == obj ?!
      				return true;
		   	}
	  		return false;
		}
		if (!(uaOne[0] === "")) {
			for (i = 0; i < uaOne.length; i++) {
				if (solOne.contains(uaOne[i]))
					document.getElementById("uaOne_"+uaOne[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
				else
					document.getElementById("uaOne_"+uaOne[i]).innerHTML = '<img class="sol" src="./images/redstar.png" alt="" />';
			}
		}
		if (!(uaTwo[0] === "")) {
			for (i = 0; i < uaTwo.length; i++) {
				if (solTwo.contains(uaTwo[i]))
					document.getElementById("uaTwo_"+uaTwo[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
				else
					document.getElementById("uaTwo_"+uaTwo[i]).innerHTML = '<img class="sol" src="./images/redstar.png" alt="" />';
			}
		}
		if (!(uaThree[0] === "")) {
			for (i = 0; i < uaThree.length; i++) {
				if (solThree.contains(uaThree[i]))
					document.getElementById("uaThree_"+uaThree[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
				else
					document.getElementById("uaThree_"+uaThree[i]).innerHTML = '<img class="sol" src="./images/redstar.png" alt="" />';
			}
		}
		if (!(uaFour[0] === "")) {
			for (i = 0; i < uaFour.length; i++) {
				if (solFour.contains(uaFour[i]))
					document.getElementById("uaFour_"+uaFour[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
				else
					document.getElementById("uaFour_"+uaFour[i]).innerHTML = '<img class="sol" src="./images/redstar.png" alt="" />';
			}
		}
		if (!(uaFive[0] === "")) {
			for (i = 0; i < uaFive.length; i++) {
				if (solFive.contains(uaFive[i]))
					document.getElementById("uaFive_"+uaFive[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
				else
					document.getElementById("uaFive_"+uaFive[i]).innerHTML = '<img class="sol" src="./images/redstar.png" alt="" />';
			}
		}
		for (i = 0; i < solOne.length; i++)
			document.getElementById("solOne_"+solOne[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
		for (i = 0; i < solTwo.length; i++)
			document.getElementById("solTwo_"+solTwo[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
		for (i = 0; i < solThree.length; i++)
			document.getElementById("solThree_"+solThree[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
		for (i = 0; i < solFour.length; i++)
			document.getElementById("solFour_"+solFour[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
		for (i = 0; i < solFive.length; i++)
			document.getElementById("solFive_"+solFive[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';


		/*for (i = 0; i < uaOne.length - 1; i++) {
			if (solOne.contains(uaOne[i]))
				document.getElementById("uaOne_"+uaOne[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
			else
				document.getElementById("uaOne_"+uaOne[i]).innerHTML = '<img class="sol" src="./images/redstar.png" alt="" />';
		}
		for (i = 0; i < uaTwo.length - 1; i++) {
			if (solTwo.contains(uaTwo[i]))
				document.getElementById("uaTwo_"+uaTwo[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
			else
				document.getElementById("uaTwo_"+uaTwo[i]).innerHTML = '<img class="sol" src="./images/redstar.png" alt="" />';
		}
		for (i = 0; i < uaThree.length - 1; i++) {
			if (solThree.contains(uaThree[i]))
				document.getElementById("uaThree_"+uaThree[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
			else
				document.getElementById("uaThree_"+uaThree[i]).innerHTML = '<img class="sol" src="./images/redstar.png" alt="" />';
		}
		for (i = 0; i < uaFour.length - 1; i++) {
			if (solFour.contains(uaFour[i]))
				document.getElementById("uaFour_"+uaFour[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
			else
				document.getElementById("uaFour_"+uaFour[i]).innerHTML = '<img class="sol" src="./images/redstar.png" alt="" />';
		}
		for (i = 0; i < uaFive.length - 1; i++) {
			if (solFive.contains(uaFive[i]))
				document.getElementById("uaFive_"+uaFive[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
			else
				document.getElementById("uaFive_"+uaFive[i]).innerHTML = '<img class="sol" src="./images/redstar.png" alt="" />';
		}
		for (i = 0; i < solOne.length - 1; i++)
			document.getElementById("solOne_"+solOne[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
		for (i = 0; i < solTwo.length - 1; i++)
			document.getElementById("solTwo_"+solTwo[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
		for (i = 0; i < solThree.length - 1; i++)
			document.getElementById("solThree_"+solThree[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
		for (i = 0; i < solFour.length - 1; i++)
			document.getElementById("solFour_"+solFour[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';
		for (i = 0; i < solFive.length - 1; i++)
			document.getElementById("solFive_"+solFive[i]).innerHTML = '<img class="sol" src="./images/star.png" alt="" />';*/

		// DEBUG
		/*alert(uaOne + "\n\n" + solOne);
		alert(uaTwo + "\n\n" + solTwo);
		alert(uaThree + "\n\n" + solThree);
		alert(uaFour + "\n\n" + solFour);
		alert(uaFive + "\n\n" + solFive);
		alert(uaThree[0]);*/
	</script>
 

	<p>&nbsp;</p>
	<a href="http://www.facebook.com/sharer.php?u=http://laxlinux.net/ihm/share.php?id=<?php echo $_GET['id']; ?>&t=MMS+Project+LaXLinux" target="_blank">
			<img class="fb_share" src="./images/fb.png" />
	</a>
	<div class="share_buttons">
		<g:plusone class="gp" size="medium"></g:plusone>
		<br />
		<fb:like class="fb" href="www.laxlinux.net/ihm/" send="false" layout="button_count" colorsheme="light" show_faces="false"></fb:like>
		<br />
		<a href="http://twitter.com/share" class="twitter-share-button" data-text="I scored <?php echo $score; ?> and made <?php echo $errors; ?> errors on " data-via="Attentah" data-count="horizontal">
			Tweet
		</a>
		<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	</div>
	<p id="link-up">
		<a href="#">Haut de la page</a>
	</p>
	</div>
	</div>
			<div class="footer">
			  <div class="line"></div>
				<p>DFHI Informatik Team</p>
			</div>
		</div>
    </body>
</html>
