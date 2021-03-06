<?php


use Phinx\Migration\AbstractMigration;

class CreateBillReceivesTable extends AbstractMigration
{
    public function up()
    {
        $this->table('bill_receives')
            ->addColumn('date_launch', 'date')
            ->addColumn('name', 'string')
            ->addColumn('value', 'float')
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id')
            ->addColumn('created_at', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP'
            ])
            ->addColumn('updated_at', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP'
            ])
            ->save();
    }

    public function down()
    {
        $this->dropTable('bill_receives');
    }
}
