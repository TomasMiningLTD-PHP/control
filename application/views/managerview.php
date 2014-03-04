<?php include_once('header.php'); ?>
[<a href="<?=$_SERVER["SCRIPT_NAME"]?>"><strong>↶ Back</strong></a>]

<div>
<h3>Manage IPs</h3>
Please enter the IPs to connect to your miners.<br />
<?php
    $notid = 0;
    if(@$notices != '')
    {
        echo '<input type="checkbox" class="notehide" id="notid'.$notid.'" /><label class="notelabel" for="notid'.$notid.'"><p class="debugS">'.$notices.'</p>';
    }
    else
        echo '&nbsp;<br />';

    $textarea = '';
    
    foreach($ips as $ip)
    {
        if($ip != '')
            $textarea .= $ip."\n";
    }
?>
<?=form_open('index.php/manage');?>

    <textarea name="ips" rows="10" cols="20"><?=$textarea?></textarea><br />
    <input type="hidden" name="manageips" value="manageips" />
    <input type="submit" value="send" />
</form>
</div>
<div style="padding-top: 10px; clear: both;">
<li>For custom ports per IP use <strong style="color: #5f0;">IP:Port</strong></li>
<li>Without explicit port, <strong>'<?=config_item('apiport')?>'</strong> will be used</li>
</div>
</body>
</html>
