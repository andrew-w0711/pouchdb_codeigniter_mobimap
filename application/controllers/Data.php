<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/// User Class Layout.
/*
9 = Administrator.  System wide access.
4 =
2 =
1 =
*/


//class Map extends CI_Controller {
class Data extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');
		$this->load->model('poi_model', 'poi', TRUE);
		$this->load->model('project_model','project', TRUE);
		$this->load->model('subscriber_model', 'subscriber', TRUE);
		$this->load->model('subscription_model', 'subscription', TRUE);
		//$this->output->enable_profiler(TRUE);
	}

	public function index()
    	{
        $data = array('status' => 'ERR', 'message' => 'Nothing to see here.');
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    	}

      public function subscription($id) {
        $data =  new stdClass();
				// Test to see if the public ID we've received is valid.
				if ($this->subscriber->test_subscriber_valid_id($id)) {
					$data->status = 'OK';
	        $data->uuid = $id;
					$data->id = $id;
	        $data->layers = array();
					$layer_array = $this->subscription->get_subscription_layers($id);
					if (count($layer_array) > 0 ) {
						$layers = $this->project->get_project_array($layer_array);
						if (count($layers) > 0 ) {
							foreach ($layers as $layer) {
	        			$data->layers[] = array('name' => $layer->description_sortable,
								  'project_name' => $layer->name,
									'project_city' => $layer->city,
									'project_state' => $layer->state,
									'project_country' => $layer->country,
									'clientname' => $layer->clientname,
									'layer_id' => $layer->id,
									'subscriber_id' => $id,
									'url' => 'https://mobimap.io/manage/index.php/data/project/'	. $id . '/' . $layer->id );
							}
						}
					}
				} else {
					$data->status ='ERR';
					$data->message = 'INVALID SUBSCRIPTION';
				}

        return $this->output
              			->set_content_type('application/json')
              			->set_output(json_encode($data));
      }

			public function project($user,$project) {
				$layer_array = $this->subscription->get_subscription_layers($user);
				if (count($layer_array) > 0 && in_array($project,$layer_array)) {
					//$layers = $this->project->get_project_array($layer_array);
					//if (isset($layers[$project])) {
					//if (array_search($project, array_column($layers, 'id'))) {
						return $this->output->set_content_type('application/json')
							->set_output($this->poi->get_project_geojson($project));
					//} else {
				//		return $this->output->set_content_type('application/json')
				//			->set_output(json_encode(array( 'status' => 'ERR', 'message' => 'Invalid Project')));
				//	}
				} else {
					return $this->output->set_content_type('application/json')
						->set_output(json_encode(array( 'status' => 'ERR', 'message' => 'Invalid Project')));
				}
			}

			public function lookup_product($code) {
				$id = '';
				switch ($code) {
    			case "CONFERENCE2015":
        		$id = "1";
        		break;
    			case 1:
						$id = "2";
						break;
				}
				return $this->output->set_content_type('application/json')
					->set_output(json_encode(array( 'status' => 'OK', 'ID' => $id)));
			}
}

/* End of file Examples.php */
/* Location: /application/controllers/Examples.php */
