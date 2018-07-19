<?php


use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class Metros extends AbstractMigration
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

        $table = $this->table('metro_lines', [
            'engine'    => 'InnoDB',
            'collation' => 'utf8_general_ci',
        ]);
        $table
            ->addColumn('region_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => false,
            ])
            ->addColumn('city_id', 'string', [
                'limit' => 46,
                'null'  => true,
            ])
            ->addColumn('name', 'string', [
                'limit' => 60,
            ])
            ->addColumn('color', 'string', [
                'limit' => 60,
            ])
            ->addColumn('status', 'integer', [
                'limit'   => MysqlAdapter::INT_TINY,
                'null'    => true,
                'default' => 1
            ])
            ->create();

        $table = $this->table('metros', [
            'engine'    => 'InnoDB',
            'collation' => 'utf8_general_ci',
        ]);
        $table
            ->addColumn('metro_line_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => false,
            ])
            ->addColumn('station_name', 'string', [
                'limit' => 60,
            ])
            ->addColumn('status', 'integer', [
                'limit'   => MysqlAdapter::INT_TINY,
                'null'    => true,
                'default' => 1
            ])
            ->addIndex('metro_line_id', ['name' => 'metro_line_id_idx'])
            ->create();

        $table = $this->table('metro_keywords', [
            'engine'    => 'InnoDB',
            'collation' => 'utf8_general_ci',
        ]);
        $table
            ->addColumn('metro_id', 'integer', [
                'limit' => MysqlAdapter::INT_SMALL,
                'null'  => false,
            ])
            ->addColumn('keyword', 'string', [
                'limit' => 100,
            ])
            ->addIndex('metro_id', ['name' => 'metro_id_idx'])
            ->create();
    }
}
