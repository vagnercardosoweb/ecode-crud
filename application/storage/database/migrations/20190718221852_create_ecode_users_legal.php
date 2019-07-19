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
 * Class CreateEcodeUsersLegal.
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class CreateEcodeUsersLegal extends Migration
{
    /**
     * @var string
     */
    protected $table = 'ecode_users_legal';

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
            ->addColumn('cnpj', 'string', ['limit' => 25])
            ->addColumn('corporate_name', 'string')
            ->addColumn('fancy_name', 'string')
            ->addColumn('state_registration', 'string', ['limit' => 50])
            ->addColumn('date_of_foundation', 'datetime')
            ->addTimestamps()
            ->addIndex($this->primaryKey)
            ->addIndex('cnpj', ['unique' => true])
            ->addIndex('corporate_name')
            ->addIndex('fancy_name')
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
