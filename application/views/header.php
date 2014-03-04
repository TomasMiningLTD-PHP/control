<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title><?=$title;?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.22" />
    <?=link_tag(base_url().'style.css');?>
</head>
<body>
    <div id="contents">
<?php

    $fancyart =
'<pre style="white-space: pre; margin-top: -1.00em; text-align: left;">
        .__              _________                __                .__   
  _____ |__| ____   ____ \_   ___ \  ____   _____/  |________  ____ |  |  
 /     \|  |/    \_/ __ \/    \  \/ /  _ \ /    \   __\_  __ \/  _ \|  |  
|  Y Y  \  |   |  \  ___/\     \___(  <_> )   |  \  |  |  | \(  <_> )  |__
|__|_|  /__|___|  /\___  >\______  /\____/|___|  /__|  |__|   \____/|____/
      \/        \/     \/        \/            \/
                                                  <a href="http://crypto-magic.com/">http://crypto-magic.com/</a>
</pre>
';
?>
    
<div id="top">
    <table id="toptable">
        <tr>
            <td>&nbsp;</td>
            <td><?=$fancyart?></td>
            <td>
                <div id="topinfo">
                    GPU Temperature Min: <span style="color: #0f0;" ><?=config_item('mintemp')?></span>°C Max: <span style="color: #f00;"><?=config_item('maxtemp')?></span>°C&nbsp;|&nbsp; [<a href="<?=$_SERVER["SCRIPT_NAME"]?>/manage" >CONFIG</a>]
                </div>
            </td>
        </tr>
    </table>
</div>
