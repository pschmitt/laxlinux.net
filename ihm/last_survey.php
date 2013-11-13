<!DOCTYPE html>
<html>
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>MMS Project | Questionnaire</title>
        <link rel="stylesheet" type="text/css" href="./css/styles.css" />
		<link rel="shortcut icon" href="./images/Pie-Chart.png" />
		<link rel="icon" type="image/png" href="./images/Pie-Chart.png" />
		<!--[if IE]>
			<style type="hidden/css">
			.clear {
			  zoom: 1;
			  display: block;
			}
			</style>
        <![endif]-->
    </head>	
	<body>
	    <div class="section" id="page">
            <div class="header">
				<a href="./index.html">
					<h1>Jeu de mémoire</h1>
				</a>
            </div>
            <div class="section" id="articles">
                <div class="line"></div>
                <div class="main">
                    <h2>Questionnaire</h2>
                    <div class="line"></div>
                    <div class="articleBody clear">
						<p>
							<em>
								Merci d'avoir répondu à notre questionnaire !
								<br/>
								Après cette dernière question vous aurez accès à vos résultats detaillés.
							</em>
							<br/>
							<br/>
							Votre score est de <span class="big"><?php echo $_POST['score']; ?></span>, soit un taux de réussite de <span class="big"><?php $percentage = round($_POST['score']*100/30, 2); echo $percentage."%";?></span>.
						</p>
						<script type="text/javascript" src="./scripts/formValidation_2.js"></script>
						<form action="results.php" method="post" name="last_survey_form" onsubmit="return validateForm()">
						<fieldset>
							<input type="hidden" name="this_id"  value="<?php echo $_POST['this_id']; ?>" />
							<input type="hidden" name="score"  value="<?php echo $_POST['score']; ?>" />
							<input type="hidden" name="errors"  value="<?php echo $_POST['errors']; ?>" />	
							<input type="hidden" name="wperiod"  value="<?php echo $_POST['wperiod']; ?>" />
							<input type="hidden" name="wbplay"  value="<?php echo $_POST['wbplay']; ?>" />	
							<input type="hidden" name="junk"  value="<?php echo $_POST['junk']; ?>" />
							<input type="hidden" name="solOne"  value="<?php echo $_POST['solOne']; ?>" />	
							<input type="hidden" name="solTwo"  value="<?php echo $_POST['solTwo']; ?>" />
							<input type="hidden" name="solThree"  value="<?php echo $_POST['solThree']; ?>" />	
							<input type="hidden" name="solFour"  value="<?php echo $_POST['solFour']; ?>" />
							<input type="hidden" name="solFive"  value="<?php echo $_POST['solFive']; ?>" />	
							<input type="hidden" name="uaOne"  value="<?php echo $_POST['uaOne']; ?>" />
							<input type="hidden" name="uaTwo"  value="<?php echo $_POST['uaTwo']; ?>" />	
							<input type="hidden" name="uaThree"  value="<?php echo $_POST['uaThree']; ?>" />
							<input type="hidden" name="uaFour"  value="<?php echo $_POST['uaFour']; ?>" />	
							<input type="hidden" name="uaFive"  value="<?php echo $_POST['uaFive']; ?>" />
							
							<input type="hidden" name="gender" value="<?php echo $_POST['gender']; ?>" />	
							<input type="hidden" name="byear" value="<?php echo $_POST['byear']; ?>" />
							<input type="hidden" name="lang" value="<?php echo $_POST['lang']; ?>" />		
							<input type="hidden" name="technology" value="<?php echo $_POST['technology']; ?>" />		

							<input type="hidden" name="difficulty" value="<?php echo $_POST['difficulty']; ?>" />		
							<input type="hidden" name="mental" value="<?php echo $_POST['mental']; ?>" />
							<input type="hidden" name="physic" value="<?php echo $_POST['physic']; ?>" />	
							<input type="hidden" name="pressure" value="<?php echo $_POST['pressure']; ?>" />
							<input type="hidden" name="perf" value="<?php echo $_POST['perf']; ?>" />	
							<input type="hidden" name="effort" value="<?php echo $_POST['effort']; ?>" />
							<input type="hidden" name="frust" value="<?php echo $_POST['frust']; ?>" />

							<input type="hidden" name="wperiod_estimation"  value="<?php echo $_POST['wperiod_estimation']; ?>" />
							<input type="hidden" name="wperiod_foc" value="<?php echo $_POST['wperiod_foc']; ?>" />	
							<input type="hidden" name="wperiod_ok" value="<?php echo $_POST['wperiod_ok']; ?>" />	
							<input type="hidden" name="wperiod_satisf" value = "<?php echo $_POST['wperiod_satisf'] ?>" />
							<input type="hidden" name="wperiod_just" value="<?php echo $_POST['wperiod_just']; ?>" />
							<input type="hidden" name="wperiod_stimul" value="<?php echo $_POST['wperiod_stimul']; ?>" />							
						<p>
							Etes vous satisfait(e) de votre score ?
						</p>
						<div class="radio">
							Pas du tout satisfait
							<input id="score_satisfOne"   type="radio" name="score_satisf" value="1">
							<input id="score_satisfTwo"   type="radio" name="score_satisf" value="2">
							<input id="score_satisfThree" type="radio" name="score_satisf" value="3">
							<input id="score_satisfFour"  type="radio" name="score_satisf" value="4">
							<input id="score_satisfFive"  type="radio" name="score_satisf" value="5">
							<input id="score_satisfSix"   type="radio" name="score_satisf" value="6">
							<input id="score_satisfSeven" type="radio" name="score_satisf" value="7">
							Tout à fait satisfait
						</div>
						</fieldset>
						<input type="submit" value="Valider" id="start">
					</div>
                </div>
            </div>
			<div class="footer">
			  <div class="line"></div>
				<p>DFHI Informatik Team</p>
			</div>
		</div>	
    </body>
</html>
