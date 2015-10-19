<?php
if (!defined('BASEPATH'))
  exit ('No direct script access allowed');

// User Class Layout.
/*
9 = Administrator.  System wide access.
4 =
2 =
1 =
*/


class Project extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
    $this->load->model('project_model', 'project', TRUE);
    $this->load->model('user_model', 'user', TRUE);
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
     $projects = $this->user->get_available_projects($this->auth_user_id, $this->auth_level);
     $this->output->set_output(json_encode($this->project->get_project_array($projects)));
   }
   else {
     $data = array('status' => 'ERR', 'message' => 'Not Logged In');
     $this->output->set_content_type('application/json')->set_output(json_encode($data));
   }
 }

 public function create() {
   if ($this->require_min_level(1)) {
     $data = $this->project->data_from_post();
     $data['id'] = $this->project->create($data);
     $data['status'] = 'OK';
     $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }
  else {
   $data = array('status' => 'ERR', 'message' => 'Not Logged In');
   $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }
 }

 public function update($id) {
   if ($this->require_min_level(1)) {
     //$this->output->enable_profiler(TRUE);
     $data = $this->project->data_from_post();
     $data['id'] = $id;
     $this->project->update($data);
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
   if ($this->require_min_level(1)) {
     $this->project->delete($id);
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
