<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI Smarty
 *
 * Smarty templating for Codeigniter
 *
 * @package   CI Smarty
 * @author      Marco Peca
 * @link        https://github.com/marcopeca/CI-Smarty
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 * @version     1.0
 */

class New_Controller extends MY_Controller
{
    function __construct (){
        parent::__construct();        
        date_default_timezone_set("Europe/Rome");
        $now = new DateTime();

        $this->data["cur_year"] = $now->format("Y");
        $this->data["config"] = $this->config->config;
        $this->data["base_img"] = PATH_IMG;
        $this->data["base_css"] = PATH_CSS;
        $this->data["base_js"] = PATH_JS;

        //$this->load->model('menu');
        //$this->data["main_menu"] = $this->menu->getMenu();

        $this->data["default_css"] = $this->getDefaultCss();
        $this->data["default_js"] = $this->getDefaultJs();
    }

    private function getDefaultJs(){
        $js = array();
        array_push($js, "general.js");

        return $js;
    }

    private function getDefaultCss(){
        $css = array();
        array_push($css, "margin-responsive.min.css?ver=1.10");
        array_push($css, "style.css?ver=1.10");

        return $css;
    }
}
