<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Video extends CI_Model {

    function __construct(){
        parent::__construct();

        $this->load->database();
    }

    public function categorie_video(){
        $this->db->select("C.*");
        $this->db->from("app_video_category C");        
        $this->db->where("C.visibile = '1'");
        $query = $this->db->get();
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
        return NULL;
    }

    public function list_video($id_categoria){
        $this->db->select("V.*");
        $this->db->from("app_video V");        
        $this->db->where("V.id_categoria = $id_categoria AND V.visibile = '1'");
        $query = $this->db->get();
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

        return NULL;
    }
}