<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title><?=$title;?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.22" />
    <style type="text/css">
        body
        {
            font-family: Tahoma, sans-serif;
            font-size: 0.9em;
            background-color: #000;
            color: #fff;
            margin-top: 40px;
        }
        div#contents
        {
            margin-top: 40px;
        }
        div.node
        {
            widht: 100%;
            height: 20px;
        }
        div.nodedisplay
        {
            display: block;
            height: 0px;
            clear: both;
            overflow: hidden;
        }
        div#top
        {
            top: 0px;
            display: block;
            text-align: right;
            position: fixed;
            background-color: rgba(0,0,0,0.8);
            border-bottom: 2px solid #444;
            height: 23px;
            padding-top: 5px;
            margin-left: -15px;
            width: 100%;
        }
        div.poolview
        {
            float: left;
            width: 50%;
            padding-left: 5px;
        }
        div.apiview
        {
            float: left;
            width: 45%;
            overflow: auto;
        }
        table
        {
            float: left;
            border-spacing: 1px;
            width: 5%;
        }
        th
        {
            background-color: #555;
            padding: 0px 5px 0px 5px;
            border-radius: 5px 5px 0px 0px;
            color: lightblue;
        }
        th, td
        {
            font-size: 0.9em;
        }
        tr
        {
            margin: 0px;
        }
        td
        {
            border: 1px solid #555;
            padding: 5px;
            text-align: center;
        }
        td.statusOK
        {
            background: green;
        }
        td.statusAlert
        {
            background: red;
            color: yellow;
        }
        td.statusAlert2
        {
            background: red;
            color: yellow;
            font-weight: bold;
            font-size: 1.0em;
        }
        tr.second td
        {
            border: 1px solid #999;
        }
        tr:hover td a
        {
            color: yellow;
        }
        td.filename
        {
            background: #eff;
        }
        a
        {
            text-decoration: none;
            font-weight: bold;
            color: #095;
        }
        form
        {
            float: left;
            display: inline;
            margin: 1px;
        }
        input
        {
            background-color: #777;
            padding: 3px;
            color: #afa;
            border: none;
        }
        input[type="submit"]
        {
            padding: 3px;
            margin-right: 1px;
            font-weight: bold;
            cursor: pointer;
            font-size: 0.7em;
        }
        p
        {
            font-family: monospace;
            padding: 3px;
            margin: 0px;
            margin-right: 3px;
        }
        p.debugN /*blue:notice*/
        {
            background-color: #049;
            color: #099;
            border-top: 1px solid #067;
            border-bottom: 1px solid #037;
        }
        p.debugE /*red:error*/
        {
            background-color: #a00;
            color: #ff0;
            border-top: 1px solid #965;
            border-bottom: 1px solid #732;
        }
        p.debugS /*green:sucess*/
        {
            background-color: #0a0;
            color: #fff;
            border-top: 1px solid #675;
            border-bottom: 1px solid #372;
        }
        p.debugS, p.debugE
        {
            text-align: center;
        }
        .alert
        {
            background-image: url('alert.png');
            background-repeat: no-repeat;
            background-position: 5px 50%;
        }
        select[multiple]
        {
            height: 18px;
            display: inline;
            position: relative;
            
        }
        select[multiple]:hover
        {
            height: auto;
            vertical-align:top;
        }
        input[type=number]
        {
            width: 35px;
            background: #555;
        }
        input.expand
        {
            display: none;
        }
        input.expand:checked + div.nodedisplay
        {
            display: block;
            clear: both;
            overflow: visible;
            background-color: black;
            padding-top: 2px;
            margin-top: 4px;
        }
        button
        {
            margin: 0px;
            border: 1px solid black;
        }
        button:hover
        {
            border: 1px solid white;
        }
        hr
        {
            border-top: 1px solid grey;
            border-bottom: 0px;
            clear: both;
            margin: 2px;
            
        }
        table.abstr
        {
            
            float: left;
            width: 100%;
        }
        table.abstr td
        {
            height: 10px;
            padding: 0px;
            width: 5%;
            border: none;
            background-color: #555;
            border-radius: 3px 3px 3px 3px;
        }
        table.abstr tr:hover td
        {
            background-color: #777;
        }
        label
        {
            cursor: pointer;
            height: 15px;
        }
        span
        {
            border-radius: 5px;
            padding: 3px;
        }
        .save
        {
            background-color: #f00;
            color: #000;
        }
        input.notehide
        {
            display:none;
        }
        input.notehide:checked+label>p
        {
            display: none;
        }
    </style>
</head>
<body>
<div id="contents">
    <?php
    echo '<div id="top">'."\n".' GPU Temperature Min: '.
    '<span style="color: #0f0;" >'.config_item('mintemp').'"</span>°C '.
    'Max: <span style="color: #f00;">'.config_item('maxtemp').'</span>°C '.
    '&nbsp; | &nbsp; '.
    '[<a href="'.$_SERVER["SCRIPT_NAME"].'/manage" >MANAGE</a>]'."\n".'</div></form>'.
    //'View: [<a href="'.$_SERVER["SCRIPT_NAME"].'/simple" '.(!@$_GET['complex']?'style="color: yellow;"':'').'>Simple</a>] [<a href="'.$_SERVER["SCRIPT_NAME"].'/complex" '.(@$_GET['complex']=='1'?'style="color: yellow;"':'').'>Complex</a>]'.
    "\n";

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
