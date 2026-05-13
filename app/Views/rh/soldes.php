<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<?php $active = 'soldes'; ?>
<div class="app-wrap">
  <?= $this->include('partials/sidebar_rh') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Soldes employés</div>
        <div class="topbar-breadcrumb">
          <a href="<?= site_url('rh') ?>">Accueil</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Soldes <?= esc($year) ?>
        </div>
      </div>
    </div>

    <div class="content">
      <?= $this->include('partials/flash') ?>

      <div class="data-card">
        <div class="data-card-head"><h3>Soldes de congés — <?= esc($year) ?></h3></div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Type</th><th>Attribués</th><th>Pris</th><th>Restants</th></tr>
          </thead>
          <tbody>
            <?php if (empty($soldes)): ?>
              <tr><td colspan="5"><div class="empty"><i class="bi bi-inbox"></i><p>Aucun solde trouvé.</p></div></td></tr>
            <?php else: ?>
              <?php foreach ($soldes as $solde): ?>
                <?php $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris']; ?>
                <tr>
                  <td class="td-name"><?= esc($solde['prenom'] . ' ' . $solde['nom']) ?></td>
                  <td><?= esc($solde['libelle']) ?></td>
                  <td class="td-mono"><?= esc($solde['jours_attribues']) ?> j</td>
                  <td class="td-mono"><?= esc($solde['jours_pris']) ?> j</td>
                  <td class="td-mono" style="color:<?= $restant <= 2 ? 'var(--warn)' : 'var(--forest)' ?>"><?= esc($restant) ?> j</td>
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
