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
 * Class Phone.
 *
 * @property int    $id
 * @property int    $user_id
 * @property int    $user_legal_id
 * @property string $name
 * @property string $number
 * @property string $created_at
 * @property string $updated_at
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class Phone extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ecode_phones';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

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
        // Values number
        foreach (['number'] as $item) {
            if (!empty($data[$item])) {
                $data[$item] = preg_replace('/[^0-9]/i', '', $data[$item]);
            }
        }

        // Validations
        if ($validate) {
            $modelUser = new User();
            $modelUserLegal = new UserLegal();

            if (empty($data['user_id']) && empty($data['user_id_legal'])) {
                throw new \InvalidArgumentException(
                    'Pessoa ausente para vincular o telefone.', E_USER_ERROR
                );
            }

            Validate::rules($data, [
                'user_id' => [
                    'databaseExists' => [
                        'check' => !empty($data['user_id']),
                        'message' => 'Pessoa não existe no sistema para ser vinculado a esse endereço.',
                        'params' => [$modelUser->table(), $modelUser->getPrimaryKey(), null],
                    ],
                ],
                'user_id_legal' => [
                    'databaseExists' => [
                        'check' => !empty($data['user_id_legal']),
                        'message' => 'Pessoa não existe no sistema para ser vinculado a esse endereço.',
                        'params' => [$modelUserLegal->table(), $modelUserLegal->getPrimaryKey(), null],
                    ],
                ],
                'name' => ['required' => 'Nome é obrigatório.'],
                'number' => ['required' => 'Número é obrigatório.'],
            ]);
        }
    }
}
