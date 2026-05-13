<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<?php $active = 'mes-conges'; ?>
<div class="app-wrap">
  <?= $this->include('partials/sidebar_employe') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Mes demandes de congé</div>
        <div class="topbar-breadcrumb">
          <a href="<?= site_url('employe') ?>">Accueil</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Mes demandes
        </div>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('employe/conges/nouveau') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
      </div>
    </div>

    <div class="content">
      <?= $this->include('partials/flash') ?>

      <div class="data-card">
        <div class="data-card-head">
          <h3>Toutes mes demandes</h3>
          <form method="get">
            <select class="f-select" name="statut" onchange="this.form.submit()" style="font-size:.8rem;padding:6px 10px;width:auto">
              <option value="" <?= ($filter ?? '') === '' ? 'selected' : '' ?>>Tous les statuts</option>
              <option value="en_attente" <?= ($filter ?? '') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
              <option value="approuvee" <?= ($filter ?? '') === 'approuvee' ? 'selected' : '' ?>>Approuvée</option>
              <option value="refusee" <?= ($filter ?? '') === 'refusee' ? 'selected' : '' ?>>Refusée</option>
              <option value="annulee" <?= ($filter ?? '') === 'annulee' ? 'selected' : '' ?>>Annulée</option>
            </select>
          </form>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th><th>Commentaire RH</th><th>Action</th></tr>
          </thead>
          <tbody>
            <?php if (empty($conges)): ?>
              <tr><td colspan="7"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande trouvée.</p></div></td></tr>
            <?php else: ?>
              <?php foreach ($conges as $conge): ?>
                <?php
                  $typeClass = 't-annuel';
                  $libelle = mb_strtolower((string) $conge['libelle']);
                  if (str_contains($libelle, 'maladie')) {
                    $typeClass = 't-maladie';
                  } elseif (str_contains($libelle, 'special')) {
                    $typeClass = 't-special';
                  }
                ?>
                <tr>
                  <td><span class="type-badge <?= esc($typeClass) ?>"><?= esc($conge['libelle']) ?></span></td>
                  <td class="td-muted"><?= esc(date('d/m/Y', strtotime($conge['date_debut']))) ?></td>
                  <td class="td-muted"><?= esc(date('d/m/Y', strtotime($conge['date_fin']))) ?></td>
                  <td class="td-mono"><?= esc($conge['nb_jours']) ?> j</td>
                  <td><span class="statut s-<?= esc($conge['statut']) ?>"><?= esc(str_replace('_', ' ', $conge['statut'])) ?></span></td>
                  <td class="td-muted" style="font-size:.78rem"><?= esc($conge['commentaire_rh'] ?? '—') ?></td>
                  <td>
                    <?php if ($conge['statut'] === 'en_attente'): ?>
                      <form method="post" action="<?= site_url('employe/conges/' . $conge['id'] . '/annuler') ?>">
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
    <div class="footer-app"><i class="bi bi-c-circle"></i> <?= esc(date('Y')) ?> <span>TechMada RH</span></div>
  </div>
</div>
<?= $this->endSection() ?>
