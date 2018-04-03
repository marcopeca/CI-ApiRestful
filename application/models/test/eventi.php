<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eventi extends CI_Model {

  function __construct(){
    parent::__construct();

    $this->db1 = $this->load->database(DB1_GROUP,TRUE);
    $this->db2 = $this->load->database(DB2_GROUP,TRUE);
}

public function getTipoMateriali($id_evento, $position){
    $this->db1->select("T.id, T.tipo");
    $this->db1->distinct();
    $this->db1->from("app_eventi_materiali M");
    $this->db1->join("app_eventi_materiali_tipo T","T.id = M.id_tipo");
    $this->db1->where("M.id_dettaglio", $id_evento);
    $this->db1->where("T.position", $position);
    $query = $this->db1->get();    
    if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
            $tipo[] = array("id" => $row->id,
                            "tipo" => $row->tipo);
        }
        return $tipo;
    }
    return NULL;
}

public function getMaterialiFromTipo($id_tipo, $id_evento){
    $this->db1->select("M.*");    
    $this->db1->from("app_eventi_materiali M");    
    $this->db1->where("M.id_dettaglio", $id_evento);
    $this->db1->where("M.id_tipo", $id_tipo);
    $this->db1->order_by("M.ordine ASC");
    $query = $this->db1->get();    
    if ($query->num_rows() > 0) {
        $return_arr = array();
        foreach ($query->result() as $row) {
            $materiale = new stdClass();
            $materiale->titolo = $row->titolo;
            $materiale->descrizione = $row->desc;
            $materiale->url = $row->materiale_url;
            $materiale->img = $row->img;
            array_push($return_arr, $materiale);
        }        
        return $return_arr;
    }
    return NULL;
}

public function dettaglio($id_evento, $cliente){
    $this->db1->select("E.evento, D.*");
    $this->db1->distinct();
    $this->db1->from("crm_eventi_def E");
    $this->db1->join("crm_eventi_dettaglio D","D.id_evento = E.id");
    $this->db1->join("crm_iscrizioni_def I","I.evento = D.id");
    $this->db1->where("I.id_cliente = $cliente AND D.id IN ($id_evento)");
    $this->db1->order_by("D.data_evento DESC");
    $query = $this->db1->get();
    if ($query->num_rows() > 0) {
      $i = 0;

      foreach ($query->result() as $row) {
        $eventi_obj = new StdClass();
        $eventi_obj->id = $row->id;
        $eventi_obj->evento = $row->evento;
        $eventi_obj->location = $row->location;
        $eventi_obj->text_data = $row->text_data;

        $eventi[$i++] = $eventi_obj;
    }
    return $eventi;
}

return NULL;
}

public function get_dettaglio($id_evento){
    $this->db1->select("E.evento,E.img_app,D.*");
    $this->db1->distinct();
    $this->db1->from("crm_eventi_def E");
    $this->db1->join("crm_eventi_dettaglio D","D.id_evento = E.id");    
    $this->db1->where("D.id = $id_evento");    
    $query = $this->db1->get();
    if ($query->num_rows() > 0) {
      $i = 0;
      $row = $query->row();
      
      $eventi_obj = new StdClass();
      $eventi_obj->id = $row->id;
      $eventi_obj->evento = $row->evento;
      $eventi_obj->location = $row->location;
      $eventi_obj->descrizione = $this->_get_descrizione_evento($id_evento);
      $eventi_obj->text_data = $row->text_data;
      $eventi_obj->img = "http://www.performancestrategies.it/wp-content/uploads/2016/06/Buzan_fbshare.jpg";
      if($row->img_app){
        $eventi_obj->img = $row->img_app;  
    }      

    return $eventi_obj;
}

return NULL;
}   

public function old_eventi($cliente,$azienda){
    $this->db1->select("E.evento, E.relatori, D.*");
    $this->db1->distinct();
    $this->db1->from("crm_eventi_def E");
    $this->db1->join("crm_eventi_dettaglio  D","D.id_evento = E.id");
    $this->db1->join("app_eventi_materiali A","A.id_evento = E.id");
    $this->db1->join("crm_iscrizioni_def I","I.evento = D.id");
    $this->db1->where("E.tipo = '$azienda' AND D.data_evento < NOW() AND I.id_cliente = $cliente");
    $this->db1->order_by("D.data_evento DESC");
    $query = $this->db1->get();
    if ($query->num_rows() > 0) {
      $i = 0;

      foreach ($query->result() as $row) {
        $eventi_obj = new StdClass();
        $eventi_obj->id = $row->id;
        $eventi_obj->id_main_evento = $row->id_evento;
        $eventi_obj->evento = $row->evento;
        $eventi_obj->relatori = $row->relatori;
        $eventi_obj->location = $row->location;        
        $eventi_obj->data = $row->data_evento;
        $eventi_obj->text_data = $row->text_data;        

        $eventi[$i++] = $eventi_obj;
    }
    return $eventi;
}

return NULL;
}

