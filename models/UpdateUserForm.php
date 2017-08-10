<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * @property User|null $user This property is read-only.
 *
 */
class UpdateUserFrom extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'email', 'required', 'max' => 32, 'unique'],
            ['username', 'max' => 32, 'required', 'unique'],
        ];
    }


    /**
     * @return bool
     */
    public function login()
    {
        $rememberDurationInDays = 3600 * 24 * 30;
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? $rememberDurationInDays : 0);
        }

        return false;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
