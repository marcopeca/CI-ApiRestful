<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servizi extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function getProfessioni(){
        $this->db->select("P.*");        
        $this->db->from("crm_professioni_utenti P");        
        $this->db->where("P.versione = 1");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $i = 0;
            foreach ($query->result() as $row) {            
                $professioni_obj = new stdClass();
                $professioni_obj->id = $row->id;
                $professioni_obj->professione = $row->professione;

                $professioni[$i++] = $professioni_obj;
            }

            return $professioni;
        }


        return NULL;
    }

    public function getProvince(){
        $this->db->select("P.*");        
        $this->db->from("crm_province P");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $i = 0;
            foreach ($query->result() as $row) {            
                $province_obj = new stdClass();
                $province_obj->id = $row->id;
                $province_obj->sigla = $row->sigla;
                $province_obj->provincia = $row->provincia;
                $province_obj->regione = $row->regione;
                $province_obj->zona = $row->zona;

                $province[$i++] = $province_obj;
            }

            return $province;
        }


        return NULL;
    }

}