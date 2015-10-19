<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_poi_visible extends CI_Migration {

        public function up()
        {
		        $fields = array(
       			    'visible' => array('type' => 'TINYINT(1)','default' => '1')
		        );
		        $this->dbforge->add_column('poi', $fields);
        }

        public function down()
        {
		        $this->dbforge->drop_column('poi', 'visible');
        }
}
