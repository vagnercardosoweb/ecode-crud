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
use App\Models\Address;
use Slim\Http\Response;

/**
 * Class AddressController.
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class AddressController extends BaseController
{
    /**
     * @return \Slim\Http\Response
     */
    public function post(): Response
    {
        try {
            return (new Address())->transaction(function (Address $model) {
                $model->save($this->getParamsFiltered());

                return $this->jsonSuccess('Endereço adicionado com sucesso.', [
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
            return (new Address())->transaction(function (Address $model) use ($id) {
                $address = $model->reset()->fetchById($id);

                if ($address->id) {
                    $address->save($this->getParamsFiltered());
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
            return (new Address())->transaction(function (Address $model) use ($id) {
                $address = $model->reset()->fetchById($id);

                if (isset($address->id)) {
                    $address->delete();
                }

                return $this->jsonSuccess('Endereço deletado com sucesso.', [
                    'reload' => true,
                ]);
            });
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
