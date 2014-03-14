<?php
class cgminerApi_model extends CI_Model
{
    public function getApiData($ip, $cmd)
    {
        $r = $this->request($ip, $cmd);
        return $r;
    }

    public function addPool($ip, $url, $user, $pass)
    {
        $r = $this->request($ip, 'addpool|'.$url.','.$user.','.$pass);
        return $r;
    }

    public function disablePool($ip, $nr)
    {
        $r = $this->request($ip, 'disablepool|'.$nr);
        return $r;
    }
    
    public function enablePool($ip, $nr)
    {
        $r = $this->request($ip, 'enablepool|'.$nr);
        return $r;
    }
    
    public function switchPool($ip, $nr)
    {
        $r = $this->request($ip, 'switchpool|'.$nr);
        return $r;
    }
    
    public function removePool($ip, $nr)
    {
        $r = $this->request($ip, 'removepool|'.$nr);
        return $r;
    }

    public function saveConfig($ip)
    {
        $r = $this->request($ip, 'save|');
    }

    
    private function request($ip, $cmd)
    {
        if(strpos($ip, ':')) //do we have IP:Port?
        {
            $connect = explode(':', $ip);
        }
        else //if not, use default port
        {
            $connect[0] = $ip;
            $connect[1] = $this->config->item('apiport');
        }
        
        $socket = $this->getsock($connect);
        
        if ($socket != null)
        {
            socket_write($socket, $cmd, strlen($cmd));
            $line = $this->readsockline($socket);
            socket_close($socket);

            if (strlen($line) == 0)
            {
                throw new Exception("$ip connected but '$cmd' returned nothing (access denied?)");
            }

            if (substr($line,0,1) == '{')
                return json_decode($line, true);

            $data = array();

            $objs = explode('|', $line);
            foreach ($objs as $obj)
            {
                if (strlen($obj) > 0)
                {
                    $items = explode(',', $obj);
                    $item = $items[0];
                    $id = explode('=', $items[0], 2);
                    if (count($id) == 1 or !ctype_digit($id[1]))
                            $name = $id[0];
                    else
                            $name = $id[0].$id[1];

                    if (strlen($name) == 0)
                            $name = 'null';

                    if (isset($data[$name]))
                    {
                            $num = 1;
                            while (isset($data[$name.$num]))
                                    $num++;
                            $name .= $num;
                    }

                    $counter = 0;
                    foreach ($items as $item)
                    {
                        $id = explode('=', $item, 2);
                        if (count($id) == 2)
                                $data[$name][$id[0]] = $id[1];
                        else
                                $data[$name][$counter] = $id[0];

                        $counter++;
                    }
                }
            }
            return $data;
        }

        return null;
    }

    private function getsock($connect)
    {
        $addr = $connect[0];
        $port = $connect[1];
        
        $socket = null;
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false || $socket === null)
        {
            $error = socket_strerror(socket_last_error());
            throw new Exception("socket create(TCP) failed");
            return null;
        }

        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec"=>5,"usec"=>0));

        $res = @socket_connect($socket, $addr, $port);
        if ($res === false)
        {
            $error = socket_strerror(socket_last_error());
            socket_close($socket);
            throw new Exception("socket connect($addr,$port) timeout - '$error'\n");
        }
        return $socket;
    }

    private function readsockline($socket)
    {
        $line = '';
        while (true)
        {
            $byte = socket_read($socket, 1);
            if ($byte === false || $byte === '')
                    break;
            if ($byte === "\0")
                    break;
            $line .= $byte;
        }
        return $line;
    }
}
