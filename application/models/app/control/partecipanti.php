<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partecipanti extends CI_Model {

    function __construct()
    {
        parent::__construct();

        $this->db1 = $this->load->database(DB1_GROUP,TRUE);
        $this->db2 = $this->load->database(DB2_GROUP,TRUE);
        $this->load->model("app/v1/domande");
    }

    public function getPartecipanti($id_evento){
        $this->db1->select("C.id, C.nome, C.cognome, C.mail, P.professione");
        $this->db1->from("crm_iscrizioni_def I");
        $this->db1->join("crm_cliente C","C.id = I.id_cliente");
        $this->db1->join("crm_professioni_utenti P","P.id = C.professione");
        $this->db1->where("I.evento",$id_evento);
        $this->db1->order_by("C.cognome, C.nome");
        $query = $this->db1->get();
        //echo $this->db1->last_query();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $partecipanti[] = array("id" => $row->id,
                                 "nome" => $row->nome,
                                 "cognome" => $row->cognome,
                                 "mail" => $row->mail,
                                 "professione" => $row->professione,
                                 "flag_networking" => $this->domande->stato_interation_list($row->id,1,$id_evento)
                                 );
            }
            return $partecipanti;
        }

        return array();
    }
}