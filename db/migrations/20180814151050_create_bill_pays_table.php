<?php


use Phinx\Migration\AbstractMigration;

class CreateBillPaysTable extends AbstractMigration
{
    public function up()
    {
        $this->table('bill_pays')
            ->addColumn('date_launch', 'date')
            ->addColumn('name', 'string')
            ->addColumn('value', 'float')
            ->addColumn('user_id', 'integer')
            ->addColumn('category_cost_id', 'integer')
            ->addForeignKey('category_cost_id', 'category_costs', 'id')
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
        $this->dropTable('bill_pays');
    }
}
