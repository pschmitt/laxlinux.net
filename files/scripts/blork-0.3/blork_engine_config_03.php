<? 
/* * * * * * * * * * * * * * * * * * * * * * *

			A Blork Engine v0.3
			Fichier de configuration 


Ce fichier est la base indispensable au bon fonctionnement du script.
Remplissez le donc avec soin. Vous trouverez à chaque point des 
messages d'explication pour vous aider à configurer le fichier, ne les
effacez pas ! Ils n'apparaitront pas lors de l'affichage de la page
sur internet.

Ce script est distribué sous licence GNU GPL, comme indiqué ci-dessous (en anglais).
Ne retirez pas ce texte ou vous risquez des poursuites judiciaires.

* * * * * * * * * * * * * * * * * * * * * * *

a Blork Engine
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

*/
// Version du script
$config=array();
$config['version']="03"; 
/* * * * * * * * * * * * * * * * * * * * * * */

/* 
LA CONFIGURATION DU SCRIPT COMMENCE A PARTIR D'ICI
*/


/* * * * * * * * * * * * * * * * * * * * * * * 
ETAPE 0 - NOMBRE DE MOTS MAXI POUR UNE RECHERCHE	   
Par défaut ce nombre est fixé à 3.

Cette limite ne s'applique que si on effectue une recherche sur chaque mot,
dans le cas d'une recherche sur une expression exacte cette limite n'existe pas. 

Le nombre de mots à rechercher ne peut pas être supérieur à 9.
ATTENTION : chaque mot supplémentaire à chercher demande beaucoup de temps
et de ressources en plus pour l'éxécution du script. Il vaut mieux
limiter au maximum le nombre de mots accordés pour une recherche 
si vous souhaitez économiser les ressources du serveur.

Evidemment, plus le nombre de fichiers à analyser est grand,
plus il est conseillé de limiter le nombre de mots maximum pour une recherche.

Indiquez à la ligne ci dessous entre les guillemets
Le nombre de mots maximum autorisés pour une recherche */


$config["maxmots"]="3";


/* FIN DE L'ETAPE 0
* * * * * * * * * * * * * * * * * * * * * */




/* * * * * * * * * * * * * * * * * * * * * * * 
ETAPE 1 - NOMBRE DE RESULTATS PAR PAGE   
Par défaut ce nombre est fixé à 10 résultats maximum par page 
(il en affichera moins s'il y a moins de 10 résultats bien sûr), 
mais vous pouvez l'adapter si vous le souhaitez.

Pour le modifier, il suffit d'indiquer le nombre de fichiers dans l'endroit signalé ci-dessous. 
Vous devez mettre un nombre entier supérieur à 0, pas de chiffres a virgule ou de fractions 
et autres formules mathématiques bien sûr sinon vous provoquez une erreur... 

Indiquez à la ligne ci dessous entre les guillemets
Le nombre de résultats maximum à afficher par page */


$config["maxipage"]="20";


/* FIN DE L'ETAPE 1
* * * * * * * * * * * * * * * * * * * * * */




