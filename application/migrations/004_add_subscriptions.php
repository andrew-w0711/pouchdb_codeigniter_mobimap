<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_subscriptions extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 5,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'subscriber_id' => array(
                          'type' => 'INT',
                          'null' => FALSE,
                          'default' => '0'
                        ),
                        'package_id' => array(
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
                        'reg_date' => array(
                          'type' => 'DATE',
                          'null' => FALSE
                        ),
                        'exp_date' => array(
                          'type' => 'DATE',
                          'null' => FALSE
			)
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('subscription');
        }

        public function down()
        {
                $this->dbforge->drop_table('subscription');
        }
}
