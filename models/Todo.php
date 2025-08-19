<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Todo extends ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_DONE = 1;

    public static function tableName()
    {
        return '{{%todo}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLables()
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
        ];
    }
}

