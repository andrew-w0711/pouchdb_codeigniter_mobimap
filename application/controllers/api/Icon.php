<?php
if (!defined('BASEPATH'))
  exit ('No direct script access allowed');

//User Class Layout.
/*
9 = Administrator.  System wide access.
4 =
2 =
1 =
*/

class Icon extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
    $this->load->model('icon_model', 'icon', TRUE);
//$this->output->enable_profiler(TRUE);
// Force SSL
//$this->force_ssl();
  }

  public function index() {
    $data = array('status' => 'ERR', 'message' => 'Nothing to see here.');
    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }

  public function get() {
    if ($this->require_min_level(1)) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($this->icon->get()));
    }
    else {
      $data = array('status' => 'ERR', 'message' => 'Not Logged In');
      $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
  }

  public function update($id) {
    if ($this->require_min_level(9)) {
      $data = $this->icon->data_from_post();
      $this->output->set_content_type('application/json')->set_output(json_encode($this->icon->update($data)));
    }
    else {
      $data = array('status' => 'ERR', 'message' => 'Not Logged In');
      $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
  }

  public function delete($id) {
    if ($this->require_min_level(9)) {
      $this->output->set_content_type('application/json')->set_output(json_encode($this->icon->delete($id)));
    }
    else {
      $data = array('status' => 'ERR', 'message' => 'Not Logged In');
      $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
  }

  public function create() {
    if ($this->require_min_level(9)) {
      $data = $this->icon->data_from_post();
      $data['id'] = $this->icon->create($data);
      $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    else {
      $data = array('status' => 'ERR', 'message' => 'Not Logged In');
      $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
  }


}
