<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LoginModel extends CI_Model {
    private $_token;
    private $_data;
    public function authenticate(&$oStatusCode,$iParam){
        $aResponse = array();
        TRC_LOG('debug',">>>> authenticate");
        TRC_LOG('debug',"Authenticating for user: ".$iParam['username']);
        $aResult = $this->db->get_where('login',$iParam);
        $this->_data = $aResult->row();
        if(isset($iParam['username']) && isset($iParam['password']) && $aResult->num_rows() == 1){
            $this->_token = $this->generateToken();
            $this->setSession();
            $this->setLog('login');
            $aResponse = $this->getResponse();
            $oStatusCode = 200;
        }else{
            $oStatusCode = 401;
            TRC_LOG('debug',"Invalid credentials");
        }
        return $aResponse;
    }
    private function setSession(){
        TRC_LOG('debug',">>>>>> setSession");
        try{
            $aSessData = array(
                'role' => $this->_data->role_id,
                'sessionId' => session_id(),
                'username' => $this->_data->username,
                'userId' => $this->_data->id,
                'token' => $this->_token
            );
            $_SESSION['sessArr'] = $aSessData; 
        }catch(Exception $e){
            TRC_LOG('error',"Error: ".$e->errorMessage());
        }
    }
    private function generateToken(){
        TRC_LOG('debug',">>>> generateToken");
        return bin2hex(random_bytes(32));
    }
    private function getResponse(){
        TRC_LOG('debug',">>>>> getResponse");
        $aResponse = array(
            'role' => $this->_data->role_id,
            'userId' => $this->_data->id,
            'username' => $this->_data->username,
            'token' => $this->_token
        );
        return $aResponse;
    }
    public function setLog($iKey){
        TRC_LOG('debug','>>>>>> setLog for '.$iKey);
        try {
            $aUserId = $this->sesssecurity->getVariableValue('userId');
            if($aUserId == ""){
                throw new Exception("No user_id in session");
            }
            if($iKey == 'login')
            {
                $aData = array(
                    'user_id' => $aUserId
                );
                $this->db->insert('time_log', $aData);
            }else{
                $this->db->from('time_log');
                $this->db->where('user_id =', $aUserId);
                $this->db->order_by('id','DESC');
                $aResult = $this->db->get()->row();

                $this->db->set('logout_timestamp', date('Y-m-d H:i:s'));
                $this->db->where('id =', $aResult->id);
                $this->db->update('time_log');
                $this->session->sess_destroy();
            }
        } catch (Exception $e) {
            TRC_LOG('error',"Error: ".$e->getMessage());
        }
    }
}

?>