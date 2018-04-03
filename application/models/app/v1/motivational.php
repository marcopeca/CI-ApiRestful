<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Motivational extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function add(){

    }


    public function list_motivational(){
        $this->db->select("M.*");
        $this->db->from("app_motivational M");        
        $this->db->where("M.stop >= "," NOW() ", FALSE);
        $this->db->where("M.visibile","1");
        $this->db->order_by("M.ordine ASC");
        $query = $this->db->get();
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

        return NULL;
    }
}