/* * * * * * * * * * * * * * * * * * * * * * * 
ETAPE 2 - DOSSIERS A SCANNER
Pour indiquer au moteur quels dossiers il faut scanner, 
il suffit pour chacun d'entre eux d'ajouter le code suivant 
à l'endroit signalé plus bas dans la page, 
en le remplaçant par les bonnes valeurs :


"la description de votre dossier"=>"le chemin d'accès au dossier",


A noter que le script vous permet avec cette version de scanner les sous-dossiers sans aucune limite. 
Donc si vous voulez éviter de trop vous compliquer la vie, indiquez simplement le dossier de base
puis activez le scan des sous-dossiers, comme ça ils seront tous scannés sans que vous n'ayez besoin de les indiquer. 
Si vous voulez que certains dans le tas ne soient pas scannés, vous pourrez l'indiquer plus tard. 
Mais attention : quand vous excluez un dossier de la recherche, 
ses sous-dossiers ne seront pas scannés non plus par sécurité. 
Si vous voulez que ses sous-dossiers soient tout de même scannés, 
il faut les indiquer dans cette partie du script. 

Dernière astuce : si vous indiquez dans la configuration un dossier, puis un de ses
sous-dossiers, celui ci ne sera pas scanné deux fois même si vous avez activé le scan des 
sous-dossiers. J'ai prévu un système spécifique pour éviter ce genre de doublons, donc à moins
que vous n'indiquiez deux fois un même dossier dans ce fichier de configuration, il n'y a aucun 
risque de voir apparaitre des réponses en double. 


----------------------------------
Explications sur la configuration: 
----------------------------------

Dans cette partie, il s'agit en quelque sorte de construire un plan d'accès pour chaque dossier,
histoire que le moteur trouve son chemin. Un plan d'accès sert à trouver une destination, c'est
cette destination que nous allons définir dans un premier temps. 

"la destination devra être indiquée ici, a gauche"=>"cette partie la on la verra plus tard",

La destination correspond à la description du contenu qui se trouve dans le dossier à scanner. 
Si le dossier que vous voulez indiquer contient des images, vous pouvez y mettre "les images". 
Si vous n'avez qu'un seul dossier à scanner, vous pouvez mettre comme description : "le site". 
Bien sûr ce ne sont que des exemples. Vous pouvez mettre le texte que vous voulez, à une exception près : 
n'y mettez pas de guillemets, parce que la description doit justement être mise entre guillemets 
et cela provoquerait immédiatemment une erreur.


Maintenant regardons la partie de droite :

"destination"=>"ici on indique au moteur le chemin pour trouver le dossier de destination",

Le dossier de destination, c'est le nom du dossier à partir de l'endroit où vous avez placé le moteur. 
Il va donc falloir dire au moteur comment on y arrive, et c'est le but de cette seconde partie. 
On considère que le dossier où on a mis le moteur est notre point de départ, 
il faut donc toujours commencer par un simple point comme celui que je mets entre guillemets ici : "."

Si on veut scanner le même dossier que celui où est placé le moteur, il suffira donc de mettre un point, 
c'est le cas le plus simple. Maintenant voyons des situations plus compliquées :

Imaginons que l'on place le script dans le répertoire de base ou on met nos fichiers en général, 
et que dans ce répertoire de base il existe un dossier "site" que l'on veut scanner. 
Il faudra alors mettre comme chemin : 
 
"./site"

On met un point pour dire qu'on est à notre point de départ, puis "/site" pour passer dans le dossier "site". 
Si on reprend le code depuis le début avec les deux parties ça nous fera donc :

"le dossier site"=>"./site",

Note : La virgule est nécessaire, ne l'enlevez pas.  
Un autre exemple : on place le moteur dans un nouveau dossier, et on veut scanner remonter les dossiers d'un niveau, 
c'est à dire scanner le dossier parent. Il faudra alors mettre le code suivant : 

".."

Pour revenir en arrière, on met un double point. Si on voulait remonter deux niveaux en arrière, 
on aurait mis ce code : 

"../.."

Dans l'ordre si on reprend les exemples on aura comme code complet :

"on remonte d'un dossier"=>"..",
"on remonte de deux dossiers"=>"../..",

Un dernier exemple : on remonte en arrière d'un dossier puis on scanne un dossier nommé "truc" qui s'y trouve, 
je vous met directement le code complet :

"on remonte d'un dossier puis on scanne le dossier truc qui y est"=>"../truc",

A partir de ces exemples vous devriez pouvoir vous en sortir je pense. 
*/ 

$dossier=array( 
// Placez en dessous de cette ligne
// Le code des différents dossiers à scanner
// Ne mettez pas de / à la fin du chemin d'accès au dossier
// Car il est rajouté automatiquement 


"../../..",


// Ne mettez plus de dossiers à scanner en dessous de cette ligne. 
); /* FIN DE L'ETAPE 2 
* * * * * * * * * * * * * * * * * * * * * * */




/* * * * * * * * * * * * * * * * * * * * * * * 
ETAPE 3 - SCAN DES SOUS-DOSSIERS (OU PAS )
Par défaut le script n'ira pas scanner les sous dossiers car cela demande du temps et des ressources supplémentaires. 
Cependant si vous avez besoin d'activer cette option, indiquez à l'endroit signalé plus bas : 

on 		Pour activer le scan des sous dossiers 
off 	Pour que les sous dossier ne soient pas scannés 

Si vous voulez que certains sous-dossiers ne soient pas scannés, 
vous pourrez l'indiquer dans une prochaine étape.


Indiquez à la ligne ci dessous entre les guillemets
on 	Pour scanner les sous dossiers lors d'une recherche 
off 	Pour que les sous dossiers ne soient pas scannés */ 


$config["scan_sousdos"]="oui";


/* FIN DE L'ETAPE 3
* * * * * * * * * * * * * * * * * * * * * * */




