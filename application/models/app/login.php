<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Model {

    function __construct(){
        parent::__construct();

        $this->db1 = $this->load->database(DB1_GROUP,TRUE);
        $this->db2 = $this->load->database(DB2_GROUP,TRUE);
    }

    public function add($ins){        
        $this->db2->insert("app_login_partecipanti",$ins);
        return $this->db2->insert_id();
    }
    
}