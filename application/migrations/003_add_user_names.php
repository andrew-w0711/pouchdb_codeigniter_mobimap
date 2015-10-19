<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_user_names extends CI_Migration {

        public function up()
        {
            $fields = array(
        		'user_name_first' => array('type' => 'VARCHAR','constraint' => '100'),
				'user_name_last' => array('type' => 'VARCHAR','constraint' => '100'),
				'user_phone' => array('type' => 'VARCHAR','constraint' => '100'),
			);
			$this->dbforge->add_column('users', $fields);
        }

        public function down()
        {
            $this->dbforge->drop_column('users', 'user_name_first');
			$this->dbforge->drop_column('users', 'user_name_last');
			$this->dbforge->drop_column('users', 'user_phone'); 
        }
}
