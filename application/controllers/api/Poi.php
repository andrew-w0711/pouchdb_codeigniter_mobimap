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
class Poi extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
    $this->load->model('user_model', 'user', TRUE);
    $this->load->model('poi_model', 'poi', TRUE);
    $this->load->model('project_model', 'project', TRUE);
//$this->output->enable_profiler(TRUE);
// Force SSL
//$this->force_ssl();
  }

  public function index() {
    $data = array('status' => 'ERR', 'message' => 'Nothing to see here.');
    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }


   		public function delete($id) {
   			if ($this->require_min_level(1)) {
   	//$this->output->enable_profiler(TRUE);
   				$projects = $this->user->get_available_projects($this->auth_user_id, $this->auth_level);
   				$this->output->set_content_type('application/json')->set_output(json_encode($this->poi->delete($id)));
   			}
   			else {
   				$data = array('status' => 'ERR', 'message' => 'Not Logged In');
   				$this->output->set_content_type('application/json')->set_output(json_encode($data));
   			}
   		}

   		public function update($id) {
   			if ($this->require_min_level(1)) {
   	//$this->output->enable_profiler(TRUE);
   				$projects = $this->user->get_available_projects($this->auth_user_id, $this->auth_level);
          $data = $this->poi->data_from_post();
   				//var_dump($data);
   				if (count($data) > 0) {
   					$this->output->set_content_type('application/json')->set_output(json_encode($this->poi->update($id, $data)));
   				}
   				else {
   					$this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'OK', 'message' => 'Nothing to do.')));
   				}
   			}
   			else {
   				$data = array('status' => 'ERR', 'message' => 'Not Logged In');
   				$this->output->set_content_type('application/json')->set_output(json_encode($data));
   			}
   		}

   		public function create($project) {
   			if ($this->require_min_level(1)) {
   				$projects = $this->user->get_available_projects($this->auth_user_id, $this->auth_level);
   	//var_dump($projects);
   				if (in_array($project, $projects)) {
   					$data = $this->poi->data_from_post();
   					$data['project'] = $project;
   					$data['id'] = $this->poi->create($data);
   					$data['status'] = 'OK';
   					$this->output->set_content_type('application/json')->set_output(json_encode($data));
   				}
   				else {
   					$data = array();
   					$data['status'] = 'ERR';
   					$data['message'] = 'You do not have permission to edit that layer.';
   					$this->output->set_content_type('application/json')->set_output($data);
   				}
   			}
   			else {
   				$data = array('status' => 'ERR', 'message' => 'Not Logged In');
   				$this->output->set_content_type('application/json')->set_output(json_encode($data));
   			}
   		}


// Retrieves JSON containing POI belonging to either a specific project or all projects a user has access to.
      public function project($project = NULL) {
        if ($this->require_min_level(1)) {
          $projects = $this->user->get_available_projects($this->auth_user_id, $this->auth_level);
          if (isset ($project) && in_array($project, $projects)) {
            $poi = $this->poi->get_poi_project_array(array($project));
          }
          else {
            $poi = $this->poi->get_poi_project_array($projects);
          }
          $this->output->set_content_type('application/json')->set_output(json_encode($poi));
        }
        else {
          $data = array('status' => 'ERR', 'message' => 'Not Logged In');
          $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
      }

      public function project_geojson($projectid = NULL) {
        if ($this->require_min_level(1)) {
          if ($projectid == NULL) {
            $data = array('status' => 'ERR', 'message' => 'You must specify a project.');
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
          } else {
            //TODO Add access control.
      //$geojson = $this->_project_json($projectid);
            $this->output->set_content_type('application/json')->set_output($this->poi->get_project_geojson($projectid));
          }
        }
        else {
          $data = array('status' => 'ERR', 'message' => 'Not Logged In');
          $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
      }

}
