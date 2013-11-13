<? 

/* * * * * * * * * * * * * * * * * * * * * * *

			a Blork engine v0.3 
			Script du moteur 

Des commentaires sont plac�s tout au long du script pour que vous puissiez
mieux comprendre son fonctionnement, et �ventuellement le personnaliser.
Attention cependant, ce script est distribu� sous licence GNU GPL, vous devez 
accepter les conditions pos�es par cette licence et les respecter, ou des actions 
pourraient �tre engag�es contre vous. 

Une copie (en anglais) de la license GNU GPL est disponible dans l'archive qui 
contient le script.

Soyez prudents si vous comptez modifier le script, ne faites pas n'importe quoi
et renseignez vous si vous n'�tes pas s�r de vos actions ou de votre code. Ce 
script permet notamment � un utilisateur de rentrer lui-m�me des donn�es par le
biais du formulaire pr�vu � cet effet, ou par l'url directement. 
Pour cette raison vous devez �tre encore plus vigilant que d'ordinaire, assurez 
vous que votre code est s�r et ne permettra pas � une personne mal intentionn�e
de vous causer des probl�mes.

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

- D�finition de la version du moteur
- Fonctions de base
- NB: Cette �tape n'est pas chronom�tr�e, car il s'agit de d�clarer des variables
de base et des fonctions indispensables. Il n'y a rien � optimiser ici hormis
les fonctions elles-m�me; on verra leur rapidit� quand elles seront utilis�es 
pour le script dans les �tapes suivantes.
* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
DEFINITION DE LA VERSION DU SCRIPT
On d�finit la version du moteur
Les fichiers de config et de listing des erreurs y correspondant seront recherch�s
*/
$engine_version="03"; 

// On en profite pour d�finir des variables de base
$mot_supprime=""; 
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
FONCTIONS DE BASE
Il s'agit de fonctions g�n�riques qui parent � la plupart des situations courantes.
Vous ne devriez pas avoir � y toucher; cette partie est donc r�serv�e de pr�f�rence
aux utilisateurs avertis, y compris la fonction qui affiche le formulaire.
Faites bien attention si vous tentez de les bidouiller! Vous ne pourrez vous en
prendre qu'� vous-m�me en cas de probl�me.
*/

