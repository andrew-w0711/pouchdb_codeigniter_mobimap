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

//class Map extends CI_Controller {
class Map extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
    $this->load->library('grocery_CRUD');
  }

  public function view_db() {
    $output = new stdClass();
    $this->load->view('view_map_db.php', $output);
  }

  public function view($id) {
     $output = new stdClass();
     $output->subscriber = $id;
     $this->load->view('view_map.php', $output);
   }



  public function migrations() {
    if ($this->require_role('admin')) {
      $this->load->library('migration');
      if ($this->migration->latest() === FALSE)
      {
        show_error($this->migration->error_string());
      } else {
        show_error("Success");
      }
    }
  }

  public function packages() {
    if ($this->require_role('admin')) {
      $output = new stdClass();
      $output->auth_user_name = $this->auth_user_name;
      $output->auth_level = $this->auth_level;
      $output->auth_project = $this->_auth_project($this->auth_user_id);
      $output->auth_client = $this->_auth_client($this->auth_user_id);
      $output->menu = $this->load->view('menu.php', $output, TRUE);
      $this->load->view('manage_packages.php', $output);
    }
  }

  public function users() {
    if ($this->require_role('admin')) {
      $output = new stdClass();
      $output->auth_user_name = $this->auth_user_name;
      $output->auth_level = $this->auth_level;
      $output->auth_project = $this->_auth_project($this->auth_user_id);
      $output->auth_client = $this->_auth_client($this->auth_user_id);
      $output->menu = $this->load->view('menu.php', $output, TRUE);
      $this->load->view('manage_users.php', $output);
    }
  }

  public function subscribers() {
    if ($this->require_role('admin')) {
      $output = new stdClass();
      $output->auth_user_name = $this->auth_user_name;
      $output->auth_level = $this->auth_level;
      $output->auth_project = $this->_auth_project($this->auth_user_id);
      $output->auth_client = $this->_auth_client($this->auth_user_id);
      $output->menu = $this->load->view('menu.php', $output, TRUE);
      $this->load->view('manage_subscribers.php', $output);
    }
  }

  public function subscriptions() {
    if ($this->require_role('admin')) {
      $output = new stdClass();
      $output->auth_user_name = $this->auth_user_name;
      $output->auth_level = $this->auth_level;
      $output->auth_project = $this->_auth_project($this->auth_user_id);
      $output->auth_client = $this->_auth_client($this->auth_user_id);
      $output->menu = $this->load->view('menu.php', $output, TRUE);
      $this->load->view('manage_subscriptions.php', $output);
    }
  }


  public function clients() {
    if ($this->require_role('admin')) {
      $output = new stdClass();
      $output->auth_user_name = $this->auth_user_name;
      $output->auth_level = $this->auth_level;
      $output->auth_project = $this->_auth_project($this->auth_user_id);
      $output->auth_client = $this->_auth_client($this->auth_user_id);
      $output->menu = $this->load->view('menu.php', $output, TRUE);
      $this->load->view('manage_clients.php', $output);
    }
  }

  public function icons() {
    if ($this->require_role('admin')) {
      $output = new stdClass();
      $output->auth_user_name = $this->auth_user_name;
      $output->auth_level = $this->auth_level;
      $output->auth_project = $this->_auth_project($this->auth_user_id);
      $output->auth_client = $this->_auth_client($this->auth_user_id);
      $output->menu = $this->load->view('menu.php', $output, TRUE);
      $this->load->view('manage_icons.php', $output);
    }
  }

