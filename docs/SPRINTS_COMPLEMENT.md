# Chapitre 4 — Sprint 2 : Gestion des rendez-vous

Plan  
— Introduction  
— 4.1 Analyse du Sprint 2  
— 4.1.1 Développement  
— 4.1.2 Backlog du Produit – Sprint 2  
— 4.1.3 Diagramme de Cas d’Utilisation Global – Sprint 2  
— 4.1.4 Gestion des Rendez-vous et Agenda  
— 4.1.4.1 Diagramme de Cas d’Utilisation – Gestion des Rendez-vous  
— 4.1.4.2 Description Textuelle « Planifier un rendez-vous »  
— 4.1.4.3 Description Textuelle « Gérer l’agenda médecin »  
— 4.1.4.4 Description Textuelle « Mettre à jour le statut »  
— 4.2 Conception  
— 4.2.1 Diagramme de Séquence – Sprint 2  
— 4.2.2 Diagramme de Classe – Sprint 2  
— 4.3 Réalisation  
— 4.3.1 Interface Agenda Médecin  
— 4.3.2 Interface de prise de rendez-vous Patient  
— Conclusion

### Introduction

Le Sprint 2 consolide la pierre angulaire opérationnelle de MedicalPlatform : la gestion des rendez-vous. Il orchestre la coordination entre patients et médecins, synchronise l’agenda médical et prépare les consultations distantes. Les développements s’appuient sur `app/Http/Controllers/Patient/RendezVousController.php`, `app/Http/Controllers/Medecin/RendezVousController.php`, les événements `RendezVousCreate` / `RendezVousModifie` et `ConsultationService` qui génère automatiquement les liens Jitsi.

### 4.1 Analyse du Sprint 2

#### 4.1.1 Développement
- Intégration de FullCalendar et d’un mini calendrier hebdomadaire dans `dashMedecin/AgendaRendezvous`.
- Centralisation des règles métier (créneaux de 30 min, fuseau `Africa/Tunis`, transition d’états `pending → confirmed → payed`) dans les contrôleurs patient/médecin.
- Publication d’événements temps réel et notifications persistantes lors des créations/modifications.
- Génération automatique des salles de visioconférence via `ConsultationService`.
- Ajout de statistiques contextuelles (nombre de rendez-vous confirmés, en attente, annulés) pour aider le médecin dans ses décisions.

#### 4.1.2 Backlog du Produit – Sprint 2

| Fonctionnalité | User Story | Priorité |
| --- | --- | --- |
| Planification patient | En tant que patient, je veux choisir un médecin, un créneau libre et préciser mon motif pour soumettre une demande de rendez-vous. | Haute |
| Agenda médecin | En tant que médecin, je veux visualiser mon agenda par jour/semaine/mois pour gérer mes disponibilités et conflits. | Haute |
| Modification & drag-drop | En tant que médecin, je veux ajuster la durée et déplacer un rendez-vous directement depuis le calendrier. | Haute |
| Synchronisation visioconférence | En tant que patient, je veux recevoir un lien sécurisé de consultation dès que le médecin confirme mon rendez-vous. | Moyenne |
| Historique & stats | En tant que médecin, je veux suivre les statistiques mensuelles pour anticiper ma charge. | Moyenne |

#### 4.1.3 Diagramme de Cas d’Utilisation Global – Sprint 2

Le diagramme global illustre les interactions croisées : le patient soumet une demande, l’administrateur peut consulter l’historique, le médecin confirme ou replanifie, tandis que le service de visioconférence prépare automatiquement la salle associée au rendez-vous. Les acteurs Patient/Médecin/Admin partagent le même référentiel, mais ne disposent que des actions autorisées par leur rôle.

#### 4.1.4 Gestion des Rendez-vous et Agenda

##### 4.1.4.1 Diagramme de Cas d’Utilisation – Gestion des Rendez-vous

Figure 4.1 présente les cas « Planifier un rendez-vous », « Gérer l’agenda médecin » et « Mettre à jour le statut ». Les relations d’inclusion matérialisent l’envoi d’alertes et la génération du lien Jitsi après confirmation.

##### 4.1.4.2 Description Textuelle – Cas d’Utilisation « Planifier un rendez-vous »

| Élément | Description |
| --- | --- |
| Nom | Planifier un rendez-vous |
| Acteur principal | Patient |
| Préconditions | Le patient est authentifié et a complété son profil. |
| Déclencheur | Sélection d’un médecin et d’un créneau libre depuis le tableau patient. |
| Scénario principal | 1. Le patient remplit le formulaire (motif, type, date, heure).<br>2. Le système valide la disponibilité et crée le rendez-vous à l’état `pending`.<br>3. Un événement `RendezVousCreate` est publié, et une notification est envoyée au médecin.<br>4. Le patient reçoit une confirmation d’enregistrement. |
| Postconditions | Le rendez-vous apparaît dans l’agenda médecin en attente de validation. |

##### 4.1.4.3 Description Textuelle – Cas d’Utilisation « Gérer l’agenda médecin »

