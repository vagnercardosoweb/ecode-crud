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
use App\Models\Phone;
use Slim\Http\Response;

/**
 * Class PhoneController.
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class PhoneController extends BaseController
{
    /**
     * @return \Slim\Http\Response
     */
    public function post(): Response
    {
        try {
            return (new Phone())->transaction(function (Phone $model) {
                $model->save($this->getParamsFiltered());

                return $this->jsonSuccess('Telefone adicionado com sucesso.', [
                    'reload' => true,
                ]);
            });
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @param int $id
     *
     * @return \Slim\Http\Response
     */
    public function put(int $id): Response
    {
        try {
            return (new Phone())->transaction(function (Phone $model) use ($id) {
                $phone = $model->reset()->fetchById($id);

                if ($phone->id) {
                    $phone->save($this->getParamsFiltered());
                }

                return $this->jsonSuccess('Endereço atualizado com sucesso.', [
                    'reload' => true,
                ]);
            });
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @param int $id
     *
     * @return \Slim\Http\Response
     */
    public function delete(int $id): Response
    {
        try {
            return (new Phone())->transaction(function (Phone $model) use ($id) {
                $phone = $model->reset()->fetchById($id);

                if (isset($phone->id)) {
                    $phone->delete();
                }

                return $this->jsonSuccess('Telefone deletado com sucesso.', [
                    'reload' => true,
                ]);
            });
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
