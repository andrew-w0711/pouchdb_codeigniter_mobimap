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
    $this->load->model('package_model', 'package', TRUE);
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
     //$projects = $this->package->get_();
     $this->output->set_output(json_encode($this->package->get()));
   }
   else {
     $data = array('status' => 'ERR', 'message' => 'Not Logged In');
     $this->output->set_content_type('application/json')->set_output(json_encode($data));
   }
 }

 public function create() {
   if ($this->require_min_level(9)) {
     $data = $this->package->data_from_post();
     $data['id'] = $this->package->create($data);
     $data['status'] = 'OK';
     $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }
  else {
   $data = array('status' => 'ERR', 'message' => 'Not Logged In');
   $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }
 }

 public function update($id) {
   if ($this->require_min_level(9)) {
     //$this->output->enable_profiler(TRUE);
     $data = $this->package->data_from_post();
     $data['id'] = $id;
     $this->package->update($data);
     $data['status'] = 'OK';
     $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
   }
   else {
     $data = array('status' => 'ERR', 'message' => 'Not Logged In');
     $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
   }

 }

 public function delete($id) {
   if ($this->require_min_level(9)) {
     $this->package->delete($id);
     $data = array();
     $data['status'] = 'OK';
     $this->output->set_content_type('application/json')->set_output(json_encode($data));
   }
   else {
     $data = array('status' => 'ERR', 'message' => 'Not Logged In');
     $this->output->set_content_type('application/json')->set_output(json_encode($data));
   }
 }



}
/* End of file Examples.php */
/* Location: /application/controllers/Examples.php */