| Élément | Description |
| --- | --- |
| Nom | Gérer l’agenda médecin |
| Acteur principal | Médecin |
| Préconditions | Le médecin dispose d’un compte validé et de rendez-vous planifiés. |
| Déclencheur | Consultation de la vue agenda (`/dashboard/agenda`). |
| Scénario principal | 1. Le médecin sélectionne la vue (jour/semaine/mois).<br>2. Le système calcule les intervalles temporels et affiche les créneaux avec codes couleur selon le type/statut.<br>3. Le médecin détaille un rendez-vous, modifie la durée ou assigne un patient existant.<br>4. Les changements déclenchent `RendezVousModifie` et mettent à jour FullCalendar en direct. |
| Postconditions | Les données persistées reflètent l’agenda révisé et les acteurs concernés reçoivent les notifications adaptées. |

##### 4.1.4.4 Description Textuelle – Cas d’Utilisation « Mettre à jour le statut »

| Élément | Description |
| --- | --- |
| Nom | Mettre à jour le statut |
| Acteur principal | Médecin / Patient |
| Préconditions | Le rendez-vous existe et est accessible à l’acteur. |
| Déclencheur | Action de confirmation, d’annulation ou de passage en « payé ». |
| Scénario principal | 1. L’acteur sélectionne l’action pertinente.<br>2. Le système vérifie les transitions autorisées via l’automate (`transitionTo`).<br>3. L’état est mis à jour (ex. `pending → confirmed`).<br>4. `RendezVousStatusChangedNotification` informe l’autre partie avec l’ancien/nouveau statut.<br>5. Les métriques sont recalculées. |
| Postconditions | Le rendez-vous reflète le nouvel état et les vues agendas/statistiques sont synchronisées. |

### 4.2 Conception

#### 4.2.1 Diagramme de Séquence – Sprint 2

La séquence « Planifier un rendez-vous » relie Patient UI → `Patient\RendezVousController` → `RendezVous` (Eloquent) → `ConsultationService` (génération lien Jitsi) → `NotificationController` / WebSockets. Elle décrit les validations successives (créneau libre, fuseau horaire, rôle) avant d’orchestrer les événements.

#### 4.2.2 Diagramme de Classe – Sprint 2

Les classes centrales sont `RendezVous`, `User`, `ConsultationService`, `Notification` et les événements. L’association `RendezVous` ↔ `User` (patient/medecin) est double, avec attributs `statut`, `type`, `date_debut`, `date_fin`. `ConsultationService` encapsule la logique de génération de salle Jitsi et offre une façade testable.

### 4.3 Réalisation

- **Interface agenda médecin** (`resources/views/dashMedecin/AgendaRendezvous/index.blade.php`) : FullCalendar enrichi, statistiques instantanées, mini calendrier, modales de création/édition.
- **Interface patient** (`resources/views/dashPatient/rendezvous/index.blade.php`) : formulaire dynamique, recherche de médecins, retour utilisateur clair sur les validations.
- **Intégrations techniques** : diffusion d’événements Laravel, notifications persistantes, service de visioconférence instancié automatiquement.

### Conclusion

Le Sprint 2 transforme MedicalPlatform en une solution opérationnelle de planification : la cohérence des agendas, la fiabilisation des statuts et l’anticipation de la téléconsultation y sont assurées. Cette base est indispensable aux sprints suivants (consultation, dossier, paiements).

---

# Chapitre 5 — Sprint 3 : Consultations synchrones

Plan  
— Introduction  
— 5.1 Analyse du Sprint 3  
— 5.1.1 Développement  
— 5.1.2 Backlog du Produit – Sprint 3  
— 5.1.3 Diagramme de Cas d’Utilisation Global – Sprint 3  
— 5.1.4 Gestion des Consultations  
— 5.1.4.1 Diagramme de Cas d’Utilisation – Consultation Médicale  
— 5.1.4.2 Description Textuelle « Conduire une consultation »  
— 5.1.4.3 Description Textuelle « Ajouter une ordonnance »  
— 5.1.4.4 Description Textuelle « Synchroniser rendez-vous / consultation »  
— 5.2 Conception  
— 5.2.1 Diagramme de Séquence – Sprint 3  
— 5.2.2 Diagramme de Classe – Sprint 3  
— 5.3 Réalisation  
— 5.3.1 Interface de suivi des consultations  
— 5.3.2 Intégration visioconférence  
— Conclusion

### Introduction

Le Sprint 3 capitalise sur l’agenda pour offrir un module de consultation complet : préparation des dossiers, enregistrement des constantes, ajout d’ordonnances numériques et démarrage des téléconsultations via les liens Jitsi générés en amont.

### 5.1 Analyse du Sprint 3

#### 5.1.1 Développement
- Contrôleur dédié `Medecin/ConsultationController` gérant listing, création, édition et affichage détaillé.
- Filtres avancés (patient, type, période, recherche texte) côté médecin avec pagination.
- Validation médicale étendue (constantes vitales, motifs, traitements) et calcul automatique de l’IMC.
- Liaison forte avec `RendezVous` (un rendez-vous payé devient la précondition d’une consultation).
- Gestion d’ordonnances via `Ordonnance` (upload PDF, stockage sécurisé, suppression).

#### 5.1.2 Backlog du Produit – Sprint 3

