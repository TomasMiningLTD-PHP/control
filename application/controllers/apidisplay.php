<?php

class Apidisplay extends CI_Controller
{
    private $ips    = '';
    private $cmds   = '';
    
    public function __construct()
	{
        //error_reporting(0);
		parent::__construct();
        
		$this->load->model('cgminerApi_model');
        
        $this->load->model('cgminerDb_model');

        $this->ips = $this->cgminerDb_model->getIps();

        $this->cmds = config_item('apicmds');

        $this->data['title'] = 'Cgminer API View';

	}
    
    public function index()
    {
        $apidata = array();
        $this->data['errors'] = '';
        $this->data['notices'] = '';
        $this->data['ip'] = '';
        $dead = array();

        $inputip = $this->input->post('ip');
        
        if(!empty($inputip))
        {
            $ip   = $this->input->post('ip');
            $url  = $this->input->post('purl');
            $user = $this->input->post('puser');
            $pass = $this->input->post('ppass');

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

            $this->data['notices'] = $result;
        }
    
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
        
        $this->data['ips'] = $db->getIps();
        
        $this->load->view('managerview', $this->data);
    }

    public function complex()
    {
        $this->index();
    }
}
?>
