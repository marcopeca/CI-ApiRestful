<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ebook extends CI_Model {

    function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function add(){

    }

    public function list_ebook(){
        $this->db->select("E.*");
        $this->db->from("app_ebook E");        
        //$this->db->where("E.data_scadenza > NOW() AND E.visibile = '1'");
        $this->db->where("E.visibile = '1'");
        $this->db->order_by("RAND()");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {  
            $i = 0;
            foreach ($query->result() as $row) {
                $ebook_obj = new stdClass();                
                $ebook_obj->id = $row->id;
                $ebook_obj->titolo = $row->titolo;
                $ebook_obj->desc = $row->descrizione;
                $ebook_obj->url = $row->url;
                $ebook_obj->img = $row->img_url;
                $ebook_obj->data_scadenza = strtotime($row->data_scadenza);

                $ebook[$i++] = $ebook_obj;                
            }
            return $ebook;
        }

        return NULL;
    }
}