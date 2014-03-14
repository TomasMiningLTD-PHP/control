<?php
/*
 * TODO
 *
 * Bulk remove/disable/enable/switch pool
 * Change visible tablefields
 * Refresh
 * 
*/
class Apidisplay extends CI_Controller
{
    private $ips    = '';
    private $cmds   = '';
    
    public function __construct()
	{
        error_reporting(0);
		parent::__construct();

        try
        {
            $this->load->model('cgminerDb_model');
        }
        catch(Exception $e)
        {
            die('Could not initialize database. :(');
        }
        
		$this->load->model('cgminerApi_model');

        $this->ips = $this->cgminerDb_model->getIps();
        $this->abstractview = $this->cgminerDb_model->getAbstractFields();
        
        $this->cmds = config_item('apicmds');

        $this->data['title'] = 'Cgminer API View';
	}
    
    public function index()
    {
        $apidata                = array();
        $this->data['errors']   = '';
        $this->data['notices']  = '';
        $this->data['ip']       = '';
        $dead                   = array();

        $inputip = $this->input->post('ip');

        if(!empty($inputip))
        {
            $this->addPool($inputip);
        }

        if($this->ips[0] != '')
        {
            foreach($this->ips as $ip)
            {
                foreach($this->cmds as $cmd)
                {
                    if(in_array($ip, $dead)) continue;

                    try
                    {
                        $apidata[$cmd] = $this->cgminerApi_model->getApiData($ip, $cmd);
                    }
                    catch(Exception $e)
                    {
                        $this->data['errors'][] = $e->getMessage();
                        $dead[] = $ip;
                        continue;
                    }
                    
                    $this->data['apidata'][$ip] = $apidata;
                }
            }
        }
        else
        {
            $this->data['errors'][] = 'Warning, you have no IPs configured, please to to <a style="color: white;" href="'.base_url().'index.php/manage">>CONFIG<</a> and add your miners!';
        }

        $this->data['abstractview'] = $this->abstractview;
        $this->data['expanded'] = $this->input->post('expanded');
        $this->load->view('apiview', $this->data);
    }

    public function manage()
    {        
        $db = $this->cgminerDb_model;
        
        $manageip = $this->input->post('manageips');

        if(!empty($manageip))
        {
            $ips = explode("\n", trim($this->input->post('ips')));
            
            $db->setIps($ips);

            $this->data['notices'] = 'Your IPs have been updated!';
        }

        $allfields = $this->input->post('allfields');
        
        if($allfields !== false)
        {
            foreach($allfields as $item)
            {
                $field = explode(':', $item);

                $db->setAbstractFields($field[0], $field[1]);
            }
        }

        $remfield = $this->input->post('remfield');

        if($remfield !== false)
        {
            foreach($remfield as $item)
            {
                $field = explode(':', $item);

                $db->setAbstractFields($field[0], $field[1], 'rem');
            }
        }
        
        $this->data['ips'] = $db->getIps();

        $this->data['abstractview'] = $db->getAbstractFields();

        $this->data['allfields'] = $db->getAllFields();
        
        $this->load->view('managerview', $this->data);
    }

    public function complex()
    {
        $this->index();
    }

    private function addPool($inputip)
    {
        $users = '';
        
        if($inputip == 'allIps')
        {
            foreach($this->ips as $ip)
            {
                $ips[] = $ip;
            }
        }
        else
        {
            $ips[]   = $this->input->post('ip');
        }

        $user = $this->input->post('puser');
        
        if(strpos($user, '#') !== false)
        {
            $user = explode('#', $user);
            for($i = 1; $i <= count($ips); $i++)
            {
                $users[$i] = $user[0].$i;
            }
        }

        $url  = $this->input->post('purl');
        $pass = $this->input->post('ppass');

        $i = 0;

        foreach($ips as $ip)
        {
            if(is_array($users))
                $user = $users[++$i];
                
            if($ip && $url && $user && $pass)
                $result[] = $this->cgminerApi_model->addPool($ip, $url, $user, $pass);
            else if($this->input->post('dispool') !== false)
                $result[] = $this->cgminerApi_model->disablePool($ip, $this->input->post('dispool'));
            else if($this->input->post('enpool') !== false)
                $result[] = $this->cgminerApi_model->enablePool($ip, $this->input->post('enpool'));
            else if($this->input->post('swpool') !== false)
                $result[] = $this->cgminerApi_model->switchPool($ip, $this->input->post('swpool'));
            else if($this->input->post('rempool') !== false)
                $result[] = $this->cgminerApi_model->removePool($ip, $this->input->post('rempool'));
            else if($this->input->post('save') !== false)
                $result[] = $this->cgminerApi_model->saveConfig($ip);
        }
        $this->data['notices'] = $result;
    }
}
?>
