<?php
if (!defined('BASEPATH'))
  exit ('No direct script access allowed');

/// User Class Layout.
/*
9 = Administrator.  System wide access.
4 =
2 =
1 =
*/

class Package extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
    $this->load->model('client_model', 'client', TRUE);
    $this->load->model('package_layer_model', 'package_layer', TRUE);
//$this->output->enable_profiler(TRUE);
// Force SSL
//$this->force_ssl();
  }

  public function index() {
    $data = array('status' => 'ERR', 'message' => 'Nothing to see here.');
    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }

  public function get() {
   if ($this->require_min_level(9)) {
     $projects = $this->user->get_available_projects($this->auth_user_id, $this->auth_level);
     $this->output->set_output(json_encode($this->project->get_project_array($projects)));
   }
   else {
     $data = array('status' => 'ERR', 'message' => 'Not Logged In');
     $this->output->set_content_type('application/json')->set_output(json_encode($data));
   }
 }


}
/* End of file Examples.php */
/* Location: /application/controllers/Examples.php */
