<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Domande extends CI_Model {

    function __construct(){
        parent::__construct();

        $this->load->database();
    }

    public function getNumDomande($id){
        $this->db->select("COUNT(D.id) AS tot");
        $this->db->from("app_domande D");
        $this->db->where("D.id_group = $id");        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->tot;
        }

        return 0;
    }

    public function getRelatore($id){
        $this->db->select("L.*");
        $this->db->from("app_interation_list L");
        $this->db->where("L.id = $id");   
        $this->db->order_by("L.ordine ASC");     
        $query = $this->db->get();
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

    public function getDomande($id){
        $this->db->select("D.*, P.nome, P.mail");
        $this->db->from("app_domande D");
        $this->db->join("app_login_partecipanti P","P.id = D.id_partecipante");
        $this->db->where("D.id_group = $id");        
        $this->db->order_by("D.data_domanda DESC");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
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

        return NULL;
    }

    public function getInterationList($id_group){
        $this->db->select("L.*");        
        $this->db->from("app_interation_list L");
        $this->db->where("L.id_group = $id_group AND L.start < NOW() AND L.end > NOW()");
        $this->db->order_by("L.ordine ASC");
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $interation_list_obj = new StdClass();


                $interation_list_obj->start = strtotime($row->start);
                $interation_list_obj->end = strtotime($row->end);
                $interation_list_obj->relatore = $row->element;
                $interation_list_obj->id = $row->id;
                $interation_list_obj->tot = $this->getNumDomande($row->id);

                $list[$i] = $interation_list_obj;
            }
            return $list;
        }

        return NULL;
    }

    public function stato_interation_list($id_cliente, $id_interation, $id_evento){
        $this->db->select("D.id");
        $this->db->from("app_domande D");
        $this->db->where("D.id_partecipante",$id_cliente);
        $this->db->where("D.id_group",$id_interation);
        $this->db->where("D.id_evento",$id_evento);
        $query = $this->db->get();
        $flag = false;
        if ($query->num_rows() > 0) {
            $flag = true;
        }

        return $flag;
    }

    public function getTotIscritti($id_interation, $id_evento){
        $this->db->select("COUNT(D.id) AS tot");
        $this->db->from("app_domande D");        
        $this->db->where("D.id_group",$id_interation);
        $this->db->where("D.id_evento",$id_evento);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->tot;
        }
        return 0;
    }    

    public function getIscrittiList($id_interation, $id_evento, $start, $filter = NULL){
        $this->load->model("test/profilo");
        $this->db->select("D.id, D.id_partecipante");        
        $this->db->from("app_domande D");
        $this->db->join("crm_cliente C","C.id = D.id_partecipante");
        if($filter != NULL){
            foreach($filter as $key=>$f){
                if($f != NULL){
                    $this->db->where("C.$key",$f);
                }
            }
        }
        $this->db->where("D.id_group",$id_interation);
        $this->db->where("D.id_evento",$id_evento);
        $this->db->limit(PAG_LIMIT, $start * PAG_LIMIT);
        $query = $this->db->get();                
        if ($query->num_rows() > 0) {
            $i = 0;            
            foreach ($query->result() as $row) {
                $list_iscritti = new StdClass();

                $list_iscritti->id = $row->id;
                $list_iscritti->id_partecipante = $row->id_partecipante;
                $list_iscritti->id_partecipante = 26216;
                $cliente = $this->profilo->getProfilo($list_iscritti->id_partecipante);
                $list_iscritti->nome = $cliente->nome;
                $list_iscritti->cognome = $cliente->cognome;
                $list_iscritti->professione = $cliente->professione;            
                $list_iscritti->provincia = $cliente->provincia;

                $iscritti[$i++] = $list_iscritti;
            }            
            return $iscritti;
        }
        return NULL;
    }

    public function delete_interation($del){
        $this->db->where("id_group",$del["id_group"]);
        $this->db->where("id_partecipante",$del["id_partecipante"]);
        $this->db->where("id_evento",$del["id_evento"]);
        $this->db->delete("app_domande");

        return TRUE;
    }

    public function add_domanda($ins){
        $this->db->insert("app_domande",$ins);
        return $this->db->insert_id();  
    }

    public function interation_list(){
        $this->db->select("G.*");
        $this->db->from("app_interation_group G");
        $this->db->where("G.visibile = '1'");
        $query = $this->db->get();
        $flag = false;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $id_group = $row->id;
                $group = $row->group;

                $this->db->select("L.*");
                $this->db->from("app_interation_list L");
                $this->db->where("L.id_group = $id_group AND L.start < NOW() AND L.end > NOW()");
                //$this->db->where("L.id_group = $id_group");
                $this->db->order_by("L.ordine ASC");
                $query2 = $this->db->get();
                $array = array();
                if ($query2->num_rows() > 0) {
                    $flag = true;
                    foreach ($query2->result() as $row2) {
                        $data = date("Y-m-d H:i:s");
                        $incrementedDate = date("Y-m-d H:i:s",strtotime($data)+86400*2);            

                        $array[] = array("start" => strtotime($data),
                                         "end" => strtotime($incrementedDate),
                                         "element" => $row2->element,
                                         "ID" => $row2->id,
                                         "ty_title" => $row2->ty_title,
                                         "ty_text" => $row2->ty_text,);
                    }                    
                }

                $list[] = array("id" => $id_group,
                                "group" => $group,
                                "list" => $array);
            }
        }
        
        return $list;

    }    
}