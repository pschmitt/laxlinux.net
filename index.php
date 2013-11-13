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
        <script type="text/javascript" src="./files/scripts/util-functions.js"></script>
        <script type="text/javascript" src="./files/scripts/clear-default-text.js"></script>

    </head>
    <body>
    <div id="container">
        <?php
            include("./header.html");
        ?>
        
        <?php
            include("./menu.html");
        ?>
        
        <?php
            $OK = array("home" => "./files/content/home.html",
                        "about" => "./files/content/about.html",
                        "about_de" => "./files/content/about_de.html",
                        "about_us" => "./files/content/about_us.html",
                        "credits" => "./files/content/credits.html",
                        "history" => "./files/content/history.html",
                        "files" => "./files/content/files.html",
                        "howtos" => "./files/content/howtos.html",
                        "commands" => "./files/content/commands.html",
                        "apps" => "./files/content/apps.html",
                        "vocab" => "./files/content/vocab.html",
                        "ip" => "./files/content/ip.html",
                        "take_part" => "./files/content/take_part.html",
                        "uni" => "./files/content/uni.html",
                        "sources" => "./files/content/sources.html",
                        "wawa" => "./files/content/wawa.html",
                        "search" => "./files/scripts/blork-0.3/engine.php");
            if ((isset($_GET["p"])) && (isset($OK[$_GET["p"]])))
                include($OK[$_GET["p"]]);
            else
                include("./files/content/home.html");
        ?>

        <?php
            include("./footer.html");
        ?>
    </div>
    </body>
</html>
