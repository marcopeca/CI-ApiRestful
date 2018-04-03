<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comunicazioni extends CI_Model {

    function __construct()
    {
        parent::__construct();

        $this->db1 = $this->load->database(DB1_GROUP,TRUE);
        $this->db2 = $this->load->database(DB2_GROUP,TRUE);
    }

    public function list_comunicazioni(){
        $this->db2->select("C.*");
        $this->db2->from("app_comunicazioni C");        
        $this->db2->where("C.start <= NOW() AND C.stop >= NOW() AND C.visibile = '1'");
        $this->db2->order_by("C.id DESC");
        $this->db2->limit(1);
        $query = $this->db2->get();
        //echo $this->db2->last_query();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $comunicazioni[] = array("id" => $row->id,
                                 "titolo" => $row->titolo,
                                 "desc" => $row->descrizione,                                 
                                 "start" => strtotime($row->start),
                                 "stop" => strtotime($row->stop),
                                 "link" => $row->link,
                                 );
            }
            return $comunicazioni;
        }

        return array();
    }
}