/* * * * * * * * * * * * * * * * * * * * * * * 
ETAPE 4 - DOSSIERS / FICHIERS A EXCLURE DE LA RECHERCHE 
Pour éviter qu'un fichier ou un dossier puisse être scanné et affiché dans les résultats, 
vous allez devoir les indiquer dans la liste plus bas. 

Par défaut les fichiers du script sont déjà exclus de la recherche, 
il ne sert à rien de rechercher dans le fichier qui est en train de faire lui-même la recherche... 
Chaque élément doit être mis entre guillemets, et suivi d'une virgule. 

Si vous voulez en rajouter il faut indiquer entre les guillemets :

¤ Pour un fichier :

Son nom avec son extension, mais surtout pas le dossier où il se trouve. 
Exemple :	index.html				FONCTIONNE
			monimage.jpg		FONCTIONNE
			monimage.gif		FONCTIONNE
		mondossier/index.html		NE FONCTIONNE PAS
		mondossier/monimage.jpg		NE FONCTIONNE PAS


¤ Pour un dossier : 

Son chemin d'accès, c'est à dire la partie de droite que l'on remplit 
lors de la personnalisation des dossiers à scanner.

Exemple : 	.			FONCTIONNE
		..			FONCTIONNE
		./mondossier	FONCTIONNE
		../mondossier	FONCTIONNE
		mondossier		NE FONCTIONNE PAS


Mettez uniquement un élément par ligne.
Les différents éléments sont à indiquer dans la liste ci-dessous */
$exclu=array(



"engine.php", 
"enginoscope.php", 
"blork_engine_config_".$config['version'].".php", 
"blork_engine_errors_".$config['version'].".php", 
"blork_engine_bas.html", 
"blork_engine_haut.html", 




// Ne mettez plus de fichiers à exclure en dessous de cette ligne. 
); /* FIN DE L'ETAPE 4
* * * * * * * * * * * * * * * * * * * * * * */




/* * * * * * * * * * * * * * * * * * * * * * * 
ETAPE 5 - PSEUDO FRAMES (OU PAS) 

--------------------------------------------
Explication : C'est quoi les pseudo frames ?
--------------------------------------------


Les pseudo frames sont un script php utilisé sur de nombreux sites qui permet de créer une seule page de menu 
pour tout votre site sans utiliser de frames, d'où son nom de pseudo frames. Son principe est le suivant :

1- Vous prenez une page fixe qui contient votre design

2- Dedans au lieu de mettre directement votre contenu, vous placez un bout de script php qui dit les choses suivantes :

¤ La variable "page" qui apparaitra dans le lien du navigateur correspond à la page qui doit être mise à la place du contenu.
¤ Si la variable ne contient rien ou que la variable ne correspond pas à une page qui existe sur le serveur, on met une page par défaut à la place de ce contenu.

3- Vous créez vos liens de la façon suivante : mapage.php?page=la_page_a_inclure.html

Ainsi au lieu de mettre un menu dans chacune de vos pages de contenu, c'est le contenu qui vient se mettre dans la page du menu. 
Vous pouvez donc modifier votre page de menu sans toucher au reste, ce qui économise pas mal de boulot. 


--------------------
Fin de l'explication
---------------------


Maintenant, on va faire simple : 
Est-ce que vous utilisez un script de ce genre sur votre site ?


Non, jamais entendu parler de ça
--------------------------------
==> C'est fini, plus besoin de toucher à quoi que ce soit dans les étapes suivantes, sauf si vous êtes un expert (ouf !).

Oui, j'en utilise un !
--------------------------------
==> Pas de chance ! Reculez de trois cases, passez votre tour, allez directement en prison
sans passer par la case départ, ne touchez pas 20 000F (de toute façon maintenant les francs
ça vaut plus un rond alors vous ne perdez pas grand chose), et puis lisez la suite pour finir 
de configurer le fichier.


Si votre site utilise un script de pseudo frames il faut indiquer à l'endroit signalé plus bas dans la page 
l'url type de votre pseudo frame et si l'extension du fichier doit être affichée dans l'url ou non. 

Dans votre url type vous aurez à remplacer le nom du fichier et le nom du dossier par [fichier] et [dossier]. 
Voici quelques exemples de pseudo frames les plus courants avec la configuration à adopter :

monsite.com/mapage.php?page=mondossier/la_page_a_afficher.html
Laissez activé l'extension du fichier. 
Pour votre url type mettez : mapage.php?page=[dossier]/[fichier]

monsite.com/mapage.php?page=mondossier/la_page_a_afficher
Désactivez l'extension du fichier. 
Pour votre url type mettez : mapage.php?page=[dossier]/[fichier]

monsite.com/mapage.php?rub=mondossier&page=la_page_a_afficher.html
Laissez activé l'extension du fichier.
Pour votre url type mettez : mapage.php?rub=[dossier]&page=[fichier]

monsite.com/mapage.php?rub=mondossier&page=la_page_a_afficher
Désactivez l'extension du fichier.
Pour votre url type mettez : mapage.php?rub=[dossier]&page=[fichier]

Indiquez à la ligne ci dessous entre les guillemets oui ou non :
oui      pour afficher l'extension des fichiers
non      pour désactiver l'affichage de l'extension des fichiers */ 


