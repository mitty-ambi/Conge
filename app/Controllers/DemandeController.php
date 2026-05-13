<?php
    namespace App\Controllers;
    use App\Models\DemandeModel;
    use App\Models\Statut;
    use App\Models\DemandeStatutModel;
    use App\Models\DemandeDecision;
    use App\Models\Departement;
    use App\Models\Utilisateur;
    use App\Models\Type;
    use App\Models\SoldeModel;
    class DemandeController extends BaseController{

        private function enrichDemandes(array $demandes): array
        {
            $demandeModel = new DemandeModel();
            $utilisateurModel = new Utilisateur();
            $departementModel = new Departement();
            $typeModel = new Type();
            $soldeModel = new SoldeModel();

            for ($i = 0; $i < count($demandes); $i++) {
                $idDemande = $demandes[$i]['id_demande'] ?? null;
                $idUser = $demandes[$i]['id_user'] ?? null;
                $idType = $demandes[$i]['id_type'] ?? null;

                if ($idDemande !== null) {
                    $demandes[$i]['statut'] = $demandeModel->getStatutByDemandeId($idDemande);
                    $demandes[$i]['decision'] = $demandeModel->getDecisionByDemandeId($idDemande);
                    $demandes[$i]['duree'] = $demandeModel->getDureeDemande($idDemande);
                }

                $user = ($idUser !== null) ? $utilisateurModel->find($idUser) : null;
                $prenom = is_array($user) ? ($user['Prenom'] ?? '') : '';
                $nom = is_array($user) ? ($user['Nom'] ?? '') : '';
                $fullName = trim(trim($prenom) . ' ' . trim($nom));
                $demandes[$i]['employe_nom'] = $fullName;
                $demandes[$i]['employe_initials'] = strtoupper(substr(trim($prenom), 0, 1) . substr(trim($nom), 0, 1));

                $deptName = '';
                if (is_array($user) && isset($user['id_departement'])) {
                    $dept = $departementModel->find((int) $user['id_departement']);
                    if (is_array($dept)) {
                        $deptName = $dept['nom'] ?? '';
                    }
                }
                $demandes[$i]['departement_nom'] = $deptName;

                $typeNom = '';
                if ($idType !== null) {
                    $typeRow = $typeModel->find((int) $idType);
                    $typeNom = is_array($typeRow) ? ($typeRow['nom'] ?? '') : '';
                }
                $typeLibelle = preg_replace('/^Congé\s+/i', '', (string) $typeNom);
                $typeLibelle = trim($typeLibelle);
                $demandes[$i]['type_libelle'] = $typeLibelle !== '' ? $typeLibelle : (string) ($idType ?? '');

                $typeClass = '';
                $typeNomLower = mb_strtolower((string) $typeNom);
                if (str_contains($typeNomLower, 'annuel')) {
                    $typeClass = 't-annuel';
                } elseif (str_contains($typeNomLower, 'maladie')) {
                    $typeClass = 't-maladie';
                } elseif (str_contains($typeNomLower, 'sans solde')) {
                    $typeClass = 't-sans-solde';
                }
                $demandes[$i]['type_class'] = $typeClass;

                $dateDebut = $demandes[$i]['date_debut'] ?? null;
                $dateFin = $demandes[$i]['date_fin'] ?? null;
                $periode = '';
                if (!empty($dateDebut) && !empty($dateFin)) {
                    $d1 = date('d/m/Y', strtotime((string) $dateDebut));
                    $d2 = date('d/m/Y', strtotime((string) $dateFin));
                    $periode = $d1 . ' – ' . $d2;
                }
                $demandes[$i]['periode'] = $periode;

                $soldeDispo = null;
                if ($idUser !== null && $idType !== null) {
                    $solde = $soldeModel->getSoldeByUserIdByType((int) $idUser, (int) $idType);
                    if (is_array($solde)) {
                        $soldeDispo = $solde['valeur'] ?? null;
                    }
                }
                $demandes[$i]['solde_dispo'] = $soldeDispo;
            }

            return $demandes;
        }

        public function index()
        {
            $departementModel = new Departement();
            $data['departements'] = $departementModel->getAllDepartements();

            $statutModel = new Statut();
            $data['statuts'] = $statutModel->getAllStatuts();

            $demandeModel = new DemandeModel();
            $data['demandes'] = $this->enrichDemandes($demandeModel->getAllDemandes());
            return view('demande/index', $data);
        }
        public function getByStatut($id_statut)
        {
            $departementModel = new Departement();
            $data['departements'] = $departementModel->getAllDepartements();

            $statutModel = new Statut();
            $data['statuts'] = $statutModel->getAllStatuts();

            $demandeModel = new DemandeModel();
            $isAll = ((int)$id_statut === -1 || (int)$id_statut === 0);
            $data['demandes'] = $isAll ? $demandeModel->getAllDemandes() : $demandeModel->getDemandeByStatut((int)$id_statut);
            $data['demandes'] = $this->enrichDemandes($data['demandes']);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['demandes' => $data['demandes']]);
            }

            if ($isAll) {
                return redirect()->to('/demande');
            }

            return view('demande/index', $data);
        }
        public function getByDepartement($id_departement)
        {
            $departementModel = new Departement();
            $data['departements'] = $departementModel->getAllDepartements();
            $statutModel = new Statut();
            $data['statuts'] = $statutModel->getAllStatuts();
            $demandeModel = new DemandeModel();
            $isAll = ((int)$id_departement === -1 || (int)$id_departement === 0);
            $data['demandes'] = $isAll ? $demandeModel->getAllDemandes() : $demandeModel->getDemandeByDepartement((int)$id_departement);
            $data['demandes'] = $this->enrichDemandes($data['demandes']);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['demandes' => $data['demandes']]);
            }

            if ($isAll) {
                return redirect()->to('/demande');
            }

            return view('demande/index', $data);
        }
        public function accepterDemande($id_demande)
        {
            $demandeModel = new DemandeModel();
            $demande = $demandeModel->find((int) $id_demande);
            if (!is_array($demande)) {
                return redirect()->to('/demande');
            }

            $currentStatut = $demandeModel->getStatutByDemandeId((int) $id_demande);
            if ($currentStatut !== 'En attente') {
                return redirect()->to('/demande');
            }

            $duree = $demandeModel->getDureeDemande((int) $id_demande);
            if ($duree === null) {
                $duree = 0;
            }

            $demandeModel->modifyStatut((int) $id_demande, 2);

            $idUser = (int) ($demande['id_user'] ?? 0);
            $idType = (int) ($demande['id_type'] ?? 0);
            if ($idUser > 0 && $idType > 0 && (int) $duree > 0) {
                $soldeModel = new SoldeModel();
                $soldeModel->updateSolde($idUser, $idType, -((int) $duree));
            }
            return redirect()->to('/demande');
        }
        public function refuserDemande($id_demande){
            $demandeModel = new DemandeModel();
            $demandeModel->modifyStatut($id_demande, 3);
            return redirect()->to('/demande');
        }
    }
?>