<?php

/*
 * VCWeb Networks <https://www.vcwebnetworks.com.br/>
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 18/07/2019 Vagner Cardoso
 */

use Core\Phinx\Migration;

/**
 * Class CreateEcodeUsers.
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class CreateEcodeUsers extends Migration
{
    /**
     * @var string
     */
    protected $table = 'ecode_users';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @throws \Exception
     *
     * @return void
     *
     * @see http://docs.phinx.org/en/latest/migrations.html
     */
    public function up(): void
    {
        $this->table($this->table)
            ->addColumn('name', 'string', ['limit' => 150])
            ->addColumn('cpf', 'string', ['limit' => 15])
            ->addColumn('rg', 'string', ['limit' => 15])
            ->addColumn('sex', 'enum', [
                'values' => ['male', 'female'],
            ])
            ->addColumn('date_of_birth', 'date')
            ->addTimestamps()
            ->addIndex($this->primaryKey)
            ->addIndex('name')
            ->addIndex('cpf', ['unique' => true])
            ->addIndex('rg', ['unique' => true])
            ->addIndex('sex')
            ->save()
        ;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function down(): void
    {
        $this->table($this->table)->drop()->save();
    }
}
