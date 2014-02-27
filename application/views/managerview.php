<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>untitled</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.22" />
    <link rel='stylesheet' type='text/css' media='all' href='../style.css' />
</head>

<body>
<?php
    $apicmds    = config_item('apicmds');
?>
[<a href="<?=$_SERVER["SCRIPT_NAME"]?>">Back</a>]

<div>
<h3>Manage IPs</h3>
<?=form_open('index.php/manage');?>

    <textarea name="ips" rows="10" cols="20">
<?php
    foreach($ips as $ip)
    {
        echo $ip."\n";
    }
?>
    </textarea><br />
    <input type="hidden" name="manageips" value="manageips" />
    <input type="submit" value="send" />
</form>
</div>
</body>
</html>
