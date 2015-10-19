 <?php

class Package_model extends CI_Model {

	public function __construct() {
// Call the CI_Model constructor
		parent::__construct();
	}

	//public function get_project_uuid($uuid) {
		//$query = $this->db->query();
		//return $query->result();
	//}

  public function get($id = null)
  {
    if (is_null($id)) {
      $sql = 'SELECT p.*, c.name as clientname FROM package p LEFT JOIN clients c ON c.id = p.clientid';
        $query = $this->db->query($sql);
        return $query->result();
    } else {
        $sql = 'SELECT p.*, c.name as clientname FROM package p LEFT JOIN clients c ON c.id = p.clientid WHERE p.id = ?';
        $query = $this->db->query($sql, array($id));
        return $query->result();
    }
  }

	/*private function _set_uuid($id) {
		$this->db->query('UPDATE package SET uuid = UUID() WHERE id = ? LIMIT 1', array($id));
	}*/

	public function create($data) {
		$this->db->insert('package', $data);
		$id = $this->db->insert_id();
		//$this->_set_uuid($id);
		return $id;
	}

	public function update($data) {
		$this->db->update('package', $data, array('id' => $data['id']));
		return array('status' => 'OK');
	}

	public function delete($id)
	{
		$this->db->query('DELETE FROM package WHERE id = ? LIMIT 1',array($id));
		return array('status' => 'OK');
	}
/*    public function get_package_array($projects = array())
    {
        if (count($projects) > 0) {
            //var_dump($projects);
            $sql = 'SELECT projects.*,
							CONCAT(projects.name, ", ", projects.city, ", ", projects.state, ", ", projects.country) AS description,
							clients.name AS clientname
						 	FROM projects
							LEFT JOIN clients ON clients.id = projects.clientid
							WHERE projects.id IN ? ORDER BY projects.country, projects.state, projects.name';
            $query = $this->db->query($sql, array($projects));
            return $query->result();
        }
    }  */

		 public function data_from_post() {
		   $data = array();
		   if ($this->input->post('status')) { $data['status'] = $this->input->post('status'); };
		   if ($this->input->post('clientid')) { $data['clientid'] = $this->input->post('clientid'); };
		   if ($this->input->post('description')) { $data['description'] = $this->input->post('description'); };
		   if ($this->input->post('layers')) {	$data['layers'] = $this->input->post('layers'); };
		   return $data;
		 }

}
