<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	public function execute(){
		TRC_LOG('debug',">>>>>> execute");
		$aStatusCode = 500;
		$aToken = $this->getAccessToken();
		$aResponse = array();
		if(!$this->sesssecurity->checkSession($aStatusCode,$aToken)){
			$this->load->model('LoginModel');
			$aData = $this->inputoutput->getRequest();
			$this->db->trans_start();
			$aResponse = $this->LoginModel->authenticate($aStatusCode,$aData);
			$this->db->trans_complete();
		}else{
			TRC_LOG('debug','Already logged in');
			$aStatusCode = 400;
		}
		$this->inputoutput->setResponse($aStatusCode,$aResponse);
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->sesssecurity->destroy();
		}
		else
		{
			$this->db->trans_commit();
		}
		TRC_LOG('debug','<<<<< execute');
	}

	public function logout(){
		TRC_LOG('debug',">>>>>>> logout");
		$this->load->model('LoginModel');
		$this->LoginModel->setLog('logout');
		$this->inputoutput->setResponse(200);
	}

	private function getAccessToken(){
		TRC_LOG('debug',">>>>> getAccessToken");
		if(substr($this->input->get_request_header('Authorization', TRUE),7)){
			return substr($this->input->get_request_header('Authorization', TRUE),7);
		}
		return null;
	}
}
?>
