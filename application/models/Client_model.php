<?php

class Client_model extends CI_Model {

        public $id;
        public $name;
        public $uuid;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function get($id = null)
        {
        	if (is_null($id)) {
        		$sql = 'SELECT * FROM clients ORDER BY name';
            	$query = $this->db->query($sql);
            	return $query->result();
        	} else {
        	    $sql = 'SELECT * FROM clients WHERE id = ?';
            	$query = $this->db->query($sql, array($id));
            	return $query->result();
        	}
        }

        private function _set_uuid($id)
        {
                $this->db->query('UPDATE clients SET uuid = UUID() WHERE id = ? LIMIT 1',array($id));
        }

        public function create($data)
        {
                $this->db->insert('clients', $data);
                $id = $this->db->insert_id();
                $this->_set_uuid($id);
                return $id;
        }

        public function update($id,$data)
        {
                $this->db->update('clients', $data, array('id' => $id));
                return array('status' => 'OK');
        }

        public function data_from_post() {
          $data = array();
          if ($this->input->post('name')) { $data['name'] = $this->input->post('name'); };
          return $data;
        }

}
