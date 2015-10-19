<?php

class Subscription_model extends CI_Model {

      public $id;
      public $subscriber_id;
      public $package_id;
	    public $status;
	    public $description;
      public $reg_date;
	    public $exp_date;
      public $whmcs_client_id;
	    public $whmcs_service_id;
      public $whmcs_package_id;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function get() {
          $query = $this->db->query('SELECT s.*, s1.public_subscriber_id, p.clientid,
            p.description AS package_description, p.layers, c.name as client_name,
            CONCAT(s1.lastname, ", ", s1.firstname) AS subscriber_name
            FROM subscription s
            LEFT JOIN subscribers s1 ON s1.id = s.subscriber_id
            LEFT JOIN package p ON p.id = s.package_id
            LEFT JOIN clients c ON c.id = p.clientid
            ');
          return $query->result();
        }

        public function get_subscription_layers($subscriber_id) {
          $package_array = array();
          $packages = $this->get_subscription_packages($subscriber_id);
          foreach ($packages as $package)
          {
            $query = '';
            // If this matches then we retrieve all layers belonging to $package->clientid
            if ($package->layers == 0 ) {
                $query = $this->db->query('SELECT id AS layer_id FROM projects WHERE clientid = ?', array($package->clientid));
            } else {
              //If we hit this we need to select layer_id
              $query = $this->db->query('SELECT layer_id FROM layer_package WHERE package_id = ?', array($package->package_id));
            }
            foreach ($query->result() as $row) {
              $package_array[] = $row->layer_id;
            }
          }
          return $package_array;
        }

	    public function get_subscription_packages($id)
        {
            $query = $this->db->query('SELECT s.*, s1.public_subscriber_id, p.clientid,
              p.description AS package_description, p.layers
              FROM subscription s
              LEFT JOIN subscribers s1 ON s1.id = s.subscriber_id
              LEFT JOIN package p ON p.id = s.package_id
              WHERE s1.public_subscriber_id = ?',array($id));
            return $query->result();
	    }

        public function create($data)
        {
          $this->db->insert('subscribers', $data);
          //$id = $this->db->insert_id();
          //$this->_set_uuid($id);
          //$this->_set_public_id($id);
          return $id;
        }
/*
        public function update($id)
        {
          $this->db->update('subscribers', $data, array('id' => $id));
          return array('status' => 'OK');
        }

        public function test_subscriber_valid_uuid($uuid)
        {
            $query = $this->db->query('SELECT UUID FROM subscribers WHERE exp_date >= CURDATE() AND UUID = ? LIMIT 1');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }*/

}
