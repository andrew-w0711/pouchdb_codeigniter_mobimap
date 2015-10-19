<?php

class User_model extends CI_Model {
	public $id;
	public $name;
	public $uuid;

	public function __construct() {
// Call the CI_Model constructor
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
	}

	public function delete($id) {
		$this->db->query('DELETE FROM users WHERE user_id = ? LIMIT 1',array($id));
		return array('status' => 'OK');
	}

	public function create($data) {
		$data['user_id'] = $this->_get_unused_id();
		$this->db->insert('users', $data);
		return $data['user_id'];
	}
	/**
* Get an unused ID for user creation
*
* @return  int between 1200 and 4294967295
*/

  private function _get_unused_id() {
// Create a random user id
    $random_unique_int = mt_rand(1200, 4294967295);
// Make sure the random user_id isn't already in use
    $query = $this->db->where('user_id', $random_unique_int)->get_where(config_item('user_table'));
    if ($query->num_rows() > 0) {
      $query->free_result();
// If the random user_id is already in use, get a new number
      return $this->_get_unused_id();
    }
    return $random_unique_int;
  }
// --------------------------------------------------------------

	public function update($id,$data) {
		$this->db->where('user_id', $id);
		$this->db->limit(1);
		$this->db->update('users', $data);
	//	$this->db->update('users', $data, array('user_id' => $id));
		return array('status' => 'OK');
	}

    public function get()
    {
        $sql = 'SELECT u.*,
                CONCAT(p.country, ", ", p.state, ", ", p.city, ", ", p.name) AS user_project_description_sortable,
				p.name as user_project_name,
				c.name as user_client_name FROM users u
				LEFT JOIN projects p ON p.id = u.user_project
				LEFT JOIN clients c ON c.id = u.user_client';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

		public function data_from_post() {
			$data = array();
			if ($this->input->post('user_client')) { $data['user_client'] = $this->input->post('user_client'); };
			if ($this->input->post('user_project')) { $data['user_project'] = $this->input->post('user_project'); };
			if ($this->input->post('user_name')) { $data['user_name'] = $this->input->post('user_name'); };
            if ($this->input->post('user_name_first')) { $data['user_name_first'] = $this->input->post('user_name_first'); };
			if ($this->input->post('user_name_last')) { $data['user_name_last'] = $this->input->post('user_name_last'); };
			if ($this->input->post('user_phone')) { $data['user_phone'] = $this->input->post('user_phone'); };
			if ($this->input->post('user_email')) { $data['user_email'] = $this->input->post('user_email'); };
			if ($this->input->post('user_level')) { $data['user_level'] = $this->input->post('user_level'); };
			if ($this->input->post('user_banned')) { $data['user_banned'] = $this->input->post('user_banned'); };
			return $data;
		}

    public function get_client_id($id)
    {
        $result = 0;
        $query = $this->db->query("SELECT user_client FROM users WHERE user_id = ? LIMIT 1", array($id));
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $result = $row->user_client;
        }
        return $result;
    }

		public function get_project_id($id)
		{
				$result = 0;
				$query = $this->db->query("SELECT user_project FROM users WHERE user_id = ? LIMIT 1", array($id));
				if ($query->num_rows() > 0) {
						$row = $query->row();
						$result = $row->user_project;
				}
				return $result;
		}

	public function get_available_projects($id, $level) {
	    $projects = array();
        $sql = '';
        $query ='';
        if ($level < 9) {
					$project = $this->get_project_id($id);
					$client = $this->get_client_id($id);
					if ($project != 0) {
						$sql = 'SELECT id FROM projects WHERE id = ? ORDER BY country, state, name';
						$query = $this->db->query($sql,array($project));
					} else if ($client != 0) {
						$sql = 'SELECT id FROM projects WHERE clientid = ? ORDER BY country, state, name';
						$query = $this->db->query($sql,array($client));
					}
        } else {
            $sql = 'SELECT id FROM projects ORDER BY country, state, name';
            $query = $this->db->query($sql);
        }
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $projects[] = $row->id;
            }
        }
        return $projects;
	}

	public function get_available_projects_id_name($id, $level) {
	    $projects = array();
        $sql = '';
        $query ='';
				if ($level < 9) {
					$project = $this->get_project_id($id);
					$client = $this->get_client_id($id);
					if ($project != 0) {
						$sql = 'SELECT id,name FROM projects WHERE id = ? ORDER BY country, state, name';
						$query = $this->db->query($sql,array($project));
					} else if ($client != 0) {
						$sql = 'SELECT id,name FROM projects WHERE clientid = ? ORDER BY country, state, name';
						$query = $this->db->query($sql,array($client));
					}
				} else {
						$sql = 'SELECT id,name FROM projects ORDER BY country, state, name';
						$query = $this->db->query($sql);
				}
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
							$projects[$row->id] = $row->name;
            }
        }
        return $projects;
	}

}
