<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Model {

    function __construct(){
        parent::__construct();

        $this->load->database();

        $this->load->library('encrypt');
        $this->load->model("app/v1/profilo");
    }

    public function doLoginFromID($real_id, $random_id, $username, $pwd){
        if(!$this->idAvailable($real_id, $random_id)){
            return array(0);
        }

        if($this->checkUsername($username)){
            return array(-1);
        }        

        $id_cliente = $this->getClienteFromID($real_id);
        $this->setUsername($username, $id_cliente);
        $this->setPassword($pwd, $id_cliente);
        $cliente = $this->profilo->getProfilo($id_cliente);
        $cliente->token = $this->setToken($id_cliente);
        return array(1, $cliente);
    }

    private function checkUsername($username){
        $this->db->select("C.username");
        $this->db->from("crm_cliente C");        
        $this->db->where("C.username",$username);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        }

        return FALSE;
    }

    private function setUsername($username, $id){
        $upd = array("username" => $username);
        $this->db->where("id",$id);
        $this->db->update("crm_cliente",$upd);
    }

    private function setPassword($pwd, $id){
        $pwd = $this->encrypt->sha1($pwd);

        $upd = array("password" => $pwd);
        $this->db->where("id",$id);
        $this->db->update("crm_cliente",$upd);
    }

    private function getClienteFromID($id){
        $this->db->_protect_identifiers=false;
        $this->db->select("C.*, IF(C.share_profilo_app,'true','false' ) AS flag_share");
        $this->db->from("crm_cliente C");
        $this->db->join("crm_iscrizioni_def I","I.id_cliente = C.id");
        $this->db->where("I.id",$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->id;
        }

        return -1;
    }

    public function doLogin($username, $pwd){
        $pwd = $this->encrypt->sha1($pwd);

        $this->db->_protect_identifiers=false;
        $this->db->select("C.*, IF(C.share_profilo_app,'true','false' ) AS flag_share");
        $this->db->from("crm_cliente C");
        $this->db->where("C.username = '$username' AND C.password = '$pwd'");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();

            $cliente = $this->profilo->getProfilo($row->id);            
            $cliente->token = $this->setToken($row->id);
            return array(1, $cliente);
        } 
        return array(0);
    }

    public function add($ins){
        $this->db->insert("app_login_partecipanti",$ins);
        return $this->db->insert_id();
    }
    
    public function controlToken($token){
        $this->db->select("C.id");
        $this->db->from("crm_cliente C");
        $this->db->where("C.token = '$token' AND C.token_expire > NOW()");
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return FALSE;
        }

        return TRUE;
    }

    public function updateToken($token){

    }

    public function getIdByToken($token){
        return $this->encrypt->decode($token);
    }

    private function idAvailable($real_id, $random_id){
        $this->db->select("I.id");
        $this->db->from("crm_iscrizioni_def I");
        $this->db->where("I.id", $real_id);
        $this->db->where("I.codice_random", $random_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return TRUE;            
        }
        return FALSE;
    }

    private function setToken($id){
        $token = $this->encrypt->encode($id);
        $this->insertLogin($id,$token);
        return $token;
    }

    private function insertLogin($id_cliente,$token){
        $now = new DateTime();
        $add = $now;
        $interval = new DateInterval("P6M");
        $add->add($interval);

        $now = $now->format("Y-m-d H:i:s");
        $add = $add->format("Y-m-d H:i:s");
        $upd = array("last_login" => $now,
                     "token_expire" => $add,
                     "token" => $token);
        $this->db->where("id = $id_cliente");
        $this->db->update("crm_cliente",$upd);
    }

}