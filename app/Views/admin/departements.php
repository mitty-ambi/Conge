<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<?php $active = 'departements'; ?>
<div class="app-wrap">
  <?= $this->include('partials/sidebar_admin') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Départements</div>
        <div class="topbar-breadcrumb"><a href="<?= site_url('admin') ?>">Admin</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Départements</div>
      </div>
    </div>

    <div class="content">
      <?= $this->include('partials/flash') ?>

      <form class="form-section" method="post" action="<?= site_url('admin/departements') ?>">
        <?= csrf_field() ?>
        <h3><i class="bi bi-building" style="color:var(--forest);margin-right:6px"></i>Ajouter un département</h3>
        <div class="form-grid-2">
          <div class="f-group">
            <label class="f-label">Nom</label>
            <input type="text" class="f-input" name="nom" value="<?= esc(old('nom') ?? '') ?>" required/>
          </div>
          <div class="f-group">
            <label class="f-label">Description</label>
            <input type="text" class="f-input" name="description" value="<?= esc(old('description') ?? '') ?>"/>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn-forest" type="submit"><i class="bi bi-plus"></i> Ajouter</button>
        </div>
      </form>

      <div class="data-card">
        <div class="data-card-head"><h3>Liste des départements</h3></div>
        <table class="tbl">
          <thead><tr><th>Nom</th><th>Description</th></tr></thead>
          <tbody>
            <?php if (empty($departments)): ?>
              <tr><td colspan="2"><div class="empty"><i class="bi bi-inbox"></i><p>Aucun département.</p></div></td></tr>
            <?php else: ?>
              <?php foreach ($departments as $department): ?>
                <tr>
                  <td class="td-name"><?= esc($department['nom']) ?></td>
                  <td class="td-muted"><?= esc($department['description'] ?? '—') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> <?= esc(date('Y')) ?> <span>TechMada RH</span></div>
  </div>
</div>
<?= $this->endSection() ?>
