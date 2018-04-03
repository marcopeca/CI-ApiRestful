<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profilo extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function getProfilo($id_utente){
        $this->db->_protect_identifiers=false;
        $this->db->select("C.*, IF(C.share_profilo_app,'true','false' ) AS flag_share");        
        $this->db->from("crm_cliente C");
        $this->db->where("C.id", $id_utente);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();

            $cliente = new StdClass();
            $cliente->id = $row->id;            
            $cliente->nome = $row->nome;
            $cliente->cognome = $row->cognome;
            $cliente->mail = $row->mail;                    
            $cliente->telefono = $row->telefono;

            $cliente->indirizzo = $row->indirizzo;
            $cliente->citta = $row->citta;
            $cliente->cap = $row->cap;
            $cliente->provincia = $row->provincia;
            $cliente->professione = $row->professione;

            $cliente->citta_nascita = $row->citta_nascita;
            $cliente->data_nascita = $row->data_nascita;
            $cliente->sesso = $row->sesso;
            $cliente->codice_fiscale = $row->codice_fiscale;
            $cliente->auto_description = $row->auto_description;
            
            $cliente->img_profilo = $row->img_profilo;
            $cliente->share_profilo_app = $row->flag_share;

            return $cliente;
        }
        return NULL;
    }    

    public function updProfilo($upd, $id_utente){        
        $this->db->where("id", $id_utente);
        $this->db->update("crm_cliente", $upd);
        
        return TRUE;
    }

    public function getImgProfilo(){
        $this->db->select("*");
        $this->db->from("crm_imgprofili_app");        
        //$this->db->where("");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $i = 0;
            foreach ($query->result() as $row) {
                $img_profilo_obj = new StdClass();
                $img_profilo_obj->id = $row->id;
                $img_profilo_obj->url = $row->url;

                $img_profilo[$i++] = $img_profilo_obj;
            }
            return $img_profilo;
        }

        return NULL;
    }
}