| Fonctionnalité | User Story | Priorité |
| --- | --- | --- |
| Création consultation | En tant que médecin, je veux documenter l’examen et les constantes pour chaque patient. | Haute |
| Association RDV | En tant que médecin, je veux lier la consultation au rendez-vous payé pour garder une traçabilité complète. | Haute |
| Ordonnances numériques | En tant que médecin, je veux générer et stocker une ordonnance numérique consultable plus tard. | Haute |
| Filtrage & recherche | En tant que médecin, je veux filtrer mes consultations par type, patient ou période. | Moyenne |
| Export / partage | En tant que médecin, je veux visualiser rapidement les recommandations pour préparer le compte rendu. | Moyenne |

#### 5.1.3 Diagramme de Cas d’Utilisation Global – Sprint 3

Patient et médecin interagissent autour de la session Jitsi, tandis que le dossier médical est mis à jour en arrière-plan. L’administrateur a un rôle d’audit (accès lecture seule). La figure 5.1 illustre la séquence « Démarrer consultation » suivie de « Compléter la fiche clinique ».

#### 5.1.4 Gestion des Consultations

##### 5.1.4.1 Diagramme de Cas d’Utilisation – Consultation médicale

Figure 5.2 décrit les cas « Conduire une consultation », « Ajouter une ordonnance », « Mettre à jour les constantes ». Les dépendances indiquent que l’ordonnance ne peut être créée qu’après avoir enregistré une consultation.

##### 5.1.4.2 Description Textuelle – Cas d’Utilisation « Conduire une consultation »

| Élément | Description |
| --- | --- |
| Nom | Conduire une consultation |
| Acteur principal | Médecin |
| Préconditions | Rendez-vous associé avec statut `payed`, patient authentifié. |
| Déclencheur | Ouverture du formulaire via `medecin.consultations.create`. |
| Scénario principal | 1. Le médecin sélectionne le patient ou le rendez-vous.<br>2. Il saisit les observations (type, motif, symptômes, constantes, diagnostic).<br>3. Le système crée la consultation, associe le dossier médical et calcule l’IMC.<br>4. Un récapitulatif est affiché, prêt pour la prescription. |
| Postconditions | La consultation est enregistrée, liée au dossier médical et accessible depuis l’espace patient. |

##### 5.1.4.3 Description Textuelle – Cas d’Utilisation « Ajouter une ordonnance »

| Élément | Description |
| --- | --- |
| Nom | Ajouter une ordonnance |
| Acteur principal | Médecin |
| Préconditions | Consultation existante. |
| Déclencheur | Clic « Ajouter/Mettre à jour l’ordonnance » sur la fiche consultation. |
| Scénario principal | 1. Le médecin encode les médicaments, posologies et notes.<br>2. Optionnellement, il téléverse un PDF signé.<br>3. Le système stocke le fichier dans `storage/app/public/ordonnances` et remplace l’ancienne version si nécessaire.<br>4. Un message de confirmation est affiché. |
| Postconditions | L’ordonnance est accessible aux patients via leur dossier médical. |

##### 5.1.4.4 Description Textuelle – Cas d’Utilisation « Synchroniser rendez-vous / consultation »

| Élément | Description |
| --- | --- |
| Nom | Synchroniser rendez-vous / consultation |
| Acteur principal | Médecin |
| Préconditions | Rendez-vous payé associé au même patient/médecin. |
| Déclencheur | Sélection d’un rendez-vous lors de la création d’une consultation. |
| Scénario principal | 1. Le médecin choisit un rendez-vous « Eligible ».<br>2. Le système vérifie la cohérence (même patient, même médecin, statut `payed`).<br>3. Les horodatages et le lien Jitsi sont réutilisés pour la consultation.<br>4. Le statut du rendez-vous peut évoluer vers « completed ». |
| Postconditions | Les vues agenda, consultation et dossier affichent des informations coordonnées. |

### 5.2 Conception

- **Diagramme de séquence** : enchaîne `Medecin/ConsultationController` → `Consultation` → `DossierMedical` + `Ordonnance`.
- **Diagramme de classe** : `Consultation` référence `RendezVous`, `DossierMedical`, `Ordonnance`. Le service `MedicalAIService` est préparé comme dépendance future.

### 5.3 Réalisation

- **Interface médecin** (`resources/views/dashMedecin/consultations`) : tableau paginé, filtres dynamiques, modales pour l’ordonnance.
- **Intégration Jitsi** : liens générés par `ConsultationService` sont affichés dans chaque carte rendez-vous / consultation.
- **Feedback utilisateur** : messages de succès/erreur, validations AJAX pour éviter les ressaisies.

### Conclusion

Le Sprint 3 formalise la consultation médicale numérique. L’équipe dispose désormais d’un socle clinique fiable, préalable aux dossiers médicaux, aux paiements et aux comptes-rendus IA.

---

# Chapitre 6 — Sprint 4 : Dossier médical électronique

Plan  
— Introduction  
— 6.1 Analyse du Sprint 4  
— 6.1.1 Développement  
— 6.1.2 Backlog du Produit – Sprint 4  
— 6.1.3 Diagramme de Cas d’Utilisation Global – Sprint 4  
— 6.1.4 Consultation et export du dossier  
— 6.1.4.1 Diagramme de Cas d’Utilisation – Accéder au dossier  
— 6.1.4.2 Description Textuelle « Consulter son dossier »  
— 6.1.4.3 Description Textuelle « Télécharger un dossier consolidé »  
— 6.2 Conception  
— 6.2.1 Diagramme de Séquence – Sprint 4  
— 6.2.2 Diagramme de Classe – Sprint 4  
— 6.3 Réalisation  
— Conclusion

