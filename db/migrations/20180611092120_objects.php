<?php


use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class Objects extends AbstractMigration
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
        $table = $this->table('objects', [
            'engine'    => 'InnoDB',
            'collation' => 'utf8_general_ci',
        ]);
        $table->addColumn('glob_id', 'string', [
            'limit' => 46,
        ])
            ->addColumn('parent_glob_id', 'string', [
                'limit' => 46,
            ])
            ->addColumn('name', 'string', [
                'limit' => 250,
            ])
            ->addColumn('full_name', 'string', [
                'limit' => 250,
            ])
            ->addColumn('type_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => false,
            ])
            ->addColumn('level', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => false,
            ])
            ->addColumn('region_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => false,
            ])
            ->addColumn('area_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => true,
            ])
            ->addColumn('autonomy_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => true,
            ])
            ->addColumn('city_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => true,
            ])
            ->addColumn('city_district_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => true,
            ])
            ->addColumn('place_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => true,
            ])
            ->addColumn('street_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => true,
            ])
            ->addColumn('external_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => true,
            ])
            ->addColumn('status', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'null'  => true,
            ])
            ->addIndex('city_id', ['name' => 'city_id_idx'])
            ->addIndex('external_id', ['name' => 'external_id_idx'])
            ->addIndex('full_name', ['name' => 'full_name_id'])
            ->addIndex('glob_id', ['name' => 'glob_Id_idx'])
            ->addIndex('level', ['name' => 'level_idx'])
            ->addIndex('name', ['name' => 'name_idx'])
            ->addIndex('parent_glob_id', ['name' => 'parent_glob_id_idx'])
            ->addIndex('region_id', ['name' => 'region_id_idx'])
            ->addIndex('status', ['name' => 'status'])
            ->create();
    }
}
