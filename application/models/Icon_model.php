<?php

class Icon_model extends CI_Model {
	public $id;
	public $name;
	public $description;

	public function __construct() {
// Call the CI_Model constructor
		parent::__construct();
	}

	public function delete($id) {
		$this->db->query('DELETE FROM icons WHERE id = ? LIMIT 1',array($id));
		return array('status' => 'OK');
	}

	public function create($data) {
		$this->db->insert('icons', $data);
		return $this->db->insert_id();
	}

	public function update($data) {
		$this->db->update('icons', $data, array('id' => $data['id']));
		return array('status' => 'OK');
	}

    public function get()
    {
        $sql = 'SELECT * FROM icons ORDER BY name';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

		public function data_from_post() {
			$data = array();
			if ($this->input->post('project')) { $data['project'] = $this->input->post('project'); };
			if ($this->input->post('description')) { $data['description'] = $this->input->post('description'); };
			return $data;
		}

}
