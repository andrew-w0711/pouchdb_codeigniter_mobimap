<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_edit_subscribers extends CI_Migration {

        public function up()
        {

		$this->dbforge->drop_column('subscribers', 'reg_date');
		$this->dbforge->drop_column('subscribers', 'exp_date');

		$fields = array(
       			'whmcs_client_id' => array('type' => 'INT','null' => TRUE)
		);
		$this->dbforge->add_column('subscribers', $fields);
        }

        public function down()
        {
		$fields = array(
       			'reg_date' => array('type' => 'TIMESTAMP','null' => FALSE),
       			'exp_date' => array('type' => 'DATE','null' => TRUE)
		);
		$this->dbforge->add_column('subscribers', $fields);
		$this->dbforge->drop_column('subscribers', 'whmcs_client_id');
        }
}
