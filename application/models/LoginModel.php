<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LoginModel extends CI_Model {
    private $_token;
    private $_data;
    public function authenticate(&$oStatusCode,$iParam){
        $aResponse = array();
        TRC_LOG('debug',"Inside authenticate");
        TRC_LOG('debug',"Authenticating for user: ".$iParam['username']);
        //log_message('debug',"<".$GLOBALS['_FILENAME'].">:: :: Authenticating for user: ".$aUsername);
        $this->_data = $this->db->get_where('login',$iParam)->row();
        if($this->_data){
            $this->_token = $this->generateToken();
            $this->setSession();
            $aResponse = $this->getResponse();
            $oStatusCode = 200;
        }else{
            $oStatusCode = 404;
            TRC_LOG('debug',"Invalid credentials");
        }
        return $aResponse;
    }
    private function setSession(){
        TRC_LOG('debug',"Inside setSession");
        try{
            $aSessData = array(
                'role' => $this->_data->role,
                'sessionId' => session_id(),
                'username' => $this->_data->username,
                'userId' => $this->_data->user_id,
                'token' => $this->_token,
                'orga' => $this->_data->organization_id
            );
            $_SESSION['sessArr'] = $aSessData; 
        }catch(Exception $e){
            TRC_LOG('error',"Error: ".$e->errorMessage());
        }
    }
    private function generateToken(){
        TRC_LOG('debug',"Inside generateToken");
        return bin2hex(random_bytes(32));
    }
    private function getResponse(){
        TRC_LOG('debug',"Inside getResponse");
        $aResponse = array(
            'role' => $this->_data->role,
            'username' => $this->_data->username,
            'token' => $this->_token
        );
        return $aResponse;
    }
}

?>