### Introduction

Le Sprint 4 concentre les efforts sur la continuité des soins : centralisation des consultations, ordonnances et documents dans `DossierMedical`. Les patients disposent d’une vue chronologique et peuvent générer un PDF consolidé via DomPDF.

### 6.1 Analyse du Sprint 4

#### 6.1.1 Développement
- Contrôleur `Patient/DossierController` avec filtrage par médecin, rendu AJAX et export PDF.
- Sélection dynamique des médecins ayant réellement traité le patient (basée sur l’historique de rendez-vous et consultations).
- Génération de rapports PDF via `resources/views/pdf/dossier-medical.blade.php`, incluant métadonnées et ordonnances.
- Sécurisation des accès (vérification patient ↔ consultation ↔ rendez-vous avant de servir un fichier).
- Visualisation directe des ordonnances stockées (prévisualisation dans le navigateur).

#### 6.1.2 Backlog du Produit – Sprint 4

| Fonctionnalité | User Story | Priorité |
| --- | --- | --- |
| Vue dossier patient | En tant que patient, je veux consulter l’historique de mes consultations par médecin. | Haute |
| Export PDF | En tant que patient, je veux télécharger un dossier consolidé pour un médecin donné. | Haute |
| Respect confidentialité | En tant qu’administrateur, je veux garantir que seul le patient concerné voit ses données. | Haute |
| Prévisualisation ordonnances | En tant que patient, je veux ouvrir l’ordonnance sans la télécharger. | Moyenne |
| Statistiques dossier | En tant que patient, je veux voir le nombre de rendez-vous/consultations/ordonnances par médecin. | Moyenne |

#### 6.1.3 Diagramme de Cas d’Utilisation Global – Sprint 4

La figure 6.1 relie l’acteur Patient aux cas « Consulter dossier », « Télécharger PDF », « Afficher ordonnance ». L’administrateur n’observe que des métriques anonymisées, tandis que le médecin accède via son dashboard aux mêmes données côté professionnel.

#### 6.1.4 Consultation et export du dossier

##### 6.1.4.1 Diagramme de Cas d’Utilisation – Accéder au dossier

Figure 6.2 présente l’alternative « Vue interactive » vs « Export PDF ».

##### 6.1.4.2 Description Textuelle – Cas d’Utilisation « Consulter son dossier »

| Élément | Description |
| --- | --- |
| Nom | Consulter son dossier |
| Acteur principal | Patient |
| Préconditions | Connexion + au moins une consultation enregistrée. |
| Déclencheur | Accès à `/patient/dossier`. |
| Scénario principal | 1. Le patient choisit un médecin dans la liste générée dynamiquement.<br>2. Le système charge (en AJAX) les rendez-vous/consultations associés.<br>3. Chaque carte affiche la date, le motif, l’ordonnance et une action « voir ».<br>4. Le patient peut dérouler les détails sans recharger la page. |
| Postconditions | Le dossier reste consultable et les préférences de filtre sont conservées. |

##### 6.1.4.3 Description Textuelle – Cas d’Utilisation « Télécharger un dossier consolidé »

| Élément | Description |
| --- | --- |
| Nom | Télécharger un dossier consolidé |
| Acteur principal | Patient |
| Préconditions | Sélection d’un médecin et présence de données. |
| Déclencheur | Bouton « Télécharger le dossier PDF ». |
| Scénario principal | 1. Le patient déclenche le téléchargement.<br>2. `DossierController::generateDossierPDF` agrège rendez-vous, consultations, ordonnances.<br>3. DomPDF génère un document structuré avec couverture, statistiques et annexes.<br>4. Le fichier est proposé en téléchargement sous forme `dossier_medical_<medecin>.pdf`. |
| Postconditions | Un PDF signé électroniquement est conservé côté patient si souhaité. |

### 6.2 Conception

- **Séquence** : Patient → `DossierController` → `DossierMedical` / `Consultation` / `RendezVous` → DomPDF.
- **Classe** : `DossierMedical` relie `Consultation`, `RendezVous`, `Ordonnance`, `User`. Les relations garantissent la cohérence des exports.

### 6.3 Réalisation

- Vue `resources/views/dashPatient/dossier/index.blade.php` avec composants modulaires (liste médecins, consultations accordéon, bouton PDF).
- Scripts `resources/js/dashboard/dossier-manager.js` pour appels AJAX et rendu progressif.
- Vérifications d’accès systématiques avant la délivrance des PDF/ordonnances.

### Conclusion

Le Sprint 4 garantit la continuité clinique, prépare la génération automatique de rapports et sécurise la conformité réglementaire.

---

# Chapitre 7 — Sprint 5 : Notifications et communication temps réel

