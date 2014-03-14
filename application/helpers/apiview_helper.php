<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

function genApiTable($data, $ip)
{
    var_dump($data);
    $mintemp = config_item('mintemp');
    $maxtemp = config_item('maxtemp');

    $queries = '';
    $debug = '';
    $nodes = array();

    $table = '';

    $views = config_item('viewfields');
    $dbFields = config_item('dbFields'); 
    $dbmap = dbFields('dbmap');
    
    $ran = false;
    
    foreach($data as $hkey => $e)
    {
        ob_implicit_flush(true);

        if($hkey == 'STATUS')
        {
            if(!$ran)
            {
                $desc = $data['STATUS']['Description'];
                if(in_array($desc, $nodes))
                    die ("<span style=\"color: red;\">WARNING: $desc appears to exist twice!</span>)</h1>");
                else
                {
                    $nodes[] = $desc;
                }

                /*db
                (
                    array
                    (
                        'nodes' => array
                        (
                            'ip' => $ip,
                            'mac' => '0:0',
                            'apidesc' => $desc
                        )
                    )
                );*/
            }
            $ran = true;
            
            continue;
        }
        
        $table.= '<table id="'.$hkey.'">';
        $table.= '<tr>';
        $table.= '<th colspan="2">';

        $table .= $hkey;
            
        $table .= '</th></tr>';
        
        $i = 0; $tdk = ''; $tdv = ''; $td = '';
        
        $mkey = matchKey($hkey, $views);
        $dbmkey = matchKey($hkey, $dbFields);
        $dbdata = '';

        foreach($e as $key => $value)
        {
            if($mkey && !in_array($key, $views[$mkey])) continue;

            $style = '';
            $table.= "<tr>\n";
            if(($key == $hkey) || ($hkey == $key.$value)) continue;
            $i++;
            $table.= "\t<td>$key</td>\n";

            $val = $value;
            
            if($key == 'Temperature')
            {
                $h = heatcolor($value, $maxtemp, $mintemp);
                
                $style = 'style="background-color: hsl('.$h.',100%,50%); '.($value>$maxtemp?'color: yellow; font-weight: bold;" class="alert"':'color: black;"');
                $val = ceil($value).'°C';
            }
            else if($key == 'Hardware Errors')
            {
                $h = heatcolor($value, 10, 0);
                
                $style = 'style="color: hsl('.$h.',100%,50%);" '.($h<10?'class="alert"':'');
            }
            else if(($key == 'Last Share Time') || ($key == 'Last Valid Work') || ($key == 'Current Block Time'))
            {
                $val = @date('Y-m-d H:i:s', $value);
            }
            
            $table.= "\t<td $style>$val</td>\n";
            $table.= "</tr>\n";
            
            if($dbmkey && @in_array($dbmap[strtolower($key)], $dbFields[$dbmkey]))
            {
                $dbdata[$dbmkey][$dbmap[strtolower($key)]] = $value;
                $dbdata[$dbmkey]['hkey'] = $hkey;
                $dbdata[$dbmkey]['apidesc'] = $desc;
            }
        }
        $table.= '</table>';

        //if($dbdata != '')
        //    db($dbdata);
    }

    return $table;
}

function getAbstract($data, $ip, $views)
{
    $abstrmap = config_item('abstrmap');
    
    $table = '<table class="abstr"><tr>';
    $table.= '<td>'.$ip.'</td>';
    
    foreach($data as $cmd => $result)
    {
        foreach($result as $field => $values)
        {
            if($field == 'STATUS') continue;
            if(@$values['Stratum Active'] == 'false') continue;
            
            foreach($values as $key => $value)
            {
                if(@in_array($key, $views[$cmd]) && ($key != '0'))
                {
                    $mintemp = config_item('mintemp');
                    $maxtemp = config_item('maxtemp');
                    $val = $value;
                    $style = '';
                    
                    if($key == 'Temperature')
                    {
                        $h = heatcolor($value, $maxtemp, $mintemp);
                        
                        $style = 'style="background-color: hsl('.$h.',100%,50%); '.($value>$maxtemp?'color: yellow; font-weight: bold;" class="alert"':'color: black;"');
                        $val = ceil($value).'°C';
                    }
                    else if($key == 'Hardware Errors')
                    {
                        $h = heatcolor($value, 10, 0);
                        
                        $style = 'style="color: hsl('.$h.',100%,50%);" '.($h<10?'class="alert"':'');
                    }
                    else if(($key == 'Last Share Time') || ($key == 'Last Valid Work') || ($key == 'Current Block Time'))
                    {
                        $val = @date('Y-m-d H:i:s', $value);
                    }
                    else if($key == 'Fan Percent')
                    {
                        $val = $value.'%';
                    }
                    
                    if(!empty($abstrmap[$key]))
                    {
                        $key = $abstrmap[$key];
                    }
                    
                    $table .= "<td $style>$key $val</td>";
                }
            }
        }
    }

    $table .= '</tr></table>';

    return $table;
}

