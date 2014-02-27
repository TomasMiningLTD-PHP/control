<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//TODO
class Nodes
{
    $desc = '';
    $ips = array();
    $nodedata = array();
    
    public function addNode($ip)
    {
        $this->ips[] = $ip;
    }
}
?>