Plan  
— Introduction  
— 7.1 Analyse du Sprint 5  
— 7.1.1 Développement  
— 7.1.2 Backlog du Produit – Sprint 5  
— 7.1.3 Diagramme de Cas d’Utilisation Global – Sprint 5  
— 7.1.4 Gestion des notifications persistantes  
— 7.1.4.1 Diagramme de Cas d’Utilisation – Notifier les acteurs  
— 7.1.4.2 Description Textuelle « Recevoir une notification rendez-vous »  
— 7.1.4.3 Description Textuelle « Marquer comme lue »  
— 7.2 Conception  
— 7.2.1 Diagramme de Séquence – Sprint 5  
— 7.2.2 Diagramme de Classe – Sprint 5  
— 7.3 Réalisation  
— Conclusion

### Introduction

Ce sprint introduit une communication fiable grâce à Laravel Notifications + Reverb (WebSockets). Les notifications deviennent persistantes et consultables via API/Dropdown.

### 7.1 Analyse du Sprint 5

#### 7.1.1 Développement
- Notifications `RendezVousCreatedNotification`, `RendezVousModifiedNotification`, `RendezVousStatusChangedNotification`.
- `NotificationController` fournissant une API REST (liste, non lues, marquer comme lues, suppression).
- Intégration front (`resources/js/dashboard/notifications.js`, navbars patient/médecin).
- Couplage avec les événements rendez-vous pour garantir le temps réel + la persistance.

#### 7.1.2 Backlog du Produit – Sprint 5

| Fonctionnalité | User Story | Priorité |
| --- | --- | --- |
| Notification création | En tant que médecin, je veux être alerté lorsqu’un patient réserve un créneau. | Haute |
| Notification modification | En tant que patient, je veux être averti si le médecin change la date ou l’horaire. | Haute |
| Compteur non lus | En tant qu’utilisateur, je veux visualiser le nombre de notifications non lues. | Haute |
| Marquer comme lu | En tant qu’utilisateur, je veux archiver les notifications après lecture. | Moyenne |
| API notifications | En tant que client mobile, je veux accéder aux notifications via une API dédiée. | Moyenne |

#### 7.1.3 Diagramme de Cas d’Utilisation Global – Sprint 5

Figure 7.1 montre les flux Patient ↔ Médecin ↔ Admin, chacun recevant uniquement les événements pertinents (création, modification, changement de statut).

#### 7.1.4 Gestion des notifications persistantes

##### 7.1.4.1 Diagramme de Cas d’Utilisation – Notifier les acteurs

Le diagramme 7.2 détaille l’inclusion « Enregistrer notification » suivie de « Diffuser en temps réel ».

##### 7.1.4.2 Description Textuelle – Cas d’Utilisation « Recevoir une notification rendez-vous »

| Élément | Description |
| --- | --- |
| Nom | Recevoir une notification rendez-vous |
| Acteur principal | Patient / Médecin |
| Préconditions | L’acteur est abonné à son canal privé Reverb. |
| Déclencheur | Création / modification / changement de statut d’un rendez-vous. |
| Scénario principal | 1. L’événement côté serveur déclenche la notification Laravel.<br>2. La notification est stockée dans la table `notifications`.<br>3. Reverb diffuse le message temps réel vers le front.<br>4. Le front met à jour le badge et la liste. |
| Postconditions | L’utilisateur visualise la notification dans son dropdown et peut agir immédiatement. |

##### 7.1.4.3 Description Textuelle – Cas d’Utilisation « Marquer comme lue »

| Élément | Description |
| --- | --- |
| Nom | Marquer comme lue |
| Acteur principal | Utilisateur |
| Préconditions | Une notification non lue existe. |
| Déclencheur | Clic « Marquer comme lu » ou action groupée « Tout marquer ». |
| Scénario principal | 1. Le front appelle `PATCH /notifications/{id}/mark-as-read` ou `mark-all-as-read`.<br>2. L’API met à jour `read_at`.<br>3. Le badge est décrémenté en direct.<br>4. La notification passe en état lu dans l’UI. |
| Postconditions | Historique conservé, mais non remontré comme non lu. |

### 7.2 Conception

- Séquence : `RendezVousController` → Notification (base de données) → Broadcast via Reverb → Front-end.
- Classe : héritage de `Illuminate\Notifications\Notification`, canaux Database + Broadcast.

### 7.3 Réalisation

- Dropdown unifié dans les navbars, animations, badge dynamique.
- Scripts JS gérant WebSockets, fallback AJAX, préférences utilisateur.
- API documentée dans `NOTIFICATION_SYSTEM_DOCUMENTATION.md`.

### Conclusion

Le Sprint 5 garantit une communication fiable entre les acteurs. La plateforme devient réactive, un prérequis pour les paiements, les consultations en direct et l’IA conversationnelle.

---

# Chapitre 8 — Sprint 6 : Paiements sécurisés

Plan  
— Introduction  
— 8.1 Analyse du Sprint 6  
— 8.1.1 Développement  
— 8.1.2 Backlog du Produit – Sprint 6  
— 8.1.3 Diagramme de Cas d’Utilisation Global – Sprint 6  
— 8.1.4 Gestion des paiements Paymee  
— 8.1.4.1 Diagramme de Cas d’Utilisation – Régler un rendez-vous  
— 8.1.4.2 Description Textuelle « Initier un paiement »  
— 8.1.4.3 Description Textuelle « Vérifier un paiement »  
— 8.1.4.4 Description Textuelle « Annuler / rembourser »  
— 8.2 Conception  
— 8.3 Réalisation  
— Conclusion

