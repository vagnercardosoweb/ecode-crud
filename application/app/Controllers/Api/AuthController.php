<?php

/*
 * VCWeb Networks <https://www.vcwebnetworks.com.br/>
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 18/07/2019 Vagner Cardoso
 */

namespace App\Controllers\Api;

use App\Controller\BaseController;
use App\Models\Admin;
use Core\Helpers\Validate;
use Slim\Http\Response;
use Slim\Http\StatusCode;

/**
 * Class AuthController.
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class AuthController extends BaseController
{
    /**
     * @return \Slim\Http\Response
     */
    public function post(): Response
    {
        try {
            Validate::rules($post = $this->getParamsFiltered(), [
                'email' => ['email' => 'E-mail informado não é válido.'],
                'password' => ['required' => 'Preencha a senha corretamente.'],
            ]);

            (new Admin())->login(
                $post['email'], $post['password']
            );

            return $this->jsonSuccess('Login realizado com sucesso...', [
                'reload' => true,
                'clear' => true,
            ]);
        } catch (\Exception $e) {
            return $this->jsonError(
                $e, [], StatusCode::HTTP_UNAUTHORIZED
            );
        }
    }
}
