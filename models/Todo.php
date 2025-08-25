<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Todo extends ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_DONE = 1;
    const STATUS_IN_PROGRESS = 2;

    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_DONE => 'Done',
        ];
    }

    public function getStatusLabel()
    {
        $list = self::getStatusList();
        return isset($list[$this->status]) ? $list[$this->status] : 'Unknown';
    }

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
            [['started_at', 'completed_at'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
        ];
    }

    public function getElapsedSeconds()
    {
        if ($this->started_at == null) {
            return null;
        }
        $end = $this->completed_at ?? time();
        return max(0, $end - (int) $this->started_at);
    }

    public static function find()
    {
        return new TodoQuery(get_called_class());
    }
}
