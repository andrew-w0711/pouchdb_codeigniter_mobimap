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
class User extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
    $this->load->model('subscriber_model', 'subscriber', TRUE);
    $this->load->model('user_model','user', TRUE);
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
      $this->output->set_content_type('application/json')->set_output(json_encode($this->user->get()));
    } else {
      $data = array('status' => 'ERR', 'message' => 'Not Logged In');
      $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
  }

  private function _hash_recovery_code($user_salt, $recovery_code) {
    return $this->authentication->hash_passwd($recovery_code, $user_salt);
  }

  private function _send_new_account_email ($email, $link)
  {
   $this->load->library('email');
   	$this->email->set_mailtype("html");
	$this->email->from('support@mobimap.io', 'MobiMap');
	$this->email->to($email);
	$this->email->bcc('darren@aleph-com.net','amos@montezumasound.com');

	$this->email->subject('New MobiMap Manager Account');
	$this->email->message('A new mobimap account has been setup for ' . $email . '.<br>
  Here is the link to set your MobiMap password.<br>
	' . $link . '<br>
  <br>
	Thanks,<br>
	MobiMap Support');

	$this->email->send();
  }

    public function create() {
      if ($this->require_min_level(9)) {
        $data = $this->user->data_from_post();
        $id = $this->user->create($data);
        if ($id) {
          $this->load->model('examples_model');
          $user_data = $this->examples_model->get_recovery_data($data['user_email']);
          $this->load->library('generate_string');
          $recovery_code = $this->generate_string->set_options(array('exclude' => array('char')))->random_string(64)->show();
          $hashed_recovery_code = $this->_hash_recovery_code($user_data->user_salt, $recovery_code);
// Update user record with recovery code and time
          $this->examples_model->update_user_raw_data($user_data->user_id, array('passwd_recovery_code' => $hashed_recovery_code, 'passwd_recovery_date' => date('Y-m-d H:i:s')));
          $view_data['special_link'] = secure_anchor('map/recovery_verification/' . $user_data->user_id . '/' . $recovery_code, secure_site_url('map/recovery_verification/' . $user_data->user_id . '/' . $recovery_code), 'target ="_blank"');
          $this->_send_new_account_email($data['user_email'], $view_data['special_link']);
          $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array("status" => 'OK', 'id' => $id)));
        } else {
          $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array("status" => 'ERR', 'message' => 'Unknown Error')));
        }
      } else {
        $data = array('status' => 'ERR', 'message' => 'Not Logged In');
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
      }
    }

    public function update($id) {
      if ($this->require_min_level(9)) {
        $data = $this->user->data_from_post();
        $this->user->update($id,$data);
        $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 'OK')));
      } else {
        $data = array('status' => 'ERR', 'message' => 'Not Logged In');
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
      }
    }

    public function delete($id) {
      if ($this->require_min_level(9)) {
        $this->user->delete($id);
        $this->output->set_content_type('application/json')->set_output(json_encode(array("status" => 'OK')));
      } else {
        $data = array('status' => 'ERR', 'message' => 'Not Logged In');
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
      }
    }


}
