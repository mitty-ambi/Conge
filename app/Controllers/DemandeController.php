<?php
    namespace App\Controllers;
    use App\Models\DemandeModel;
    use App\Models\Statut;
    class DemandeController extends BaseController{
        public function index()
        {
            $statutModel = new Statut();
            $data['statuts'] = $statutModel->getAllStatuts();

            $demandeModel = new DemandeModel();
            $data['demandes'] = $demandeModel->getAllDemandes();

            for($i = 0; $i < count($data['demandes']); $i++) {
                $data['demandes'][$i]['statut'] = $demandeModel->getStatutByDemandeId($data['demandes'][$i]['id_demande']);
                $data['demandes'][$i]['decision'] = $demandeModel->getDecisionByDemandeId($data['demandes'][$i]['id_demande']);
                $data['demandes'][$i]['duree'] = $demandeModel->getDureeDemande($data['demandes'][$i]['id_demande']);
            }
            return view('demande/index', $data);
        }
        public function getByStatut($id_statut)
        {
            $statutModel = new Statut();
            $data['statuts'] = $statutModel->getAllStatuts();

            $demandeModel = new DemandeModel();
            $isAll = ((int)$id_statut === -1 || (int)$id_statut === 0);
            $data['demandes'] = $isAll ? $demandeModel->getAllDemandes() : $demandeModel->getDemandeByStatut((int)$id_statut);
            
            for($i = 0; $i < count($data['demandes']); $i++) {
                $data['demandes'][$i]['statut'] = $demandeModel->getStatutByDemandeId($data['demandes'][$i]['id_demande']);
                $data['demandes'][$i]['decision'] = $demandeModel->getDecisionByDemandeId($data['demandes'][$i]['id_demande']);
                $data['demandes'][$i]['duree'] = $demandeModel->getDureeDemande($data['demandes'][$i]['id_demande']);
            }

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
            $statutModel = new Statut();
            $data['statuts'] = $statutModel->getAllStatuts();
            $demandeModel = new DemandeModel();
            $data['demandes'] = $demandeModel->getDemandeByDepartement($id_departement);
            
            for($i = 0; $i < count($data['demandes']); $i++) {
                $data['demandes'][$i]['statut'] = $demandeModel->getStatutByDemandeId($data['demandes'][$i]['id_demande']);
                $data['demandes'][$i]['decision'] = $demandeModel->getDecisionByDemandeId($data['demandes'][$i]['id_demande']);
                $data['demandes'][$i]['duree'] = $demandeModel->getDureeDemande($data['demandes'][$i]['id_demande']);
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['demandes' => $data['demandes']]);
            }

            return view('demande/index', $data);
        }
        public function accepterDemande($id_demande)
        {
            $demandeModel = new DemandeModel();
            $demandeModel->modifyStatut($id_demande, 2);
            return redirect()->to('/demande');
        }
        public function refuserDemande($id_demande){
            $demandeModel = new DemandeModel();
            $demandeModel->modifyStatut($id_demande, 3);
            return redirect()->to('/demande');
        }
    }
?>