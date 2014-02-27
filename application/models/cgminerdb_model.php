<?php
class cgminerDb_model extends CI_Model
{
    public function __construct()
    {
        try
        {
            parent::__construct();
            $this->load->database();
        }
        catch(Exception $e)
        {
            throw new Exception("Couldnt initialize database: $e\n");
        }
    }

    public function getIps()
    {
        $result = $this->db->get('ipscfg');

        foreach($result->result_array() as $row)
        {
            $ips[] = $row['ip'];
        }

        return $ips;
    }

    public function setIps($ips)
    {
        $this->db->empty_table('ipscfg');
        
        foreach($ips as $ip)
        {
            $result = $this->db->insert('ipscfg', array('ip' => $ip));
        }

        return $result;
    }
}
