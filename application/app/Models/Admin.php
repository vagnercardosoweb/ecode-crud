<?php

/*
 * VCWeb Networks <https://www.vcwebnetworks.com.br/>
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 18/07/2019 Vagner Cardoso
 */

namespace App\Models;

use Core\Helpers\Validate;

/**
 * Class Admin.
 *
 * @property int    $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 * @property string $status
 *
 * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class Admin extends BaseModel
{
    const SESSION_NAME_LOGIN = 'ecode::login';

    /**
     * @var string
     */
    protected $table = 'ecode_admins';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @param string $email
     * @param string $password
     *
     * @throws \Exception
     *
     * @return \App\Models\Admin
     */
    public function login(string $email, string $password): Admin
    {
        $email = filter_values($email)[0];
        $password = filter_values($password)[0];

        if (!$admin = $this->clear()
            ->where('AND email = :e', "e={$email}")
            ->limit(1)
            ->fetch()) {
            throw new \Exception(
                'Esse e-mail não está registrado em nosso sistema.', E_USER_ERROR
            );
        }

        if ('offline' == $admin->status) {
            throw new \Exception(
                'Seu acesso está desativado, favor entre em contato com nossa equipe.', E_USER_WARNING
            );
        }

        if (!$this->hash->verify($password, $admin->toObject()->password)) {
            throw new \Exception(
                'E-mail ou senha inválidos.', E_USER_WARNING
            );
        }

        unset($admin->password);

        $this->logoff();

        $this->session->set(
            self::SESSION_NAME_LOGIN,
            $this->encryption->encrypt($admin->id)
        );

        return $admin;
    }

    /**
     * @throws \Exception
     *
     * @return \App\Models\Admin|null
     */
    public function isLogged(): ?Admin
    {
        if ($id = $this->session->get(self::SESSION_NAME_LOGIN)) {
            $id = $this->encryption->decrypt($id);

            return $this->clear()
                ->limit(1)
                ->fetchById($id)
            ;
        }

        $this->logoff();

        return null;
    }

    /**
     * @return bool
     */
    public function logoff(): bool
    {
        $this->session->remove(
            self::SESSION_NAME_LOGIN
        );

        return true;
    }

    /**
     * @param array $data
     * @param bool  $validate
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function _data(array &$data, $validate): void
    {
        // Where
        $where = !empty($this->getPrimaryValue())
            ? sprintf('AND %s.%s != "%s"', $this->table, $this->primaryKey, $this->getPrimaryValue())
            : null;

        // Validate
        if ($validate) {
            Validate::rules($data, [
                'name' => ['required' => 'Nome não pode ser vázio.'],
                'email' => [
                    'email' => 'O E-mail informado não é válido.',
                    'databaseNotExists' => [
                        'message' => 'O e-mail digitado já foi registrado.',
                        'params' => [$this->table, 'email', $where],
                    ],
                ],
                'password' => [
                    'required' => [
                        'message' => 'Senha não pode ser vázio.',
                        'check' => empty($data['id']),
                    ],
                ],
            ]);
        }

        // Password
        if (!empty($data['password'])) {
            $data['password'] = $this->hash->make($data['password']);
        }
    }
}
