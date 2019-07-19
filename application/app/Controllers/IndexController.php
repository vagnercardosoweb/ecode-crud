<?php

/*
 * VCWeb Networks <https://www.vcwebnetworks.com.br/>
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 18/07/2019 Vagner Cardoso
 */

namespace App\Controllers;

use App\Controller\BaseController;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserLegal;
use Slim\Http\Response;

/**
 * Class IndexController.
 *
 * @property \App\Models\Admin $auth
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class IndexController extends BaseController
{
    /**
     * [GET] /.
     *
     * @throws \Exception
     *
     * @return \Slim\Http\Response
     */
    public function index(): Response
    {
        // Login
        if (!$this->auth) {
            return $this->view('@web.auth.login');
        }

        // Logged
        $array = [];

        // Users
        $array['users'] = (new User())
            ->order('created_at DESC')
            ->fetchAll()
        ;

        // Users legal
        $array['users_legal'] = (new UserLegal())
            ->order('created_at DESC')
            ->fetchAll()
        ;

        return $this->view('@web.dashboard.index', $array);
    }

    /**
     * @return \Slim\Http\Response
     */
    public function logoff(): Response
    {
        (new Admin())->logoff();

        return $this->redirect('web.index');
    }
}
