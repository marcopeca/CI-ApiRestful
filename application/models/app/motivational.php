<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Motivational extends CI_Model {

    function __construct()
    {
        parent::__construct();

        $this->db1 = $this->load->database(DB1_GROUP,TRUE);
        $this->db2 = $this->load->database(DB2_GROUP,TRUE);
    }

    public function add(){
        
    }


    public function list_motivational(){
        $this->db2->select("M.*");
        $this->db2->from("app_motivational M");        
        $this->db2->where("M.stop >= NOW() AND M.visibile = '1'");
        $query = $this->db2->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $motivational[] = array("id" => $row->id,
                                 "autore" => $row->autore,
                                 "img" => $row->autore_img,                                 
                                 "aforisma" => $row->aforisma,                                 
                                 "url" => $row->url,
                                 "stop" => strtotime($row->stop),                                 
                                 );
            }
            return $motivational;
        }

        return array();
    }
}