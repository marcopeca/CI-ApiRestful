<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Connect extends CI_Controller
{    

    public function __construct()
    {
        parent::__construct();

        $this->db1 = $this->load->database(DB1_GROUP,TRUE);
        $this->db2 = $this->load->database(DB2_GROUP,TRUE);
    }

    public function index(){}

    public function login_app(){
        if($this->input->post("password") != false){
            $this->load->model("app/login");
            $pwd = trim(strtolower($this->input->post("password")));
            $nome = ucwords(strtolower($this->input->post("nome")));
            $mail = strtolower($this->input->post("mail"));

            $incrementedDate = date("2017-02-25 21:00:00");
            //$incrementedDate = date("Y-m-d H:i:s",strtotime($inputedDate)+86400*2);            

            if($pwd == "leadershipday"){
                $ins = array("nome" => $nome,
                             "mail" => $mail,
                             "pwd" => $pwd);
                $id_user = $this->login->add($ins);

                die(json_encode(array(TRUE, $id_user, strtotime($incrementedDate))));
            }            
        }

        die(json_encode(array(FALSE)));
    }

    public function list_ebook(){
        $this->load->model("app/ebook");

        $ebook = $this->ebook->list_ebook();
        $flag = true;
        if($ebook != NULL){
            $flag = true;
        }        

        die(json_encode(array($flag,$ebook)));
    }

    public function list_motivational(){
        $this->load->model("app/motivational");

        $motivational = $this->motivational->list_motivational();
        $flag = true;
        if($motivational != NULL) {
            $flag = true;
        }
        
        die(json_encode(array($flag,$motivational)));
    }

    public function list_category_video(){
        $this->load->model("app/video");

        $categorie = $this->video->categorie_video();
        $flag = false;
        if($categorie != NULL) {
            $flag = true;
        }
        
        die(json_encode(array($flag,$categorie)));
    }

    public function list_video(){
        if($this->input->post("id_categoria") != false){
            $this->load->model("app/video");

            $id_categoria = $this->input->post("id_categoria");
            $video = $this->video->list_video($id_categoria);
            $flag = false;
            if($video != NULL) {
                $flag = true;
            }

            die(json_encode(array($flag,$video)));
        }
        die(json_encode(array(FALSE)));
    }

    public function list_eventi(){
        $this->load->model("app/eventi");

        $eventi = $this->eventi->list_eventi("PS");
        $flag = false;
        if($eventi != NULL) {
            $flag = true;
        }

        die(json_encode(array($flag,$eventi)));
    }

    public function list_comunicazioni(){
        $this->load->model("app/comunicazioni");

        $comunicazioni = $this->comunicazioni->list_comunicazioni();
        $flag = true;
        /*if($comunicazioni != NULL){
            $flag = true;
        }*/
        die(json_encode(array($flag,$comunicazioni)));
    }

    public function question_app(){
        if($this->input->post("testoDomanda") != false){
            $this->load->model("app/domande");
            $domanda = $this->input->post("testoDomanda");
            $id_partecipante = $this->input->post("user");
            $id_group = $this->input->post("interazione");

            $ins = array("domanda" => $domanda,
                         "id_partecipante" => $id_partecipante,
                         "id_group" => $id_group);
            
            $id_domanda = $this->domande->add_domanda($ins);
            die(json_encode(array(TRUE, $id_domanda)));
        }

        die(json_encode(FALSE));
    }    

    public function interation_list(){
        $this->load->model("app/domande");

        $list = $this->domande->interation_list();
        $flag = false;
        if($list != NULL){
            $flag = true;
        }
        die(json_encode(array($flag,$list)));
    }
}