<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model app\models\Todo */
/* @var $todos app\models\Todo[] */

$this->title = 'Home';
?>

<h1>Your Todos</h1>
<p>
    <?= \yii\helpers\Html::button('Add Todo', [
        'class' => 'btn btn-primary',
        'data-bs-toggle' => 'modal',
        'data-bs-target' => '#addTodoModal'
    ]) ?>
</p>

<div class="modal fade" id="addTodoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Todo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'required' => true]) ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
      </div>
      <div class="modal-footer">
        <?= \yii\helpers\Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
  </div>
</div>

<hr>

<div class="row g-4 board-columns">
  <div class="col-md-4">
    <div class="board-header d-flex justify-content-between align-items-center mb-2">
      <h4 class="mb-0">Backlog</h4>
      <span class="badge rounded-pill bg-secondary"><?= isset($backlog) ? count($backlog) : 0 ?></span>
    </div>
    <?php if (empty($backlog)): ?>
      <p class="text-muted">No items.</p>
    <?php else:
    foreach ($backlog as $t): ?>
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <strong><?= \yii\helpers\Html::encode($t->title) ?></strong>
            <?= \yii\helpers\Html::a('Start', ['site/start-todo', 'id' => $t->id], [
                'class' => 'btn btn-sm btn-outline-primary',
                'data-method' => 'post'
            ]) ?>
            <?= \yii\helpers\Html::a('Delete', ['site/delete', 'id' => $t->id], [
                'class' => 'btn btn-sm btn-outline-danger ms-2',
                'data' => [
                    'confirm' => 'Delete this todo?',
                    'method' => 'post',
                ],
            ]) ?>
          </div>
          <?php if ($t->description): ?>
            <div class="mt-2 text-muted"><?= nl2br(\yii\helpers\Html::encode($t->description)) ?></div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach;
endif; ?>
  </div>

  <div class="col-md-4">
    <div class="board-header d-flex justify-content-between align-items-center mb-2">
      <h4 class="mb-0">In Progress</h4>
      <span class="badge rounded-pill bg-warning text-dark"><?= isset($inProgress) ? count($inProgress) : 0 ?></span>
    </div>
    <?php if (empty($inProgress)): ?>
      <p class="text-muted">No items.</p>
    <?php else:
    foreach ($inProgress as $t): ?>
      <div class="card mb-3 border-warning shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <strong><?= \yii\helpers\Html::encode($t->title) ?></strong>
            <?= \yii\helpers\Html::a('Complete', ['site/complete-todo', 'id' => $t->id], [
                'class' => 'btn btn-sm btn-success',
                'data-method' => 'post'
            ]) ?>
            <?= \yii\helpers\Html::a('Delete', ['site/delete', 'id' => $t->id], [
                'class' => 'btn btn-sm btn-outline-danger ms-2',
                'data' => [
                    'confirm' => 'Delete this todo?',
                    'method' => 'post',
                ],
            ]) ?>
          </div>
          <?php if ($t->description): ?>
            <div class="mt-2 text-muted"><?= nl2br(\yii\helpers\Html::encode($t->description)) ?></div>
          <?php endif; ?>
          <div class="mt-2">
            <small class="text-warning">
              Time spent: <span class="todo-timer" data-started-at="<?= (int) $t->started_at ?>"></span>
            </small>
          </div>
        </div>
      </div>
    <?php endforeach;
endif; ?>
  </div>

  <div class="col-md-4">
    <div class="board-header d-flex justify-content-between align-items-center mb-2">
      <h4 class="mb-0">Completed</h4>
      <span class="badge rounded-pill bg-success"><?= isset($completed) ? count($completed) : 0 ?></span>
    </div>
    <?php if (empty($completed)): ?>
      <p class="text-muted">No items.</p>
    <?php else:
    foreach ($completed as $t): ?>
      <div class="card mb-3 border-success shadow-sm">
        <div class="card-body">
          <strong><?= \yii\helpers\Html::encode($t->title) ?></strong>
          <?php if ($t->description): ?>
            <div class="mt-2 text-muted"><?= nl2br(\yii\helpers\Html::encode($t->description)) ?></div>
          <?php endif; ?>
          <?php
        $elapsed = ($t->started_at && $t->completed_at) ? max(0, $t->completed_at - $t->started_at) : null;
        $elapsedText = $elapsed !== null ? gmdate('H:i:s', $elapsed) : '—';
        ?>
          <div class="mt-2">
            <small class="text-success">Took: <?= $elapsedText ?></small>
          </div>
          <div class="mt-2 text-end">
            <?= \yii\helpers\Html::a('Delete', ['site/delete', 'id' => $t->id], [
                'class' => 'btn btn-sm btn-outline-danger',
                'data' => [
                    'confirm' => 'Delete this todo?',
                    'method' => 'post',
                ],
            ]) ?>
          </div>
        </div>
      </div>
    <?php endforeach;
endif; ?>
  </div>
</div>

<?php
$this->registerJs(<<<'JS'
    function formatDuration(totalSeconds){
      totalSeconds = Math.max(0, parseInt(totalSeconds || 0, 10));
      const h = Math.floor(totalSeconds / 3600);
      const m = Math.floor((totalSeconds % 3600) / 60);
      const s = totalSeconds % 60;
      const pad = n => n.toString().padStart(2,'0');
      return `${pad(h)}:${pad(m)}:${pad(s)}`;
    }

    function startStopwatch(el, startedAt){
      function update(){
        const now = Math.floor(Date.now() / 1000);
        const elapsed = now - startedAt;
        el.textContent = formatDuration(elapsed);
      }
      update();
      setInterval(update, 1000);
    }

    document.querySelectorAll('.todo-timer[data-started-at]').forEach(el => {
      let startedAt = parseInt(el.getAttribute('data-started-at'), 10);
      if (!isNaN(startedAt) && startedAt > 0) {
        // normalize ms → seconds if needed
        if (startedAt > 9999999999) {
          startedAt = Math.floor(startedAt / 1000);
        }
        startStopwatch(el, startedAt);
      } else {
        el.textContent = "00:00:00"; // not started yet
      }
    });
    JS);
?>