/*  public function maps() {
    if ($this->require_role('admin')) {
      $crud = new grocery_CRUD();
      $crud->set_table('maps');
      $crud->set_subject('Maps');
      $crud->set_relation('project', 'projects', 'name');
      $crud->set_relation('tiles', 'tiles', 'name');
      $output = $crud->render();
      $this->_gc_output($output);
    }
  }*/

  private function _parse_csv($file) {
    $rows = array_map('str_getcsv', file($file));
    $header = array_shift($rows);
    $csv = array();
    foreach ($rows as $row) {
      $csv[] = array_combine($header, $row);
    }
    return $csv;
  }

  public function import_poi() {
    if ($this->require_min_level(1)) {
      $config['upload_path'] = './uploads/';
      $config['allowed_types'] = 'csv|txt';
      $this->load->library('upload', $config);
      $output = new stdClass();
	    $output->auth_user_name = $this->auth_user_name;
      $output->auth_level = $this->auth_level;
      $output->auth_project = $this->_auth_project($this->auth_user_id);
      $output->auth_client = $this->_auth_client($this->auth_user_id);
      $output->menu = $this->load->view('menu.php', $output, TRUE);
      if (!$this->upload->do_upload('poifile')) {
        $error = array('error' => $this->upload->display_errors());
        $output->upload_error = $error;
        $output->upload_form = 1;
        $output->output = '';
        $this->load->view('import.php', $output);
      }
      else {
        $output->upload_success = '<h3>Starting Import</h3><br>';
        $data = array('upload_data' => $this->upload->data());
        $csv = $this->_parse_csv($data['upload_data']['full_path']);
        $projectid = $this->input->post('projectid');
        $this->load->model('poi_model', 'poi', TRUE);
        foreach ($csv as $item) {
          $output->upload_success .= "Adding: " . $item['name'] . " " . $item['lat'] . " " . $item['lon'] . "<br>";
          $item = $this->poi->data_from_csv($item);
          $item['project'] = $projectid;
          $this->poi->import($item);
        }
	       $output->upload_success .= '<h3>Finished Import</h3><br>';
         $output->upload_form = 1;
         $output->output = '';
         $this->load->view('import.php', $output);
      }
    }
  }

  public function poi($layer = NULL) {
    if ($this->require_min_level(1)) {
        $output = new stdClass();
        $output->auth_user_name = $this->auth_user_name;
        $output->auth_level = $this->auth_level;
        $output->auth_project = $this->_auth_project($this->auth_user_id);
        $output->auth_client = $this->_auth_client($this->auth_user_id);
        $output->menu = $this->load->view('menu.php', $output, TRUE);
        $output->layer = $layer;
        $this->load->view('manage_poi.php', $output);
    }
  }

  public function projects() {
    if ($this->require_min_level(1)) {
      $this->load->model('user_model', 'user', TRUE);
      $output = new stdClass();
      $output->auth_user_name = $this->auth_user_name;
      $output->auth_level = $this->auth_level;
      $output->auth_project = $this->_auth_project($this->auth_user_id);
      $output->auth_client = $this->_auth_client($this->auth_user_id);
      $output->menu = $this->load->view('menu.php', $output, TRUE);
      $this->load->view('projects.php',$output);
//      if ($this->auth_level < 9) {
//        $crud->in_where('id', $this->user->get_available_projects($this->auth_user_id));
//      }
//      else {
//      }
    }
  }

/*  public function _set_project_uuid($post_array, $primary_key) {
    $this->load->model('project', '', TRUE);
    $this->project->set_project_uuid($primary_key);
    return true;
  }*/

