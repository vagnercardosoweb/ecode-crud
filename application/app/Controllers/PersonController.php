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
use App\Models\User;
use App\Models\UserLegal;
use Slim\Http\Response;

/**
 * Class PersonController.
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class PersonController extends BaseController
{
    /**
     * @param string|null $type
     * @param int|null    $id
     *
     * @throws \Exception
     *
     * @return \Slim\Http\Response
     */
    public function index(?string $type = null, ?int $id = null): Response
    {
        // Edit
        if (!empty($id)) {
            return $this->edit($type, $id);
        }

        // Variables
        $type = filter_values($type)[0];
        $array = [];
        $array['type'] = $type;

        if (!empty($type) && !in_array($type, ['fisica', 'juridica'])) {
            return $this->redirect('web.person');
        }

        return $this->view('@web.person.index', $array);
    }

    /**
     * @param string $type
     * @param int    $id
     *
     * @throws \Exception
     *
     * @return \Slim\Http\Response|null
     */
    public function edit(string $type, int $id)
    {
        $id = filter_values($id)[0];
        $type = filter_values($type)[0];
        $array = [];
        $array['type'] = $type;

        if ('fisica' == $type) {
            $array['person'] = (new User())->fetchById($id);
        } elseif ('juridica' == $type) {
            $array['person'] = (new UserLegal())->fetchById($id);
        }

        if (empty($array['person'])) {
            return $this->redirect('web.index');
        }

        return $this->view('@web.person.edit', $array);
    }

    /**
     * @param string $type
     *
     * @return \Slim\Http\Response
     */
    public function post(string $type): Response
    {
        try {
            $type = filter_values($type)[0];

            if ('fisica' == $type) {
                $person = (new User())->save($this->getParamsFiltered());
            } elseif ('juridica' == $type) {
                $person = (new UserLegal())->save($this->getParamsFiltered());
            } else {
                throw new \Exception(
                    'Tipo inválido.', E_USER_ERROR
                );
            }

            return $this->jsonSuccess('Pessoa cadastrada com sucesso.', [
                'location' => $this->pathFor('web.person', [
                    'type' => $type,
                    'id' => $person->id,
                ]),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @param string $type
     * @param int    $id
     *
     * @return \Slim\Http\Response
     */
    public function put(string $type, int $id): Response
    {
        try {
            $id = filter_values($id)[0];
            $type = filter_values($type)[0];

            if ('fisica' == $type) {
                $person = (new User())->fetchById($id);
                $person->save($this->getParamsFiltered());
            } elseif ('juridica' == $type) {
                $person = (new UserLegal())->fetchById($id);
                $person->save($this->getParamsFiltered());
            } else {
                throw new \Exception(
                    'Tipo inválido.', E_USER_ERROR
                );
            }

            return $this->jsonSuccess('Pessoa atualizada com sucesso.');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @param string $type
     * @param int    $id
     *
     * @return \Slim\Http\Response
     */
    public function delete(string $type, int $id): Response
    {
        try {
            $id = filter_values($id)[0];
            $type = filter_values($type)[0];

            $this->db->transaction(function () use ($type, $id) {
                if ('fisica' == $type) {
                    $person = (new User())->fetchById($id);
                    $person->deleteAddress();
                    $person->deletePhones();
                    $person->delete();
                } elseif ('juridica' == $type) {
                    $person = (new UserLegal())->fetchById($id);
                    $person->deleteAddress();
                    $person->deletePhones();
                    $person->delete();
                } else {
                    throw new \Exception(
                        'Tipo inválido.', E_USER_ERROR
                    );
                }
            });

            return $this->jsonSuccess('Pessoa deletada com sucesso.', [
                'location' => $this->pathFor('web.index'),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