### Introduction

Ce sprint introduit la monétisation avec l’intégration Paymee (sandbox), la gestion des états financiers et l’automatisation des transitions de rendez-vous vers `payed`.

### 8.1 Analyse du Sprint 6

#### 8.1.1 Développement
- `Patient/PaiementController` gère la création Paymee, les retours success/cancel et le webhook.
- Stockage des tokens de paiement sur `RendezVous` pour relier transaction et acte médical.
- Vérification post-paiement (`/payments/{token}/check`) avant d’émettre un changement d’état.
- Notifications automatiques envoyées au médecin lors d’un paiement réussi/annulé.
- Vues `payment.redirect`, `payment.success`, `payment.cancel` avec instructions utilisateur.

#### 8.1.2 Backlog du Produit – Sprint 6

| Fonctionnalité | User Story | Priorité |
| --- | --- | --- |
| Paiement en ligne | En tant que patient, je veux payer ma consultation en ligne de façon sécurisée. | Haute |
| Confirmation automatique | En tant que médecin, je veux être informé dès qu’un paiement est validé. | Haute |
| Gestion annulation | En tant que patient, je veux pouvoir annuler un paiement avant sa validation. | Moyenne |
| Webhook | En tant qu’administrateur, je veux loguer chaque webhook Paymee pour audit. | Moyenne |
| Rapprochement | En tant que responsable, je veux retrouver les transactions associées aux rendez-vous. | Moyenne |

#### 8.1.3 Diagramme de Cas d’Utilisation Global – Sprint 6

Figure 8.1 relie le patient (initie) au prestataire Paymee et au médecin (informé). L’administrateur audite via les logs.

#### 8.1.4 Gestion des paiements Paymee

##### 8.1.4.1 Diagramme de Cas d’Utilisation – Régler un rendez-vous

Le diagramme 8.2 met en évidence les interactions Patient ↔ Paymee ↔ MedicalPlatform.

##### 8.1.4.2 Description Textuelle – Cas d’Utilisation « Initier un paiement »

| Élément | Description |
| --- | --- |
| Nom | Initier un paiement |
| Acteur principal | Patient |
| Préconditions | Rendez-vous confirmé, prix défini pour le médecin. |
| Déclencheur | Clic sur « Payer en ligne ». |
| Scénario principal | 1. `PaiementController@create` prépare le payload Paymee (montant, note, URLs).<br>2. L’API Paymee renvoie un token.<br>3. Le système enregistre le token sur le rendez-vous et redirige l’utilisateur vers la passerelle.<br>4. Le patient saisit ses informations bancaires côté Paymee. |
| Postconditions | La transaction est en attente de confirmation Paymee. |

##### 8.1.4.3 Description Textuelle – Cas d’Utilisation « Vérifier un paiement »

| Élément | Description |
| --- | --- |
| Nom | Vérifier un paiement |
| Acteur principal | Système |
| Préconditions | `payment_token` et `payment_id` reçus dans le callback. |
| Déclencheur | Retour `success` de Paymee. |
| Scénario principal | 1. Le système vérifie les paramètres reçus.<br>2. Il interroge `/payments/{token}/check`.<br>3. Si `payment_status` est vrai, un enregistrement `Paiement` est créé et le rendez-vous passe à `payed`.<br>4. Notifications envoyées au médecin. |
| Postconditions | Rendez-vous prêt pour la consultation, facture horodatée. |

##### 8.1.4.4 Description Textuelle – Cas d’Utilisation « Annuler / rembourser »

| Élément | Description |
| --- | --- |
| Nom | Annuler / rembourser |
| Acteur principal | Patient |
| Préconditions | Rendez-vous en `pending` ou `confirmed`. |
| Déclencheur | Clic « Annuler le paiement » ou retour `cancel`. |
| Scénario principal | 1. Le système marque la transaction comme annulée, enregistre un `Paiement` avec statut `cancel`.<br>2. Le rendez-vous transite vers `cancelled`.<br>3. Le médecin est notifié de l’annulation. |
| Postconditions | Le créneau redevient disponible, aucune consultation ne peut être créée. |

### 8.2 Conception

- Diagramme de séquence reliant Patient UI → Paymee API → `PaiementController` → `RendezVous` + Notifications.
- Diagramme de classe reliant `RendezVous`, `Paiement`, `User`, `Notification`.

### 8.3 Réalisation

- Vues de statut explicites (succès/échec) avec instructions de suivi.
- Logs détaillés (`Log::info` / `Log::warning`) pour chaque étape et webhook.
- Transition d’état centralisée via `transitionTo` pour garder la cohérence métier.

### Conclusion

Le Sprint 6 sécurise la dimension financière et conditionne l’accès aux consultations aux paiements validés.

---

# Chapitre 9 — Sprint 7 : Chatbot médical multilingue

Plan  
— Introduction  
— 9.1 Analyse du Sprint 7  
— 9.1.1 Développement  
— 9.1.2 Backlog du Produit – Sprint 7  
— 9.1.3 Diagramme de Cas d’Utilisation Global – Sprint 7  
— 9.1.4 Conversation médicale guidée  
— 9.1.4.1 Diagramme de Cas d’Utilisation – Discuter avec le chatbot  
— 9.1.4.2 Description Textuelle « Poser une question santé »  
— 9.1.4.3 Description Textuelle « Historiser la conversation »  
— 9.2 Conception  
— 9.3 Réalisation  
— Conclusion

