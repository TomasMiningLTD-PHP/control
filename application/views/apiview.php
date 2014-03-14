<?php include_once('header.php');

        if(@$apidata != NULL)
        {
            echo form_open(urlparam());?>
                Add this pool to <strong>all</strong> miners:
                <input type="text" name="purl" size="30" placeholder="stratum+tcp://host:port" />
                <input type="text" name="puser" placeholder="user.worker#" /><input type="text" name="ppass" placeholder="password"/>
                <input type="submit" value="+ add" />&nbsp;&nbsp;<input class="save" type="submit" name="save" value="save" />
                <input type="hidden" name="ip" value="allIps" />
            </form>
            <?php
            foreach($apidata as $ip => $tables)
            {
                $id = str_replace('.', '', $ip);?>
                <?=form_open(urlparam());?>
                    <label for="<?=$id?>"><?=getAbstract($tables, $ip, $abstractview)?></label>
                    <input type="checkbox" id="<?=$id?>" name="expanded[]" value="<?=$id?>" class="expand" <?=(@in_array($id, $expanded)?'checked':'')?> />
                    <div class="nodedisplay">
                        <div class="apiview">
                            <?=getApiView($tables, $ip, 'pools')?>
                        </div>
                        <div class="poolview">
                            Add Pool <input type="text" name="purl" size="30" placeholder="stratum+tcp://host:port" /><input type="text" name="puser" placeholder="user.worker" /><input type="text" name="ppass" placeholder="password"/>
                            <input type="submit" value="+ add" />&nbsp;&nbsp;<input class="save" type="submit" name="save" value="save" /><br /><input type="hidden" name="ip" value="<?=$ip?>" />
                            <?=getPoolView($tables['pools'], $ip);?>
                        </div>
                    </div>
                </form>
            <?php }
        }
        else
        {?>
            <p>Coulnt access any miners :(</p>
        <?php }?>
    <div style="position: fixed; top: 5px;">
    <?php
        $notebox = '';
        $notid = 0;
        if($errors != '')
        {
            foreach($errors as $error)
            {
                $notid++;
                $notebox .= '<input type="checkbox" class="notehide" id="notid'.$notid.'" /><label class="notelabel" for="notid'.$notid.'"><p class="debugE">'.$error.' [✕]</p></label>';
            }
        }
        if($notices != '')
        {
            foreach($notices as $notice)
            {
                $notid++;
                $notebox .= '<input type="checkbox" class="notehide" id="notid'.$notid.'" /><label class="notelabel" for="notid'.$notid.'"><p class="debugS">'.$notice['STATUS']['Description'].': '.$notice['STATUS']['Msg'].' [✕]</p></label>';
            }
        }

        echo $notebox;//$this->output->enable_profiler();
    ?>
    </div>
</div><br style="clear: both;"/>
<!--?php var_dump($_GET)?-->
</body>
</html>
