<?php include_once('header.php'); ?>

[<a href="<?=$_SERVER["SCRIPT_NAME"]?>"><strong>↶ Back</strong></a>]<br />

<div style="float: left;">
    <h3>Manage IPs</h3>
Please enter the IPs to connect to your miners.<br />
<?php
    $notid = 0;
    if(@$notices != ''){?>
    <input type="checkbox" class="notehide" id="notid'.$notid.'" /><label class="notelabel" for="notid<?=$notid?>"><p class="debugS"><?=$notices?></p></label>
    <?php }?>
    <?phpelse{?>
    &nbsp<br />
    <?php};?>
<?php   
    $textarea = '';
    $tplaceholder = '';
    
    if($ips[0] != '')
    {
        foreach($ips as $ip)
        {
            if($ip != '')
                $textarea .= $ip."\n";
        }
    }
    else $tplaceholder = "Miner address here One per line";
?>
    <?=form_open('index.php/manage');?>

        <textarea name="ips" rows="10" cols="20" placeholder="<?=$tplaceholder?>"><?=$textarea?></textarea><br />
        <input type="hidden" name="manageips" value="manageips" />
        <input type="submit" value="send" />
        <div style="padding-top: 10px;">
            <li>For custom ports per IP use <strong style="color: #5f0;">IP:Port</strong></li>
            <li>Without explicit port, <strong>'<?=config_item('apiport')?>'</strong> will be used</li>
        </div>
    </form>
</div>

<div style="float: left; margin-left: 100px;">
    <?=form_open('index.php/manage');?>
        <h3>Abstract View</h3>
        Fields to show in rig overview<br />
        &nbsp;<br />
        <table style="width: 100%;">
            <tr><th>Remove</th><th>API Command</th><th>Display</th></tr>
            <?php
                foreach($abstractview as $cmd => $view)
                {
                    foreach($view as $item)
                    {
                        $fid = sprintf("%u", crc32($item));
                    ?>
                        <tr><td><input name="remfield[]" type="checkbox" value="<?=$cmd.':'.$item;?>" id="<?=$fid?>" /></td><td><label for="<?=$fid?>"><?=$cmd?></label></td><td><label for="<?=$fid?>"><?=$item;?></label></td></tr>
                    <?php
                    }
                }
            ?>
        </table>
        <hr />
        Add new field (ctrl-mouse for multiple select/unselect): <br />
        <div style="float: left;">
            <select style="padding: 0px 10px 0px 10px;" name="allfields[]" multiple>
                <?php
                foreach($allfields as $fields)
                {
                    foreach($fields as $field => $vcmd)
                    {
                        if(!@in_array($field, $abstractview[$vcmd]))
                        {?>
                            <option value="<?=$vcmd.':'.$field?>"><?=$vcmd.': '.$field?></option>
                        <?php
                        }
                    }
                }
                ?>
            </select>
        </div>
        <div style="float: left; padding-left: 10px;">
            <input type="submit" value="send">
        </div>
    </form>
</div>
</body>
</html>
