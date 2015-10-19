<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_subscriber_ids extends CI_Migration {

        public function up()
        {
		        $fields = array(
       			    'public_subscriber_id' => array('type' => 'INT','null' => TRUE)
		        );
		        $this->dbforge->add_column('subscribers', $fields);
        }

        public function down()
        {
		        $this->dbforge->drop_column('subscribers', 'public_subscriber_id');
        }
}
