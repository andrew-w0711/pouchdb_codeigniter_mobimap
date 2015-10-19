<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/// User Class Layout.
/**
9 = Administrator.  System wide access.

4 =
2 =
1 =
**/


//class Map extends CI_Controller {
class Sync extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->helper('file');


	        // Force SSL
       		//$this->force_ssl();
	}

	public function index()
    	{
		if ($this->_logged_in()) {
		
		}
    	}	

	public function get_sql() 
	{
		if ($this->_logged_in()) {
			$data = array();
			$data['status'] = 'OK';
			$data['query'][] = array( 'schedule_hours' => array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,0), 'uuid' => "12312313132", 'name' => "Get All Customers", 'sql' => "SELECT * FROM QS36F.CMASTR");
			$data['query'][] = array( 'schedule_hours' => array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,0), 'uuid' => "25asdf3425a", 'name' => "Get all Parts", 'sql' => "SELECT * FROM QS36F.PARTMAST");
			$data['query'][] = array( 'schedule_hours' => array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,0), 'uuid' => "asdf3425a", 'name' => "Get all Parts", 'sql' => "SELECT * FROM QS36F.PARTMST WHERE \"id\" < 1500");
			return $this->output
            			->set_content_type('application/json')
            			->set_output(json_encode($data));
		}
	}

	public function results($uuid)
	{
		if ($this->_logged_in()) {
			
//$this->output->enable_profiler(TRUE);
$json = file_get_contents('php://input');
$obj = json_decode($json);

log_message('error', $json);
			$data = array();
			if ($uuid == '12312313132' || $uuid == '25asdf3425a"' || $uuid = 'asdf3425a') {
				$data['status'] = 'OK';
			} else {
				$data['status'] = 'ERR';
				$data['status_description'] = 'Invalid UUID';
			}
			return $this->output
            			->set_content_type('application/json')
            			->set_output(json_encode($data));

		}
	}

	private function _logged_in()
	{
		if (empty($this->input->server('PHP_AUTH_USER')))
   		{
       			header('HTTP/1.1 401 Unauthorized');
       			header('WWW-Authenticate: Basic realm="Sync"');
       			echo 'You must login to use this service'; // User sees this if hit cancel
       			die();
    		}
    		$username = $this->input->server('PHP_AUTH_USER');
    		$password = $this->input->server('PHP_AUTH_PW');
		if ($username = 'user') {
			return true;
		} else {
			return false;
		}
	}
}

/* End of file Examples.php */
/* Location: /application/controllers/Examples.php */
