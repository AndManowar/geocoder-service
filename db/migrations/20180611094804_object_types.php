<?php


use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class ObjectTypes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {

        $table = $this->table('object_types', [
            'engine'    => 'InnoDB',
            'collation' => 'utf8_general_ci',
        ]);
        $table->addColumn('level', 'integer', [
            'limit' => MysqlAdapter::INT_SMALL,
            'null'  => false,
        ])
            ->addColumn('name', 'string', [
                'limit' => 30,
            ])
            ->addColumn('status', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'null'  => true,
            ])
            ->create();
    }
}
