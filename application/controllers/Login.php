<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$GLOBALS['_FILENAME'] = basename(__FILE__);
class Login extends CI_Controller {
	public function execute(){
		TRC_LOG('debug',"Inside login()");
		$aStatusCode = 500;
		$aToken = $this->getAccessToken();
		$aResponse = array();
		if(!$this->sesssecurity->checkSession($aStatusCode,$aToken)){
			$this->load->model('LoginModel');
			$aData = $this->inputoutput->getRequest();
			$aResponse = $this->LoginModel->authenticate($aStatusCode,$aData);
		}else{
			TRC_LOG('debug','Already logged in');
			$aStatusCode = 400;
		}
		$this->inputoutput->setResponse($aStatusCode,$aResponse);
	}

	public function logout(){
		TRC_LOG('debug',"Inside logout");
		$this->sesssecurity->destroy();
		$this->inputoutput->setResponse(200);
	}

	private function getAccessToken(){
		if(substr($this->input->get_request_header('Authorization', TRUE),7)){
			return substr($this->input->get_request_header('Authorization', TRUE),7);
		}
		return null;
	}
}
?>