### Introduction

Sprint 7 apporte un chatbot médical qui fournit des conseils pré-consultation et oriente les patients. Il exploite `ChatBotService`, la détection automatique de langue et les modèles Hugging Face (Mistral-7B-Instruct via Router API).

### 9.1 Analyse du Sprint 7

#### 9.1.1 Développement
- Service `ChatBotService` centralisant l’orchestration (détection de langue, prompts, appels API).
- `LanguageDetectionService` pour répondre dans la langue du patient (FR/EN/AR).
- `Patient/ChatBotController` exposant une API JSON + interface conversationnelle.
- Scripts front `resources/js/dashboard/chatbot.js` gérant l’historique et les états de chargement.
- Gestion des erreurs explicite (token Hugging Face invalide, quotas, temps de réponse).

#### 9.1.2 Backlog du Produit – Sprint 7

| Fonctionnalité | User Story | Priorité |
| --- | --- | --- |
| Détection langue | En tant que patient, je veux que le chatbot réponde dans ma langue. | Haute |
| Conseils pré-consultation | En tant que patient, je veux obtenir des conseils généraux avant de réserver. | Haute |
| Historique conversation | En tant que patient, je veux revoir mes échanges pour préparer la consultation. | Moyenne |
| Sécurité médicale | En tant qu’administrateur, je veux empêcher le chatbot de diagnostiquer ou prescrire. | Haute |
| Résilience API | En tant que développeur, je veux des logs et messages d’erreur clairs. | Moyenne |

#### 9.1.3 Diagramme de Cas d’Utilisation Global – Sprint 7

Figure 9.1 relie l’acteur Patient au chatbot et aux services IA (Hugging Face, OpenAI, Claude) en fallback éventuel.

#### 9.1.4 Conversation médicale guidée

##### 9.1.4.1 Diagramme de Cas d’Utilisation – Discuter avec le chatbot

Le diagramme 9.2 illustre la boucle « Poser question » → « Analyser langue » → « Générer réponse » → « Afficher ».

##### 9.1.4.2 Description Textuelle – Cas d’Utilisation « Poser une question santé »

| Élément | Description |
| --- | --- |
| Nom | Poser une question santé |
| Acteur principal | Patient |
| Préconditions | Authentification, clé Hugging Face valide. |
| Déclencheur | Saisie d’une question dans l’interface chatbot. |
| Scénario principal | 1. L’utilisateur saisit sa question.<br>2. `ChatBotService` détecte la langue et construit le prompt système.<br>3. Le service appelle l’API Router Hugging Face (Mistral-7B).<br>4. La réponse nettoyée est renvoyée au front et stockée dans l’historique temporaire.<br>5. Le chatbot rappelle que les conseils ne remplacent pas une consultation. |
| Postconditions | La conversation est mise à jour côté client, prête à être utilisée lors de la prise de rendez-vous. |

##### 9.1.4.3 Description Textuelle – Cas d’Utilisation « Historiser la conversation »

| Élément | Description |
| --- | --- |
| Nom | Historiser la conversation |
| Acteur principal | Patient |
| Préconditions | Session chatbot active. |
| Déclencheur | Nouvelle question/réponse. |
| Scénario principal | 1. Chaque échange est conservé en mémoire côté front (et peut être persisté côté serveur selon configuration future).<br>2. L’historique est renvoyé à `ChatBotService` pour fournir du contexte.<br>3. Seuls les trois derniers échanges sont envoyés pour maîtriser les coûts.<br>4. L’utilisateur peut effacer la conversation. |
| Postconditions | Contexte prêt pour un futur compte-rendu IA ou pour enrichir la fiche pré-consultation. |

### 9.2 Conception

- Séquence : Patient → `ChatBotController` → `ChatBotService` → API Hugging Face → Patient.
- Classe : `ChatBotService` dépend de `LanguageDetectionService`; possibilité de fallback vers OpenAI/Anthropic/Gemini selon la configuration.

### 9.3 Réalisation

- Vue `resources/views/dashPatient/chatbot/index.blade.php` avec composants Tailwind.
- JS gérant le streaming visuel (spinner, bulles), la relance en cas d’erreur et la conservation locale.
- Logs applicatifs détaillant la langue détectée et les exceptions.

### Conclusion

Le Sprint 7 ajoute une couche conversationnelle proactive, améliore l’expérience patient et prépare la génération automatique de comptes-rendus.

---

# Chapitre 10 — Sprint 8 : Compte-rendu et assistance IA

Plan  
— Introduction  
— 10.1 Analyse du Sprint 8  
— 10.1.1 Développement  
— 10.1.2 Backlog du Produit – Sprint 8  
— 10.1.3 Diagramme de Cas d’Utilisation Global – Sprint 8  
— 10.1.4 Génération de compte-rendu IA  
— 10.1.4.1 Diagramme de Cas d’Utilisation – Générer un compte-rendu IA  
— 10.1.4.2 Description Textuelle « Produire un compte-rendu complet »  
— 10.1.4.3 Description Textuelle « Générer un résumé / lettre de sortie »  
— 10.2 Conception  
— 10.3 Réalisation  
— Conclusion

