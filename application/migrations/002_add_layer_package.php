<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_layer_package extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(

                        'package_id' => array(
                          'type' => 'INT',
                          'null' => FALSE,
                          'default' => '0'
                        ),
                        'layer_id' => array(
                          'type' => 'INT',
                          'null' => FALSE,
                          'default' => '0'
                        )
                ));
                $this->dbforge->create_table('layer_package');
        }

        public function down()
        {
                $this->dbforge->drop_table('layer_package');
        }
}
