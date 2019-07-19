<?php

/*
 * VCWeb Networks <https://www.vcwebnetworks.com.br/>
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 18/07/2019 Vagner Cardoso
 */

namespace App\Providers;

use App\Models\Admin;

/**
 * Class AuthProvider.
 *
 * @property Admin $auth
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class AuthProvider extends Provider
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register(): void
    {
        $this->container['auth'] = function () {
            return (new Admin())->isLogged();
        };
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function boot(): void
    {
        $this->view->addGlobal('auth', $this->auth);
    }
}
