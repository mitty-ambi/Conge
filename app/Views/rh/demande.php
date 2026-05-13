<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-dark mb-4">Demandes de Conges</h1>
        </div>
    </div>

    <!-- Flash Messages -->
    <?= $this->include('partials/flash') ?>

    <!-- Filter & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Rechercher par nom employe...">
                </div>
                <div class="col-md-4">
                    <select class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En attente</option>
                        <option value="approuvee">Approuvees</option>
                        <option value="refusee">Refusees</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary btn-block">Rechercher</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Demandes Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Liste des Demandes</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Employe</th>
                        <th>Type de Conge</th>
                        <th>Dates</th>
                        <th>Nombre de Jours</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($conges) && count($conges) > 0): ?>
                        <?php foreach ($conges as $conge): ?>
                        <tr>
                            <td><?= esc($conge['employe_nom']) ?> <?= esc($conge['employe_prenom']) ?></td>
                            <td><?= esc($conge['type_libelle']) ?></td>
                            <td><?= $conge['date_debut'] ?> au <?= $conge['date_fin'] ?></td>
                            <td><?= $conge['nb_jours'] ?> jour(s)</td>
                            <td>
                                <?php
                                $badges = [
                                    'en_attente' => 'warning',
                                    'approuvee' => 'success',
                                    'refusee' => 'danger'
                                ];
                                $badge_class = $badges[$conge['statut']] ?? 'secondary';
                                ?>
                                <span class="badge badge-<?= $badge_class ?>">
                                    <?= ucfirst(str_replace('_', ' ', $conge['statut'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($conge['statut'] === 'en_attente'): ?>
                                    <button class="btn btn-sm btn-success" title="Approuver">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" title="Refuser">
                                        <i class="fas fa-times"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-info" title="Voir details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Aucune demande trouvee
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