/*  public function tiles() {
    if ($this->require_role('admin')) {
      $crud = new grocery_CRUD();
      $crud->set_table('tiles');
      $crud->set_subject('Tiles');
      $output = $crud->render();
      $this->_gc_output($output);
    }
  }*/

  public function printable($projectid = null, $layout = 1) {
    if ($this->require_min_level(1)) {
      $query = $this->db->query('SELECT * FROM maps WHERE project = ? AND `order` > 0 ORDER by `order`', array($projectid));
      $maps = $query->result();
      $output = new stdClass();
      $output->address_table = $this->_project_addresses($projectid);
      $output->title = 'CUSTOM MAP';
      $output->maps = $maps;
      $this->load->view('layout/' . $layout . '.php', $output);
    }
  }

  private function _auth_client($id) {
    $result = 0;
    $query = $this->db->query("SELECT user_client FROM users WHERE user_id = ? LIMIT 1", array($id));
    if ($query->num_rows() > 0) {
      $row = $query->row();
      $result = $row->user_client;
    }
    return $result;
  }

  private function _auth_project($id) {
    $result = 0;
    $query = $this->db->query("SELECT user_project FROM users WHERE user_id = ? LIMIT 1", array($id));
    if ($query->num_rows() > 0) {
      $row = $query->row();
      $result = $row->user_project;
    }
    return $result;
  }

  private function _gc_output($output) {
    $output->auth_user_name = $this->auth_user_name;
    $output->auth_level = $this->auth_level;
    $output->auth_project = $this->_auth_project($this->auth_user_id);
    $output->auth_client = $this->_auth_client($this->auth_user_id);
    $output->menu = $this->load->view('menu.php', $output, TRUE);
    $this->load->view('example.php', $output);
  }

 /* private function _get_layers($auth_user_id) {
    $result = array();
    $query = $this->db->query("SELECT id, name FROM projects ORDER BY name");
    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $result[$row->id] = $row->name;
      }
    }
    return $result;
  }       */

  /*public function export_gpx($projectid = null) {
    if ($this->require_min_level(1)) {
      $this->load->model('poi_model', 'poi', TRUE);
      $this->load->model('project_model', 'project', TRUE);
      $output = new stdClass();
      $output->projects = $this->project->get_project_array(array($projectid));
      $output->markers = $this->poi->get_poi_project_array(array($projectid));
      $this->output->set_content_type('text/xml');
      $this->load->view('gpx_file.php', $output);
    }
  }*/

  public function export_gpi($subscriptionid = null) {
    if ($this->require_min_level(1)) {
      $this->load->helper('download');
      $this->load->model('poi_model', 'poi', TRUE);
  		$this->load->model('project_model','project', TRUE);
  		$this->load->model('subscriber_model', 'subscriber', TRUE);
  		$this->load->model('subscription_model', 'subscription', TRUE);
      $output = new stdClass();
      $layer_array = $this->subscription->get_subscription_layers($subscriptionid);
      if (count($layer_array) > 0 ) {
        $layers = $this->project->get_project_array($layer_array);
        if (count($layers) > 0 ) {
          foreach ($layers as $layer) {
            if ($layer->visible == 1) {
              $output->layers[] = array(
                'name' => $layer->description_sortable,
                'project_name' => $layer->name,
                'project_city' => $layer->city,
                'project_state' => $layer->state,
                'project_country' => $layer->country,
                'clientname' => $layer->clientname,
                'layer_id' => $layer->id,
                'subscription_id' => $subscriptionid,
                'markers' => $this->poi->get_poi_project_array(array($layer->id))
              );
            }
          }
        }
      }
      $this->output->set_content_type('text/xml');
      $results = $this->load->view('gpi_file.php', $output, true);
      force_download('Subscription_' . $subscriptionid .  '.xml', $results);
    }
  }

  public function view_project($projectid = null) {
    if ($this->require_min_level(1)) {
      //$this->output->enable_profiler(TRUE);
      $output = new stdClass();
      $output->auth_user_name = $this->auth_user_name;
      $output->auth_level = $this->auth_level;
      $output->auth_project = $this->_auth_project($this->auth_user_id);
      $output->auth_client = $this->_auth_client($this->auth_user_id);
      $output->projectid = $projectid;
      $output->menu = $this->load->view('menu.php', $output, TRUE);
	    $this->load->model('user_model', 'user', TRUE);
 	    $output->layers = $this->user->get_available_projects_id_name($this->auth_user_id, $this->auth_level);
      $output->layer = $projectid;
      $this->load->view('project_map.php', $output);
    }
  }

  public function project_addresses($projectid = null) {
    if ($this->require_min_level(1)) {
      $this->output->set_output($this->_project_addresses($projectid));
    }
  }

  public function _project_addresses($projectid = null) {
    $html = '<table>
<tr>
<th>Number</th>
<th>Name</th>
<th>Address 1</th>
<th>Address 2</th>
<th>City</th>
<th>Province/State</th>
<th>Lat</th>
<th>Lon</th>
</tr>
';
    $sql = 'select poi.* from poi WHERE project = ? ORDER BY name';
    $query = $this->db->query($sql, array($projectid));
    $poi = array();
    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $html .= '<tr><td>' . $row->number . '</td><td>' . $row->name . '</td><td>' . $row->address1 . '</td><td>' . $row->address1 . '</td><td>' . $row->address2 . '</td><td>' . $row->city . '</td><td>' . $row->state . '</td><td>' . $row->lat . '</td><td>' . $row->lon . '</td></tr>
';
      }
    }
    $html .= '</table>';
    return $html;
  }

