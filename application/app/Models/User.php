<?php

/*
 * VCWeb Networks <https://www.vcwebnetworks.com.br/>
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 18/07/2019 Vagner Cardoso
 */

namespace App\Models;

use Core\Date;
use Core\Helpers\Validate;

/**
 * Class User.
 *
 * @property int    $id
 * @property string $name
 * @property string $cpf
 * @property string $rg
 * @property string $sex
 * @property string $date_of_birth
 * @property string $created_at
 * @property string $updated_at
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class User extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ecode_users';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @throws \Exception
     *
     * @return \App\Models\Address[]
     */
    public function address(): ?array
    {
        return (new Address())->reset()
            ->where('AND user_id = :id', "id={$this->id}")
            ->fetchAll()
        ;
    }

    /**
     * @throws \Exception
     *
     * @return \App\Models\Phone[]
     */
    public function phones(): ?array
    {
        return (new Phone())->reset()
            ->where('AND user_id = :id', "id={$this->id}")
            ->fetchAll()
        ;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function deleteAddress(): void
    {
        (new Address())->reset()
            ->where('AND user_id = :id', "id={$this->id}")
            ->delete()
        ;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function deletePhones(): void
    {
        (new Phone())->reset()
            ->where('AND user_id = :id', "id={$this->id}")
            ->delete()
        ;
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
        $where = !empty($data['id'])
            ? sprintf('AND %s.%s != "%s"', $this->table, $this->primaryKey, $data['id'])
            : null;

        // Values number
        foreach (['cpf', 'rg'] as $item) {
            if (!empty($data[$item])) {
                $data[$item] = preg_replace('/[^0-9]/i', '', $data[$item]);
            }
        }

        // Values date
        foreach (['date_of_birth', 'created_at', 'updated_at'] as $item) {
            if (!empty($data[$item])) {
                if ('date_of_birth' == $item) {
                    $data[$item] = Date::formatFromDateDatabase($data[$item]);
                } else {
                    $data[$item] = Date::formatFromDateTimeDatabase($data[$item]);
                }
            }
        }

        // Validations
        if ($validate) {
            Validate::rules($data, [
                'name' => ['required' => 'Nome é obrigatório.'],
                'cpf' => [
                    'cpf' => 'Cpf informado não é válido.',
                    'databaseNotExists' => [
                        'message' => 'Cpf informado já está em uso, favor digite outro.',
                        'params' => [$this->table(), 'cpf', $where],
                    ],
                ],
                'rg' => [
                    'databaseNotExists' => [
                        'message' => 'Rg informado já está em uso, favor digite outro.',
                        'params' => [$this->table(), 'rg', $where],
                    ],
                ],
                'sex' => [
                    'inArray' => [
                        'message' => 'O Sexo selecionado não é válido.',
                        'params' => [['male', 'female']],
                    ],
                ],
                'date_of_birth' => ['required' => 'Data de nascimento é obrigatório.'],
            ]);
        }
    }
}
