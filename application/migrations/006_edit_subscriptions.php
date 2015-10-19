<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_edit_subscriptions extends CI_Migration {

        public function up()
        {

		$fields = array(
       			'whmcs_client_id' => array('type' => 'INT','null' => TRUE),
       			'whmcs_service_id' => array('type' => 'INT','null' => TRUE),
       			'whmcs_product_id' => array('type' => 'INT','null' => TRUE)
		);
		$this->dbforge->add_column('subscription', $fields);
        }

        public function down()
        {
		$this->dbforge->drop_column('subscription', 'whmcs_service_id');
		$this->dbforge->drop_column('subscription', 'whmcs_client_id');
		$this->dbforge->drop_column('subscription', 'whmcs_product_id');
        }
}
