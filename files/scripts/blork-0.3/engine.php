<? 

/* * * * * * * * * * * * * * * * * * * * * * *

			a Blork engine v0.3 
			Script du moteur 

Des commentaires sont placés tout au long du script pour que vous puissiez
mieux comprendre son fonctionnement, et éventuellement le personnaliser.
Attention cependant, ce script est distribué sous licence GNU GPL, vous devez 
accepter les conditions posées par cette licence et les respecter, ou des actions 
pourraient être engagées contre vous. 

Une copie (en anglais) de la license GNU GPL est disponible dans l'archive qui 
contient le script.

Soyez prudents si vous comptez modifier le script, ne faites pas n'importe quoi
et renseignez vous si vous n'êtes pas sûr de vos actions ou de votre code. Ce 
script permet notamment à un utilisateur de rentrer lui-même des données par le
biais du formulaire prévu à cet effet, ou par l'url directement. 
Pour cette raison vous devez être encore plus vigilant que d'ordinaire, assurez 
vous que votre code est sûr et ne permettra pas à une personne mal intentionnée
de vous causer des problèmes.

* * * * * * * * * * * * * * * * * * * * * * *

a Blork engine
Copyright (C) 2003-2008 zulios@blork.net

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
ETAPE 0 - DECLARATIONS

- Définition de la version du moteur
- Fonctions de base
- NB: Cette étape n'est pas chronométrée, car il s'agit de déclarer des variables
de base et des fonctions indispensables. Il n'y a rien à optimiser ici hormis
les fonctions elles-même; on verra leur rapidité quand elles seront utilisées 
pour le script dans les étapes suivantes.
* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
DEFINITION DE LA VERSION DU SCRIPT
On définit la version du moteur
Les fichiers de config et de listing des erreurs y correspondant seront recherchés
*/
$engine_version="03"; 

// On en profite pour définir des variables de base
$mot_supprime=""; 
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
FONCTIONS DE BASE
Il s'agit de fonctions génériques qui parent à la plupart des situations courantes.
Vous ne devriez pas avoir à y toucher; cette partie est donc réservée de préférence
aux utilisateurs avertis, y compris la fonction qui affiche le formulaire.
Faites bien attention si vous tentez de les bidouiller! Vous ne pourrez vous en
prendre qu'à vous-même en cas de problème.
*/

