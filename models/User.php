<?php

namespace app\models;


use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $username
 * @property string $auth_key
 * @property string $password
 * @property string $type
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STUDENT = 'student';
    const TEACHER = 'teacher';
    const ADMIN = 'admin';

    public static $types = [
        self::STUDENT => 'student',
        self::TEACHER => 'teacher',
        self::ADMIN   => 'admin'
    ];

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'password', 'username'], 'required'],
            [['email', 'username', 'email'], 'string', 'max' => 128],
            [['email', 'username', 'email'], 'unique'],
            ['email', 'email'],
            ['type', 'in', 'range' => array_keys(self::getTypes())]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'email'    => 'Email',
            'password' => 'Password',
            'username' => 'Username',
            'type'     => 'Type'
        ];
    }

    /**
     * @param int|string $id
     * @return static
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @throws NotSupportedException
     * @return void
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @param $email
     * @return static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }


    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public static function getTypes()
    {
        return self::$types;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $password
     * @return mixed
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @return void
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }

            return true;
        }

        return false;
    }

}
