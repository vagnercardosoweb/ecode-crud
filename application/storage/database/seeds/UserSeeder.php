<?php

/*
 * VCWeb Networks <https://www.vcwebnetworks.com.br/>
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 18/07/2019 Vagner Cardoso
 */

use App\Models\Admin;
use Phinx\Seed\AbstractSeed;

/**
 * Class UserSeeder.
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class UserSeeder extends AbstractSeed
{
    /**
     * @throws \Exception
     *
     * @return void
     */
    public function run(): void
    {
        try {
            // Variables
            $email = 'admin@admin.com.br';
            $modelAdmin = new Admin();

            if (!$modelAdmin->reset()
                ->where('AND email = :e', "e={$email}")
                ->limit(1)
                ->fetch()
            ) {
                $modelAdmin->save([
                    'name' => 'Administrador',
                    'email' => $email,
                    'password' => 'admin@2019@',
                    'status' => 'online',
                ]);
            }
        } catch (Exception $e) {
            die("ERROR: {$e->getMessage()}");
        }
    }
}
