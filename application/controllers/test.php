<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH.'/libraries/REST_Controller.php';

class Test extends REST_Controller
{    

    public function __construct()
    {
        parent::__construct();
        
        $this->load->database();
        $this->load->model("test/login");

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Headers: authorization');
        }
    }        


    public function loginFromID_post(){
        if($this->input->post("id_iscrizione") != false){
            $id = $this->input->post("id_iscrizione");
            $username = $this->input->post("username");
            $pwd = $this->input->post("password");

            $return = $this->login->doLoginFromID($id, $username, $pwd);
        // 200
            if($return[0] == 1){
                $return = array("data" => $return[1],
                                "status" => STATE_OK,
                                "navigation" => NULL);
                $this->response($return,STATE_OK);
            }

        // 409
            if($return[0] == -1){
                $this->response(array("status" => USERNAME_NOT_USABLE, "error" => "Username non utilizzabile"),USERNAME_NOT_USABLE);
            }

        // 404
            if($return[0] == 0){
                $this->response(array("status" => STATE_NOT_FOUND, "error" => "Login Errato"),STATE_NOT_FOUND);
            }        
        } else {
            // 400
            $this->response(array("status" => STATE_NOT_OK, "error" => "Bad Request"),STATE_NOT_OK);
        }
    }

    public function login_post(){
        if($this->input->post("password") != false){
            $username = trim(strtolower($this->input->post("username")));
            $password = trim($this->input->post("password"));            

            $return = $this->login->doLogin($username,$password);
            
            // 200
            if($return[0] == 1){
                $return = array("data" => $return[1],
                                "status" => STATE_OK,
                                "navigation" => NULL);
                $this->response($return,STATE_OK);
            }

            // 404
            if($return[0] == 0){
                $this->response(array("status" => STATE_NOT_FOUND, "error" => "Login Errato"),STATE_NOT_FOUND);
            }
        }

        die(json_encode(array(FALSE)));
    }

    public function ebook_get(){
        $this->load->model("test/ebook");
        
        $ebook = $this->ebook->list_ebook();            
        if($ebook != NULL){
            $return = array("data" => $ebook,
                            "status" => STATE_OK,
                            "navigation" => NULL);
            $this->response($return,STATE_OK);
        } else {
            $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessun Ebook trovato"),STATE_NOT_FOUND);
        }        
    }

    public function motivational_get(){
        $this->load->model("test/motivational");        

        $motivational = $this->motivational->list_motivational();
        $flag = false;
        if($motivational != NULL) {
            $return = array("data" => $motivational,
                            "status" => STATE_OK,
                            "navigation" => NULL);
            $this->response($return,STATE_OK);            
        } else {
            $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessun Aforisma trovato"),STATE_NOT_FOUND);
        }
    }

    public function category_video_get(){
        $this->load->model("test/video");

        $categorie_video = $this->video->categorie_video();        
        if($categorie_video != NULL) {
            $return = array("data" => $categorie_video,
                            "status" => STATE_OK,
                            "navigation" => NULL);
            $this->response($return,STATE_OK);            
        } else {
            $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessuna Categoria video trovata"),STATE_NOT_FOUND);
        }
    }

    public function video_get($id_categoria){        
        $this->load->model("test/video");

        $video = $this->video->list_video($id_categoria);
        if($video != NULL) {
            $return = array("data" => $video,
                            "status" => STATE_OK,
                            "navigation" => NULL);

            $this->response($return,STATE_OK);            
        } else {
            $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessun Video trovato"),STATE_NOT_FOUND);
        }        
    }

    public function dettaglio_eventi_get($id_utente, $id_evento){
        $this->load->model("test/eventi");

        $id_utente = 8198;
        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            $dettaglio_eventi = $this->eventi->dettaglio($id_evento, $id_utente);
            if($dettaglio_eventi != NULL) {
                $return = array("data" => $dettaglio_eventi,
                                "status" => STATE_OK,
                                "navigation" => NULL);

                $this->response($return,STATE_OK);            
            } else {
                $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessun Evento trovato"),STATE_NOT_FOUND);
            }
        } 

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Accesso non permesso"),STATE_FORBIDDEN);
    }    

    public function download_eventi_get($id_evento){
        $this->load->model("test/eventi");

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            $arr_video = array();
            $arr_materiali = array();
            $tipo_video = $this->eventi->getTipoMateriali($id_evento, "video");
            if($tipo_video != NULL){
                foreach($tipo_video as $t){
                    $arr_video = $this->eventi->getMaterialiFromTipo($t["id"],$id_evento);
                }
            }

            $tipo = $this->eventi->getTipoMateriali($id_evento, "mat");
            if($tipo != NULL){
                foreach($tipo as $t){
                    $arr_materiali[] = array("label" => $t["tipo"],
                                             "img" => "",
                                             "data" => $this->eventi->getMaterialiFromTipo($t["id"],$id_evento));
                }
            }


            $download_eventi[] = array("evento" => $this->eventi->get_dettaglio($id_evento),
                                       "video" => $arr_video,
                                       "materiali" => $arr_materiali,
                                       "faq" => $this->eventi->get_faq_evento($id_evento));

            $return = array("data" => $download_eventi,
                            "status" => STATE_OK,
                            "navigation" => NULL);

            $this->response($return,STATE_OK);
        }
        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Accesso non permesso"),STATE_FORBIDDEN);
    }

    public function old_partecipazioni_get(){
        $this->load->model("test/eventi");

        $id_utente = 8198;
        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            $old_partecipazioni = $this->eventi->old_eventi($id_utente,"PS");
            if($old_partecipazioni != NULL) {
                $return = array("data" => $old_partecipazioni,
                                "status" => STATE_OK,
                                "navigation" => NULL);
                $this->response($return,STATE_OK);
            } else {
                $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessun Evento trovato"),STATE_NOT_FOUND);
            }
        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Accesso non permesso"),STATE_FORBIDDEN);
    }    

    public function new_partecipazioni_get(){
        $this->load->model("test/eventi");

        $id_utente = 8198;

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            $new_partecipazioni = $this->eventi->new_eventi($id_utente,"PS");        
            if($new_partecipazioni != NULL) {
                $return = array("data" => $new_partecipazioni,
                                "status" => STATE_OK,
                                "navigation" => NULL);

                $this->response($return,STATE_OK);            
            } else {
                $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessun Evento trovato"),STATE_NOT_FOUND);
            }
        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Accesso non permesso"),STATE_FORBIDDEN);
    }

    public function eventi_get(){
        $this->load->model("test/eventi");

        $eventi = $this->eventi->list_eventi("PS");
        if($eventi != NULL) {
            $return = array("data" => $eventi,
                            "status" => STATE_OK,
                            "navigation" => NULL);
            $this->response($return,STATE_OK);            
        } else {
            $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessun Evento trovato"),STATE_NOT_FOUND);
        }
    }

    public function comunicazioni_get(){
        $this->load->model("test/comunicazioni");

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            $comunicazioni = $this->comunicazioni->list_comunicazioni();        
            if($comunicazioni != NULL) {
                $return = array("data" => $comunicazioni,
                                "status" => STATE_OK,
                                "navigation" => NULL);
                $this->response($return,STATE_OK);            
            } else {
                $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessuna Comunicazioni trovata"),STATE_NOT_FOUND);
            }
        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Accesso non permesso"),STATE_FORBIDDEN);
    }

    public function question_app_put(){
        $this->load->model("test/domande");
        $request = $this->input->request_headers();
        $question_app = array("isset" => isset($request[AUTH_REQUEST]),
                              "data" => $this->login->controlToken($request[AUTH_REQUEST]));        

        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){

            if($this->put("testoDomanda") != false){
                $domanda = $this->put("testoDomanda");
                $id_group = $this->put("interazione");
                $id_evento = $this->put("id_evento");

                $ins = array("domanda" => $domanda,
                             "id_partecipante" => $this->login->getIdByToken($request[AUTH_REQUEST]),
                             "id_group" => $id_group,
                             "id_evento" => $id_evento);

                $id_domanda = $this->domande->add_domanda($ins);
                $return = array("data" => $id_domanda,
                                "status" => STATE_OK,
                                "navigation" => NULL);
                $this->response($return,STATE_OK);            
            } 
        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Non Permesso"),STATE_FORBIDDEN);
    }

    public function question_app_delete(){
        $this->load->model("test/domande");
        $request = $this->input->request_headers();
        $question_app = array("isset" => isset($request[AUTH_REQUEST]),
                              "data" => $this->login->controlToken($request[AUTH_REQUEST]));        

        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            if($this->delete("interazione") != false){
                $id_group = $this->delete("interazione");
                $id_evento = $this->delete("id_evento");
                $del = array("id_partecipante" => $this->login->getIdByToken($request[AUTH_REQUEST]),
                             "id_group" => $id_group,
                             "id_evento" => $id_evento);

                $this->domande->delete_interation($del);

                $return = array("data" => TRUE,
                                "status" => STATE_OK,
                                "navigation" => NULL);
                $this->response($return,STATE_OK);
            }
        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Non Permesso"),STATE_FORBIDDEN);
    }

    public function interation_list_get(){
        $this->load->model("test/domande");

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            $interation_list = $this->domande->interation_list();
            if($interation_list != NULL) {
                $return = array("data" => $interation_list,
                                "status" => STATE_OK,
                                "navigation" => NULL);
                $this->response($return,STATE_OK);            
            } else {
                $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessuna Interation List trovata"),STATE_NOT_FOUND);
            }
        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Non Permesso"),STATE_FORBIDDEN);
    }

    public function imgProfilo_get(){
        $this->load->model("test/profilo");

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){            
            $profilo = $this->profilo->getImgProfilo();
            if($profilo != NULL){
                $return = array("data" => $profilo,
                                "status" => STATE_OK,
                                "navigation" => NULL);
                $this->response($return,STATE_OK);            
            } else {
                $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessuna Immagine Profilo trovata"),STATE_NOT_FOUND);
            }
        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Non Permesso"),STATE_FORBIDDEN);
    }

    public function profilo_get($id_utente = 0){
        $this->load->model("test/profilo");

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            if($id_utente != 0){
                $profilo = $this->profilo->getProfilo($id_utente);
                if($profilo != NULL){
                    $return = array("data" => $profilo,
                                    "status" => STATE_OK,
                                    "navigation" => NULL);
                    $this->response($return,STATE_OK);            
                } else {
                    $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessuna Profilo trovato"),STATE_NOT_FOUND);
                }
            }
        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Non Permesso"),STATE_FORBIDDEN);
    }

    public function profilo_put(){
        $this->load->model("test/profilo");

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            $id_cliente = $this->login->getIdByToken($request[AUTH_REQUEST]);
            $upd = json_decode($this->put("utente"));
            $return = array("data" => $this->profilo->updProfilo($upd, $id_cliente),
                            "status" => STATE_OK,
                            "navigation" => NULL);
            $this->response($return,STATE_OK);            
        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Non Permesso"),STATE_FORBIDDEN);
    }

    public function getProfessioni_get(){
        $this->load->model("test/servizi");

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            $professioni = $this->servizi->getProfessioni();
            if($professioni != NULL){
                $return = array("data" => $professioni,
                                "status" => STATE_OK,
                                "navigation" => NULL);        
                $this->response($return,STATE_OK);
            } else {
                $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessuna Professione trovata"),STATE_NOT_FOUND);
            }
        }
        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Non Permesso"),STATE_FORBIDDEN);
    }    

    public function getProvince_get(){
        $this->load->model("test/servizi");

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            $province = $this->servizi->getProvince();
            if($province != NULL){
                $return = array("data" => $province,
                                "status" => STATE_OK,
                                "navigation" => NULL);        
                $this->response($return,STATE_OK);
            } else {
                $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessuna Provincia trovata"),STATE_NOT_FOUND);
            }
        }
        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Non Permesso"),STATE_FORBIDDEN);
    }

    public function statoIscrizioniInteration_get($id_interation = 0, $id_evento = 0){
        $this->load->model("test/profilo");
        $this->load->model("test/domande");

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            if($id_interation != 0 && $id_evento != 0){                
                $id_cliente = $this->login->getIdByToken($request[AUTH_REQUEST]);

                $return = array("data" => $this->domande->stato_interation_list($id_cliente, $id_interation, $id_evento),
                                "status" => STATE_OK,
                                "navigation" => NULL);
                $this->response($return,STATE_OK);
            }
        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Non Permesso"),STATE_FORBIDDEN);
    }

    public function getIscrittiInteration_get($id_interation = 0, $id_evento = 0, $cur_pag = 0){
        $this->load->model("test/profilo");
        $this->load->model("test/domande");
        $base = $this->config->item("base_url");
        $app = $this->config->item("test_url");
        $path = $base . $app;

        $prof = $this->get("professione");
        $provincia = $this->get("provincia");
        $filter = NULL;
        if($prof != NULL || $provincia != NULL){
            $filter = array("professione" => $prof,
                            "provincia" => $provincia);
        }        

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            if($id_interation != 0 && $id_evento != 0){
                $id_evento = 39;

                $tot = $this->domande->getTotIscritti($id_interation, $id_evento);
                $pag = ceil($tot / PAG_LIMIT);                
                $next = $prev = $path . "getIscrittiInteration/$id_interation/$id_evento/";
                $next .= $cur_pag + 1 . "/";
                $prev .= $cur_pag - 1 . "/";                

                if($filter != NULL){
                    $next .= "?";
                    $prev .= "?";
                    foreach($filter as $key=>$f){
                        $next .= "$key=$f&";
                        $prev .= "$key=$f&";
                    }
                    $next = substr($next, 0,-1);
                    $prev = substr($prev, 0,-1);
                } else {
                    $next .= "?professione=&provincia=";
                    $prev .= "?professione=&provincia=";
                }

                if($cur_pag == $pag){
                    $next = NULL;
                }
                if($cur_pag == 0){
                    $prev = NULL;
                }

                $iscritti_list = $this->domande->getIscrittiList($id_interation, $id_evento, $cur_pag, $filter);
                if($iscritti_list != NULL){                    
                    $navigation = array("prev" => $prev,
                                        "next" => $next);
                    $return = array("data" => $iscritti_list,
                                    "status" => STATE_OK,
                                    "navigation" => $navigation);
                    $this->response($return,STATE_OK);
                } else {
                    $this->response(array("status" => STATE_NOT_FOUND, "error" => "Nessuna Profilo iscritto trovato"),STATE_NOT_FOUND);
                }
            }
        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Non Permesso"),STATE_FORBIDDEN);
    }

    public function iscrittiChart_get($id_interation = 0, $id_evento = 0){
        $this->load->model("test/domande");

        $request = $this->input->request_headers();
        if(isset($request[AUTH_REQUEST]) && $this->login->controlToken($request[AUTH_REQUEST])){
            for( $i = 1 ; $i <= 4; $i++ ){
                $professioni["prof $i"] = array(10);
            }
            $arr_label = array();
            $arr_data = array();
            foreach($professioni as $key => $val){
                array_push($arr_label, $key);
                array_push($arr_data, $val[0]);
            }

            $flag_show = FALSE;
            if(count($arr_data) >= FLAG_CHART_SBD){
                $flag_show = TRUE;
            }


            $chart = array("label" => $arr_label,
                           "data" => $arr_data,
                           "show" => $flag_show);

            $return = array("data" => $chart,
                            "status" => STATE_OK,
                            "navigation" => NULL);
            $this->response($return,STATE_OK);

        }

        $this->response(array("status" => STATE_FORBIDDEN, "error" => "Non Permesso"),STATE_FORBIDDEN);
    }

}