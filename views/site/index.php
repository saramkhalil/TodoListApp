<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model app\models\Todo */
/* @var $todos app\models\Todo[] */

$this->title = 'Home';
?>

<h1>Your Todos</h1>

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'title')->textInput(['maxlength' => true, 'required' => true]) ?>
<?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
<div class="form-group">
    <?= Html::submitButton('Add Todo', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

<hr>

<?php if (empty($todos)): ?>
    <p>Not Todos yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($todos as $t): ?>
            <li>
                <strong><?= Html::encode($t->title) ?></strong>
                <?php if ($t->description): ?>
                    <div><?= nl2br(Html::encode($t->description)) ?></div>
                <?php endif; ?>
                <small>Status: <?= Html::encode($t->getStatusLabel()) ?></small>

                <div style="margin-top: 6px;">
                    <?php if ((int) $t->status === \app\models\Todo::STATUS_PENDING): ?>
                        <?= Html::a(
                            'Mark as Done',
                            ['site/toggle-todo', 'id' => $t->id],
                            ['class' => 'btn btn-success btn-sm', 'data-method' => 'post']
                        ) ?>
                    <?php endif; ?>
                    <?= Html::a(
                        'Edit',
                        ['site/index', 'edit_id' => $t->id],
                        ['class' => 'btn btn-secondary btn-sm']
                    ) ?>
                </div>

                <?php if ((int) Yii::$app->request->get('edit_id') === (int) $t->id): ?>
                    <div style="margin-top: 10px;">
                        <?php $form = ActiveForm::begin([
                            'action' => ['site/update-todo', 'id' => $t->id],
                            'method' => 'post',
                        ]); ?>
                        <?= $form->field($t, 'title')->textInput(['maxlength' => true, 'required' => true]) ?>
                        <?= $form->field($t, 'description')->textarea(['rows' => 3]) ?>
                        <div class="form-group">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-sm']) ?>
                            <?= Html::a('Cancel', ['site/index'], ['class' => 'btn btn-link btn-sm']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                <?php endif; ?>

                <hr>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
