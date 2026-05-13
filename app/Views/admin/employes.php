<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<?php $active = 'employes'; ?>
<div class="app-wrap">
  <?= $this->include('partials/sidebar_admin') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Gestion des employés</div>
        <div class="topbar-breadcrumb"><a href="<?= site_url('admin') ?>">Admin</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Employés</div>
      </div>
    </div>

    <div class="content">
      <?= $this->include('partials/flash') ?>

      <form class="form-section" method="post" action="<?= site_url('admin/employes') ?>">
        <?= csrf_field() ?>
        <h3><i class="bi bi-person-plus" style="color:var(--forest);margin-right:6px"></i>Ajouter un employé</h3>
        <div class="form-grid-2">
          <div class="f-group">
            <label class="f-label">Prénom</label>
            <input type="text" class="f-input" name="prenom" value="<?= esc(old('prenom') ?? '') ?>" required/>
          </div>
          <div class="f-group">
            <label class="f-label">Nom</label>
            <input type="text" class="f-input" name="nom" value="<?= esc(old('nom') ?? '') ?>" required/>
          </div>
          <div class="f-group">
            <label class="f-label">Email</label>
            <input type="email" class="f-input" name="email" value="<?= esc(old('email') ?? '') ?>" required/>
          </div>
          <div class="f-group">
            <label class="f-label">Mot de passe initial</label>
            <input type="password" class="f-input" name="password" required/>
          </div>
          <div class="f-group">
            <label class="f-label">Département</label>
            <select class="f-select" name="department_id" required>
              <option value="">Choisir...</option>
              <?php foreach ($departments as $department): ?>
                <option value="<?= esc($department['id']) ?>" <?= old('department_id') == $department['id'] ? 'selected' : '' ?>><?= esc($department['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label">Rôle</label>
            <select class="f-select" name="role" required>
              <option value="employe" <?= old('role') === 'employe' ? 'selected' : '' ?>>Employé</option>
              <option value="rh" <?= old('role') === 'rh' ? 'selected' : '' ?>>Responsable RH</option>
              <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label">Date d'embauche</label>
            <input type="date" class="f-input" name="date_embauche" value="<?= esc(old('date_embauche') ?? date('Y-m-d')) ?>"/>
          </div>
        </div>
        <div class="flash flash-info" style="margin-bottom:1rem">
          <i class="bi bi-info-circle-fill"></i>
          <span style="font-size:.82rem">Les soldes de congés seront initialisés automatiquement.</span>
        </div>
        <div class="form-actions">
          <button class="btn-forest" type="submit"><i class="bi bi-plus"></i> Créer l'employé</button>
        </div>
      </form>

      <div class="data-card">
        <div class="data-card-head">
          <h3>Tous les employés</h3>
          <div class="td-muted" style="font-size:.8rem">Soldes <?= esc($year) ?></div>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Département</th><th>Rôle</th><th>Embauche</th><th>Statut</th><th>Solde annuel</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php if (empty($employees)): ?>
              <tr><td colspan="7"><div class="empty"><i class="bi bi-inbox"></i><p>Aucun employé.</p></div></td></tr>
            <?php else: ?>
              <?php foreach ($employees as $employee): ?>
                <?php
                  $soldeText = [];
                  foreach ($types as $type) {
                    $value = $soldeMap[$employee['id']][$type['libelle']] ?? null;
                    if ($value !== null) {
                      $soldeText[] = $type['libelle'] . ': ' . $value;
                    }
                  }
                ?>
                <tr style="<?= (int) $employee['actif'] === 0 ? 'opacity:.55' : '' ?>">
                  <td>
                    <div class="profile-row">
                      <div class="avatar av-green" style="width:32px;height:32px;font-size:.68rem"><?= esc(strtoupper(substr($employee['prenom'], 0, 1) . substr($employee['nom'], 0, 1))) ?></div>
                      <div class="profile-info"><div class="pname"><?= esc($employee['prenom'] . ' ' . $employee['nom']) ?></div><div class="pdept"><?= esc($employee['email']) ?></div></div>
                    </div>
                  </td>
                  <td class="td-muted"><?= esc($employee['departement'] ?? '—') ?></td>
                  <td><span class="type-badge" style="background:#f1efe8;color:#444441"><?= esc($employee['role']) ?></span></td>
                  <td class="td-muted td-mono" style="font-size:.78rem"><?= esc($employee['date_embauche'] ?? '—') ?></td>
                  <td><span class="statut <?= (int) $employee['actif'] === 1 ? 's-approuvee' : 's-annulee' ?>" style="font-size:.68rem"><?= (int) $employee['actif'] === 1 ? 'actif' : 'inactif' ?></span></td>
                  <td><span style="font-family:'DM Mono',monospace;font-size:.76rem;color:var(--forest)"><?= esc(!empty($soldeText) ? implode(' · ', $soldeText) : '—') ?></span></td>
                  <td>
                    <div class="action-btns">
                      <details>
                        <summary class="btn-sm btn-edit" style="list-style:none"><i class="bi bi-pencil"></i> Éditer</summary>
                        <form method="post" action="<?= site_url('admin/employes/' . $employee['id'] . '/update') ?>" style="margin-top:8px;min-width:260px">
                          <?= csrf_field() ?>
                          <input class="f-input" style="margin-bottom:6px;padding:7px 10px;font-size:.78rem" name="prenom" value="<?= esc($employee['prenom']) ?>" required/>
                          <input class="f-input" style="margin-bottom:6px;padding:7px 10px;font-size:.78rem" name="nom" value="<?= esc($employee['nom']) ?>" required/>
                          <select class="f-select" style="margin-bottom:6px;padding:7px 10px;font-size:.78rem" name="department_id" required>
                            <?php foreach ($departments as $department): ?>
                              <option value="<?= esc($department['id']) ?>" <?= (int) $employee['department_id'] === (int) $department['id'] ? 'selected' : '' ?>><?= esc($department['nom']) ?></option>
                            <?php endforeach; ?>
                          </select>
                          <select class="f-select" style="margin-bottom:6px;padding:7px 10px;font-size:.78rem" name="role" required>
                            <option value="employe" <?= $employee['role'] === 'employe' ? 'selected' : '' ?>>Employé</option>
                            <option value="rh" <?= $employee['role'] === 'rh' ? 'selected' : '' ?>>Responsable RH</option>
                            <option value="admin" <?= $employee['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                          </select>
                          <input class="f-input" style="margin-bottom:6px;padding:7px 10px;font-size:.78rem" name="password" type="password" placeholder="Nouveau mot de passe (optionnel)"/>
                          <button class="btn-sm btn-edit" type="submit"><i class="bi bi-check2"></i> Enregistrer</button>
                        </form>
                      </details>
                      <form method="post" action="<?= site_url('admin/employes/' . $employee['id'] . '/toggle') ?>">
                        <?= csrf_field() ?>
                        <button class="btn-sm <?= (int) $employee['actif'] === 1 ? 'btn-del' : 'btn-view' ?>" type="submit">
                          <?php if ((int) $employee['actif'] === 1): ?>
                            <i class="bi bi-slash-circle"></i>
                          <?php else: ?>
                            <i class="bi bi-arrow-counterclockwise"></i> Réactiver
                          <?php endif; ?>
                        </button>
                      </form>
                    </div>
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