public function new_eventi($cliente,$azienda){
    $this->db1->select("E.evento, E.relatori, D.*");
    $this->db1->distinct();
    $this->db1->from("crm_eventi_def E");
    $this->db1->join("crm_eventi_dettaglio D","D.id_evento = E.id");
    $this->db1->join("app_eventi_materiali A","A.id_evento = E.id");
    $this->db1->join("crm_iscrizioni_def I","I.evento = D.id");
    $this->db1->where("E.tipo = '$azienda' AND D.data_evento >= NOW() AND I.id_cliente = $cliente");
    $this->db1->order_by("D.data_evento DESC");
    $query = $this->db1->get();
    //echo $this->db1->last_query
    if ($query->num_rows() > 0) {
      $i = 0;

      foreach ($query->result() as $row) {
        $eventi_obj = new StdClass();
        $eventi_obj->id = $row->id;
        $eventi_obj->id_main_evento = $row->id_evento;
        $eventi_obj->evento = $row->evento;
        $eventi_obj->relatori = $row->relatori;
        $eventi_obj->location = $row->location;        
        $eventi_obj->data = $row->data_evento;
        $eventi_obj->text_data = $row->text_data;

        $eventi[$i++] = $eventi_obj;
    }
    return $eventi;
}

return NULL;
}


public function get_faq_evento($id_evento){
    $this->db1->select("A.faq_url");    
    $this->db1->from("crm_eventi_app A");    
    $this->db1->join("crm_eventi_dettaglio D","D.id_evento = A.id_evento");
    $this->db1->where("D.id = $id_evento");
    $query = $this->db1->get();
    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row->faq_url;
    } 

    return NULL;
}

private function _get_descrizione_evento($id_evento){
    $this->db1->select("A.descrizione");    
    $this->db1->from("crm_eventi_app A");    
    $this->db1->join("crm_eventi_dettaglio D","D.id_evento = A.id_evento");
    $this->db1->where("D.id = $id_evento");
    $query = $this->db1->get();
    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row->descrizione;
    } 

    return "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sit amet nisi id nunc imperdiet.";
}

private function _elenco_dettaglio($id_evento, $id_utente){
    $this->db1->select("D.id");
    $this->db1->distinct();
    $this->db1->from("crm_eventi_dettaglio D");
    $this->db1->join("crm_iscrizioni_def I","I.evento = D.id");
    $this->db1->where("D.id_evento = $id_evento AND I.id_cliente = $id_utente");
    $query = $this->db1->get();
    if ($query->num_rows() > 0) {
        $return = "";
        foreach ($query->result() as $row) {
            $id_dettaglio = $row->id;
            $return .= "$id_dettaglio-";
        }

        $return = substr($return, 0, -1);
        return $return;
    }

    return 0;
}

public function list_eventi($azienda){        
    $this->db1->select("E.evento, A.*");
    $this->db1->from("crm_eventi_def E");
    $this->db1->join("crm_eventi_app A","A.id_evento = E.id");
    $this->db1->where("E.tipo = '$azienda' AND A.data_stop > NOW()");
    $this->db1->order_by("A.ordine ASC, A.data_start ASC");
    $query = $this->db1->get();        
    $first = true;
    if ($query->num_rows() > 0) {
      $now = new DateTime();
      $flag_test = true;
      foreach ($query->result() as $row) {
        $bool_login = false;
        $id_evento_live = NULL;
        $text_btn = "Scopri di più";
        $titolo_card = "Prossimo Corso in Programma";
        $data_start = new DateTime($row->data_start);
        $data_stop = new DateTime($row->data_stop);


        if($flag_test){
          $bool_login = true;
          $text_btn = "Live 2.0";
          $titolo_card = "";
          $flag_test = false;
          $id_evento_live = 69;
      }



      $eventi[] = array("id" => $row->id_evento,
                        "id_evento_live" => $id_evento_live,
                        "evento" => $row->evento,
                        "titolo_card" => $titolo_card,
                        "location" => $row->location,
                        "text_data" => $row->text_data,
                        "url" => $row->url,
                        "bool_login" => $bool_login,
                        "descrizione" => $row->descrizione,
                        "img" => $row->img,
                        "text_btn" => $text_btn,
                        "hashtag" => $row->hashtag,
                        "numero_verde" => $row->numero_verde,
                        "mail_richiesta" => $row->mail_richiesta,
                        "mail_obj" => $row->mail_obj,
                        "mail_body" => $row->mail_body,
                        );

  }

  return $eventi;
}

return NULL;
}

}