<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-dark mb-4">Tableau de Bord RH</h1>
        </div>
    </div>

    <!-- Flash Messages -->
    <?= $this->include('partials/flash') ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-warning text-uppercase mb-1">Demandes en attente</div>
                    <div class="h3 mb-0 font-weight-bold">
                        <?= isset($pending_count) ? $pending_count : '0' ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-success text-uppercase mb-1">Approuvees</div>
                    <div class="h3 mb-0 font-weight-bold">
                        <?= isset($approved_count) ? $approved_count : '0' ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-danger text-uppercase mb-1">Refusees</div>
                    <div class="h3 mb-0 font-weight-bold">
                        <?= isset($rejected_count) ? $rejected_count : '0' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions Principales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?= base_url('rh/demande') ?>" class="btn btn-primary btn-block mb-2">
                                <i class="fas fa-inbox"></i> Consulter les Demandes
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?= base_url('employe/dashboard') ?>" class="btn btn-secondary btn-block mb-2">
                                <i class="fas fa-user"></i> Mon Espace Personnel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
