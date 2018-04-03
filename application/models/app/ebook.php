<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ebook extends CI_Model {

    function __construct()
    {
        parent::__construct();

        $this->db1 = $this->load->database(DB1_GROUP,TRUE);
        $this->db2 = $this->load->database(DB2_GROUP,TRUE);
    }

    public function add(){
        
    }


    public function list_ebook(){
        $this->db2->select("E.*");
        $this->db2->from("app_ebook E");        
        $this->db2->where("E.data_scadenza > NOW() AND E.visibile = '1'");
        $this->db2->order_by("RAND()");
        $query = $this->db2->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $ebook[] = array("id" => $row->id,
                                 "titolo" => $row->titolo,
                                 "desc" => $row->descrizione,
                                 "url" => $row->url,
                                 "img" => $row->img_url,
                                 "data_scadenza" => strtotime($row->data_scadenza),
                                 );
            }
            return $ebook;
        }

        return array();
    }
}