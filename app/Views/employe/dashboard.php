<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <title>Document</title>
</head>
<?= view("navbar") ?>
<?= view("sidebar") ?>

<body>

    <!-- MAIN CONTENT -->
    <div class="main">

        <div class="content">
            <!-- Flash message -->
            <div class="flash flash-success">
                <i class="bi bi-check-circle-fill"></i>
                Votre demande de congé a bien été soumise. Elle est en attente de validation.
            </div>

            <!-- Metrics -->
            <div class="metrics">
                <div class="metric">
                    <div class="metric-top">
                        <div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div>
                    </div>
                    <div class="metric-val"><?= $en_attente ?? 0 ?></div>
                    <div class="metric-label">En attente</div>
                </div>
                <div class="metric">
                    <div class="metric-top">
                        <div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div>
                    </div>
                    <div class="metric-val"><?= $approuvees ?? 0 ?></div>
                    <div class="metric-label">Approuvées</div>
                </div>
                <div class="metric">
                    <div class="metric-top">
                        <div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div>
                    </div>
                    <div class="metric-val">18</div>
                    <div class="metric-label">Jours restants</div>
                    <div class="metric-sub">sur 30 cette année</div>
                </div>
                <div class="metric">
                    <div class="metric-top">
                        <div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div>
                    </div>
                    <div class="metric-val"><?= $refusees ?? 0 ?></div>
                    <div class="metric-label">Refusée</div>
                </div>
            </div>

            <!-- Soldes de congés -->
            <div class="data-card">
                <div class="data-card-head">
                    <h3>Mes soldes de congés — 2025</h3>
                </div>
                <div
                    style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
                    <div class="solde-card" style="margin:0">
                        <div class="solde-header">
                            <span class="solde-type">Congé annuel</span>
                            <span class="solde-nums"><strong>18</strong> / 30 j</span>
                        </div>
                        <div class="solde-bar">
                            <div class="solde-fill" style="width:60%"></div>
                        </div>
                        <div class="solde-label">18 jours restants · 12 pris</div>
                    </div>
                    <div class="solde-card" style="margin:0">
                        <div class="solde-header">
                            <span class="solde-type">Congé maladie</span>
                            <span class="solde-nums"><strong>8</strong> / 10 j</span>
                        </div>
                        <div class="solde-bar">
                            <div class="solde-fill" style="width:80%"></div>
                        </div>
                        <div class="solde-label">8 jours restants · 2 pris</div>
                    </div>
                    <div class="solde-card" style="margin:0">
                        <div class="solde-header">
                            <span class="solde-type">Congé spécial</span>
                            <span class="solde-nums"><strong>1</strong> / 5 j</span>
                        </div>
                        <div class="solde-bar">
                            <div class="solde-fill warn" style="width:20%"></div>
                        </div>
                        <div class="solde-label">1 jour restant · 4 pris</div>
                    </div>
                </div>
            </div>

            <!-- Dernières demandes -->
            <div class="data-card">
                <div class="data-card-head">
                    <h3>Mes dernières demandes</h3>
                    <a href="#" style="font-size:.8rem;color:var(--forest);text-decoration:none">Voir tout →</a>
                </div>
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Du</th>
                            <th>Au</th>
                            <th>Durée</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($demandes)): ?>
                            <?php foreach ($demandes as $d): ?>
                                <tr>
                                    <td><span class="type-badge t-annuel"><?= ucfirst($d['type_conge'] ?? 'Annuel') ?></span>
                                    </td>
                                    <td class="td-muted"><?= date('d M Y', strtotime($d['date_debut'])) ?></td>
                                    <td class="td-muted"><?= date('d M Y', strtotime($d['date_fin'])) ?></td>
                                    <td class="td-mono"><?= $d['duree'] ?? 1 ?> j</td>
                                    <td><span
                                            class="statut s-<?= ($d['statut'] == 1) ? 'attente' : (($d['statut'] == 2) ? 'approuvee' : 'refusee') ?>"><?= ($d['statut'] == 1) ? 'en attente' : (($d['statut'] == 2) ? 'approuvée' : 'refusée') ?></span>
                                    </td>
                                    <td><?php if ($d['statut'] == 1): ?><button class="btn-sm btn-cancel"><i
                                                    class="bi bi-x"></i> Annuler</button><?php else: ?><span
                                                class="td-muted">—</span><?php endif; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center;padding:1rem;color:var(--muted)">Aucune demande
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-app">
            <i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Projet CodeIgniter 4
        </div>
    </div>

</body>

</html>