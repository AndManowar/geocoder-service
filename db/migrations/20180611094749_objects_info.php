<?php


use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class ObjectsInfo extends AbstractMigration
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

        $table = $this->table('object_info', [
            'id'          => false,
            'primary_key' => 'object_id',
            'engine'      => 'InnoDB',
            'collation'   => 'utf8_general_ci',
        ]);
        $table->addColumn('object_id', 'integer', [
            'limit' => MysqlAdapter::INT_BIG,
            'null'  => false,
        ])
            ->addColumn('fias_entry_id', 'string', [
                'limit' => 46,
            ])
            ->addColumn('kladr', 'string', [
                'limit' => 32,
            ])
            ->addColumn('postcode', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => true,
            ])
            ->addColumn('emls_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => true,
            ])
            ->addColumn('emls_not_found', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'null'  => true,
            ])
            ->addColumn('is_edited', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'null'  => true,
            ])
            ->addColumn('parent_data', 'text', [
            ])
            ->addColumn('end_date', 'date', [
                'null' => false,
            ])
            ->addColumn('is_folk_district', 'integer', [
                'null'  => false,
                'limit' => MysqlAdapter::INT_TINY,
            ])
            ->addForeignKey(
                'object_id',
                'objects',
                'id',
                [
                    'delete' => 'CASCADE',
                    'update' => 'CASCADE',
                ]
            )
            ->addIndex('fias_entry_id', ['name' => 'fias_entry_id_unique_idx'])
            ->create();
    }
}
