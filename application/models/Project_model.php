<?php

class Project_model extends CI_Model {

	public function __construct() {
// Call the CI_Model constructor
		parent::__construct();
	}

	//public function get_project_uuid($uuid) {
		//$query = $this->db->query();
		//return $query->result();
	//}

	private function _set_uuid($id) {
		$this->db->query('UPDATE projects SET uuid = UUID() WHERE id = ? LIMIT 1', array($id));
	}

	public function create($data) {
		$this->db->insert('projects', $data);
		$id = $this->db->insert_id();
		$this->_set_uuid($id);
		return $id;
	}

	public function update($data) {
		$this->db->update('projects', $data, array('id' => $data['id']));
		return array('status' => 'OK');
	}

	public function delete($id)
	{
		$this->db->query('DELETE FROM projects WHERE id = ? LIMIT 1',array($id));
		return array('status' => 'OK');
	}

    public function get_project_array($projects = array())
    {
        if (count($projects) > 0) {
            //var_dump($projects);
            $sql = 'SELECT projects.*,
							CONCAT(projects.name, ", ", projects.city, ", ", projects.state, ", ", projects.country) AS description,
							CONCAT(projects.country, ", ", projects.state, ", ", projects.city, ", ", projects.name) AS description_sortable,
							clients.name AS clientname
						 	FROM projects
							LEFT JOIN clients ON clients.id = projects.clientid
							WHERE projects.id IN ? ORDER BY projects.country, projects.state, projects.name';
            $query = $this->db->query($sql, array($projects));
            return $query->result();
        }
    }

		 public function data_from_post() {
		   $data = array();
		   if ($this->input->post('name')) { $data['name'] = $this->input->post('name'); };
		   if ($this->input->post('clientid')) { $data['clientid'] = $this->input->post('clientid'); };
		   if ($this->input->post('city')) { $data['city'] = $this->input->post('city'); };
		   if ($this->input->post('state')) {	$data['state'] = $this->input->post('state'); };
		   if ($this->input->post('country')) {	$data['country'] = $this->input->post('country'); };
		   if ($this->input->post('notes')) {	$data['notes'] = $this->input->post('notes'); };
		   return $data;
		 }

}
