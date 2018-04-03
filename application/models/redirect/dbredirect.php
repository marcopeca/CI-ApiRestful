<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dbredirect extends CI_Model {

    function __construct()
    {
        parent::__construct();

        $this->db1 = $this->load->database(DB1_GROUP,TRUE);
        $this->db2 = $this->load->database(DB2_GROUP,TRUE);
    }

    public function add(){
        $this->db2->select("L.*, G.group");
        $this->db2->from("app_interation_list L");
        $this->db2->join("app_interation_group G","G.id = L.id_group");
        //$this->db2->where("");        
        
        $query = $this->db2->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $array[] = array("start" => strtotime($row->start),
                                 "end" => strtotime($row->end),
                                 "element" => $row->element,
                                 "ID" => $row->id);
            }
        }
         
        return TRUE;
    }
}