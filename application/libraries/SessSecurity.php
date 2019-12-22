<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$GLOBALS['_FILENAME'] = basename(__FILE__);
class SessSecurity{
    private static $_roles = array(
        "user" => 1,
        "admin" => 2
    );
    public function checkSession(&$oStatusCode,$iToken,$iRole = null){
        TRC_LOG('debug',"REQUEST TYPE :: ".$_SERVER['REQUEST_METHOD']);
        TRC_LOG('debug',">>>>>>> checkSession");
        $aStatus = false;
        //echo ($_SESSION['sessArr']['token']==$iToken);
        if(isset($_SESSION['sessArr']) && isset($_SESSION['sessArr']['token']) && $_SESSION['sessArr']['token'] == $iToken){
            if(!$iRole || $this->checkRole($iRole)){
                $oStatusCode = 200;
                $aStatus = true;
            }else{
                $oStatusCode = 403;
            }
        }else{
            $oStatusCode = 401;
        }
        TRC_LOG('debug',"Check status: ".($aStatus?'true':'false')." StatusCode: ".$oStatusCode);
        return $aStatus;
    }
    
    public function getVariableValue($iKey){
        TRC_LOG('debug',">>>>> getVariableValue key: ".$iKey);
        $aValue = "";
        if(isset($_SESSION['sessArr'][$iKey])){
            $aValue = $_SESSION['sessArr'][$iKey];
        }else{
            TRC_LOG('error',"No value found for variable: ".$iKey);
        }
        TRC_LOG('debug',"Returning key: ".$iKey." value: ".$aValue);
        return $aValue;
    }
    private function checkRole($iRole){
        $aStatus = false;
        TRC_LOG('debug','Session Role: '.$this->getVariableValue('role').' Required Role: '.self::$_roles[$iRole]);
        if($_SESSION['sessArr']['role'] >= self::$_roles[$iRole]){
            $aStatus = true;
        }
        TRC_LOG('debug',"CheckRole status: ".($aStatus?'true':'false'));
        return $aStatus;
    }
}
?>