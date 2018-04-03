<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Video extends CI_Model {

    function __construct(){
        parent::__construct();

        $this->db1 = $this->load->database(DB1_GROUP,TRUE);
        $this->db2 = $this->load->database(DB2_GROUP,TRUE);
    }

    public function categorie_video(){
        $this->db2->select("C.*");
        $this->db2->from("app_video_category C");        
        $this->db2->where("C.visibile = '1'");
        $query = $this->db2->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $categoria[] = array("id" => $row->id,
                                 "categoria" => $row->categoria,
                                 "descrizione" => $row->descrizione,
                                 "colore" => $row->colore,
                                 "immagine" => $row->immagine
                                 );
            }
            return $categoria;
        }
        return array();
    }

    public function list_video($id_categoria){
        $this->db2->select("V.*");
        $this->db2->from("app_video V");        
        $this->db2->where("V.id_categoria = $id_categoria AND V.visibile = '1'");
        $this->db2->order_by("V.ordine asc");
        $query = $this->db2->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $video[] = array("id" => $row->id,
                                 "url" => $row->url,
                                 "titolo" => $row->titolo,
                                 "descrizione" => $row->descrizione,                                 
                                 "immagine" => $row->immagine
                                 );
            }
            return $video;
        }

        return array();
    }
}