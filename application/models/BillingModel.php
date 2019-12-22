<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BillingModel extends CI_Model {
    private static $_roles = array(
        "user" => 1,
        "admin" => 2
    );

    //fetching total number of items sold in a perticular date
    public function getTotalItems(&$oStatusCode,$iDate){
        TRC_LOG('debug','>>>>>>>>>> Inside getTotalItems for date: '.$iDate);
        $aData = Array();
        try{
            $this->db->select_sum('quantity');
            $this->db->from('bill_master');
            $this->db->join('bill_items','bill_master.bill_id = bill_items.bill_id');
            $this->db->where(array('DATE(timestamp) =' => $iDate));
            $aResult = $this->db->get()->row();
            TRC_LOG('debug',"QUERY :: ".$this->db->last_query());
            TRC_LOG('debug',"DATA :: ".json_encode($aResult, JSON_UNESCAPED_SLASHES));
            $aData = array('nItems' => $aResult->quantity);
            $oStatusCode = 200;
        }catch(Exception $e){
            TRC_LOG('error',"ERROR: ".$e->getMessage());
            $oStatusCode = 500;
        }
        
        return $aData;
    }

    //fetching total amount sold in a perticular date
    public function getTotalSales(&$oStatusCode,$iDate){
        TRC_LOG('debug','>>>>>>>>>> Inside getTotalSales for date: '.$iDate);
        $aData = Array();
        try{
            $this->db->select_sum('amount');
            $this->db->from('bill_master');
            $this->db->where(array('DATE(timestamp) =' => $iDate));
            $aResult = $this->db->get()->row()->amount;
            TRC_LOG('debug',"QUERY :: ".$this->db->last_query());
            TRC_LOG('debug',"DATA :: ".json_encode($aResult, JSON_UNESCAPED_SLASHES));
            $aData = array('totalSales' => $aResult);
            $oStatusCode = 200;
        }catch(Exception $e){
            TRC_LOG('error',"ERROR: ".$e->getMessage());
            $oStatusCode = 500;
        }
        
        return $aData;
    }
}

?>