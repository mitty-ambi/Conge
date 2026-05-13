<?php if ($message = session()->getFlashdata('success')): ?>
<div class="flash flash-success">
  <i class="bi bi-check-circle-fill"></i>
  <?= esc($message) ?>
</div>
<?php endif; ?>

<?php if ($message = session()->getFlashdata('error')): ?>
<div class="flash flash-error">
  <i class="bi bi-exclamation-circle-fill"></i>
  <?= esc($message) ?>
</div>
<?php endif; ?>

<?php if ($errors = session()->getFlashdata('errors')): ?>
<div class="flash flash-error">
  <i class="bi bi-exclamation-circle-fill"></i>
  <?= esc(reset($errors)) ?>
</div>
<?php endif; ?>