$config["montre_ext"]="non"; 


// Indiquez à la ligne ci dessous entre les guillemets 
// l'url type à utiliser dans le moteur 
// si vous n'utilisez pas les pseudos frames ne modifiez pas cette url type
// utilisez [dossier] pour indiquer le dossier
// et [fichier] pour indiquer le fichier 


$go2url="../../../index.php?p=[fichier]"; 


/* FIN DE L'ETAPE 5
* * * * * * * * * * * * * * * * * * * * * * */




/* * * * * * * * * * * * * * * * * * * * * * * 
ETAPE 6 - ELIMINATION DES MOTS COURANTS
Pour gagner du temps et consommer moins de ressources, le script peut supprimer de la recherche 
les mots qui sont trop courants et n'aident pas à différencier un fichier d'un autre. 

Pour en rajouter, à l'endroit indiqué mettez entre guillemets le mot concerné, suivi d'une virgule.

ATTENTION ! 
- Un seul mot ou une seule expression à la fois ! 
- Un seul mot ou expression par ligne, mis entre guillemets et suivi d'une virgule ! 
*/ 

$mots_courants=array(
// Rajoutez vos mots ou expressions ci-dessous :
"des",
"les",
"une",
"un",
"du",
"le",
// Ne rajoutez plus rien après cette ligne 
); /* FIN DE L'ETAPE 6
* * * * * * * * * * * * * * * * * * * * * * */

/*
FIN DE LA CONFIGURATION DU SCRIPT POUR LES DEBUTANTS 
LE RESTE DU FICHIER NE COMPORTE PAS D'EXPLICATIONS ET EST RESERVE AUX EXPERTS.
*/


/* * * * * * * * * * * * * * * * * * * * * * * 
- PARTIE RESERVEE AUX EXPERTS - 
NE TOUCHEZ PAS A CES QUELQUES LIGNES SANS SAVOIR CE QUE VOUS FAITES SVP */

// Fichiers utilisés 
$config['fichiers']=array(
"blork_engine_errors_".$config['version'].".php", 
"blork_engine_bas.html", 
"blork_engine_haut.html", 
);

// Extensions type texte scannées 
$liste_extensions=array();
$liste_extensions["txt"]=array(
"html", 
"htm", 
"php", 
"php3", 
"php4",
"php5", 
"php6",  
"txt", 
);

// Extensions type image scannées  
$liste_extensions["img"]=array(
"bmp",
"gif", 
"jpg", 
"jpeg",
"png",
"tiff",
); 

// Types de recherche 
$config['types_recherche']=array(
"all",
"exact",
"now_exact",
); 

// Liste des caractères spéciaux
// utilisés en fichiers html  
$caractere_special=array(
"&agrave;"=>"à",
"&aacute;"=>"á",
"&acirc;"=>"â",
"&atilde;"=>"ã",
"&auml;"=>"ä",
"&aring;"=>"å",
"&aelig;"=>"æ",
"&ccedil;"=>"ç",
"&egrave;"=>"è",
"&eacute;"=>"é",
"&ecirc;"=>"ê",
"&euml;"=>"ë",
"&icirc;"=>"î",
"&iuml;"=>"ï",
"&ocirc;"=>"ô",
"&ouml;"=>"ö",
"&ugrave;"=>"ù",
"&uacute;"=>"ú",
"&ucirc;"=>"û",
"&uuml;"=>"ü",
"&amp;"=>"&",
); 

// Activation du debug mode 
$config['debug_mode']="non"; 
/* * * * * * * * * * * * * * * * * * * * * * */

?>