### Introduction

Dernier sprint de ce lot, il industrialise l’assistance IA pour les médecins : génération automatique de comptes-rendus, résumés et lettres de sortie en s’appuyant sur `MedicalAIService`, `AmbulatoryMedicalAIService` et `FreeMedicalAIService`.

### 10.1 Analyse du Sprint 8

#### 10.1.1 Développement
- Service `MedicalAIService` orchestrant OpenAI (GPT‑4) pour les comptes-rendus, Claude pour les résumés, Gemini comme fallback.
- Prompts structurés injectant les données de consultation (motif, constantes, diagnostic, traitement).
- Contrôleurs `Medecin/AmbulatoryAIController` et `Medecin/FreeAIConsultationController` pour piloter les appels IA depuis l’interface.
- Gestion d’erreurs/resilience (logs, messages UX en cas d’indisponibilité).
- Préparation à l’export PDF des comptes-rendus générés et archivage dans le dossier médical.

#### 10.1.2 Backlog du Produit – Sprint 8

| Fonctionnalité | User Story | Priorité |
| --- | --- | --- |
| Compte-rendu IA | En tant que médecin, je veux obtenir un compte-rendu structuré à partir des données saisies. | Haute |
| Résumé rapide | En tant que médecin, je veux un résumé synthétique à partager avec le patient. | Haute |
| Lettre de sortie | En tant que médecin, je veux générer automatiquement une lettre de sortie. | Moyenne |
| Multi modèles | En tant que tech lead, je veux basculer entre modèles (OpenAI, Claude, Gemini) selon les coûts. | Moyenne |
| Traçabilité | En tant qu’administrateur, je veux loguer chaque génération IA pour audit. | Moyenne |

#### 10.1.3 Diagramme de Cas d’Utilisation Global – Sprint 8

Figure 10.1 relie le médecin aux services IA. Les cas « Générer compte-rendu », « Générer résumé », « Générer lettre de sortie » partagent les mêmes données d’entrée.

#### 10.1.4 Génération de compte-rendu IA

##### 10.1.4.1 Diagramme de Cas d’Utilisation – Générer un compte-rendu IA

Le diagramme 10.2 détaille la préparation des données, l’appel API, la restitution structurée.

##### 10.1.4.2 Description Textuelle – Cas d’Utilisation « Produire un compte-rendu complet »

| Élément | Description |
| --- | --- |
| Nom | Produire un compte-rendu complet |
| Acteur principal | Médecin |
| Préconditions | Consultation enregistrée avec données cliniques complètes; API keys valides. |
| Déclencheur | Clic « Générer le compte-rendu IA » depuis la fiche consultation. |
| Scénario principal | 1. Le système collecte les champs pertinents (motif, symptômes, constantes, diagnostic).<br>2. `MedicalAIService` construit un prompt détaillé et appelle OpenAI GPT‑4.<br>3. La réponse JSON (compte-rendu, résumé, recommandations) est parsée.<br>4. Les données sont affichées au médecin, qui peut les éditer ou enregistrer dans le dossier médical.<br>5. En cas d’échec, un fallback (Claude/Gemini) peut être proposé. |
| Postconditions | Un compte-rendu est associé à la consultation et prêt pour l’export. |

##### 10.1.4.3 Description Textuelle – Cas d’Utilisation « Générer un résumé / lettre de sortie »

| Élément | Description |
| --- | --- |
| Nom | Générer un résumé / lettre de sortie |
| Acteur principal | Médecin |
| Préconditions | Consultation existante, champs critiques saisis. |
| Déclencheur | Action « Résumé IA » ou « Lettre de sortie ». |
| Scénario principal | 1. `MedicalAIService::generateResume` ou `generateLettreSortie` construit un prompt ciblé.<br>2. L’IA (Claude ou GPT‑4) renvoie un texte concis et compréhensible par le patient.<br>3. Le médecin peut copier/coller ou sauvegarder le document en PDF.<br>4. Un historique de génération est conservé dans les logs pour audit. |
| Postconditions | Document prêt à être communiqué au patient ou ajouté au dossier. |

### 10.2 Conception

- Séquence : Médecin → `MedicalAIService` → API IA → Médecin → Dossier Medical.
- Classe : `MedicalAIService` encapsule les dépendances API, propose plusieurs méthodes (compte-rendu, résumé, lettre), et reste remplaçable par `AmbulatoryMedicalAIService` pour d’autres contextes.

### 10.3 Réalisation

- Interfaces dans `resources/views/dashMedecin/ai` (boutons, modales de restitution).
- Gestion d’états de chargement et de messages d’erreur (ex : clé manquante, coût dépassé).
- Journalisation centralisée (`Log::error`, `Log::info`) pour chaque appel IA.

### Conclusion

Le Sprint 8 parachève la valeur ajoutée de MedicalPlatform : les médecins gagnent un temps considérable grâce aux comptes-rendus générés automatiquement, tandis que les patients bénéficient de documents normalisés et compréhensibles.

---

Ces chapitres complètent la documentation du Sprint 1 et couvrent désormais l’ensemble des fonctionnalités demandées : gestion des rendez-vous, consultations, dossiers médicaux, notifications, paiements, chatbot et comptes-rendus IA.


