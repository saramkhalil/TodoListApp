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
  <div class="col-md-4" data-column-status="0">
    <div class="board-header d-flex justify-content-between align-items-center mb-2">
      <h4 class="mb-0">Backlog</h4>
      <span class="badge rounded-pill bg-secondary"><?= isset($backlog) ? count($backlog) : 0 ?></span>
    </div>
    <?php if (empty($backlog)): ?>
      <p class="text-muted">No items.</p>
    <?php else:
    foreach ($backlog as $t): ?>
      <div class="card mb-3 shadow-sm" draggable="true" data-todo-id="<?= (int)$t->id ?>">
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

  <div class="col-md-4" data-column-status="2">
    <div class="board-header d-flex justify-content-between align-items-center mb-2">
      <h4 class="mb-0">In Progress</h4>
      <span class="badge rounded-pill bg-warning text-dark"><?= isset($inProgress) ? count($inProgress) : 0 ?></span>
    </div>
    <?php if (empty($inProgress)): ?>
      <p class="text-muted">No items.</p>
    <?php else:
    foreach ($inProgress as $t): ?>
      <div class="card mb-3 border-warning shadow-sm" draggable="true" data-todo-id="<?= (int)$t->id ?>">
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

  <div class="col-md-4" data-column-status="1">
    <div class="board-header d-flex justify-content-between align-items-center mb-2">
      <h4 class="mb-0">Completed</h4>
      <span class="badge rounded-pill bg-success"><?= isset($completed) ? count($completed) : 0 ?></span>
    </div>
    <?php if (empty($completed)): ?>
      <p class="text-muted">No items.</p>
    <?php else:
    foreach ($completed as $t): ?>
      <div class="card mb-3 border-success shadow-sm" draggable="true" data-todo-id="<?= (int)$t->id ?>">
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
// Provide safe URL variables for JS
$this->registerJsVar('moveTodoUrl', \yii\helpers\Url::to(['site/move-todo']));

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
    
    // Drag & Drop move between columns
    (function(){
      const cards = document.querySelectorAll('.card[draggable][data-todo-id]');
      const columns = document.querySelectorAll('[data-column-status]');
      let draggedId = null;

      cards.forEach(card => {
        card.addEventListener('dragstart', e => {
          draggedId = card.getAttribute('data-todo-id');
          e.dataTransfer.setData('text/plain', draggedId);
          e.dataTransfer.effectAllowed = 'move';
          card.classList.add('dragging');
        });
        card.addEventListener('dragend', () => {
          draggedId = null;
          card.classList.remove('dragging');
        });
      });

      columns.forEach(col => {
        col.addEventListener('dragover', e => {
          if (!draggedId) return;
          
          // Check if this move is allowed
          const draggedCard = document.querySelector('.card[draggable][data-todo-id="'+draggedId+'"]');
          if (!draggedCard) return;
          
          const currentCol = draggedCard.closest('[data-column-status]');
          const currentStatus = parseInt(currentCol.getAttribute('data-column-status'), 10);
          const targetStatus = parseInt(col.getAttribute('data-column-status'), 10);
          
          // Apply business rules
          let isAllowed = true;
          if (currentStatus === 2 && targetStatus === 0) isAllowed = false; // In Progress -> Backlog
          if (currentStatus === 1 && [0, 2].includes(targetStatus)) isAllowed = false; // Completed -> Backlog/In Progress
          if (currentStatus === 0 && targetStatus === 1) isAllowed = false; // Backlog -> Completed
          
          if (!isAllowed) {
            e.dataTransfer.dropEffect = 'none';
            col.classList.add('drop-forbidden');
            return;
          }
          
          e.preventDefault();
          e.dataTransfer.dropEffect = 'move';
          col.classList.add('drop-hover');
        });
        col.addEventListener('dragleave', () => {
          col.classList.remove('drop-hover');
          col.classList.remove('drop-forbidden');
        });
        col.addEventListener('drop', async e => {
          e.preventDefault();
          col.classList.remove('drop-hover');
          const id = draggedId || e.dataTransfer.getData('text/plain');
          if (!id) return;
          const status = parseInt(col.getAttribute('data-column-status'), 10);
          // CSRF
          const csrfParam = document.querySelector('meta[name="csrf-param"]');
          const csrfToken = document.querySelector('meta[name="csrf-token"]');
          const formData = new URLSearchParams();
          formData.set('id', String(id));
          formData.set('status', String(status));
          if (csrfParam && csrfToken) {
            formData.set(csrfParam.getAttribute('content'), csrfToken.getAttribute('content'));
          }
          try {
            const resp = await fetch(moveTodoUrl, {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
              body: formData.toString(),
              credentials: 'same-origin'
            });
            const data = await resp.json();
            if (data && data.ok) {
              // Optimistic UI: move the card DOM into the new column
              const cardEl = document.querySelector('.card[draggable][data-todo-id="'+id+'"]');
              const columnBody = col.querySelector('.board-header') ? col : col; // insert under the column
              if (cardEl && columnBody) {
                columnBody.appendChild(cardEl.parentElement && cardEl.parentElement.classList.contains('card') ? cardEl.parentElement : cardEl);
              }
              // Reload to reflect counts and badges quickly
              window.location.reload();
            } else {
              console.error('Move failed', data);
            }
          } catch(err) {
            console.error('Move error', err);
          }
        });
      });
    })();
    JS);
?>


