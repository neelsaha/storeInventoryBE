<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales extends CI_Controller {

    //GET count of total items sold
    public function getTotalItems($iParam = NULL){
        TRC_LOG('debug','>>>>>>>> Inside getTotalItems');
        $aToken = substr($this->input->get_request_header('Authorization', TRUE),7);
        $aStatusCode = 500;
        $aResponse = array();
        if($this->sesssecurity->checkSession($aStatusCode,$aToken,'user')){
            $aDate = date('Y-m-d');
            if($iParam != NULL){
                TRC_LOG('debug','Input Date: '.$iParam);
                $aDate = date('Y-m-d',strtotime($iParam));
            }
            $this->load->model('BillingModel');
            try{
                $this->db->trans_start();
                $aResponse = $this->BillingModel->getTotalItems($aStatusCode,$aDate);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    $aStatusCode = 500;
                }
                else
                {
                    $this->db->trans_commit();
                }
            }catch(Exception $e){
                TRC_LOG('error',$e->getMessage());
                $this->db->trans_rollback();
                $aStatusCode = 500;
            }            
        }else{
            TRC_LOG('debug','Unaothorized access');
        }
        $this->inputoutput->setResponse($aStatusCode,$aResponse);
    }

    //GET count of total amount sold
    public function getTotalSales($iParam = NULL){
        TRC_LOG('debug','>>>>>>>> Inside getTotalSales');
        $aToken = substr($this->input->get_request_header('Authorization', TRUE),7);
        $aStatusCode = 500;
        $aResponse = array();
        if($this->sesssecurity->checkSession($aStatusCode,$aToken,'user')){
            $aDate = date('Y-m-d');
            if($iParam != NULL){
                TRC_LOG('debug','Input Date: '.$iParam);
                $aDate = date('Y-m-d',strtotime($iParam));
            }
            $this->load->model('BillingModel');
            try{
                $this->db->trans_start();
                $aResponse = $this->BillingModel->getTotalSales($aStatusCode,$aDate);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    $aStatusCode = 500;
                }
                else
                {
                    $this->db->trans_commit();
                }
            }catch(Exception $e){
                TRC_LOG('error',$e->getMessage());
                $this->db->trans_rollback();
                $aStatusCode = 500;
            }            
        }else{
            TRC_LOG('debug','Unaothorized access');
        }
        $this->inputoutput->setResponse($aStatusCode,$aResponse);
    }
}
?>
