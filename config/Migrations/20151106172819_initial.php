<?php
use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('justifications');
        $table
            ->addColumn('justification', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('is_active', 'integer', [
                'default' => 1,
                'limit' => 4,
                'null' => false,
            ])
            ->create();

        $table = $this->table('requesthistorics');
        $table
            ->addColumn('request_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('requeststatus_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('justification_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('is_active', 'integer', [
                'default' => 1,
                'limit' => 4,
                'null' => false,
            ])
            ->addIndex(
                [
                    'justification_id',
                ]
            )
            ->addIndex(
                [
                    'request_id',
                ]
            )
            ->addIndex(
                [
                    'requeststatus_id',
                ]
            )
            ->create();

        $table = $this->table('requests');
        $table
            ->addColumn('owner_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('target_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('duration', 'time', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('start_time', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('end_time', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('requeststatus_id', 'integer', [
                'comment' => 'Sistema de status padrão:

0 - rejeitada
1 - aceita 
2 - analise
3 - cancelada pelo owner
4 - cancelada pelo Target
5 - cancelada pelo sistema',
                'default' => 2,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('is_active', 'integer', [
                'default' => 1,
                'limit' => 4,
                'null' => false,
            ])
            ->addIndex(
                [
                    'requeststatus_id',
                ]
            )
            ->create();

        $table = $this->table('requests_resources');
        $table
            ->addColumn('request_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('resource_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('service_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('is_active', 'integer', [
                'default' => 1,
                'limit' => 4,
                'null' => false,
            ])
            ->addIndex(
                [
                    'request_id',
                ]
            )
            ->create();

        $table = $this->table('requeststatus');
        $table
            ->addColumn('status', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('is_active', 'integer', [
                'default' => 1,
                'limit' => 4,
                'null' => false,
            ])
            ->create();

        $this->table('requesthistorics')
            ->addForeignKey(
                'justification_id',
                'justifications',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->addForeignKey(
                'request_id',
                'requests',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->addForeignKey(
                'requeststatus_id',
                'requeststatus',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('requests')
            ->addForeignKey(
                'requeststatus_id',
                'requeststatus',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('requests_resources')
            ->addForeignKey(
                'request_id',
                'requests',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

    }

    public function down()
    {
        $this->table('requesthistorics')
            ->dropForeignKey(
                'justification_id'
            )
            ->dropForeignKey(
                'request_id'
            )
            ->dropForeignKey(
                'requeststatus_id'
            );

        $this->table('requests')
            ->dropForeignKey(
                'requeststatus_id'
            );

        $this->table('requests_resources')
            ->dropForeignKey(
                'request_id'
            );

        $this->dropTable('justifications');
        $this->dropTable('requesthistorics');
        $this->dropTable('requests');
        $this->dropTable('requests_resources');
        $this->dropTable('requeststatus');
    }
}
