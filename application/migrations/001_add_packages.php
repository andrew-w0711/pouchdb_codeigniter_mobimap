<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_packages extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 5,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'clientid' => array(
                          'type' => 'INT',
                          'null' => FALSE,
                          'default' => '0'
                        ),
                        'status' => array(
                          'type' => 'INT',
                          'null' => FALSE,
                          'default' => '0'
                        ),
                        'status' => array(
                          'type' => 'INT',
                          'null' => FALSE,
                          'default' => '0'
                        ),
                        'description' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '128',
                                'null' => TRUE,
                        ),
                        'layers' => array(
                          'type' => 'INT',
                          'null' => FALSE,
                          'default' => '0'
                        )
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('package');
        }

        public function down()
        {
                $this->dbforge->drop_table('package');
        }
}
