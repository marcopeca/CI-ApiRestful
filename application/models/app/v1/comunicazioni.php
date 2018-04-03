<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comunicazioni extends CI_Model {

    function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function list_comunicazioni(){
        $this->db->select("C.*");
        $this->db->from("app_comunicazioni C");        
        $this->db->where("C.start <= NOW() AND C.stop >= NOW() AND C.visibile = '1'");
        $this->db->where("C.visibile = '1'");
        $this->db->order_by("C.id DESC");
        $this->db->limit(1);
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $i = 0;
            foreach ($query->result() as $row) {
                $comunicazioni_obj = new StdClass();


                $comunicazioni_obj->id = $row->id;
                $comunicazioni_obj->titolo = $row->titolo;
                $comunicazioni_obj->desc = $row->descrizione;                                 
                $comunicazioni_obj->start = strtotime($row->start);
                $comunicazioni_obj->stop = strtotime($row->stop);
                $comunicazioni_obj->link = "";

                $comunicazioni[$i] = $comunicazioni_obj;
            }
            return $comunicazioni;
        }

        return array();
    }
}