<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<div class="app-wrap">
  <?php $pendingCount = $metrics['pending'] ?? 0; ?>
  <?php $active = 'dashboard'; ?>
  <?= $this->include('partials/sidebar_employe') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Tableau de bord</div>
        <div class="topbar-breadcrumb">Accueil</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('employe/conges/nouveau') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
          <i class="bi bi-plus-lg"></i> Nouvelle demande
        </a>
      </div>
    </div>

    <div class="content">
      <?= $this->include('partials/flash') ?>

      <div class="metrics">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
          <div class="metric-val"><?= esc($metrics['pending'] ?? 0) ?></div>
          <div class="metric-label">En attente</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
          <div class="metric-val"><?= esc($metrics['approved'] ?? 0) ?></div>
          <div class="metric-label">Approuvees</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div>
          <div class="metric-val"><?= esc($metrics['remaining'] ?? 0) ?></div>
          <div class="metric-label">Jours restants</div>
          <div class="metric-sub">sur <?= esc($metrics['total'] ?? 0) ?> cette annee</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
          <div class="metric-val"><?= esc($metrics['refused'] ?? 0) ?></div>
          <div class="metric-label">Refusees</div>
        </div>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Mes soldes de conges — <?= esc(date('Y')) ?></h3></div>
        <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
          <?php foreach ($soldes as $solde): ?>
            <div class="solde-card" style="margin:0">
              <div class="solde-header">
                <span class="solde-type">Conge <?= esc(strtolower($solde['libelle'])) ?></span>
                <span class="solde-nums"><strong><?= esc($solde['restant']) ?></strong> / <?= esc($solde['jours_attribues']) ?> j</span>
              </div>
              <div class="solde-bar"><div class="solde-fill" style="width:<?= esc(min(100, max(0, $solde['percent']))) ?>%"></div></div>
              <div class="solde-label"><?= esc($solde['restant']) ?> jours restants · <?= esc($solde['jours_pris']) ?> pris</div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="data-card">
        <div class="data-card-head">
          <h3>Mes dernieres demandes</h3>
          <a href="<?= site_url('employe/conges') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Voir tout →</a>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Type</th><th>Du</th><th>Au</th><th>Duree</th><th>Statut</th><th>Action</th></tr>
          </thead>
          <tbody>
            <?php if (empty($recent)): ?>
              <tr><td colspan="6"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande pour le moment.</p></div></td></tr>
            <?php else: ?>
              <?php foreach ($recent as $demande): ?>
                <?php
                  $typeClass = 't-annuel';
                  $libelle = mb_strtolower((string) $demande['libelle']);
                  if (str_contains($libelle, 'maladie')) {
                    $typeClass = 't-maladie';
                  } elseif (str_contains($libelle, 'special')) {
                    $typeClass = 't-special';
                  }
                ?>
                <tr>
                  <td><span class="type-badge <?= esc($typeClass) ?>"><?= esc($demande['libelle']) ?></span></td>
                  <td class="td-muted"><?= esc(date('d/m/Y', strtotime($demande['date_debut']))) ?></td>
                  <td class="td-muted"><?= esc(date('d/m/Y', strtotime($demande['date_fin']))) ?></td>
                  <td class="td-mono"><?= esc($demande['nb_jours']) ?> j</td>
                  <td>
                    <span class="statut s-<?= esc($demande['statut']) ?>">
                      <?= esc(str_replace('_', ' ', $demande['statut'])) ?>
                    </span>
                  </td>
                  <td>
                    <?php if ($demande['statut'] === 'en_attente'): ?>
                      <form method="post" action="<?= site_url('employe/conges/' . $demande['id'] . '/annuler') ?>">
                        <?= csrf_field() ?>
                        <button class="btn-sm btn-cancel" type="submit"><i class="bi bi-x"></i> Annuler</button>
                      </form>
                    <?php else: ?>
                      <span class="td-muted" style="font-size:.75rem">—</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> <?= esc(date('Y')) ?> <span>TechMada RH</span> — Projet CodeIgniter 4</div>
  </div>
</div>
<?= $this->endSection() ?>
