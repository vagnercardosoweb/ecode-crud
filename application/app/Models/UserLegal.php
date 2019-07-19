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
 * Class UserLegal.
 *
 * @property int    $id
 * @property string $cnpj
 * @property string $corporate_name
 * @property string $fancy_name
 * @property string $state_registration
 * @property string $date_of_foundation
 * @property string $created_at
 * @property string $updated_at
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class UserLegal extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ecode_users_legal';

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
            ->where('AND user_id_legal = :id', "id={$this->id}")
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
            ->where('AND user_id_legal = :id', "id={$this->id}")
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
            ->where('AND user_id_legal = :id', "id={$this->id}")
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
            ->where('AND user_id_legal = :id', "id={$this->id}")
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
        foreach (['cnpj'] as $item) {
            if (!empty($data[$item])) {
                $data[$item] = preg_replace('/[^0-9]/i', '', $data[$item]);
            }
        }

        // Values date
        foreach (['date_of_foundation', 'created_at', 'updated_at'] as $item) {
            if (!empty($data[$item])) {
                $data[$item] = Date::formatFromDateTimeDatabase($data[$item]);
            }
        }

        // Validations
        if ($validate) {
            Validate::rules($data, [
                'cnpj' => [
                    'cnpj' => 'CNPJ informado não é válido.',
                    'databaseNotExists' => [
                        'message' => 'CNPJ informado já está em uso, favor digite outro.',
                        'params' => [$this->table(), 'cnpj', $where],
                    ],
                ],
                'corporate_name' => ['required' => 'Razão social é obrigatório.'],
                'fancy_name' => ['required' => 'Nome fantasia é obrigatório.'],
                'state_registration' => ['required' => 'Inscrição estadual é obrigatório.'],
                'date_of_foundation' => ['required' => 'Data de fundação é obrigatório.'],
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
