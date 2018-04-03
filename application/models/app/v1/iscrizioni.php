<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Iscrizioni extends CI_Model {

    function __construct()
    {
        parent::__construct();

        $this->load->database();
    }   

    public function getIscrizione($id_evento, $id_cliente = NULL){
        if($id_cliente != NULL){
            $this->db->select("I.id");
            $this->db->from("crm_iscrizioni_def I");
            $this->db->join("crm_iscrizioni_tipo T","T.sigla = I.tipo");
            $this->db->where("I.id_cliente", $id_cliente);
            $this->db->where("I.evento", $id_evento);
            $this->db->where("T.valore", "p");
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
}