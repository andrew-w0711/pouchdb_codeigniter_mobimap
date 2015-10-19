<?php
if (!defined('BASEPATH'))
  exit ('No direct script access allowed');

/// User Class Layout.
/**
9 = Administrator.  System wide access.

4 =
2 =
1 =
**/
class Layer extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
    $this->load->model('subscriber_model', 'subscriber', TRUE);
//$this->output->enable_profiler(TRUE);
// Force SSL
//$this->force_ssl();
  }

  public function index() {
    $data = array('status' => 'ERR', 'message' => 'Nothing to see here.');
    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }

  public function my_layers($uuid) {

    $projects = $this->subscriber->get_subscriber_projects($uuid);
    $data = new stdClass;
    $data->uuid = $uuid;
    foreach ($projects as $key => $value) {
      $name = $value->name;
      $uuid = $value->uuid;
      $data->layers->$name = array('name' => $name, 'url' => $uuid);
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }

}
/* End of file Examples.php */
/* Location: /application/controllers/Examples.php */
