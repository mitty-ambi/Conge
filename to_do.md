## to list rh:
### Page de liste des demandes en attente:
    .. Affichage : 
        * Statistiques des demandes (Tous, en attente, accepte, refuse)
        * Tableau des listes des demandes:
            . user
            . type de conge demande
            . periode
            . duree
            . solde dispo
            . statut
            . actions
        * Refus inevitable(quota de conges insuffisant)
    .. Services : 
        * GetAlldemandes(Demande)
        * GetDemandeByStatut(Demande)
        * GetDemandeByDepartement(Demande)
        * GetSoldeUserByType(Solde)
        * ModifierStatut(Demande)
        * ModifierSolde(Solde)
        * GetAgentDedecision(celui qui a approuve ou refuse la demande)(DemandeDecision)