// -----------------------------------------------------------------------
/**
* Demonstrate being redirected to login.
* If you are logged in and request this method,
* you'll see the message, otherwise you will be
* shown the login form. Once login is achieved,
* you will be redirected back to this method.
*/

  public function index() {
    if ($this->require_min_level(1)) {
        $output = new stdClass();
        $output->auth_user_name = $this->auth_user_name;
        $output->auth_level = $this->auth_level;
        $output->auth_project = $this->_auth_project($this->auth_user_id);
        $output->auth_client = $this->_auth_client($this->auth_user_id);
        $output->menu = $this->load->view('menu.php', $output, TRUE);
        $this->load->view('index.php', $output);
    }
  }
// -----------------------------------------------------------------------
/**
* Most minimal user creation. You will of course make your
* own interface for adding users, and you may even let users
* register and create their own accounts.
*
* The password used in the $user_data array needs to meet the
* following default strength requirements:
*   - Must be at least 8 characters long
*   - Must have at least one digit
*   - Must have at least one lower case letter
*   - Must have at least one upper case letter
*   - Must not have any space, tab, or other whitespace characters
*   - No backslash, apostrophe or quote chars are allowed
*/
/*
public function create_user()
{
// Customize this array for your user
//    $user_data = array(
//       'user_name'     => 'admin',
//      'user_pass'     => 'Dkw4569!',
//     'user_email'    => 'darren@aleph-com.net',
//          'user_level'    => '1', // 9 if you want to login @ examples/index.
//      );

$this->load->library('form_validation');

$this->form_validation->set_data( $user_data );

$validation_rules = array(
array(
'field' => 'user_name',
'label' => 'user_name',
'rules' => 'max_length[12]'
),
array(
'field' => 'user_pass',
'label' => 'user_pass',
'rules' => 'trim|required|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]',
),
array(
'field' => 'user_email',
'label' => 'user_email',
'rules' => 'required|valid_email'
),
array(
'field' => 'user_level',
'label' => 'user_level',
'rules' => 'required|integer|in_list[1,6,9]'
)
);

$this->form_validation->set_rules( $validation_rules );

if( $this->form_validation->run() )
{
$user_data['user_salt']     = $this->authentication->random_salt();
$user_data['user_pass']     = $this->authentication->hash_passwd($user_data['user_pass'], $user_data['user_salt']);
$user_data['user_id']       = $this->_get_unused_id();
$user_data['user_date']     = date('Y-m-d H:i:s');
$user_data['user_modified'] = date('Y-m-d H:i:s');

// If username is not used, it must be entered into the record as NULL
if( empty( $user_data['user_name'] ) )
{
$user_data['user_name'] = NULL;
}

$this->db->set($user_data)
->insert(config_item('user_table'));

if ($this->db->affected_rows() == 1) {
echo '<h1>Congratulations</h1>' . '<p>User ' . $user_data['user_name'] . ' was created.</p>';
}
}
else
{
echo '<h1>User Creation Error(s)</h1>' . validation_errors();
}
}
**/
// -----------------------------------------------------------------------
/**
* This login method only serves to redirect a user to a
* location once they have successfully logged in. It does
* not attempt to confirm that the user has permission to
* be on the page they are being redirected to.
*/

  public function login() {
// Method should not be directly accessible
    if ($this->uri->uri_string() == 'examples/login') {
      show_404();
    }
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
      $this->require_min_level(1);
    }
    $this->setup_login_form();
//        $html = $this->load->view('examples/page_header', '', TRUE);
//        $html .= $this->load->view('examples/login_form', '', TRUE);
//        $html .= $this->load->view('examples/page_footer', '', TRUE);
    $this->load->view('login.php');
//        echo $html;
  }
// --------------------------------------------------------------
/**
* Log out
*/

  public function logout() {
    $this->authentication->logout();
    redirect(secure_site_url(LOGIN_PAGE . '?logout=1'));
  }