/*
Fonction envoie_headers() (fonction de base)
Cette fonction permet d'envoyer des ent�tes HTTP 
dans le cas o� le script serait amen� � �tre arr�t� lors de la p�riode 
des v�rification pr�liminaires � la recherche.
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
Cette fonction est compl�mentaire � envoie_headers()
Elle permet d'afficher un message d'erreur d�taill� pour les erreurs courantes
connues, et emp�che le script de fonctionner pour �viter bugs et/ou probl�mes
de s�curit�.
*/
function erreur_creve($erreur_id,$raison,$engine_version)
	{
	envoie_headers(); 
	require("blork_engine_errors_".$engine_version.".php"); 
	echo("<br /><br />* a Blork engine - arr�t maladie *");
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
Ces fonctions sont utilis�es pour le debug mode; elles d�marrent un chronom�tre
qui permettra de mesurer le temps utilis� par le script � diff�rents intervalles.
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
Fonctions compl�mentaires � demarre_chrono_you() et demarre_chrono_sys(), utilis�es pour le debug mode.
Elles retournent le temps utilis� par le script � l'instant o� on les lance.
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
Cette fonction affiche le formulaire basique qui servira � lancer une recherche,
avec une s�lection automatique de l'option choisie pour la recherche.
Elle permet �galement de retraiter ce qui a �t� entr� dans le formulaire,
notamment pour retirer du code ou autre contenu qui pourrait �tre dangereux.
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
Cette fonction liste tous les sous-dossiers qu'elle trouve � partir du chemin qui
lui est indiqu� � la base. Elle prend en compte la liste des �ventuels dossiers
exclus, donc aucune chance qu'ils ne se retrouvent list�s, m�me temporairement.
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
Comme son nom l'indique, elle sert � afficher les r�sultats
Elle affiche aussi la barre de navigation au besoin pour voir les pages pr�c�dentes ou suivantes
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
	// On d�finit les diff�rentes variables qui serviront pour la barre de navigation 
	$finstart=intval($nb_resultats/$maxi); 
	
	// Affichage du message de r�sultat 
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
		// On s�lectionne les r�sultats correspondants � la page en cours et on les affiche 
		for($i=$start*$maxi; $i<$start*$maxi+$maxi; $i++)
			{ 
			
			if($i>=$nb_resultats){ break; }
			
			echo("
			<p>
			".($i+1)." - ".$tableau_resultats[$i]."
			</p>
			"); 
			}  
	
		// Et si on a trop de r�sultats par rapport au nombre � afficher dans une seule page 
		// on met la ou les barre(s) de navigation correspondante(s) 
		if($nb_resultats>$maxi)
			{ 
			echo("<p align=center><br />"); 
			
			// R�sultats pr�c�dents 
			if($start>=1)
				{ 
				echo("<a href=\"".$page."?blork=".$blork."&type=".$type."&start=".($start-1)."&cherche=go\"><< R&eacute;sultats pr&eacute;c&eacute;dents</a>"); 
				}
			
			// Barre centrale 
			echo(" | ");
		
			// R�sultats suivants 
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
Cette fonction affiche les infos relatives au debug mode, s'il a �t� activ�.
A noter que le header et le footer ne sont pas inclus lorsque le debug mode est activ�,
afin de voir si le probl�me vient bien du script en lui-m�me. Si ce n'est pas le cas,
il est tr�s probable que ce soit le code mis dans un de ces deux fichiers qui provoque des bugs.
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
			// Taille de la m�moire swap
			echo("<br /> Taille m�moire swap // ".$ze_data["ru_nswap"].""); 
			// Nombre de pages m�moire utilis�es
			echo("<br /> Pages m�moire utilis�es // ".$ze_data["ru_majflt"]."");  
			}
		
		if(function_exists("memory_get_usage"))
			{
			$ze_memoire=memory_get_usage();
			echo("<br /> M�moire utilis�e par PHP // ".$ze_memoire."");  
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
Une fonction tr�s simple pour lancer le debug mode, mettre le footer et stopper le script
*/
function debug_footer_basta($config,$chrono)
	{
	// On lance le debug mode (ne s'�x�cute que si demand� dans le fichier config)  
	debug_mode($config,$chrono);
	
	// On met le footer si le debug mode est d�sactiv� 
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
- V�rification de la configuration du script
- Retraitement des termes recherch�s
* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
DEMARRAGE DE LA SESSION ET LANCEMENT DU CHRONO
On d�marre une session, et on lance le chrono
*/
session_start(); 
$chrono_start_you=demarre_chrono_you();
$chrono_start_sys=demarre_chrono_sys();


/* * * * * * * * * * * * * * * * * * * * * * *
INCLUSION DU FICHIER CONFIG 
On inclut la config, apr�s avoir v�rifi� qu'elle est bien la
On v�rifie au passage qu'on poss�de le fichier de listing des erreurs courantes
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
		echo("<br /><br /><b>* a Blork engine - arr�t maladie *</b>");
		echo("<br />Un fichier indispensable au bon fonctionnement du script n'a pas pu �tre trouv�."); 
		echo("<br />Il a peut-�tre �t� d�plac�, renomm� ou effac�.");
		echo("<br /><br /><b>Fichier non trouv�: fichier ".$cle." (".$resultat.")</b>");
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

// V�rification de la version PHP 
$version_php=phpversion(); 
$version_php=str_replace(".","",$version_php); 
if($version_php<433)
	{
	erreur_creve("version_php","Version actuelle : ".phpversion()."",$engine_version);	
	} 

// V�rification de la pr�sence des fichiers n�cessaires
for($i=0; $i<count($config['fichiers']); $i++)
	{
	if(!file_exists($config['fichiers'][$i]))
		{
		erreur_creve("fichier_introuvable","Fichier non trouv�: ".$config['fichiers'][$i]."",$engine_version); 	
		} 
	} 

// V�rification du nombre de r�sultats � afficher par page 
if(empty($config['maxipage']) 
|| !is_numeric($config['maxipage'])
	)
	{ 
	erreur_creve("config_incomplete","Nombre de r�sultats � afficher par page non indiqu� (�tape 1 du fichier de config)<br /> (ou alors c'est pas indiqu� par un chiffre...)",$engine_version); 
	}
	
// V�rification du nombre de mots maxi pour la recherche 
if(empty($config['maxmots']) 
|| !is_numeric($config['maxmots'])
|| $config['maxmots']>9
	)
	{ 
	erreur_creve("config_incomplete","Nombre de mots maxi pour une recherche non indiqu� (�tape 0 du fichier de config)<br /> (ou alors il n'est soit pas indiqu� par un chiffre, soit sup�rieur � 9...)",$engine_version); 
	}

// V�rification que les variables diverses sont bien indiqu�es 
$var_config_ouinon=array(
"de configuration du scan des sous-dossiers (�tape 3)"=>"$config[scan_sousdos]",
"qui indique si les extensions des fichiers doivent �tre affich�es (�tape 5)"=>"$config[montre_ext]",
"qui indique si le debug mode doit �tre activ� (fin du fichier, partie experts)"=>"$config[debug_mode]",
); 

$var_config_sketuveu=array(
"de la version du fichier de config (avant l'�tape 1)"=>"$config[version]",
"qui indique les dossiers � scanner (�tape 2)"=>"$dossier",
"qui indique l'url � utiliser pour les liens vers les fichiers (�tape 5)"=>"$go2url",
"qui indique les types de fichiers texte � scanner (fin du fichier, partie experts)"=>"$liste_extensions[txt]",
"qui indique les types de fichiers image � scanner(fin du fichier, partie experts)"=>"$liste_extensions[img]",
); 

foreach($var_config_ouinon as $cle=>$resultat)
	{
	if(empty($resultat))
		{
		erreur_creve("config_incomplete","La variable ".$cle." est vide.<br /> Recherchez la dans le fichier de config et compl�tez la pour que le script fonctionne � nouveau.<br />",$engine_version);
		}
	if($resultat!="oui" && $resultat!="non")
		{
		erreur_creve("config_incomplete","La variable ".$cle." doit �tre renseign�e par oui ou non.<br /> Recherchez la dans le fichier de config,<br /> vous avez indiqu� \"".$resultat."\" � la place.",$engine_version);
		}
	} 
	
foreach($var_config_sketuveu as $cle=>$resultat)
	{
	if(empty($resultat))
		{
		erreur_creve("config_incomplete","La variable ".$cle." est vide.<br /> Recherchez la dans le fichier de config et compl�tez la pour que le script fonctionne � nouveau.<br />",$engine_version);
		}
	} 
	
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
On cr�e les variables par d�faut si elles n'existent pas 
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
Chrono: V�rification de la config 
*/
$chrono['Etape 1 // V�rification de la config // Temps user']=affiche_chrono_you($chrono_start_you);
$chrono['Etape 1 // V�rification de la config // Temps syst�me']=affiche_chrono_sys($chrono_start_sys);
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
On v�rifie si une recherche est lanc�e, et si on a toutes les variables n�cessaires  
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
Chrono: Recherche lanc�e et variables trouv�es 
*/
$chrono['Etape 1 // Recherche lanc�e et variables trouv�es // Temps user']=affiche_chrono_you($chrono_start_you);
$chrono['Etape 1 // Recherche lanc�e et variables trouv�es // Temps syst�me']=affiche_chrono_sys($chrono_start_sys);
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
La recherche est lanc�e, on met le header 
mais uniquement � condition que le debug mode soit d�sactiv� 
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
V�rification de la validit� de la recherche:
Pour commencer on v�rifie la longueur de la recherche, si elle fait moins 
de trois caract�res, formulaire, footer, bonne nuit les petits. 
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
Retraitement des termes recherch�s:
Si on recherche l'expression exacte, la recherche est identique � ce qui a �t� tap�
Le texte a �t� retrait� plus haut pour v�rifier sa validit�, donc pas de sou�a�.

Pour les autres types de recherche on s�pare les diff�rents mots recherch�s
On supprime d'�ventuels doublons, puis on supprime les mots qui ne sont pas 
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
		Si on a supprim� des mots on l'indique par un message.
		On r�indexe l'array pour �viter des erreurs
		en utilisant array_unshift() puis array_shift() et on v�rifie le nombre de mots restants. 
		S'il n'y en a plus on balance le formulaire et au revoir;
		S'il n'en reste qu'un on red�finit le type de recherche sur 
		"expression exacte" pour gagner du temps ensuite;
		S'il y en a plus de 9 on met un message d'erreur 
		car la cl� de r�sultat devra indiquer le nombre de mots trouv�s
		et elle doit indiquer 1 chiffre, qui ne peut pas �tre �gal � 0.
		*/
		if(!empty($mot_supprime))
			{
			$mot_supprime="<br />A �t� supprim� de votre recherche (trop courant) : <b>".$mot_supprime."</b><br />"; 
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
Chrono: V�rification de la validit� de la recherche 
*/
$chrono['Etape 1 // V�rification de la validit� de la recherche // Temps user']=affiche_chrono_you($chrono_start_you); 
$chrono['Etape 1 // V�rification de la validit� de la recherche // Temps syst�me']=affiche_chrono_sys($chrono_start_sys); 
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
AFFICHAGE DIRECT
Si on a d�j� la recherche en m�moire
et qu'il ne s'agit que d'une actualisation de la page
ou d'un passage � une page pr�c�dente / suivante
on passe directement � l'affichage des r�sultats */

if(!empty($_SESSION['ze_blork']) 
&& !empty($_SESSION['ze_type']) 
&& $_SESSION['ze_blork']==$_GET['blork']
&& $_SESSION['ze_type']==$_GET['type'])
	{
	
	// Formulaire du haut 
	form_recherche($_SESSION['ze_blork'],$_SESSION['ze_type']); 	
	
	// Affichage des r�sultats
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
	$chrono['*** RESULTAT FINAL // Temps syst�me ']=affiche_chrono_sys($chrono_start_sys); 
	
	// Debug mode, footer et au revoir
	debug_footer_basta($config['debug_mode'],$chrono);  	
	}
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Scan des sous dossiers si on l'a activ� 
On v�rifie les sous-dossiers � scanner uniquement ici
Ensuite on les rajoute � la liste 
Comme �a apr�s on n'aura plus qu'a faire un scan classique 
Sur tous les dossiers de la liste 
*/

if($config['scan_sousdos']=="oui")
	{
	explore($dossier,$exclu); 
	
	/* * * * * * * * * * * * * * * * * * * * * * *
	Chrono: Recherche des sous-dossiers 
	*/
	$chrono['Etape 1 // Recherche des sous-dossiers // Temps user']=affiche_chrono_you($chrono_start_you); 
	$chrono['Etape 1 // Recherche des sous-dossiers // Temps syst�me']=affiche_chrono_sys($chrono_start_sys);
	/* * * * * * * * * * * * * * * * * * * * * * */
	} 
	
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Chrono: Etape 1 
*/
$chrono['*** ETAPE 1 TERMINEE // Temps user ***']=affiche_chrono_you($chrono_start_you); 
$chrono['*** ETAPE 1 TERMINEE // Temps syst�me ***']=affiche_chrono_sys($chrono_start_sys); 
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
FIN DE L'ETAPE 1
* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
ETAPE 2 - RECHERCHE 
- Retraitement des fichiers et calcul des r�sultats
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
R�sultats � 0
*/
$compteresultats="0"; 
$zetotal="0"; 
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
Passage en minuscules de la recherche
�a nous permettra d'utiliser des fonctions sensibles � la casse
(elles sont plus rapides que celles qui cherchent indiff�remment majuscules et minuscules)
*/
foreach($recherche as $key=>$value)
	{
	$recherche[$key]=strtolower($value);
	}
/* * * * * * * * * * * * * * * * * * * * * * */
	

/* * * * * * * * * * * * * * * * * * * * * * *
On lance le scan sur les dossiers de la liste 
Les sous-dossiers ont �t� rajout�s � la liste pr�c�demment si l'option est activ�e
Du coup on ne scanne pas les dossiers qu'on trouve
*/

foreach($dossier as $nomdos=>$d)
	{ 

	/* 
	S�same ouvre toi
	*/ 
	
	$fp=opendir("$d"); 
	while($file = readdir($fp))
		{ 
		if($file=="." || $file==".." || is_dir($file))
			{ 
			continue; 
			} 

		/*
		Pour aller plus vite on va chercher � zapper les fichiers qui ne nous int�ressent pas
		On commence par v�rifier s'il s'agit d'un fichier exclu
		*/
 
		if(in_array($file, $exclu))
			{ 
			continue; 
			} 

		/* 
		C'est pas un fichier exclu.
		On r�cup�re l'extension, et on va v�rifier si c'est un type de fichier 
		qu'on peut scanner; autrement, hop on zappe.  
		(un grand merci � Fr�d�ric Bouchery pour ce regex :-)
		
		Au passage : d�termination du type de fichier 
		On ne v�rifiera que le nom des fichiers de type "img" (image) 
		alors que les fichiers de type "texte" seront enti�rement retrait�s 
		car consid�r�s comme contenant du texte lisible par le moteur. 
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
		Maintenant qu'on a d�termin� la place de notre fichier entre les deux types 
		On va appliquer des retraitements pr�liminaires sur les fichiers 
		de type "texte" uniquement.
		Pour aller plus vite on zappe les fichiers image directement.
		*/
 
		if($filetype=="texte")
			{ 
	
			/* 
			On r�cup�re le contenu du fichier
			*/    
	
			$recupere_le_fichier=fopen("$d/$file","r"); 
			$tout=fread($recupere_le_fichier,500000); 
			fclose($recupere_le_fichier); 
			
			/* 
			Passage en minuscules du texte
			La encore il s'agit de pouvoir par la suite utiliser des fonctions 
			sensibles � la casse et gagner du temps.
			*/
			 
			$tout=strtolower($tout); 
			
			/* 
			On vire le html et le php, 
			sauf quelques balises qui nous int�ressent pour les infos qu'elles contiennent
			ou parce que ce serait mal fait et qu'on les supprimera par une autre fonction ensuite
			*/

			$tout=strip_tags($tout,'<title></title><script></script><head></head><style></style><select></select>'); 
	
			/* 
			On r�cup�re le titre du fichier, ou alors on affiche le nom avec l'extension 
			Puis on supprime le titre pour ne pas fausser les r�sultats lors du calcul.
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
			Les &nbsp; (code html pour un espace) sont remplac�s par des espaces 
			Les doubles espaces sont remplac�s par un simple espace 
			*/
			
			$tout=str_replace("\n"," ",$tout); 
			$tout=str_replace("&nbsp;"," ",$tout); 
			$tout=str_replace("  "," ",$tout); 
			$tout=trim($tout); 
			
			/* 
			On lance les regex:
			On vire le code entre <head> et </head> qui contient en g�n�ral tout les trucs qui ne nous int�ressent pas ici (feuille de style, javascript...) 
			On vire le javascript pour �viter les bugs au cas ou une partie nous aurait �chapp�e 
			On vire les attributs de style pour les m�mes raisons
			Et enfin on vire les listes de type select 
			Merci encore une fois � Fr�d�ric Bouchery pour le regex  
			*/
			
			$tout=preg_replace('`<head.*?/head>`s', '', $tout); 
			$tout=preg_replace('`<script.*?/script>`s', '', $tout); 
			$tout=preg_replace('`<style.*?/style>`s', '', $tout); 
			$tout=preg_replace('`<select.*?/select>`s', '', $tout); 
			
			/*
			On remplace le code html des accents et autres caract�res sp�ciaux 
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
		On incr�mente le nb de fichiers scann�s
		Chrono tous les 50 fichiers scann�s
		*/ 
		$zetotal++; 
		
		if(!is_float($zetotal/50))
			{
			$chrono["Etape 2 // Retraitement apr�s ".$zetotal." fichiers // Temps user"]=affiche_chrono_you($chrono_start_you); 
			$chrono["Etape 2 // Retraitement apr�s ".$zetotal." fichiers // Temps syst�me"]=affiche_chrono_sys($chrono_start_sys); 
			}
				

		/*
		Maintenant le fichier a �t� retrait� (si n�cessaire), 
		on peut voir s'il contient ce qu'on cherche.
		On adapte le code selon les diff�rents types de recherche
		*/

		switch($_GET['type'])
			{ 
			
			/*
			On adapte le code selon les diff�rents types de recherche
			PLUSIEURS MOTS (type = all)
			*/

			case "all":
				
				/* 
				Si on trouve tous les mots recherch�s 
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
					R�sultats +1
					Si les r�sultats d�passent le nombre maximum (limit� par la cl� de r�sultat � 9 chiffres max),
					on arr�te tout et on passe � l'affichage des r�sultats
					*/ 
					
					$compteresultats++; 
					
					if($compteresultats>9999)
						{ 
						break;
						continue 2; 
						} 

					/*
					Total des occurences � 0
					*/					
					$total_mots="0"; 

					/* 
					S'il s'agit d'un fichier de type "texte"
					*/ 
					
					if($filetype=="texte")
						{ 
	
						/* 
						On compte les occurences du terme 
						Les occurences trouv�es dans le titre comptent pour 10 
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
	
						// On cr�e la description
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
	
					// On met en gras le terme recherch� dans le titre et dans la description 
					// Pas besoin ici de v�rifier le type de fichier 
					
					foreach($recherche as $key=>$value)
						{
						$resume=str_replace($value,"<b>$value</b>",$resume);
						$titre=str_replace($value,"<b>$value</b>",$titre);
						} 
		
					// Calcul du pourcentage de pertinence 
					// La pertinence du texte n'est calcul�e que pour les fichiers texte 
					// Comme on a plusieurs mots on fera une moyenne sur la pertinence de chacun
					
					$p1=array(); 
					$p2=array(); 
					
					// V�rification de la pertinence du texte pour les fichiers texte					
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
					
					// V�rification de la pertinence du titre pour tous les types de fichiers
					foreach($recherche as $key=>$value)
						{
						similar_text($value, $titre, $p2[]);
						} 
					
					// Calcul des moyennes 
					$p1=array_sum($p1)/count($p1);
					$p2=array_sum($p2)/count($p2);
					
					// Obtention du pourcentage d�finitif  
					$p=intval($p1+$p2); 
					
					// Si le pourcentage est sup�rieur ou �gal � 100 on le ram�ne � 99 
					
					if($p>=100){ $p="99"; }  
					
					// On va cr�er une cl� identique pour chaque r�sultat.
					// Le premier sera un "1", pour que la cl� soit r�index�e 
					// Le suivant sera le nombre d'occurences total de mots trouv�s (en dizaines) 
					// Ensuite le pourcentage de similarit� du texte + celui du titre (deux chiffres) 
					// Enfin le num�ro du r�sultat (4 chiffres) 
					// Avec cette cl� on pourra classer les r�sultats par ordre d�croissant selon le chiffre obtenu, donc par pertinence. 
					
					// Notes : 
					// La cl� ne doit pas commencer par 0 donc il �tait important de mettre en premier 
					// un "1", ou un chiffre sup�rieur � 0 en tout cas.
					// La cl� ne doit pas �tre sup�rieure � 9 chiffres, sinon elle ne sera pas r�index�e. 
					
					// Cette bidouille me permettra par la suite avec array_unshift() de r�indexer le tableau avec 
					// des cl�s num�riques pour pouvoir afficher uniquement les r�sultats souhait�s, donc j'�conomise 
					// du temps d'�x�cution et des ressources par rapport � l'ancienne m�thode qui consistait � cr�er
					// un nouveau tableau. L'array_unshift() me rajoutera une valeur de cl� 0 que je ne supprime pas 
					// parce que je pourrai ainsi g�rer mes r�sultats � partir de 1, ce qui est plus logique. 
					
					// On ram�ne les occurences au maxi � 99 
					// Puis on rajoute un 0 devant le chiffre s'il est inf�rieur � 10 
					// Enfin on ne garde que le chiffre des dizaines 
					
					if($total_mots>=100){ $total_mots="99"; } 
					if (strlen($total_mots)==1){ 
					$total_mots=str_repeat("0",2-strlen($total_mots)).$total_mots; } 
					
					// Idem pour les pourcentages, 
					// � la diff�rence qu'on s'assure que le pourcentage soit � deux chiffres
					// histoire de ne pas fausser les r�sultats 
					
					if($p>=100){ $p="99"; } 
					if (strlen($p)==1){ 
					$p=str_repeat("0",2-strlen($p)).$p; } 
					
					// Et enfin le num�ro du r�sultat 
					
					$compteresultats2=$compteresultats; 
					if (strlen($compteresultats2)<4){ 
					$compteresultats2=str_repeat("0",4-strlen($compteresultats2)).$compteresultats2; } 
					
					
					// On met la premi�re lettre du titre en majuscules 
					$titre=ucfirst($titre); 
	
	
					// URL par d�faut pour les fichiers 
					
					// On vire l'extension si besoin 
					if($config["montre_ext"]=="non"){ 
					$file=str_replace(".$ext","",$file); } 

					// On remet les caract�res sp�ciaux sp�cial URL pour �viter des bugs avec IE 
					$file=rawurlencode($file); 
					
					if($go2url==""){ $go_2_url="$d/$file"; } 
					else{ 
					$go_2_url="$go2url";
					$go_2_url=str_replace("[dossier]",$d,$go_2_url); 
					$go_2_url=str_replace("[fichier]",$file,$go_2_url); } 
	
	
					// Source du r�sultat 
					
					$src=" <a href=\"$go_2_url\">$titre</a> <br /> 
					$resume
					
					"; 
					
					// On enregistre 
					$zeresults["1".$total_mots."".$p."".$compteresultats2]="$src"; 
					
									}
					
					// On remet a z�ro histoire d'�viter des doublons 
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
			On adapte le code selon les diff�rents types de recherche
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
					R�sultats +1
					Si les r�sultats d�passent le nombre maximum (limit� par la cl� de r�sultat � 9 chiffres max),
					on arr�te tout et on passe � l'affichage des r�sultats. �a nous limite � 9999 r�sultats pour 
					pouvoir utiliser la cl� num�rique.
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
						Les occurences trouv�es dans le titre comptent pour 10 
						(pire qu'au scrabble) car ils sont souvent explicites 
						sur le contenu de la page
						*/ 
	
						$total_mots="0"; 
						$total_mots=intval(substr_count($titre,$recherche[0])*10+$total_mots); 
						$total_mots=intval(substr_count($tout,$recherche[0])+$total_mots); 
	
						// On cr�e la description 
	
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
	
							// On met en gras le terme recherch� dans la description 
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
	
					// On met en gras le terme recherch� dans le titre 
					// Pas besoin ici de v�rifier le type de fichier
					$titre=str_replace($recherche[0],"<b>$recherche[0]</b>",$titre); 
	
	
					// Calcul du pourcentage de pertinence 
					// La pertinence du texte n'est calcul�e que pour les fichiers texte 
					
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
					
					// Si le pourcentage est sup�rieur ou �gal � 100 on le ram�ne � 99 
					
					if($p>=100){ $p="99"; }  
					
					// On va cr�er une cl� identique pour chaque r�sultat.
					// Le premier sera un "1", pour que la cl� soit r�index�e 
					// Le suivant sera le nombre d'occurences total de mots trouv�s (en dizaines) 
					// Ensuite le pourcentage de similarit� du texte + celui du titre (deux chiffres) 
					// Enfin le num�ro du r�sultat (4 chiffres) 
					// Avec cette cl� on pourra classer les r�sultats par ordre d�croissant selon le chiffre obtenu, donc par pertinence. 
					
					// Notes : 
					// La cl� ne doit pas commencer par 0 donc il �tait important de mettre en premier 
					// un "1", ou un chiffre sup�rieur � 0 en tout cas.
					// La cl� ne doit pas �tre sup�rieure � 9 chiffres, sinon elle ne sera pas r�index�e. 
					
					// Cette bidouille me permettra par la suite avec array_unshift() de r�indexer le tableau avec 
					// des cl�s num�riques pour pouvoir afficher uniquement les r�sultats souhait�s, donc j'�conomise 
					// du temps d'�x�cution et des ressources par rapport � l'ancienne m�thode qui consistait � cr�er
					// un nouveau tableau. L'array_unshift() me rajoutera une valeur de cl� 0 que je supprimerai 
					// avec array_shift() parce que je pourrai ainsi g�rer mes r�sultats � partir de 0 � nouveau
					// (c'est n�cessaire pour le calcul des r�sultats � afficher)  
					
					// On ram�ne les occurences au maxi � 99 
					// Puis on rajoute un 0 devant le chiffre s'il est inf�rieur � 10  
					 
					if($total_mots>=100){ $total_mots="99"; } 
					if (strlen($total_mots)==1){ 
					$total_mots=str_repeat("0",2-strlen($total_mots)).$total_mots; }  
					
					// Idem pour les pourcentages 
					
					if($p>=100){ $p="99"; } 
					if (strlen($p)==1){ 
					$p=str_repeat("0",2-strlen($p)).$p; } 
					
					// Et enfin le num�ro du r�sultat 
					
					$compteresultats2=$compteresultats; 
					if (strlen($compteresultats2)<4){ 
					$compteresultats2=str_repeat("0",4-strlen($compteresultats2)).$compteresultats2; } 
					
					
					// On met la premi�re lettre du titre en majuscules 
					$titre=ucfirst($titre); 
	
	
					// URL par d�faut pour les fichiers 
					
					// On vire l'extension si besoin 
					if($config["montre_ext"]=="non"){ 
					$file=str_replace(".$ext","",$file); } 

					// On remet les caract�res sp�ciaux sp�cial URL pour �viter des bugs avec IE 
					$file=rawurlencode($file); 
					
					if($go2url==""){ $go_2_url="$d/$file"; } 
					else{ 
					$go_2_url="$go2url";
					$go_2_url=str_replace("[dossier]",$d,$go_2_url); 
					$go_2_url=str_replace("[fichier]",$file,$go_2_url); } 
	
	
					// Source du r�sultat 
					
					$src=" <a href=\"$go_2_url\">$titre</a> <br /> 
					$resume
					
					"; 
					
					// On enregistre 
					$zeresults["1".$total_mots."".$p."".$compteresultats2]="$src"; 
					
									}
					
					// On remet a z�ro histoire d'�viter des doublons 
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
			On adapte le code selon les diff�rents types de recherche
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
					R�sultats +1
					Si les r�sultats d�passent le nombre maximum (limit� par la cl� de r�sultat � 9 chiffres max),
					on arr�te tout et on passe � l'affichage des r�sultats. �a nous limite � 9999 r�sultats pour 
					pouvoir utiliser la cl� num�rique.
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
						Les occurences trouv�es dans le titre comptent pour 10 
						(pire qu'au scrabble) car ils sont souvent explicites 
						sur le contenu de la page
						*/ 
	
						$total_mots="0"; 
						$total_mots=intval(substr_count($titre,$recherche[0])*10+$total_mots); 
						$total_mots=intval(substr_count($tout,$recherche[0])+$total_mots); 
	
						// On cr�e la description 
	
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
	
							// On met en gras le terme recherch� dans la description 
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
	
					// On met en gras le terme recherch� dans le titre 
					// Pas besoin ici de v�rifier le type de fichier
					$titre=str_replace($recherche[0],"<b>$recherche[0]</b>",$titre); 
	
	
					// Calcul du pourcentage de pertinence 
					// La pertinence du texte n'est calcul�e que pour les fichiers texte 
					
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
					
					// Si le pourcentage est sup�rieur ou �gal � 100 on le ram�ne � 99 
					
					if($p>=100){ $p="99"; }  
					
					// On va cr�er une cl� identique pour chaque r�sultat.
					// Le premier sera un "1", pour que la cl� soit r�index�e 
					// Le suivant sera le nombre d'occurences total de mots trouv�s (en dizaines) 
					// Ensuite le pourcentage de similarit� du texte + celui du titre (deux chiffres) 
					// Enfin le num�ro du r�sultat (4 chiffres) 
					// Avec cette cl� on pourra classer les r�sultats par ordre d�croissant selon le chiffre obtenu, donc par pertinence. 
					
					// Notes : 
					// La cl� ne doit pas commencer par 0 donc il �tait important de mettre en premier 
					// un "1", ou un chiffre sup�rieur � 0 en tout cas.
					// La cl� ne doit pas �tre sup�rieure � 9 chiffres, sinon elle ne sera pas r�index�e. 
					
					// Cette bidouille me permettra par la suite avec array_unshift() de r�indexer le tableau avec 
					// des cl�s num�riques pour pouvoir afficher uniquement les r�sultats souhait�s, donc j'�conomise 
					// du temps d'�x�cution et des ressources par rapport � l'ancienne m�thode qui consistait � cr�er
					// un nouveau tableau. L'array_unshift() me rajoutera une valeur de cl� 0 que je supprimerai 
					// avec array_shift() parce que je pourrai ainsi g�rer mes r�sultats � partir de 0 � nouveau
					// (c'est n�cessaire pour le calcul des r�sultats � afficher)  
					
					// On ram�ne les occurences au maxi � 99 
					// Puis on rajoute un 0 devant le chiffre s'il est inf�rieur � 10  
					 
					if($total_mots>=100){ $total_mots="99"; } 
					if (strlen($total_mots)==1){ 
					$total_mots=str_repeat("0",2-strlen($total_mots)).$total_mots; }  
					
					// Idem pour les pourcentages 
					
					if($p>=100){ $p="99"; } 
					if (strlen($p)==1){ 
					$p=str_repeat("0",2-strlen($p)).$p; } 
					
					// Et enfin le num�ro du r�sultat 
					
					$compteresultats2=$compteresultats; 
					if (strlen($compteresultats2)<4){ 
					$compteresultats2=str_repeat("0",4-strlen($compteresultats2)).$compteresultats2; } 
					
					
					// On met la premi�re lettre du titre en majuscules 
					$titre=ucfirst($titre); 
	
	
					// URL par d�faut pour les fichiers 
					
					// On vire l'extension si besoin 
					if($config["montre_ext"]=="non"){ 
					$file=str_replace(".$ext","",$file); } 

					// On remet les caract�res sp�ciaux sp�cial URL pour �viter des bugs avec IE 
					$file=rawurlencode($file); 
					
					if($go2url==""){ $go_2_url="$d/$file"; } 
					else{ 
					$go_2_url="$go2url";
					$go_2_url=str_replace("[dossier]",$d,$go_2_url); 
					$go_2_url=str_replace("[fichier]",$file,$go_2_url); } 
	
	
					// Source du r�sultat 
					
					$src=" <a href=\"$go_2_url\">$titre</a> <br /> 
					$resume
					
					"; 
					
					// On enregistre 
					$zeresults["1".$total_mots."".$p."".$compteresultats2]="$src"; 
					
									}
					
					// On remet a z�ro histoire d'�viter des doublons 
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
			Par d�faut, si le type ne correspond � rien de connu 
			on met un message d'erreur
			*/

			default:
				erreur_creve("type_recherche_inconnu",$_GET['type'],$engine_version); 
				break;
				
		}}   
				
				
	// On referme 
	// S�same ferme toi 
				
	closedir($fp); 
	unset($tout,$filetype,$fp,$ext); 
	} 

/***************************************************
Chrono: Fin de l'�tape 2
*/
$chrono['*** ETAPE 2 TERMINEE // Temps user ***']=affiche_chrono_you($chrono_start_you); 
$chrono['*** ETAPE 2 TERMINEE // Temps syst�me ***']=affiche_chrono_sys($chrono_start_sys); 
/**************************************************/
				

/* * * * * * * * * * * * * * * * * * * * * * *
FIN DE L'ETAPE 2
/* * * * * * * * * * * * * * * * * * * * * * */


/* * * * * * * * * * * * * * * * * * * * * * *
ETAPE 3 - AFFICHAGE ET SAUVEGARDE DES RESULTATS 
/* * * * * * * * * * * * * * * * * * * * * * */	

// On enregistre une premi�re partie des donn�es dans les variables de session 
// �a nous servira � lancer directement l'affichage 
// en cas d'actualisation de la page ou de passage � la page de r�sultats pr�c�dente / suivante

$_SESSION['ze_blork']=$_GET['blork']; 			// Texte de la recherche (non retrait�)  
$_SESSION['ze_type']=$_GET['type'];				// Type de recherche 
$_SESSION['ze_nb_results']=$compteresultats;	// Nombre de r�sultats 
$_SESSION['ze_nb_fichiers']=$zetotal; 			// Nombre de fichiers scann�s 

// Changement du texte selon les r�sultats 
// Entre singulier et pluriel 

$rs=" r&eacute;sultat trouv&eacute; ";
$fich=" fichier"; 
if($compteresultats>1){ $rs=" r&eacute;sultats trouv&eacute;s "; }
if($zetotal>1){ $fich=" fichiers"; } 

// Si nous n'avons aucun r�sultat, on affiche un message, les formulaires, le footer et au revoir
if($compteresultats<=0)
	{	
	// On d�finit la variable zeresults (qui normalement contient la description des r�sultats)
	// pour �viter une erreur (logique, y'a pas de r�sultats) 
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
	
	// Chrono: Finish sans r�sultat 
	$chrono['*** RESULTAT FINAL // Temps user ']=affiche_chrono_you($chrono_start_you); 
	$chrono['*** RESULTAT FINAL // Temps syst�me ']=affiche_chrono_sys($chrono_start_sys); 
	
	// Debug, footer et basta 
	debug_footer_basta($config['debug_mode'],$chrono); 
	} 

// Maintenant qu'on est s�r d'avoir des r�sultats on va les retraiter 
// On les classe par ordre d�croissant de pertinence 
// Ensuite on lance un array_unshift() qui r�indexe le tableau 
// ce qui nous permet de remplacer les cl�s num�riques par des nombres classiques 
// et on supprime la valeur cr��e avec array_shift() 
// pour g�rer les r�sultats � partir de 0 
// �a nous servira pour l'affichage et a barre de navigation parce qu'on calcule 
// num�ro de page * nombre de fichiers � afficher par page pour savoir 
// � quel num�ro on commence l'affichage des r�sultats
// Si les r�sultats �taient g�r�s � partir de 1 on ne pourrait pas afficher
// les r�sultats de 0 � 10 !
	
krsort($zeresults); 
array_unshift($zeresults,"rien");  
array_shift($zeresults); 

// Suite et fin de l'enregistrement des donn�es dans les variables de session 
// �a nous servira � lancer directement l'affichage 
// en cas d'actualisation de la page ou de passage � la page de r�sultats pr�c�dente / suivante

$_SESSION['ze_results']=$zeresults; 	// Contenu des r�sultats 
$_SESSION['rs']=$rs; 					// Texte pour l'affichage du message de r�sultats (1�re partie) 
$_SESSION['fich']=$fich; 				// Texte pour l'affichage du message de r�sultats (2�me partie)  

// Maintenant on commence l'affichage 
// Formulaire du haut 
form_recherche($_GET['blork'],$_GET['type']); 

// On affiche les r�sultats 
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
Chrono: Finish avec r�sultats 
*/
$chrono['*** RESULTAT FINAL // Temps user']=affiche_chrono_you($chrono_start_you); 
$chrono['*** RESULTAT FINAL // Temps syst�me']=affiche_chrono_sys($chrono_start_sys); 
/**************************************************/


// Debug mode, footer et c'est fini ! 
debug_footer_basta($config['debug_mode'],$chrono); 
?>
