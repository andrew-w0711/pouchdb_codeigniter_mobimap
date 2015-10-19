<?php

class Subscriber_model extends CI_Model {

      public $id;
      public $firstname;
      public $lastname;
	    public $email;
	    public $reg_date;
	    public $exp_date;
      public $uuid;
	    public $clientid;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function get() {
          $query = $this->db->query('SELECT s.*, c.name as client_name FROM subscribers s
          LEFT JOIN clients c ON c.id = s.clientid');
          return $query->result();
        }

        private function _set_public_id($id)
        {
          $query = $this->db->query('
            SELECT random_num FROM (
              SELECT FLOOR(RAND() * 9999999) AS random_num FROM poi p
              UNION
              SELECT FLOOR(RAND() * 9999999) AS random_num
            ) AS numbers_mst_plus_1 WHERE "random_num" NOT IN (SELECT s2.public_subscriber_id FROM subscribers s2) LIMIT 1
          ');
          $row = $query->row();
          $this->db->query('UPDATE subscribers SET public_subscriber_id = ? WHERE id = ? LIMIT 1', array($row->random_num));
          return $row->random_num;
        }

        private function _set_uuid($id)
        {
          $this->db->query('UPDATE subscribers SET uuid = UUID() WHERE id = ? LIMIT 1',array($id));
        }

	    public function get_subscriber_projects($uuid)
        {
            $query = $this->db->query('SELECT `name`,`uuid` FROM projects WHERE clientid =
(SELECT clientid FROM subscribers WHERE UUID = ?)
ORDER BY `name`',array($uuid));
            return $query->result();
	    }

        public function create($data)
        {
          $this->db->insert('subscribers', $data);
          $id = $this->db->insert_id();
          $this->_set_uuid($id);
          $this->_set_public_id($id);
          return $id;
        }

        public function update($id)
        {
          $this->db->update('subscribers', $data, array('id' => $id));
          return array('status' => 'OK');
        }

        public function test_subscriber_valid_id($id)
        {
            $query = $this->db->query('SELECT id FROM subscribers WHERE public_subscriber_id = ? LIMIT 1',array($id));
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public function test_subscriber_valid_uuid($uuid)
        {
            $query = $this->db->query('SELECT UUID FROM subscribers WHERE exp_date >= CURDATE() AND UUID = ? LIMIT 1');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

}