/*
Fonction envoie_headers() (fonction de base)
Cette fonction permet d'envoyer des entêtes HTTP 
dans le cas où le script serait amené à être arrêté lors de la période 
des vérification préliminaires à la recherche.
*/
function envoie_headers()
	{
	if(!headers_sent())
		{
		echo('
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
            <head>
                <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
                <meta http-equiv="content-script-type" content="text/javascript" />
                <meta name="description" content="UNIX, Linux stuff" />
                <meta name="author" content="Philipp SCHMITT" />
                <meta name="keywords" content="linux, gnu, gnu/linux, html, internet, open-source" />
                <meta name="date" content="2010-03-21T02:22:37+01:00" />
                <link rel="shortcut icon" href="./files/pictures/link/favicon.ico" />
                <link rel="icon" type="image/gif" href="./files/pictures/link/animated_favicon.gif" />
                <link rel="stylesheet" type="text/css" media="screen" title="General Design" href="./files/styles/design.css" />
                <link rel="alternate" type="application/atom+xml" title="LaXLinux Feed" href="./files/atom/laxlinux.xml" />
                <title>LaXLinux.net</title>
            </head>
            <body>
		');
		} 
	}


/*
Fonction erreur_creve() (fonction de base)
Cette fonction est complémentaire à envoie_headers()
Elle permet d'afficher un message d'erreur détaillé pour les erreurs courantes
connues, et empêche le script de fonctionner pour éviter bugs et/ou problèmes
de sécurité.
*/
function erreur_creve($erreur_id,$raison,$engine_version)
	{
	envoie_headers(); 
	require("blork_engine_errors_".$engine_version.".php"); 
	echo("<br /><br />* a Blork engine - arrêt maladie *");
	echo(nl2br($erreurs[$erreur_id]));
	echo("<br /><b>".$raison."</b>");
	echo("<br /><br />
	Pendant que vous corrigez votre erreur, <br />
	ce script et son auteur partiront boire des cocktails sous les cocotiers.<br /> 
	Elle est pas belle la vie?	
	</body>
    </html>
	"); 
	exit();
	}


/*
Fonctions demarre_chrono_you() et demarre_chrono_sys() (fonctions de base)
Ces fonctions sont utilisées pour le debug mode; elles démarrent un chronomètre
qui permettra de mesurer le temps utilisé par le script à différents intervalles.
*/
function demarre_chrono_you()
	{
	if(function_exists("getrusage"))
		{
		$dat=getrusage();
		$utime_before=$dat["ru_utime.tv_sec"]*1e6+$dat["ru_utime.tv_usec"];
		return($utime_before);
		}
	else
		{
		return("0");
		}
	}
	
function demarre_chrono_sys()
	{
	if(function_exists("getrusage"))
		{
	$dat=getrusage();
 	$stime_before=$dat["ru_stime.tv_sec"]*1e6+$dat["ru_stime.tv_usec"];
	return($stime_before); 
		} 
		else
		{
		return("0");
		}
	}


/*
Fonctions affiche_chrono_you() et affiche_chrono_sys() (fonctions de base)
Fonctions complémentaires à demarre_chrono_you() et demarre_chrono_sys(), utilisées pour le debug mode.
Elles retournent le temps utilisé par le script à l'instant où on les lance.
*/
function affiche_chrono_you($debut)
	{ 
	$end=demarre_chrono_you(); 
	$time_taken= ($end - $debut);
	return $time_taken; 
	} 
	
function affiche_chrono_sys($debut)
	{ 
	$end=demarre_chrono_sys(); 
	$time_taken= ($end - $debut);
	return $time_taken; 
	} 


/*
Fonction form_recherche() (fonction de base)
Cette fonction affiche le formulaire basique qui servira à lancer une recherche,
avec une sélection automatique de l'option choisie pour la recherche.
Elle permet également de retraiter ce qui a été entré dans le formulaire,
notamment pour retirer du code ou autre contenu qui pourrait être dangereux.
*/
function form_recherche($contenu,$type)
	{
	$contenu=strip_tags($contenu);
	$contenu=trim($contenu); 
	$contenu=htmlspecialchars($contenu,ENT_COMPAT);

	echo(" <form method=\"get\" action=\"".$_SERVER['PHP_SELF']."\"> ");
	echo("  <p>
                <input type=\"text\" name=\"blork\" value=\"".$contenu."\" maxlength=\"50\" size=\"30\" /> "); 
	echo("      <input type=\"hidden\" name=\"cherche\" value=\"go\" /> ");
	echo("      <input type=\"hidden\" name=\"".strip_tags(session_name())."\" value=\"".strip_tags(session_id())."\" /> ");
	echo("      <input type=\"submit\" value=\"ok\" />
                <br /> "); 
	/*echo("      &nbsp;<input type=\"checkbox\" ");
	
	
       if($type=="exact")
		{
		echo(" checked ");
		}
	

	echo("      value=\"exact\" name=\"type\" /> case sensitive */
    echo("
            </p>
           </form>"); 
	}


/*
Fonction explore() (fonction de base)
Cette fonction liste tous les sous-dossiers qu'elle trouve à partir du chemin qui
lui est indiqué à la base. Elle prend en compte la liste des éventuels dossiers
exclus, donc aucune chance qu'ils ne se retrouvent listés, même temporairement.
*/	
function explore(&$d,$exclu)
	{
    /*foreach($d as $cle=>$lien)*/
    while(list($cle, $lien) = each ($d))
		{
		$fp=opendir("$lien");  
		while($file = readdir($fp))
			{ 
			if($file!='.' && $file!='..')
				{ 	
				$verif=$lien."/".$file; 
                if(
				is_dir($verif) 
				&& !(in_array($verif, $d)) 
				&& !(in_array($verif, $exclu)))
					{ 
					$d[]=$verif;
					}
				}
			}
		closedir($fp);
		}
	}


/*
Fonction affiche_resultats() (fonction de base)
Comme son nom l'indique, elle sert à afficher les résultats
Elle affiche aussi la barre de navigation au besoin pour voir les pages précédentes ou suivantes
*/
function affiche_resultats(
$start,
$maxi,
$nb_resultats,
$nb_fichiers,
$tableau_resultats,
$page,
$blork,
$type,
$rs,
$fich,
$mot_supprime
)
	{
	// On définit les différentes variables qui serviront pour la barre de navigation 
	$finstart=intval($nb_resultats/$maxi); 
	
	// Affichage du message de résultat 
	echo("
	R&eacute;sultats de votre recherche pour <b>".$blork."</b>
	<br /> ".$nb_resultats." ".$rs." sur ".$nb_fichiers." ".$fich.".
	$mot_supprime"); 
	
	if($nb_resultats<=0)
		{
		echo("<br /><br />");
		}
	else
		{
		// On sélectionne les résultats correspondants à la page en cours et on les affiche 
		for($i=$start*$maxi; $i<$start*$maxi+$maxi; $i++)
			{ 
			
			if($i>=$nb_resultats){ break; }
			
			echo("
			<p>
			".($i+1)." - ".$tableau_resultats[$i]."
			</p>
			"); 
			}  
	
		// Et si on a trop de résultats par rapport au nombre à afficher dans une seule page 
		// on met la ou les barre(s) de navigation correspondante(s) 
		if($nb_resultats>$maxi)
			{ 
			echo("<p align=center><br />"); 
			
			// Résultats précédents 
			if($start>=1)
				{ 
				echo("<a href=\"".$page."?blork=".$blork."&type=".$type."&start=".($start-1)."&cherche=go\"><< R&eacute;sultats pr&eacute;c&eacute;dents</a>"); 
				}
			
			// Barre centrale 
			echo(" | ");
		
			// Résultats suivants 
			if($_GET["start"]<$finstart)
				{ 
				echo("<a href=\"".$page."?blork=".$blork."&type=".$type."&start=".($start+1)."&cherche=go\">R&eacute;sultats suivants >></a>"); 
				}
		
			echo("</p>"); 
			} 
		}
	}

/*
Fonction debug_mode() (fonction de base)
Cette fonction affiche les infos relatives au debug mode, s'il a été activé.
A noter que le header et le footer ne sont pas inclus lorsque le debug mode est activé,
afin de voir si le problème vient bien du script en lui-même. Si ce n'est pas le cas,
il est très probable que ce soit le code mis dans un de ces deux fichiers qui provoque des bugs.
*/
function debug_mode($config,$chrono)
	{
	if($config!="oui")
	{ 
		; 
	}
	else
	{ 		
		// Debug mode 
		echo("<br /><br />
		******************************************************<br />
		* <b>DEBUG MODE</b> <br />
		****************************************************** <br /> <br />");
		
		// Chronos 
		echo("<br />*********** <b>Chronos</b> ***********<br />");
		foreach($chrono as $cle=>$resultat)
				{
				echo("<br />".$cle.": ".$resultat.""); 
				}
		
		// Ressources
		echo("<br /><br />*********** <b>Ressources</b> ***********<br />");
		if(function_exists("getrusage"))
			{
			$ze_data=getrusage();
			// Taille de la mémoire swap
			echo("<br /> Taille mémoire swap // ".$ze_data["ru_nswap"].""); 
			// Nombre de pages mémoire utilisées
			echo("<br /> Pages mémoire utilisées // ".$ze_data["ru_majflt"]."");  
			}
		
		if(function_exists("memory_get_usage"))
			{
			$ze_memoire=memory_get_usage();
			echo("<br /> Mémoire utilisée par PHP // ".$ze_memoire."");  
			}
	
		// Fonctions 
		echo("<br /><br /> <br />*********** <b>Fonctions</b> ***********<br />");
		$func_use=get_defined_functions();
		while (list($key, $val) = each($func_use['user'])) 
			{
    		echo("<br />".$key." => ".$val.""); 
			}
		
		// Variables 
		echo("<br /><br /> <br />*********** <b>Variables</b> ***********");
		
			// GET
			echo("<br /><br />// <b>GET</b>"); 		
			while (list($key, $val) = each($_GET)) 
				{
	    		echo("<br />".$key." => ".$val.""); 
				}
				
			// POST
			echo("<br /><br />// <b>POST</b>"); 			
			while (list($key, $val) = each($_POST)) 
				{
	    		echo("<br />".$key." => ".$val.""); 
				}

			// COOKIE
			echo("<br /><br />// <b>COOKIE</b>"); 			
			while (list($key, $val) = each($_COOKIE)) 
				{
	    		echo("<br />".$key." => ".$val.""); 
				}

			// SESSION
			echo("<br /><br />// <b>SESSION</b>"); 			
			while (list($key, $val) = each($_SESSION)) 
				{
	    		echo("<br />".$key." => ".$val.""); 
				} 

			// FICHIERS INCLUS 
			echo("<br /><br />// <b>FICHIERS INCLUS</b>"); 	
			$included_files=get_included_files();		
			while (list($key, $val) = each($included_files)) 
				{
	    		echo("<br />".$key." => ".$val.""); 
				}
				
			// SERVEUR
			echo("<br /><br />// <b>SERVEUR</b>"); 			
			while (list($key, $val) = each($_SERVER)) 
				{
	    		echo("<br />".$key." => ".$val.""); 
				}

		// PHPINFO
		echo("<br /><br /><br />*********** <b>PHPinfo</b> ***********<br /><br />");
		phpinfo(); 

		echo("<br /><br />
		******************************************************<br />
		* <b>FIN DU DEBUG MODE</b> <br />
		****************************************************** <br /> <br />");
		
		}
	}
	
	
/*
Fonction debug_footer_basta() (fonction de base)
Une fonction très simple pour lancer le debug mode, mettre le footer et stopper le script
*/
function debug_footer_basta($config,$chrono)
	{
	// On lance le debug mode (ne s'éxécute que si demandé dans le fichier config)  
	debug_mode($config,$chrono);
	
	// On met le footer si le debug mode est désactivé 
	if($config=="oui")
		{
		echo("</body></html>"); 
		} 
	else
		{
		include("blork_engine_bas.html");
		}
	
	// Et puis basta 
	exit;
	}

/* * * * * * * * * * * * * * * * * * * * * * */

	
/* * * * * * * * * * * * * * * * * * * * * * *
FIN DE L'ETAPE 0
* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
ETAPE 1 - VERIFICATIONS PRELIMINAIRES A LA RECHERCHE
- Vérification de la configuration du script
- Retraitement des termes recherchés
* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
DEMARRAGE DE LA SESSION ET LANCEMENT DU CHRONO
On démarre une session, et on lance le chrono
*/
session_start(); 
$chrono_start_you=demarre_chrono_you();
$chrono_start_sys=demarre_chrono_sys();


/* * * * * * * * * * * * * * * * * * * * * * *
INCLUSION DU FICHIER CONFIG 
On inclut la config, après avoir vérifié qu'elle est bien la
On vérifie au passage qu'on possède le fichier de listing des erreurs courantes
*/ 
$var_config=array(
"de configuration"=>"blork_engine_config_".$engine_version.".php",
"de listing des erreurs courantes"=>"blork_engine_errors_".$engine_version.".php",
); 

foreach($var_config as $cle=>$resultat)
	{
	if(!file_exists($resultat))
		{
		envoie_headers(); 
		echo("<br /><br /><b>* a Blork engine - arrêt maladie *</b>");
		echo("<br />Un fichier indispensable au bon fonctionnement du script n'a pas pu être trouvé."); 
		echo("<br />Il a peut-être été déplacé, renommé ou effacé.");
		echo("<br /><br /><b>Fichier non trouvé: fichier ".$cle." (".$resultat.")</b>");
		echo("<br /><br />
		Pendant que vous le cherchez, <br />
		ce script et son auteur partiront boire des cocktails sous les cocotiers.<br /> 
		Elle est pas belle la vie?
		<br /><br />	
        </body></html>
		"); 
		exit();	
		}
	} 

require("blork_engine_config_".$engine_version.".php"); 
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
VERIFICATION DE LA CONFIGURATION DU SCRIPT
*/ 

// Vérification de la version PHP 
$version_php=phpversion(); 
$version_php=str_replace(".","",$version_php); 
if($version_php<433)
	{
	erreur_creve("version_php","Version actuelle : ".phpversion()."",$engine_version);	
	} 

// Vérification de la présence des fichiers nécessaires
for($i=0; $i<count($config['fichiers']); $i++)
	{
	if(!file_exists($config['fichiers'][$i]))
		{
		erreur_creve("fichier_introuvable","Fichier non trouvé: ".$config['fichiers'][$i]."",$engine_version); 	
		} 
	} 

// Vérification du nombre de résultats à afficher par page 
if(empty($config['maxipage']) 
|| !is_numeric($config['maxipage'])
	)
	{ 
	erreur_creve("config_incomplete","Nombre de résultats à afficher par page non indiqué (étape 1 du fichier de config)<br /> (ou alors c'est pas indiqué par un chiffre...)",$engine_version); 
	}
	
// Vérification du nombre de mots maxi pour la recherche 
if(empty($config['maxmots']) 
|| !is_numeric($config['maxmots'])
|| $config['maxmots']>9
	)
	{ 
	erreur_creve("config_incomplete","Nombre de mots maxi pour une recherche non indiqué (étape 0 du fichier de config)<br /> (ou alors il n'est soit pas indiqué par un chiffre, soit supérieur à 9...)",$engine_version); 
	}

// Vérification que les variables diverses sont bien indiquées 
$var_config_ouinon=array(
"de configuration du scan des sous-dossiers (étape 3)"=>"$config[scan_sousdos]",
"qui indique si les extensions des fichiers doivent être affichées (étape 5)"=>"$config[montre_ext]",
"qui indique si le debug mode doit être activé (fin du fichier, partie experts)"=>"$config[debug_mode]",
); 

$var_config_sketuveu=array(
"de la version du fichier de config (avant l'étape 1)"=>"$config[version]",
"qui indique les dossiers à scanner (étape 2)"=>"$dossier",
"qui indique l'url à utiliser pour les liens vers les fichiers (étape 5)"=>"$go2url",
"qui indique les types de fichiers texte à scanner (fin du fichier, partie experts)"=>"$liste_extensions[txt]",
"qui indique les types de fichiers image à scanner(fin du fichier, partie experts)"=>"$liste_extensions[img]",
); 

foreach($var_config_ouinon as $cle=>$resultat)
	{
	if(empty($resultat))
		{
		erreur_creve("config_incomplete","La variable ".$cle." est vide.<br /> Recherchez la dans le fichier de config et complétez la pour que le script fonctionne à nouveau.<br />",$engine_version);
		}
	if($resultat!="oui" && $resultat!="non")
		{
		erreur_creve("config_incomplete","La variable ".$cle." doit être renseignée par oui ou non.<br /> Recherchez la dans le fichier de config,<br /> vous avez indiqué \"".$resultat."\" à la place.",$engine_version);
		}
	} 
	
foreach($var_config_sketuveu as $cle=>$resultat)
	{
	if(empty($resultat))
		{
		erreur_creve("config_incomplete","La variable ".$cle." est vide.<br /> Recherchez la dans le fichier de config et complétez la pour que le script fonctionne à nouveau.<br />",$engine_version);
		}
	} 
	
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
On crée les variables par défaut si elles n'existent pas 
*/

if(!isset($_GET['start']) 
|| empty($_GET['start']) 
|| $_GET['start']<0
)
	{ 
	$_GET['start']="0"; 
	} 
	
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Chrono: Vérification de la config 
*/
$chrono['Etape 1 // Vérification de la config // Temps user']=affiche_chrono_you($chrono_start_you);
$chrono['Etape 1 // Vérification de la config // Temps système']=affiche_chrono_sys($chrono_start_sys);
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
On vérifie si une recherche est lancée, et si on a toutes les variables nécessaires  
Si ce n'est pas le cas, on met le header, le formulaire, le footer et basta  
*/

if(!isset($_GET['cherche'])
|| !isset($_GET['blork'])
||	empty($_GET['cherche'])
||	empty($_GET['blork'])
|| 	$_GET['cherche']!="go" 
	)
	{
	if($config['debug_mode']=="oui")
		{
		envoie_headers();
		} 
	else
		{
		include("blork_engine_haut.html");
		}
	form_recherche("","");
	debug_footer_basta($config['debug_mode'],$chrono);  
	}

if(	!empty($_GET['type'])  
&&	!in_array($_GET['type'],$config['types_recherche'])
	)
	{ 
	if($config['debug_mode']=="oui")
		{
		envoie_headers();
		} 
	else
		{
		include("blork_engine_haut.html");
		}
	form_recherche("","all");
	debug_footer_basta($config['debug_mode'],$chrono); 
	}


/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Chrono: Recherche lancée et variables trouvées 
*/
$chrono['Etape 1 // Recherche lancée et variables trouvées // Temps user']=affiche_chrono_you($chrono_start_you);
$chrono['Etape 1 // Recherche lancée et variables trouvées // Temps système']=affiche_chrono_sys($chrono_start_sys);
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
La recherche est lancée, on met le header 
mais uniquement à condition que le debug mode soit désactivé 
*/
if($config['debug_mode']=="oui")
	{
	envoie_headers();
	} 
else
	{
	include("blork_engine_haut.html");
	}
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Vérification de la validité de la recherche:
Pour commencer on vérifie la longueur de la recherche, si elle fait moins 
de trois caractères, formulaire, footer, bonne nuit les petits. 
*/
$_GET['blork']=strip_tags($_GET['blork']);
$_GET['blork']=trim($_GET['blork']); 
/*$_GET['blork']=preg_replace(" +", " ", $_GET['blork']); */
$_GET['blork']=preg_replace("/ +/", " ", $_GET['blork']); 


if(strlen($_GET['blork'])<3)
	{ 
	echo("<br />Votre recherche doit comporter au moins trois caract&egrave;res.<br />");
	$_GET['type']="exact";  
	form_recherche($_GET['blork'],$_GET['type']); 
	debug_footer_basta($config['debug_mode'],$chrono);  
	} 

$longueur_blork=strlen($_GET['blork']); 
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Retraitement des termes recherchés:
Si on recherche l'expression exacte, la recherche est identique à ce qui a été tapé
Le texte a été retraité plus haut pour vérifier sa validité, donc pas de souçaï.

Pour les autres types de recherche on sépare les différents mots recherchés
On supprime d'éventuels doublons, puis on supprime les mots qui ne sont pas 
discriminatoires  
*/

if(!empty($_GET['type']) && $_GET['type']=="exact")
	{ 
	$mot_supprime=""; 
	$recherche=array(); 
	$recherche[]=$_GET['blork']; 
	}
	else
	{
	$mot_supprime=""; 
	$recherche=explode(" ",$_GET['blork']);
	$recherche=array_unique($recherche); 
	
	foreach($recherche as $cle=>$mot)
		{
		if(in_array($mot, $mots_courants) || strlen($recherche[$cle])<3)
			{
			$mot_supprime.=" $mot "; 
			unset($recherche[$cle]);
			}
		} 
	
		/* * * * * * * * * * * * * * * * * * * * * * *
		Si on a supprimé des mots on l'indique par un message.
		On réindexe l'array pour éviter des erreurs
		en utilisant array_unshift() puis array_shift() et on vérifie le nombre de mots restants. 
		S'il n'y en a plus on balance le formulaire et au revoir;
		S'il n'en reste qu'un on redéfinit le type de recherche sur 
		"expression exacte" pour gagner du temps ensuite;
		S'il y en a plus de 9 on met un message d'erreur 
		car la clé de résultat devra indiquer le nombre de mots trouvés
		et elle doit indiquer 1 chiffre, qui ne peut pas être égal à 0.
		*/
		if(!empty($mot_supprime))
			{
			$mot_supprime="<br />A été supprimé de votre recherche (trop courant) : <b>".$mot_supprime."</b><br />"; 
			}
	
		if(
			count($recherche)<=0
		|| 	strlen($_GET['blork'])<3 
		)
			{ 
			$_GET['type']=""; 
			form_recherche($_GET['blork'],$_GET['type']);
			debug_footer_basta($config['debug_mode'],$chrono); 
			} 
		elseif(count($recherche)=="1")
			{
			$_GET['type']="now_exact"; 
			array_unshift($recherche,"rien"); 
			array_shift($recherche);  
			}
		elseif(count($recherche)>1)
			{
			$_GET['type']="all"; 
			}	
		elseif(count($recherche)>$config[maxmots])
			{ 
			$_GET['type']=""; 
			echo("<br />Votre recherche doit comporter $config[maxmots] mots au maximum.<br />");
			form_recherche($_GET['blork'],$_GET['type']);
			debug_footer_basta($config['debug_mode'],$chrono); 
			}
		/* * * * * * * * * * * * * * * * * * * * * * */
	}
			
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Chrono: Vérification de la validité de la recherche 
*/
$chrono['Etape 1 // Vérification de la validité de la recherche // Temps user']=affiche_chrono_you($chrono_start_you); 
$chrono['Etape 1 // Vérification de la validité de la recherche // Temps système']=affiche_chrono_sys($chrono_start_sys); 
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
AFFICHAGE DIRECT
Si on a déjà la recherche en mémoire
et qu'il ne s'agit que d'une actualisation de la page
ou d'un passage à une page précédente / suivante
on passe directement à l'affichage des résultats */

if(!empty($_SESSION['ze_blork']) 
&& !empty($_SESSION['ze_type']) 
&& $_SESSION['ze_blork']==$_GET['blork']
&& $_SESSION['ze_type']==$_GET['type'])
	{
	
	// Formulaire du haut 
	form_recherche($_SESSION['ze_blork'],$_SESSION['ze_type']); 	
	
	// Affichage des résultats
	affiche_resultats(
	$_GET['start'],
	$config['maxipage'],
	$_SESSION['ze_nb_results'],
	$_SESSION['ze_nb_fichiers'],
	$_SESSION['ze_results'],
	$_SERVER['PHP_SELF'],
	$_SESSION['ze_blork'],
	$_SESSION['ze_type'],
	$_SESSION['rs'],
	$_SESSION['fich'],
	$mot_supprime
	); 
	
	// Formulaire du bas
	form_recherche($_SESSION['ze_blork'],$_SESSION['ze_type']); 
	
	// Chrono : Finish avec affichage direct 
	$chrono['*** RESULTAT FINAL // Temps user ']=affiche_chrono_you($chrono_start_you); 
	$chrono['*** RESULTAT FINAL // Temps système ']=affiche_chrono_sys($chrono_start_sys); 
	
	// Debug mode, footer et au revoir
	debug_footer_basta($config['debug_mode'],$chrono);  	
	}
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Scan des sous dossiers si on l'a activé 
On vérifie les sous-dossiers à scanner uniquement ici
Ensuite on les rajoute à la liste 
Comme ça après on n'aura plus qu'a faire un scan classique 
Sur tous les dossiers de la liste 
*/

if($config['scan_sousdos']=="oui")
	{
	explore($dossier,$exclu); 
	
	/* * * * * * * * * * * * * * * * * * * * * * *
	Chrono: Recherche des sous-dossiers 
	*/
	$chrono['Etape 1 // Recherche des sous-dossiers // Temps user']=affiche_chrono_you($chrono_start_you); 
	$chrono['Etape 1 // Recherche des sous-dossiers // Temps système']=affiche_chrono_sys($chrono_start_sys);
	/* * * * * * * * * * * * * * * * * * * * * * */
	} 
	
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Chrono: Etape 1 
*/
$chrono['*** ETAPE 1 TERMINEE // Temps user ***']=affiche_chrono_you($chrono_start_you); 
$chrono['*** ETAPE 1 TERMINEE // Temps système ***']=affiche_chrono_sys($chrono_start_sys); 
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
FIN DE L'ETAPE 1
* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
ETAPE 2 - RECHERCHE 
- Retraitement des fichiers et calcul des résultats
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Résultats à 0
*/
$compteresultats="0"; 
$zetotal="0"; 
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Passage en minuscules de la recherche
ça nous permettra d'utiliser des fonctions sensibles à la casse
(elles sont plus rapides que celles qui cherchent indifféremment majuscules et minuscules)
*/
foreach($recherche as $key=>$value)
	{
	$recherche[$key]=strtolower($value);
	}
/* * * * * * * * * * * * * * * * * * * * * * */
	

/* * * * * * * * * * * * * * * * * * * * * * *
On lance le scan sur les dossiers de la liste 
Les sous-dossiers ont été rajoutés à la liste précédemment si l'option est activée
Du coup on ne scanne pas les dossiers qu'on trouve
*/

foreach($dossier as $nomdos=>$d)
	{ 

	/* 
	Sésame ouvre toi
	*/ 
	
	$fp=opendir("$d"); 
	while($file = readdir($fp))
		{ 
		if($file=="." || $file==".." || is_dir($file))
			{ 
			continue; 
			} 

		/*
		Pour aller plus vite on va chercher à zapper les fichiers qui ne nous intéressent pas
		On commence par vérifier s'il s'agit d'un fichier exclu
		*/
 
		if(in_array($file, $exclu))
			{ 
			continue; 
			} 

		/* 
		C'est pas un fichier exclu.
		On récupère l'extension, et on va vérifier si c'est un type de fichier 
		qu'on peut scanner; autrement, hop on zappe.  
		(un grand merci à Frédéric Bouchery pour ce regex :-)
		
		Au passage : détermination du type de fichier 
		On ne vérifiera que le nom des fichiers de type "img" (image) 
		alors que les fichiers de type "texte" seront entièrement retraités 
		car considérés comme contenant du texte lisible par le moteur. 
		*/
 
		/*$ext=ereg_replace('^.*[.]([^.]*)$', '\\1', $file); */
        $ext=preg_replace('/^.*[.]([^.]*)$/', '\\1', $file);

		if(in_array($ext,$liste_extensions['txt']))
			{ 
			$filetype="texte"; 
			} 
		elseif(in_array($ext,$liste_extensions['img']))
			{ 
			$filetype="img"; 
			$titre=strtolower($file); 
			} 
		else
			{
			continue;
			}
			
		/*
		Maintenant qu'on a déterminé la place de notre fichier entre les deux types 
		On va appliquer des retraitements préliminaires sur les fichiers 
		de type "texte" uniquement.
		Pour aller plus vite on zappe les fichiers image directement.
		*/
 
		if($filetype=="texte")
			{ 
	
			/* 
			On récupère le contenu du fichier
			*/    
	
			$recupere_le_fichier=fopen("$d/$file","r"); 
			$tout=fread($recupere_le_fichier,500000); 
			fclose($recupere_le_fichier); 
			
			/* 
			Passage en minuscules du texte
			La encore il s'agit de pouvoir par la suite utiliser des fonctions 
			sensibles à la casse et gagner du temps.
			*/
			 
			$tout=strtolower($tout); 
			
			/* 
			On vire le html et le php, 
			sauf quelques balises qui nous intéressent pour les infos qu'elles contiennent
			ou parce que ce serait mal fait et qu'on les supprimera par une autre fonction ensuite
			*/

			$tout=strip_tags($tout,'<title></title><script></script><head></head><style></style><select></select>'); 
	
			/* 
			On récupère le titre du fichier, ou alors on affiche le nom avec l'extension 
			Puis on supprime le titre pour ne pas fausser les résultats lors du calcul.
			*/ 
			
			if(strstr($tout,"<title>") && strstr($tout,"</title>"))
				{ 
				$titre1=strstr($tout,'<title>'); 
				$titre2=strstr($tout,'</title>'); 
				$titre1=str_replace("$titre2","",$titre1); 
				$titre1=str_replace("<title>","",$titre1); 
					if($titre1=="")
						{ 
						$titre=$file; 
						} 
					else
						{ 
						$titre=$titre1; 
						}
				}  
			else
				{ 
				$titre=$file; 
				} 

			$titre=strtolower($titre); 
			unset($titre1, $titre2); 
			$tout=preg_replace('`<title.*?/title>`', '', $tout); 

			/* 
			On effectue des remplacements pour pouvoir appliquer les regex : 
			On remplace le saut de ligne par un espace 
			Les &nbsp; (code html pour un espace) sont remplacés par des espaces 
			Les doubles espaces sont remplacés par un simple espace 
			*/
			
			$tout=str_replace("\n"," ",$tout); 
			$tout=str_replace("&nbsp;"," ",$tout); 
			$tout=str_replace("  "," ",$tout); 
			$tout=trim($tout); 
			
			/* 
			On lance les regex:
			On vire le code entre <head> et </head> qui contient en général tout les trucs qui ne nous intéressent pas ici (feuille de style, javascript...) 
			On vire le javascript pour éviter les bugs au cas ou une partie nous aurait échappée 
			On vire les attributs de style pour les mêmes raisons
			Et enfin on vire les listes de type select 
			Merci encore une fois à Frédéric Bouchery pour le regex  
			*/
			
			$tout=preg_replace('`<head.*?/head>`s', '', $tout); 
			$tout=preg_replace('`<script.*?/script>`s', '', $tout); 
			$tout=preg_replace('`<style.*?/style>`s', '', $tout); 
			$tout=preg_replace('`<select.*?/select>`s', '', $tout); 
			
			/*
			On remplace le code html des accents et autres caractères spéciaux 
			par le terme correspondant pour le titre ET le contenu 
			*/
			
			foreach($caractere_special as $caractere_code=>$caractere_traduction)
				{ 
				$tout=str_replace("$caractere_code","$caractere_traduction",$tout); 
				$titre=str_replace("$caractere_code","$caractere_traduction",$titre); 
				} 

			/*
			Fin du retraitement
			*/ 
			}
			
		/* 
		On incrémente le nb de fichiers scannés
		Chrono tous les 50 fichiers scannés
		*/ 
		$zetotal++; 
		
		if(!is_float($zetotal/50))
			{
			$chrono["Etape 2 // Retraitement après ".$zetotal." fichiers // Temps user"]=affiche_chrono_you($chrono_start_you); 
			$chrono["Etape 2 // Retraitement après ".$zetotal." fichiers // Temps système"]=affiche_chrono_sys($chrono_start_sys); 
			}
				

		/*
		Maintenant le fichier a été retraité (si nécessaire), 
		on peut voir s'il contient ce qu'on cherche.
		On adapte le code selon les différents types de recherche
		*/

		switch($_GET['type'])
			{ 
			
			/*
			On adapte le code selon les différents types de recherche
			PLUSIEURS MOTS (type = all)
			*/

			case "all":
				
				/* 
				Si on trouve tous les mots recherchés 
				*/				
			 	 
				$mots_trouves="0"; 
				foreach($recherche as $key=>$value)
					{ 
					if($filetype=="texte" && 
					(
					strstr($tout,$value) 
					|| strstr(strtolower($file),$value)  
					|| strstr(strtolower($titre),$value) 
					))
						{ 
						$mots_trouves++; 
						} 

					elseif($filetype=="img" && strstr(strtolower($file),$value))
						{ 
						$mots_trouves++; 
						} 
					}
					
				if($mots_trouves==count($recherche))
					{
					
					/* 
					Résultats +1
					Si les résultats dépassent le nombre maximum (limité par la clé de résultat à 9 chiffres max),
					on arrête tout et on passe à l'affichage des résultats
					*/ 
					
					$compteresultats++; 
					
					if($compteresultats>9999)
						{ 
						break;
						continue 2; 
						} 

					/*
					Total des occurences à 0
					*/					
					$total_mots="0"; 

					/* 
					S'il s'agit d'un fichier de type "texte"
					*/ 
					
					if($filetype=="texte")
						{ 
	
						/* 
						On compte les occurences du terme 
						Les occurences trouvées dans le titre comptent pour 10 
						(pire qu'au scrabble) car ils sont souvent explicites 
						sur le contenu de la page
						*/ 
	
						$titre_total_mots=array(); 
						$texte_total_mots=array();
						
						foreach($recherche as $key=>$value)
							{ 
							$titre_total_mots[]=intval(substr_count($titre,$value)*10); 
							$texte_total_mots[]=intval(substr_count($tout,$value)); 
							}
						
						$total_mots=array_sum($titre_total_mots)+array_sum($texte_total_mots);
	
						// On crée la description
						// Vu qu'on cherche plusieurs mots une courte description sera faite pour chaque mot   
						
						$resume="... "; 
						
						foreach($recherche as $key=>$value)
							{	
							$position=strpos($tout, $value); 
							$start_position=intval($position-20); 
							if($start_position<0){ $start_position="0"; } 
							$fin_position=intval($longueur_blork+40); 
							
							if($position === FALSE ){ $resume.=""; } 
							else
								{ 
								$resume.=substr($tout, $start_position, $fin_position); 
								$resume.=" ... "; 							    
								} 
							}
						}
	
					// Si c'est une image ou un autre type de fichier 
					// On adapte la description  
					else{ $resume="Fichier $ext"; } 
	
					// On met en gras le terme recherché dans le titre et dans la description 
					// Pas besoin ici de vérifier le type de fichier 
					
					foreach($recherche as $key=>$value)
						{
						$resume=str_replace($value,"<b>$value</b>",$resume);
						$titre=str_replace($value,"<b>$value</b>",$titre);
						} 
		
					// Calcul du pourcentage de pertinence 
					// La pertinence du texte n'est calculée que pour les fichiers texte 
					// Comme on a plusieurs mots on fera une moyenne sur la pertinence de chacun
					
					$p1=array(); 
					$p2=array(); 
					
					// Vérification de la pertinence du texte pour les fichiers texte					
					if($filetype=="texte")
						{ 
						
						foreach($recherche as $key=>$value)
							{
							similar_text($value, $tout, $p1[]);
							}
						}
						else
						{
						$p1[0]="0";
						}
					
					// Vérification de la pertinence du titre pour tous les types de fichiers
					foreach($recherche as $key=>$value)
						{
						similar_text($value, $titre, $p2[]);
						} 
					
					// Calcul des moyennes 
					$p1=array_sum($p1)/count($p1);
					$p2=array_sum($p2)/count($p2);
					
					// Obtention du pourcentage définitif  
					$p=intval($p1+$p2); 
					
					// Si le pourcentage est supérieur ou égal à 100 on le ramène à 99 
					
					if($p>=100){ $p="99"; }  
					
					// On va créer une clé identique pour chaque résultat.
					// Le premier sera un "1", pour que la clé soit réindexée 
					// Le suivant sera le nombre d'occurences total de mots trouvés (en dizaines) 
					// Ensuite le pourcentage de similarité du texte + celui du titre (deux chiffres) 
					// Enfin le numéro du résultat (4 chiffres) 
					// Avec cette clé on pourra classer les résultats par ordre décroissant selon le chiffre obtenu, donc par pertinence. 
					
					// Notes : 
					// La clé ne doit pas commencer par 0 donc il était important de mettre en premier 
					// un "1", ou un chiffre supérieur à 0 en tout cas.
					// La clé ne doit pas être supérieure à 9 chiffres, sinon elle ne sera pas réindexée. 
					
					// Cette bidouille me permettra par la suite avec array_unshift() de réindexer le tableau avec 
					// des clés numériques pour pouvoir afficher uniquement les résultats souhaités, donc j'économise 
					// du temps d'éxécution et des ressources par rapport à l'ancienne méthode qui consistait à créer
					// un nouveau tableau. L'array_unshift() me rajoutera une valeur de clé 0 que je ne supprime pas 
					// parce que je pourrai ainsi gérer mes résultats à partir de 1, ce qui est plus logique. 
					
					// On ramène les occurences au maxi à 99 
					// Puis on rajoute un 0 devant le chiffre s'il est inférieur à 10 
					// Enfin on ne garde que le chiffre des dizaines 
					
					if($total_mots>=100){ $total_mots="99"; } 
					if (strlen($total_mots)==1){ 
					$total_mots=str_repeat("0",2-strlen($total_mots)).$total_mots; } 
					
					// Idem pour les pourcentages, 
					// à la différence qu'on s'assure que le pourcentage soit à deux chiffres
					// histoire de ne pas fausser les résultats 
					
					if($p>=100){ $p="99"; } 
					if (strlen($p)==1){ 
					$p=str_repeat("0",2-strlen($p)).$p; } 
					
					// Et enfin le numéro du résultat 
					
					$compteresultats2=$compteresultats; 
					if (strlen($compteresultats2)<4){ 
					$compteresultats2=str_repeat("0",4-strlen($compteresultats2)).$compteresultats2; } 
					
					
					// On met la première lettre du titre en majuscules 
					$titre=ucfirst($titre); 
	
	
					// URL par défaut pour les fichiers 
					
					// On vire l'extension si besoin 
					if($config["montre_ext"]=="non"){ 
					$file=str_replace(".$ext","",$file); } 

					// On remet les caractères spéciaux spécial URL pour éviter des bugs avec IE 
					$file=rawurlencode($file); 
					
					if($go2url==""){ $go_2_url="$d/$file"; } 
					else{ 
					$go_2_url="$go2url";
					$go_2_url=str_replace("[dossier]",$d,$go_2_url); 
					$go_2_url=str_replace("[fichier]",$file,$go_2_url); } 
	
	
					// Source du résultat 
					
					$src=" <a href=\"$go_2_url\">$titre</a> <br /> 
					$resume
					
					"; 
					
					// On enregistre 
					$zeresults["1".$total_mots."".$p."".$compteresultats2]="$src"; 
					
									}
					
					// On remet a zéro histoire d'éviter des doublons 
					unset(
					$compteresultats2,
					$tout,
					$resume,
					$src,
					$titre,
					$filetype,
					$p,
					$p1,
					$p2,
					$file,
					$ext,
					$total_mots,
					$register
					); 
				
				/* 
				FIN RECHERCHE SUR PLUSIEURSMOTS
				*/
			
				break;
	

			/*
			On adapte le code selon les différents types de recherche
			EXPRESSION EXACTE (type = exact)
			*/
			
			case "exact":

				/* 
				Si on trouve la recherche
				*/				
			 	 
				if((!empty($tout) && strstr($tout,$recherche[0]))
					|| strstr(strtolower($file),$recherche[0])  
					|| strstr(strtolower($file),$recherche[0]) 
					)
					{ 
					
					/* 
					Résultats +1
					Si les résultats dépassent le nombre maximum (limité par la clé de résultat à 9 chiffres max),
					on arrête tout et on passe à l'affichage des résultats. ça nous limite à 9999 résultats pour 
					pouvoir utiliser la clé numérique.
					*/ 
					
					$compteresultats++; 
					
					if($compteresultats>9999)
						{ 
						break;
						continue 2; 
						} 
	
					/* 
					S'il s'agit d'un fichier de type "texte"
					*/ 
					
					if($filetype=="texte")
						{ 
	
						/* 
						On compte les occurences du terme 
						Les occurences trouvées dans le titre comptent pour 10 
						(pire qu'au scrabble) car ils sont souvent explicites 
						sur le contenu de la page
						*/ 
	
						$total_mots="0"; 
						$total_mots=intval(substr_count($titre,$recherche[0])*10+$total_mots); 
						$total_mots=intval(substr_count($tout,$recherche[0])+$total_mots); 
	
						// On crée la description 
	
						$position=strpos($tout, $recherche[0]); 
						$start_position=intval($position-50); 
						if($start_position<0){ $start_position="0"; } 
						$fin_position=intval($longueur_blork+100); 
						
						$resume=""; 
						if($position === FALSE )
							{ 
							$resume.="Terme exact introuvable dans le contenu du fichier."; 
							} 
						else
							{ 
							$resume="... "; 
							$resume.=substr($tout, $start_position, $fin_position); 
							$resume.=" ... "; 
	
							// On met en gras le terme recherché dans la description 
							$resume=str_replace($recherche[0],"<b>$recherche[0]</b>",$resume); 
							}    
	
						} 
	
					// Si c'est une image ou un autre type de fichier 
					// On adapte la description et les actions 
					else
						{ 
						$resume="Fichier $ext"; 
						$total_mots="0"; 
						$total_mots=intval(substr_count($titre,$recherche[0])*10+$total_mots); 
						} 
	
					// On met en gras le terme recherché dans le titre 
					// Pas besoin ici de vérifier le type de fichier
					$titre=str_replace($recherche[0],"<b>$recherche[0]</b>",$titre); 
	
	
					// Calcul du pourcentage de pertinence 
					// La pertinence du texte n'est calculée que pour les fichiers texte 
					
					if($filetype=="texte")
						{ 
						similar_text($recherche[0], $tout, $p1);
						}
						else
						{
						$p1="0";
						}
					 
					similar_text($recherche[0], $titre, $p2); 
					$p=intval($p1+$p2); 
					
					// Si le pourcentage est supérieur ou égal à 100 on le ramène à 99 
					
					if($p>=100){ $p="99"; }  
					
					// On va créer une clé identique pour chaque résultat.
					// Le premier sera un "1", pour que la clé soit réindexée 
					// Le suivant sera le nombre d'occurences total de mots trouvés (en dizaines) 
					// Ensuite le pourcentage de similarité du texte + celui du titre (deux chiffres) 
					// Enfin le numéro du résultat (4 chiffres) 
					// Avec cette clé on pourra classer les résultats par ordre décroissant selon le chiffre obtenu, donc par pertinence. 
					
					// Notes : 
					// La clé ne doit pas commencer par 0 donc il était important de mettre en premier 
					// un "1", ou un chiffre supérieur à 0 en tout cas.
					// La clé ne doit pas être supérieure à 9 chiffres, sinon elle ne sera pas réindexée. 
					
					// Cette bidouille me permettra par la suite avec array_unshift() de réindexer le tableau avec 
					// des clés numériques pour pouvoir afficher uniquement les résultats souhaités, donc j'économise 
					// du temps d'éxécution et des ressources par rapport à l'ancienne méthode qui consistait à créer
					// un nouveau tableau. L'array_unshift() me rajoutera une valeur de clé 0 que je supprimerai 
					// avec array_shift() parce que je pourrai ainsi gérer mes résultats à partir de 0 à nouveau
					// (c'est nécessaire pour le calcul des résultats à afficher)  
					
					// On ramène les occurences au maxi à 99 
					// Puis on rajoute un 0 devant le chiffre s'il est inférieur à 10  
					 
					if($total_mots>=100){ $total_mots="99"; } 
					if (strlen($total_mots)==1){ 
					$total_mots=str_repeat("0",2-strlen($total_mots)).$total_mots; }  
					
					// Idem pour les pourcentages 
					
					if($p>=100){ $p="99"; } 
					if (strlen($p)==1){ 
					$p=str_repeat("0",2-strlen($p)).$p; } 
					
					// Et enfin le numéro du résultat 
					
					$compteresultats2=$compteresultats; 
					if (strlen($compteresultats2)<4){ 
					$compteresultats2=str_repeat("0",4-strlen($compteresultats2)).$compteresultats2; } 
					
					
					// On met la première lettre du titre en majuscules 
					$titre=ucfirst($titre); 
	
	
					// URL par défaut pour les fichiers 
					
					// On vire l'extension si besoin 
					if($config["montre_ext"]=="non"){ 
					$file=str_replace(".$ext","",$file); } 

					// On remet les caractères spéciaux spécial URL pour éviter des bugs avec IE 
					$file=rawurlencode($file); 
					
					if($go2url==""){ $go_2_url="$d/$file"; } 
					else{ 
					$go_2_url="$go2url";
					$go_2_url=str_replace("[dossier]",$d,$go_2_url); 
					$go_2_url=str_replace("[fichier]",$file,$go_2_url); } 
	
	
					// Source du résultat 
					
					$src=" <a href=\"$go_2_url\">$titre</a> <br /> 
					$resume
					
					"; 
					
					// On enregistre 
					$zeresults["1".$total_mots."".$p."".$compteresultats2]="$src"; 
					
									}
					
					// On remet a zéro histoire d'éviter des doublons 
					unset(
					$compteresultats2,
					$tout,
					$resume,
					$src,
					$titre,
					$filetype,
					$p,
					$p1,
					$p2,
					$file,
					$ext,
					$total_mots,
					$register
					); 
				
				/* 
				FIN RECHERCHE EXPRESSION EXACTE
				*/	
				break;



			/*
			On adapte le code selon les différents types de recherche
			EXPRESSION EXACTE SUITE A SUPPRESSION DE MOTS (type = now_exact)
			*/
			
			case "now_exact":

				/* 
				Si on trouve la recherche
				*/				
			 	 
				if((!empty($tout) && strstr($tout,$recherche[0]))
					|| strstr(strtolower($file),$recherche[0])  
					|| strstr(strtolower($file),$recherche[0]) 
					)
					{ 
					
					/* 
					Résultats +1
					Si les résultats dépassent le nombre maximum (limité par la clé de résultat à 9 chiffres max),
					on arrête tout et on passe à l'affichage des résultats. ça nous limite à 9999 résultats pour 
					pouvoir utiliser la clé numérique.
					*/ 
					
					$compteresultats++; 
					
					if($compteresultats>9999)
						{ 
						break;
						continue 2; 
						} 
	
					/* 
					S'il s'agit d'un fichier de type "texte"
					*/ 
					
					if($filetype=="texte")
						{ 
	
						/* 
						On compte les occurences du terme 
						Les occurences trouvées dans le titre comptent pour 10 
						(pire qu'au scrabble) car ils sont souvent explicites 
						sur le contenu de la page
						*/ 
	
						$total_mots="0"; 
						$total_mots=intval(substr_count($titre,$recherche[0])*10+$total_mots); 
						$total_mots=intval(substr_count($tout,$recherche[0])+$total_mots); 
	
						// On crée la description 
	
						$position=strpos($tout, $recherche[0]); 
						$start_position=intval($position-50); 
						if($start_position<0){ $start_position="0"; } 
						$fin_position=intval($longueur_blork+100); 
						
						$resume=""; 
						if($position === FALSE )
							{ 
							$resume.="Terme exact introuvable dans le contenu du fichier."; 
							} 
						else
							{ 
							$resume="... "; 
							$resume.=substr($tout, $start_position, $fin_position); 
							$resume.=" ... "; 
	
							// On met en gras le terme recherché dans la description 
							$resume=str_replace($recherche[0],"<b>$recherche[0]</b>",$resume); 
							}    
	
						} 
	
					// Si c'est une image ou un autre type de fichier 
					// On adapte la description et les actions 
					else
						{ 
						$resume="Fichier $ext"; 
						$total_mots="0"; 
						$total_mots=intval(substr_count($titre,$recherche[0])*10+$total_mots); 
						} 
	
					// On met en gras le terme recherché dans le titre 
					// Pas besoin ici de vérifier le type de fichier
					$titre=str_replace($recherche[0],"<b>$recherche[0]</b>",$titre); 
	
	
					// Calcul du pourcentage de pertinence 
					// La pertinence du texte n'est calculée que pour les fichiers texte 
					
					if($filetype=="texte")
						{ 
						similar_text($recherche[0], $tout, $p1);
						}
						else
						{
						$p1="0";
						}
					 
					similar_text($recherche[0], $titre, $p2); 
					$p=intval($p1+$p2); 
					
					// Si le pourcentage est supérieur ou égal à 100 on le ramène à 99 
					
					if($p>=100){ $p="99"; }  
					
					// On va créer une clé identique pour chaque résultat.
					// Le premier sera un "1", pour que la clé soit réindexée 
					// Le suivant sera le nombre d'occurences total de mots trouvés (en dizaines) 
					// Ensuite le pourcentage de similarité du texte + celui du titre (deux chiffres) 
					// Enfin le numéro du résultat (4 chiffres) 
					// Avec cette clé on pourra classer les résultats par ordre décroissant selon le chiffre obtenu, donc par pertinence. 
					
					// Notes : 
					// La clé ne doit pas commencer par 0 donc il était important de mettre en premier 
					// un "1", ou un chiffre supérieur à 0 en tout cas.
					// La clé ne doit pas être supérieure à 9 chiffres, sinon elle ne sera pas réindexée. 
					
					// Cette bidouille me permettra par la suite avec array_unshift() de réindexer le tableau avec 
					// des clés numériques pour pouvoir afficher uniquement les résultats souhaités, donc j'économise 
					// du temps d'éxécution et des ressources par rapport à l'ancienne méthode qui consistait à créer
					// un nouveau tableau. L'array_unshift() me rajoutera une valeur de clé 0 que je supprimerai 
					// avec array_shift() parce que je pourrai ainsi gérer mes résultats à partir de 0 à nouveau
					// (c'est nécessaire pour le calcul des résultats à afficher)  
					
					// On ramène les occurences au maxi à 99 
					// Puis on rajoute un 0 devant le chiffre s'il est inférieur à 10  
					 
					if($total_mots>=100){ $total_mots="99"; } 
					if (strlen($total_mots)==1){ 
					$total_mots=str_repeat("0",2-strlen($total_mots)).$total_mots; }  
					
					// Idem pour les pourcentages 
					
					if($p>=100){ $p="99"; } 
					if (strlen($p)==1){ 
					$p=str_repeat("0",2-strlen($p)).$p; } 
					
					// Et enfin le numéro du résultat 
					
					$compteresultats2=$compteresultats; 
					if (strlen($compteresultats2)<4){ 
					$compteresultats2=str_repeat("0",4-strlen($compteresultats2)).$compteresultats2; } 
					
					
					// On met la première lettre du titre en majuscules 
					$titre=ucfirst($titre); 
	
	
					// URL par défaut pour les fichiers 
					
					// On vire l'extension si besoin 
					if($config["montre_ext"]=="non"){ 
					$file=str_replace(".$ext","",$file); } 

					// On remet les caractères spéciaux spécial URL pour éviter des bugs avec IE 
					$file=rawurlencode($file); 
					
					if($go2url==""){ $go_2_url="$d/$file"; } 
					else{ 
					$go_2_url="$go2url";
					$go_2_url=str_replace("[dossier]",$d,$go_2_url); 
					$go_2_url=str_replace("[fichier]",$file,$go_2_url); } 
	
	
					// Source du résultat 
					
					$src=" <a href=\"$go_2_url\">$titre</a> <br /> 
					$resume
					
					"; 
					
					// On enregistre 
					$zeresults["1".$total_mots."".$p."".$compteresultats2]="$src"; 
					
									}
					
					// On remet a zéro histoire d'éviter des doublons 
					unset(
					$compteresultats2,
					$tout,
					$resume,
					$src,
					$titre,
					$filetype,
					$p,
					$p1,
					$p2,
					$file,
					$ext,
					$total_mots,
					$register
					); 
				
				/* 
				FIN RECHERCHE NOW_EXACT
				*/	
				break;
			
			
			/*
			Par défaut, si le type ne correspond à rien de connu 
			on met un message d'erreur
			*/

			default:
				erreur_creve("type_recherche_inconnu",$_GET['type'],$engine_version); 
				break;
				
		}}   
				
				
	// On referme 
	// Sésame ferme toi 
				
	closedir($fp); 
	unset($tout,$filetype,$fp,$ext); 
	} 

/***************************************************
Chrono: Fin de l'étape 2
*/
$chrono['*** ETAPE 2 TERMINEE // Temps user ***']=affiche_chrono_you($chrono_start_you); 
$chrono['*** ETAPE 2 TERMINEE // Temps système ***']=affiche_chrono_sys($chrono_start_sys); 
/**************************************************/
				

/* * * * * * * * * * * * * * * * * * * * * * *
FIN DE L'ETAPE 2
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
ETAPE 3 - AFFICHAGE ET SAUVEGARDE DES RESULTATS 
/* * * * * * * * * * * * * * * * * * * * * * */	

// On enregistre une première partie des données dans les variables de session 
// ça nous servira à lancer directement l'affichage 
// en cas d'actualisation de la page ou de passage à la page de résultats précédente / suivante

$_SESSION['ze_blork']=$_GET['blork']; 			// Texte de la recherche (non retraité)  
$_SESSION['ze_type']=$_GET['type'];				// Type de recherche 
$_SESSION['ze_nb_results']=$compteresultats;	// Nombre de résultats 
$_SESSION['ze_nb_fichiers']=$zetotal; 			// Nombre de fichiers scannés 

// Changement du texte selon les résultats 
// Entre singulier et pluriel 

$rs=" r&eacute;sultat trouv&eacute; ";
$fich=" fichier"; 
if($compteresultats>1){ $rs=" r&eacute;sultats trouv&eacute;s "; }
if($zetotal>1){ $fich=" fichiers"; } 

// Si nous n'avons aucun résultat, on affiche un message, les formulaires, le footer et au revoir
if($compteresultats<=0)
	{	
	// On définit la variable zeresults (qui normalement contient la description des résultats)
	// pour éviter une erreur (logique, y'a pas de résultats) 
	$zeresults=array();  	
	
	// Formulaire du haut
	form_recherche($_GET['blork'],$_GET['type']); 
	
	// Message 
	affiche_resultats(
	$_GET['start'],
	$config['maxipage'],
	$compteresultats,
	$zetotal,
	$zeresults,
	$_SERVER['PHP_SELF'],
	$_GET['blork'],
	$_GET["type"],
	$rs,
	$fich,
	$mot_supprime
	);
	 
	// Formulaire du bas 
	form_recherche($_GET['blork'],$_GET['type']); 
	
	// Chrono: Finish sans résultat 
	$chrono['*** RESULTAT FINAL // Temps user ']=affiche_chrono_you($chrono_start_you); 
	$chrono['*** RESULTAT FINAL // Temps système ']=affiche_chrono_sys($chrono_start_sys); 
	
	// Debug, footer et basta 
	debug_footer_basta($config['debug_mode'],$chrono); 
	} 

// Maintenant qu'on est sûr d'avoir des résultats on va les retraiter 
// On les classe par ordre décroissant de pertinence 
// Ensuite on lance un array_unshift() qui réindexe le tableau 
// ce qui nous permet de remplacer les clés numériques par des nombres classiques 
// et on supprime la valeur créée avec array_shift() 
// pour gérer les résultats à partir de 0 
// ça nous servira pour l'affichage et a barre de navigation parce qu'on calcule 
// numéro de page * nombre de fichiers à afficher par page pour savoir 
// à quel numéro on commence l'affichage des résultats
// Si les résultats étaient gérés à partir de 1 on ne pourrait pas afficher
// les résultats de 0 à 10 !
	
krsort($zeresults); 
array_unshift($zeresults,"rien");  
array_shift($zeresults); 

// Suite et fin de l'enregistrement des données dans les variables de session 
// ça nous servira à lancer directement l'affichage 
// en cas d'actualisation de la page ou de passage à la page de résultats précédente / suivante

$_SESSION['ze_results']=$zeresults; 	// Contenu des résultats 
$_SESSION['rs']=$rs; 					// Texte pour l'affichage du message de résultats (1ère partie) 
$_SESSION['fich']=$fich; 				// Texte pour l'affichage du message de résultats (2ème partie)  

// Maintenant on commence l'affichage 
// Formulaire du haut 
form_recherche($_GET['blork'],$_GET['type']); 

// On affiche les résultats 
affiche_resultats(
$_GET['start'],
$config['maxipage'],
$compteresultats,
$zetotal,
$zeresults,
$_SERVER['PHP_SELF'],
$_GET['blork'],
$_GET["type"],
$rs,
$fich,
$mot_supprime
);

// Formulaire du bas 
form_recherche($_GET['blork'],$_GET['type']); 


/***************************************************
Chrono: Finish avec résultats 
*/
$chrono['*** RESULTAT FINAL // Temps user']=affiche_chrono_you($chrono_start_you); 
$chrono['*** RESULTAT FINAL // Temps système']=affiche_chrono_sys($chrono_start_sys); 
/**************************************************/


// Debug mode, footer et c'est fini ! 
debug_footer_basta($config['debug_mode'],$chrono); 
?>
