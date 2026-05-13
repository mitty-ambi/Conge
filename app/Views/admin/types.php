<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<?php $active = 'types'; ?>
<div class="app-wrap">
  <?= $this->include('partials/sidebar_admin') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Types de congé</div>
        <div class="topbar-breadcrumb"><a href="<?= site_url('admin') ?>">Admin</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Types</div>
      </div>
    </div>

    <div class="content">
      <?= $this->include('partials/flash') ?>

      <form class="form-section" method="post" action="<?= site_url('admin/types') ?>">
        <?= csrf_field() ?>
        <h3><i class="bi bi-tags" style="color:var(--forest);margin-right:6px"></i>Ajouter un type</h3>
        <div class="form-grid-2">
          <div class="f-group">
            <label class="f-label">Libellé</label>
            <input type="text" class="f-input" name="libelle" value="<?= esc(old('libelle') ?? '') ?>" required/>
          </div>
          <div class="f-group">
            <label class="f-label">Jours annuels</label>
            <input type="number" class="f-input" name="jours_annuels" min="0" value="<?= esc(old('jours_annuels') ?? '0') ?>" required/>
          </div>
          <div class="f-group">
            <label class="f-label">Déductible</label>
            <select class="f-select" name="deductible">
              <option value="1" <?= old('deductible', '1') === '1' ? 'selected' : '' ?>>Oui</option>
              <option value="0" <?= old('deductible') === '0' ? 'selected' : '' ?>>Non</option>
            </select>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn-forest" type="submit"><i class="bi bi-plus"></i> Ajouter</button>
        </div>
      </form>

      <div class="data-card">
        <div class="data-card-head"><h3>Liste des types</h3></div>
        <table class="tbl">
          <thead><tr><th>Libellé</th><th>Jours annuels</th><th>Déductible</th></tr></thead>
          <tbody>
            <?php if (empty($types)): ?>
              <tr><td colspan="3"><div class="empty"><i class="bi bi-inbox"></i><p>Aucun type de congé.</p></div></td></tr>
            <?php else: ?>
              <?php foreach ($types as $type): ?>
                <?php
                  $typeClass = 't-annuel';
                  $libelle = mb_strtolower((string) $type['libelle']);
                  if (str_contains($libelle, 'maladie')) {
                    $typeClass = 't-maladie';
                  } elseif (str_contains($libelle, 'special')) {
                    $typeClass = 't-special';
                  }
                ?>
                <tr>
                  <td><span class="type-badge <?= esc($typeClass) ?>"><?= esc($type['libelle']) ?></span></td>
                  <td class="td-mono"><?= esc($type['jours_annuels']) ?> j</td>
                  <td class="td-muted"><?= (int) $type['deductible'] === 1 ? 'Oui' : 'Non' ?></td>
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
