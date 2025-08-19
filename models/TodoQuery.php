<?php

namespace app\models;

use yii\db\ActiveQuery;

class TodoQuery extends ActiveQuery
{
    public function forUser(int $userId): self
    {
        return $this->andWhere(['user_id' => $userId]);
    }

    public function pending(): self
    {
        return $this->andWhere(['status' => Todo::STATUS_PENDING]);
    }

    public function done(): self
    {
        return $this->andWhere(['status' => Todo::STATUS_DONE]);
    }

    public function recent(): self
    {
        return $this->orderBy(['created_at' => SORT_DESC]);
    }

    public function byId(int $id): self
    {
        return $this->andWhere(['id' => $id]);
    }
}
