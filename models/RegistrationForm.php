<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegistrationForm extends Model
{
    public $email        = ''; //Email
    public $password     = ''; //Пароль
    public $surname      = ''; //Фамилия
    public $first_name   = ''; //Имя
    public $age             = 0; //Возраст
    public $sex             = 0; //Пол
    public $interests    = ''; //Интересы
    public $city         = ''; //Город

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            [['age', 'sex'], 'integer'],
            [['email', 'password', 'surname', 'first_name', 'city'], 'string', 'max' => 255],
            [['interests'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Пароль',
            'surname' => 'Фамилия',
            'first_name' => 'Имя',
            'age' => 'Возраст',
            'sex' => 'Пол',
            'interests' => 'Интересы',
            'city' => 'Город',
        ];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     * @throws \yii\base\Exception
     */
    public function singUp()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = User::create($this->toArray());
        return Yii::$app->user->login($user);
    }
}
