<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "note".
 *
 * @property integer $id
 * @property string $title
 * @property string $message
 * @property integer $author
 *
 * @property User $user
 */
class Note extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'note';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'message'], 'required'],
            [['author'], 'integer'],
            [['title'], 'string', 'max' => 64],
            [['message'], 'string', 'max' => 255],
            [['author'], 'exist', 'skipOnError' => true,
             'targetClass'                      => User::className(),
             'targetAttribute'                  => ['author' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'title'   => 'Title',
            'message' => 'Message',
            'author'  => 'Author'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $this->author = Yii::$app->getUser()->getId();

            return true;
        }

        return false;
    }


}
