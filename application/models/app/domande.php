<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Domande extends CI_Model {

    function __construct(){
        parent::__construct();

        $this->db1 = $this->load->database(DB1_GROUP,TRUE);
        $this->db2 = $this->load->database(DB2_GROUP,TRUE);
    }

    public function getNumDomande($id){
        $this->db2->select("COUNT(D.id) AS tot");
        $this->db2->from("app_domande D");
        $this->db2->where("D.id_group = $id");        
        $query = $this->db2->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->tot;
        }

        return 0;
    }

    public function getRelatore($id){
        $this->db2->select("L.*");
        $this->db2->from("app_interation_list L");
        $this->db2->where("L.id = $id");   
        $this->db2->order_by("L.ordine ASC");     
        $query = $this->db2->get();        
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $relatore = array("id" => $id,
                              "nome" => $row->element,
                              "start" => $row->start,
                              "end" => $row->end,);
            return $relatore;
        }

        return NULL;
    }

    public function getDomande($id, $id_evento = 0){
        $this->db2->select("D.*, P.nome, P.mail");
        $this->db2->from("app_domande D");
        $this->db2->join("app_login_partecipanti P","P.id = D.id_partecipante");
        $this->db2->where("D.id_group",$id);
        if($id_evento != 0){
            $this->db2->where("D.id_evento",$id_evento);
        }
        $this->db2->order_by("D.data_domanda DESC");
        $query = $this->db2->get();
        //echo $this->db2->last_query();
        if ($query->num_rows() > 0 && FALSE) {
            foreach ($query->result() as $row) {
                $invio = new DateTime($row->data_domanda);

                $domande[] = array("id" => $row->id,
                                   "domanda" => $row->domanda,
                                   "nome" => $row->nome,
                                   "mail" => $row->mail,
                                   "invio" => $invio->format("d/m/Y H:i"));
            }
            return $domande;
        }

        $this->db2->select("D.*, C.nome, C.cognome, C.mail");
        $this->db2->from("app_domande D");
        $this->db2->join("crm_cliente C","C.id = D.id_partecipante");
        $this->db2->where("D.id_group",$id);
        if($id_evento != 0){
            $this->db2->where("D.id_evento",$id_evento);
        }        
        $this->db2->order_by("D.data_domanda DESC");
        $query = $this->db2->get();
        //echo $this->db2->last_query();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $invio = new DateTime($row->data_domanda);

                $domande[] = array("id" => $row->id,
                                   "domanda" => $row->domanda,
                                   "nome" => $row->nome,
                                   "cognome" => $row->cognome,
                                   "mail" => $row->mail,
                                   "invio" => $invio->format("d/m/Y H:i"));
            }
            return $domande;
        }        

        return NULL;
    }

    public function getInterationList($id_group){
        $this->db2->select("L.*");        
        $this->db2->from("app_interation_list L");
        //$this->db2->where("L.id_group = $id_group AND L.start < NOW() AND L.end > NOW()");
        $this->db2->where("L.id_group = $id_group");
        $this->db2->order_by("L.ordine ASC");
        $query = $this->db2->get();
        //echo $this->db2->last_query();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $list[] = array("start" => strtotime($row->start),
                                "end" => strtotime($row->end),
                                "relatore" => $row->element,
                                "id" => $row->id,
                                "tot" => $this->getNumDomande($row->id));
            }
            return $list;
        }

        return NULL;
    }

    public function add_domanda($ins){
        $this->db2->insert("app_domande",$ins);
        return $this->db2->insert_id();  
    }

    public function interation_list(){
        $this->db2->select("G.*");        
        $this->db2->from("app_interation_group G");
        $this->db2->where("G.visibile = '1'");
        $query = $this->db2->get();
        $flag = false;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $id_group = $row->id;
                $group = $row->group;

                $this->db2->select("L.*");
                $this->db2->from("app_interation_list L");
                $this->db2->where("L.id_group = $id_group AND L.start < NOW() AND L.end > NOW()");
                $this->db2->order_by("L.ordine ASC");
                $query2 = $this->db2->get();
                $array = array();
                if ($query2->num_rows() > 0) {
                    $flag = true;
                    foreach ($query2->result() as $row2) {
                        $array[] = array("start" => strtotime($row2->start),
                                         "end" => strtotime($row2->end),
                                         "element" => $row2->element,
                                         "ID" => $row2->id,
                                         "ty_title" => $row2->ty_title,
                                         "ty_text" => $row2->ty_text,);
                    }

                    $list[] = array("id" => $id_group,
                                    "group" => $group,
                                    "list" => $array);
                }
            }
        }

        if($flag){
            return $list;
        }

        return NULL;        
    }    
}