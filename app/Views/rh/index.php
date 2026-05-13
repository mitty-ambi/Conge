<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<?php
$active = 'demandes';
$pendingCount = 0;
$approvedCount = 0;
$refusedCount = 0;
foreach ($conges as $item) {
    if ($item['statut'] === 'en_attente') {
        $pendingCount++;
    } elseif ($item['statut'] === 'approuvee') {
        $approvedCount++;
    } elseif ($item['statut'] === 'refusee') {
        $refusedCount++;
    }
}

$queryBase = [];
if (($filters['department'] ?? '') !== '') {
    $queryBase['department'] = $filters['department'];
}
?>
<div class="app-wrap">
  <?= $this->include('partials/sidebar_rh') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Demandes à traiter</div>
        <div class="topbar-breadcrumb"><a href="<?= site_url('rh') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Demandes</div>
      </div>
      <div class="topbar-actions">
        <span style="font-size:.8rem;color:var(--muted);background:var(--warn-bg);border:1px solid var(--warn-br);border-radius:6px;padding:5px 10px;display:flex;align-items:center;gap:5px;color:var(--warn)">
          <i class="bi bi-hourglass-split"></i> <?= esc($pendingCount) ?> en attente
        </span>
      </div>
    </div>

    <div class="content">
      <?= $this->include('partials/flash') ?>

      <div style="display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap">
        <a href="<?= site_url('rh/demandes' . (($filters['department'] ?? '') !== '' ? '?' . http_build_query(['department' => $filters['department']]) : '')) ?>" style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid <?= ($filters['statut'] ?? '') === '' ? 'var(--forest)' : 'var(--border)' ?>;background:<?= ($filters['statut'] ?? '') === '' ? 'var(--forest)' : 'var(--white)' ?>;color:<?= ($filters['statut'] ?? '') === '' ? 'var(--white)' : 'var(--muted)' ?>;text-decoration:none">Tous (<?= esc(count($conges)) ?>)</a>
        <a href="<?= site_url('rh/demandes?' . http_build_query(array_merge($queryBase, ['statut' => 'en_attente']))) ?>" style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid <?= ($filters['statut'] ?? '') === 'en_attente' ? 'var(--forest)' : 'var(--border)' ?>;background:<?= ($filters['statut'] ?? '') === 'en_attente' ? 'var(--forest)' : 'var(--white)' ?>;color:<?= ($filters['statut'] ?? '') === 'en_attente' ? 'var(--white)' : 'var(--muted)' ?>;text-decoration:none">En attente (<?= esc($pendingCount) ?>)</a>
        <a href="<?= site_url('rh/demandes?' . http_build_query(array_merge($queryBase, ['statut' => 'approuvee']))) ?>" style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid <?= ($filters['statut'] ?? '') === 'approuvee' ? 'var(--forest)' : 'var(--border)' ?>;background:<?= ($filters['statut'] ?? '') === 'approuvee' ? 'var(--forest)' : 'var(--white)' ?>;color:<?= ($filters['statut'] ?? '') === 'approuvee' ? 'var(--white)' : 'var(--muted)' ?>;text-decoration:none">Approuvées (<?= esc($approvedCount) ?>)</a>
        <a href="<?= site_url('rh/demandes?' . http_build_query(array_merge($queryBase, ['statut' => 'refusee']))) ?>" style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid <?= ($filters['statut'] ?? '') === 'refusee' ? 'var(--forest)' : 'var(--border)' ?>;background:<?= ($filters['statut'] ?? '') === 'refusee' ? 'var(--forest)' : 'var(--white)' ?>;color:<?= ($filters['statut'] ?? '') === 'refusee' ? 'var(--white)' : 'var(--muted)' ?>;text-decoration:none">Refusées (<?= esc($refusedCount) ?>)</a>

        <form method="get" style="margin-left:auto">
          <?php if (($filters['statut'] ?? '') !== ''): ?>
            <input type="hidden" name="statut" value="<?= esc($filters['statut']) ?>"/>
          <?php endif; ?>
          <select name="department" class="f-select" onchange="this.form.submit()" style="font-size:.8rem;padding:6px 10px;width:auto">
            <option value="">Tous les départements</option>
            <?php foreach ($departments as $department): ?>
              <option value="<?= esc($department['id']) ?>" <?= (string) ($filters['department'] ?? '') === (string) $department['id'] ? 'selected' : '' ?>><?= esc($department['nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </form>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Toutes les demandes</h3></div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Type</th><th>Période</th><th>Durée</th><th>Solde dispo</th><th>Statut</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php if (empty($conges)): ?>
              <tr><td colspan="7"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande trouvée.</p></div></td></tr>
            <?php else: ?>
              <?php foreach ($conges as $conge): ?>
                <?php
                  $typeClass = 't-annuel';
                  $libelle = mb_strtolower((string) $conge['type_libelle']);
                  if (str_contains($libelle, 'maladie')) {
                    $typeClass = 't-maladie';
                  } elseif (str_contains($libelle, 'special')) {
                    $typeClass = 't-special';
                  }
                ?>
                <tr>
                  <td>
                    <div class="profile-row">
                      <div class="avatar av-green" style="width:32px;height:32px;font-size:.7rem"><?= esc(strtoupper(substr($conge['prenom'], 0, 1) . substr($conge['nom'], 0, 1))) ?></div>
                      <div class="profile-info">
                        <div class="pname"><?= esc($conge['prenom'] . ' ' . $conge['nom']) ?></div>
                        <div class="pdept"><?= esc($conge['departement'] ?? '—') ?></div>
                      </div>
                    </div>
                  </td>
                  <td><span class="type-badge <?= esc($typeClass) ?>"><?= esc($conge['type_libelle']) ?></span></td>
                  <td class="td-muted" style="font-size:.8rem"><?= esc(date('d/m/Y', strtotime($conge['date_debut']))) ?> – <?= esc(date('d/m/Y', strtotime($conge['date_fin']))) ?></td>
                  <td class="td-mono"><?= esc($conge['nb_jours']) ?> j</td>
                  <td>
                    <?php if ($conge['solde_restant'] === null): ?>
                      <span class="td-muted">—</span>
                    <?php elseif ($conge['solde_insuffisant']): ?>
                      <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--warn);font-weight:500"><?= esc($conge['solde_restant']) ?> j</span>
                      <span style="font-size:.72rem;color:var(--danger)"> insuffisant</span>
                    <?php else: ?>
                      <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--success);font-weight:500"><?= esc($conge['solde_restant']) ?> j</span>
                    <?php endif; ?>
                  </td>
                  <td><span class="statut s-<?= esc($conge['statut']) ?>"><?= esc(str_replace('_', ' ', $conge['statut'])) ?></span></td>
                  <td>
                    <?php if (in_array($conge['statut'], ['en_attente', 'approuvee'], true)): ?>
                      <div class="action-btns">
                        <?php if ($conge['statut'] === 'en_attente'): ?>
                          <form method="post" action="<?= site_url('rh/demandes/' . $conge['id'] . '/approuver') ?>">
                            <?= csrf_field() ?>
                            <button class="btn-sm btn-approve" type="submit" <?= $conge['solde_insuffisant'] ? 'disabled style="opacity:.4;cursor:not-allowed"' : '' ?>><i class="bi bi-check-lg"></i> Approuver</button>
                          </form>
                        <?php endif; ?>
                        <a class="btn-sm btn-refuse" href="<?= site_url('rh/demandes?' . http_build_query(array_merge($queryBase, ['statut' => $filters['statut'] ?? '', 'refuse_id' => $conge['id']]))) ?>"><i class="bi bi-x-lg"></i> Refuser</a>
                      </div>
                    <?php else: ?>
                      <span class="td-muted" style="font-size:.75rem">Traité</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if (!empty($refuseDemande)): ?>
        <div class="form-section" style="border-color:var(--danger-br);background:var(--danger-bg)">
          <h3 style="color:var(--danger)"><i class="bi bi-x-circle"></i> Confirmer le refus — <?= esc($refuseDemande['prenom'] . ' ' . $refuseDemande['nom']) ?></h3>
          <div style="font-size:.875rem;color:var(--ink);margin-bottom:1rem">
            Demande de <strong><?= esc($refuseDemande['nb_jours']) ?> jour(s)</strong> du <?= esc(date('d/m/Y', strtotime($refuseDemande['date_debut']))) ?> au <?= esc(date('d/m/Y', strtotime($refuseDemande['date_fin']))) ?> · Type : <?= esc($refuseDemande['type_libelle']) ?>
          </div>
          <form method="post" action="<?= site_url('rh/demandes/' . $refuseDemande['id'] . '/refuser') ?>">
            <?= csrf_field() ?>
            <div class="f-group">
              <label class="f-label">Commentaire pour l'employé (optionnel)</label>
              <textarea class="f-textarea" name="commentaire_rh" placeholder="Motif du refus..."></textarea>
            </div>
            <div class="form-actions">
              <button class="btn-sm btn-refuse" style="padding:9px 16px;font-size:.875rem" type="submit"><i class="bi bi-x-lg"></i> Confirmer le refus</button>
              <a class="btn-secondary" href="<?= site_url('rh/demandes' . (!empty(array_filter($filters)) ? '?' . http_build_query(array_filter($filters, static fn ($v) => $v !== '')) : '')) ?>"><i class="bi bi-arrow-left"></i> Annuler</a>
            </div>
          </form>
        </div>
      <?php endif; ?>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> <?= esc(date('Y')) ?> <span>TechMada RH</span></div>
  </div>
</div>
<?= $this->endSection() ?>
