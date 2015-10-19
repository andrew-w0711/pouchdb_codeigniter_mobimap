<?php

class Package_layer_model extends CI_Model {

 public function __construct() {
// Call the CI_Model constructor
   parent::__construct();
 }

 //public function get_project_uuid($uuid) {
   //$query = $this->db->query();
   //return $query->result();
 //}

 /*private function _set_uuid($id) {
   $this->db->query('UPDATE package SET uuid = UUID() WHERE id = ? LIMIT 1', array($id));
 }*/

 public function create($layer,$package) {
   $this->db->insert('layer_package', $data);
   //$id = $this->db->insert_id();
   //$this->_set_uuid($id);
   return 0;
 }

 /*public function update($data) {
   $this->db->update('layer_package', $data, array('id' => $data['id']));
   return array('status' => 'OK');
 }*/

 public function delete($layer, $package)
 {
   $this->db->query('DELETE FROM layer_package WHERE layer_id = ? AND package_id = ? LIMIT 1',
    array($layer,$package));
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
      if ($this->input->post('status')) { $data['name'] = $this->input->post('status'); };
      if ($this->input->post('clientid')) { $data['clientid'] = $this->input->post('clientid'); };
      if ($this->input->post('description')) { $data['city'] = $this->input->post('description'); };
      if ($this->input->post('layers')) {	$data['state'] = $this->input->post('layers'); };
      return $data;
    }

}