// --------------------------------------------------------------
/**
* User recovery form
*/

  public function recover() {
// Load resources
    $this->load->model('examples_model');
	$view_data = array();
/// If IP or posted email is on hold, display message
    if ($on_hold = $this->authentication->current_hold_status(TRUE)) {
      $view_data['disabled'] = 1;
    }
    else {
// If the form post looks good
      if ($this->tokens->match && $this->input->post('user_email')) {
        if ($user_data = $this->examples_model->get_recovery_data($this->input->post('user_email'))) {
// Check if user is banned
          if ($user_data->user_banned == '1') {
// Log an error if banned
            $this->authentication->log_error($this->input->post('user_email', TRUE));
// Show special message for banned user
            $view_data['user_banned'] = 1;
          }
          else {
/**
* Use the string generator to create a random string
* that will be hashed and stored as the password recovery key.
*/
            $this->load->library('generate_string');
            $recovery_code = $this->generate_string->set_options(array('exclude' => array('char')))->random_string(64)->show();
            $hashed_recovery_code = $this->_hash_recovery_code($user_data->user_salt, $recovery_code);
// Update user record with recovery code and time
            $this->examples_model->update_user_raw_data($user_data->user_id, array('passwd_recovery_code' => $hashed_recovery_code, 'passwd_recovery_date' => date('Y-m-d H:i:s')));
            $view_data['special_link'] = secure_anchor('map/recovery_verification/' . $user_data->user_id . '/' . $recovery_code, secure_site_url('map/recovery_verification/' . $user_data->user_id . '/' . $recovery_code), 'target ="_blank"');
            $this->_send_recovery_email($this->input->post('user_email'), $view_data['special_link']);
			$view_data['confirmation'] = 1;
          }
        }
// There was no match, log an error, and display a message
        else {
// Log the error
          $this->authentication->log_error($this->input->post('user_email', TRUE));
          $view_data['no_match'] = 1;
        }
      }
    }
    //echo $this->load->view('examples/page_header', '', TRUE);
	$this->load->view('recover', $view_data);
    //echo $this->load->view('examples/page_footer', '', TRUE);
  }

  private function _send_recovery_email ($email, $link)
  {
   $this->load->library('email');
   	$this->email->set_mailtype("html");
	$this->email->from('support@mobimap.io', 'MobiMap');
	$this->email->to($email);
	//$this->email->cc('another@another-example.com');
	$this->email->bcc('darren@aleph-com.net');

	$this->email->subject('MobiMap Password Reset');
	$this->email->message('Here is your MobiMap password reset link.<br>
	' . $link . '<br>
	Thanks,<br>
	MobiMap Support');

	$this->email->send();
  }
// --------------------------------------------------------------
/**
* Verification of a user by email for recovery
*
* @param  int     the user ID
* @param  string  the passwd recovery code
*/

  public function recovery_verification($user_id = '', $recovery_code = '') {
/// If IP is on hold, display message
    if ($on_hold = $this->authentication->current_hold_status(TRUE)) {
      $view_data['disabled'] = 1;
    }
    else {
// Load resources
      $this->load->model('examples_model');
      if (
/**
* Make sure that $user_id is a number and less
* than or equal to 10 characters long
*/
        is_numeric($user_id) && strlen($user_id) <= 10 &&
/**
* Make sure that $recovery code is exactly 64 characters long
*/
        strlen($recovery_code) == 64 &&
/**
* Try to get a hashed password recovery
* code and user salt for the user.
*/
        $recovery_data = $this->examples_model->get_recovery_verification_data($user_id)) {
/**
* Check that the recovery code from the
* email matches the hashed recovery code.
*/
        if ($recovery_data->passwd_recovery_code == $this->_hash_recovery_code($recovery_data->user_salt, $recovery_code)) {
          $view_data['user_id'] = $user_id;
          $view_data['user_name'] = $recovery_data->user_name;
          $view_data['recovery_code'] = $recovery_data->passwd_recovery_code;
        }
// Link is bad so show message
        else {
          $view_data['recovery_error'] = 1;
// Log an error
          $this->authentication->log_error('');
        }
      }
// Link is bad so show message
      else {
        $view_data['recovery_error'] = 1;
// Log an error
        $this->authentication->log_error('');
      }
/**
* If form submission is attempting to change password
*/
      if ($this->tokens->match) {
        $this->examples_model->recovery_password_change();
      }
    }
    //echo $this->load->view('examples/page_header', '', TRUE);
    $this->load->view('choose_password_form', $view_data);
    //echo $this->load->view('examples/page_footer', '', TRUE);
  }
// --------------------------------------------------------------
/**
* Hash the password recovery code (uses the authentication library's hash_passwd method)
*/

  private function _hash_recovery_code($user_salt, $recovery_code) {
    return $this->authentication->hash_passwd($recovery_code, $user_salt);
  }
// --------------------------------------------------------------


}
/* End of file Examples.php */
/* Location: /application/controllers/Examples.php */
