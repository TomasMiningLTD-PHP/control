<?php
class cgminerDb_model extends CI_Model
{
    public function __construct()
    {
        try
        {
            parent::__construct();
            $this->load->database();
            
            if(!$this->_testDb())
            {
                $this->_initDb();
            }
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

    public function getAbstractFields()
    {
        $commands = array();
        $fields = array();
        $t = 'abstractview';

        $query = $this->db->query("SELECT * FROM $t");
        $res = $query;
        
        foreach($res->result_array() as $row)
        {            
            $query = $this->db->query("SELECT command FROM commands WHERE id={$row['command']}");
            $command = $query->row_array(0);

            $query = $this->db->query("SELECT name FROM fields WHERE id={$row['field']}");
            $field = $query->row_array(0);
            $fields[$command['command']][] = $field['name'];
        }

        return $fields;
    }

    public function getAllFields()
    {
        $query = $this->db->query("SELECT * FROM fields");
        $res = $query->result_array();
        
        foreach($res as $row)
        {
            $query = $this->db->query("SELECT command FROM commands WHERE id={$row['command']}");
            $r = $query->row_array(0);
            $command = $r['command'];
            
            $fields[][$row['name']] = $command;
        }

        return $fields;
    }
    
    public function setAbstractFields($command, $field, $action = 'add')
    {
        $query = $this->db->query("SELECT id FROM commands WHERE command='$command'");
            $cmdId = $query->row_array(0);

        $query = $this->db->query("SELECT id FROM fields WHERE name='$field'");
            $fId = $query->row_array(0);
            
        if($action == 'add')
        {
            $this->db->query("INSERT INTO abstractview(command, field) VALUES({$cmdId['id']}, {$fId['id']})");
        }
        else if($action == 'rem')
        {
            $this->db->query("DELETE FROM abstractview WHERE command={$cmdId['id']} AND field={$fId['id']}");
        }
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

    private function _testDb()
    {
        $query = $this->db->query("SELECT count(*) FROM sqlite_master WHERE type='table'");
        $row = $query->row_array(0);

        if($row['count(*)'] > 0)
            return true;
        else
            return false;
    }

    private function _initDb()
    {
        $sql = "CREATE TABLE IF NOT EXISTS 'ipscfg' ('id' INTEGER PRIMARY KEY NOT NULL, 'ip' TEXT);
                CREATE TABLE IF NOT EXISTS 'fields' ('id' INTEGER PRIMARY KEY NOT NULL, 'name' TEXT, 'command' INTEGER default 0 );
                CREATE TABLE IF NOT EXISTS 'commands' ('id' INTEGER PRIMARY KEY NOT NULL, 'command' TEXT);
                CREATE TABLE IF NOT EXISTS 'abstractview' ('id' INTEGER PRIMARY KEY NOT NULL, 'command' TEXT, 'fields' TEXT);";

        $this->db->query($sql);

        $viewfields = $this->config->item('viewfields');
        
        foreach($viewfields as $cmd => $fields)
        {
            $sql = "INSERT INTO commands('command') VALUES('$cmd')";
            $this->db->query($sql);

            $sql = "SELECT id FROM commands WHERE command='$cmd'";
            $query = $this->db->query($sql);
            $row = $query->row_array();
            //yeah thats ugly, but CI messed up here with Compound Select
            //maybe sometime I will look into the abstraction to get clean
            
            foreach($fields as $field)
            {                
                $sql = "INSERT INTO fields('command', 'name') VALUES({$row['id']}, '$field')";
                $this->db->query($sql);
            }
        }

        $abstractview = $this->config->item('abstractView');
        
        foreach($abstractview as $cmd => $fields)
        {
            $query = $this->db->query("SELECT commands.id FROM commands WHERE command='$cmd'");
            $row = $query->row_array();
            
            $cmdId = $row['id'];

            foreach($fields as $field)
            {
                $query = $this->db->query("SELECT fields.id FROM fields WHERE name='$field'");
                $row = $query->row_array();

                $query = false;
                
                $fieldId = $row['id'];
                
                $this->db->query("INSERT INTO abstractview('command', 'fields') VALUES('$cmdId', '$fieldId')");
            }
        }
    }
}