function getApiView($data, $ip, $exclude = '')
{
    $views = config_item('viewfields');

    $table = '';
    
    foreach($data as $cmd => $result)
    {
        if($cmd == $exclude) continue;
        
        foreach($result as $field => $values)
        {
            if($field == 'STATUS') continue;
            
            $table .= '<table>';
            $table .= '<tr>';
            $table .= "\t<th colspan=\"2\">$field</th>\n";
            $table .= '</tr>';
            foreach($values as $key => $value)
            {
                if(@in_array($key, $views[$cmd]) && ($key != '0'))
                {
                    $mintemp = config_item('mintemp');
                    $maxtemp = config_item('maxtemp');
                    $val = $value;
                    $style = '';
                    
                    if($key == 'Temperature')
                    {
                        $h = heatcolor($value, $maxtemp, $mintemp);
                        
                        $style = ' style="background-color: hsl('.$h.',100%,50%); '.($value>$maxtemp?'color: yellow; font-weight: bold;" class="alert"':'color: black;"');
                        $val = ceil($value).'°C';
                    }
                    else if($key == 'Hardware Errors')
                    {
                        $h = heatcolor($value, 10, 0);
                        
                        $style = ' style="color: hsl('.$h.',100%,50%);" '.($h<10?'class="alert"':'');
                    }
                    else if(($key == 'Last Share Time') || ($key == 'Last Valid Work') || ($key == 'Current Block Time'))
                    {
                        $val = @date('Y-m-d H:i:s', $value);
                    }
                    $table .= '<tr>';
                    $table .= "\t<td>$key</td><td$style>$val</td>\n";
                    $table .= '</tr>';
                }
            }

            $table .= '</table>';
        }
    }

    return $table;
}

function getPoolView($data, $ip, $view = 'simple')
{
    $status = array_shift($data);
    
    $rows = '';
    $head = "<tr>\n";

    $views = config_item('viewfields');

    foreach($data as $poolnr => $pooldata)
    {
        $rows .= "<tr>\n";
        foreach($pooldata as $key => $v)
        {
            if(in_array($key, $views['pools']))
            {
                $rows .= "\t<td>$v</td>\n";
                $keys[$key] = $key;
            }
        }
        $rows .= "\t<td style=\"min-width: 180px;\">\n";
            $rows .= '<button style="background-color: red;" name="rempool" type="submit" value="'.filter_var($poolnr, FILTER_SANITIZE_NUMBER_INT).'">Rem</button>';
            $rows .= '<button style="background-color: #4f0;" name="swpool" type="submit" value="'.filter_var($poolnr, FILTER_SANITIZE_NUMBER_INT).'">Sw</button>';
            $rows .= '<button style="background-color: yellow;" name="enpool" type="submit" value="'.filter_var($poolnr, FILTER_SANITIZE_NUMBER_INT).'">En</button>';
            $rows .= '<button style="background-color: grey;" name="dispool" type="submit" value="'.filter_var($poolnr, FILTER_SANITIZE_NUMBER_INT).'">Dis</button>';
        $rows .= "</td>\n";
            
        $rows.= '</tr>';
    }
    
    foreach($keys as $key)
    {
        $head .= "\t<th>$key</th>\n";
    }
    $head .= "\t<th>Control</th>\n";
    $head.= '</tr>';

    $table = "<table>\n";
    $table.= $head;
    $table.= $rows;
    $table.= "</table>\n";

    return $table;
}

function heatcolor($value, $max, $min, &$percent = 0)
{
    $ep = ($max - $min)/100;
    
    $a = $value - $min;
    $percent = ceil($a/$ep);
    $h = 100-$percent; 

    $h = ($h>100?100:$h);
    $h = ($h<0?0:$h);

    return $h;
}

function matchKey($search, $hay)
{
    if(is_array($hay))
        $keys = array_keys($hay);
    else
        $keys = array($hay);
    
    foreach($keys as $key)
    {
        $pos = @strpos(strtolower($search), strtolower($key));
        if($pos !== false) return $key;
    }
    return false;
}

function urlparam($parameter = '', $value = '') 
{
    $output = '';
    $params = array(); 
    $firstRun = true; 
    foreach($_GET as $key=>$val) 
    { 
        if($key != $parameter) 
        { 
            if(!$firstRun) 
            { 
                $output .= '&'; 
            } 
            else 
            { 
                $firstRun = false; 
            } 
            $output .= $key."=".urlencode($val); 
        } 
    } 
    if($parameter != '')
    {
        if(!$firstRun) 
            $output .= '&';
        $output .= $parameter.'='.urlencode($value);
    }
    return '?'.htmlentities($output); 
}
?>
