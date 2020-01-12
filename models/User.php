<?php

namespace app\models;

use Yii;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id         ; //ID
    public $email      ; //Email
    public $password   ; //Пароль
    public $surname    ; //Фамилия
    public $first_name ; //Имя
    public $age        ; //Возраст
    public $sex        ; //Пол
    public $interests  ; //Интересы
    public $city       ; //Город
    public $auth_key   ;
    public $access_token;

    private static $users = [
        '100' => [
            'id' => '100',
            'email' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'email' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = Yii::$app->db->createCommand('SELECT * FROM user WHERE id=:id')
            ->bindValue(':id', $id)
            ->queryOne();

        return $user ? new static($user) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = Yii::$app->db->createCommand('SELECT * FROM user WHERE access_token=:accessToken')
            ->bindValue(':accessToken', $token)
            ->queryOne();
        return $user ? new static($user) : null;
    }

    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     * @throws \yii\db\Exception
     */
    public static function findByEmail($email)
    {
        $user = Yii::$app->db->createCommand('SELECT * FROM user WHERE email=:email')
            ->bindValue(':email', $email)
            ->queryOne();
        return $user ? new static($user) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     * @throws \yii\base\Exception
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @param $data
     * @return User
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public static function create($data)
    {
        $data['password'] = Yii::$app->security->generatePasswordHash($data['password']);
        $data['auth_key'] = Yii::$app->security->generateRandomString();
        Yii::$app->db->createCommand()->insert('user', $data)->execute();
        return new static(static::findByEmail($data['email']));
    }
}
