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
 * Class CreateEcodeAddress.
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class CreateEcodeAddress extends Migration
{
    /**
     * @var string
     */
    protected $table = 'ecode_address';

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
        $modelUser = new \App\Models\User();
        $modelUserLegal = new \App\Models\UserLegal();

        $this->table($this->table)
            ->addColumn('user_id', 'integer', ['null' => true, 'default' => null])
            ->addColumn('user_id_legal', 'integer', ['null' => true, 'default' => null])
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('zipcode', 'string', ['limit' => 10])
            ->addColumn('street', 'string', ['limit' => 100])
            ->addColumn('number', 'string', ['null' => true, 'default' => null, 'limit' => 10])
            ->addColumn('complement', 'string', ['null' => true, 'default' => null, 'limit' => 150])
            ->addColumn('district', 'string', ['limit' => 100])
            ->addColumn('city', 'string', ['limit' => 100])
            ->addColumn('state', 'string', ['limit' => 100])
            ->addColumn('country', 'string', ['limit' => 100, 'default' => 'Brasil'])
            ->addTimestamps()
            ->addIndex($this->primaryKey)
            ->addIndex('user_id')
            ->addIndex('user_id_legal')
            ->addIndex('city')
            ->addIndex('state')
            ->addIndex('country')
            ->addIndex('created_at')
            ->addForeignKey('user_id', $modelUser->table(), $modelUser->getPrimaryKey())
            ->addForeignKey('user_id_legal', $modelUserLegal->table(), $modelUserLegal->getPrimaryKey())
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
