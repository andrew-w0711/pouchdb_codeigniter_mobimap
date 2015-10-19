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
class Subscription extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
    $this->load->model('subscriber_model', 'subscriber', TRUE);
    $this->load->model('subscription_model', 'subscription', TRUE);
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
      $this->output->set_content_type('application/json')->set_output(json_encode($this->subscription->get()));
    } else {
      $data = array('status' => 'ERR', 'message' => 'Not Logged In');
      $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
  }

    public function create() {
      if ($this->require_min_level(9)) {
        //$data = $this->client->data_from_post();
        //$id = $this->client->create($data);
        //$this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 'OK', 'id' => $id)));
      } else {
        $data = array('status' => 'ERR', 'message' => 'Not Logged In');
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
      }
    }

    public function update($id = null) {
      if ($this->require_min_level(9)) {
        //$data = $this->client->data_from_post();
        //$this->client->update($id,$data);
        //$this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 'OK')));
      } else {
        $data = array('status' => 'ERR', 'message' => 'Not Logged In');
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
      }
    }

    public function delete($id) {
      if ($this->require_min_level(9)) {
        //$this->client->delete($id);
        //$this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 'OK')));
      } else {
        $data = array('status' => 'ERR', 'message' => 'Not Logged In');
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
      }
    }


  }
