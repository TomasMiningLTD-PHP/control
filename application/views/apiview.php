<?php
        include_once('header.php');

        foreach($apidata as $ip => $tables)
        {
            $id = str_replace('.', '', $ip); //clean ID, lets keep '.' disallowed

            echo form_open(urlparam())."\n";
            echo '<label for="'.$id.'">'.getAbstract($tables, $ip)."</label>\n";
            echo '<input type="checkbox" name="'.$id.'" id="'.$id.'" class="expand" ';
            if(@$_POST[$id]) echo 'checked';
            echo ' />';
            echo '<div class="nodedisplay">';
            echo '<div class="apiview">';
            echo getApiView($tables, $ip, 'pools');
            echo "</div>\n";
            echo '<div class="poolview">';
            echo 'Add Pool <input type="text" name="purl" size="30" placeholder="stratum+tcp://host:port" /><input type="text" name="puser" placeholder="user.worker" /><input type="text" name="ppass" placeholder="password"/>';
            echo '<input type="submit" value="+ add" />&nbsp;&nbsp;<input class="save" type="submit" name="save" value="save" /><br /><input type="hidden" name="ip" value="'.$ip.'" />'."\n";
            echo  getPoolView($tables['pools'], $ip);
            echo '</div></form>';
            echo "</div>\n";
        }
    ?>
    <div style="position: fixed; bottom: 0px;">
    <?php
        $notebox = '';
        $notid = 0;
        if($errors != '')
        {
            foreach($errors as $error)
            {
                $notid++;
                $notebox .= '<input type="checkbox" class="notehide" id="notid'.$notid.'" /><label class="notelabel" for="notid'.$notid.'"><p class="debugE">'.$error.' [✕]&nbsp;</p></label>';
            }
        }
        if($notices != '')
        {
            foreach($notices as $notice)
            {
                $notid++;
                $notebox .= '<input type="checkbox" class="notehide" id="notid'.$notid.'" /><label class="notelabel" for="notid'.$notid.'"><p class="debugS">'.$notice['STATUS']['Description'].': '.$notice['STATUS']['Msg'].' [✕]&nbsp;</p></label>';
            }
        }

        echo $notebox;//$this->output->enable_profiler();
    ?>
    </div>
</div>
</body>
</html>
