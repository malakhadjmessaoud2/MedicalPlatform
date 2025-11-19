# Identification des Acteurs et Fonctionnalités - MedicalPlatform

## Vue d'ensemble

La plateforme **MedicalPlatform** prend en charge trois types d'utilisateurs principaux (Admin, Médecin, Patient), chacun avec des droits adaptés aux fonctionnalités proposées. Chaque acteur dispose d'un tableau de bord personnalisé et d'interfaces spécifiques à ses besoins.

---

## 1. ACTEUR : ADMINISTRATEUR

### 1.1 Dashboard Administrateur
**Description** : Vue d'ensemble complète de la plateforme avec statistiques en temps réel.

**Fonctionnalités** :
- Affichage des statistiques globales :
  - Nombre total de médecins
  - Nombre total de patients
  - Nombre total de pharmacies
  - Nombre total de donateurs
  - Nombre total de rendez-vous
  - Rendez-vous du jour
  - Consultations du jour
  - Revenus du jour et du mois
- Répartition des rendez-vous du jour par statut (Confirmés, En attente, Annulés, Terminés)
- Liste des rendez-vous récents avec détails (patient, médecin, date, statut)
- Liste des médecins les plus actifs (classement par nombre de rendez-vous)
- Actions rapides : Ajouter un médecin, Gérer les utilisateurs, Voir les statistiques

### 1.2 Gestion des Médecins
**Description** : Administration complète des comptes médecins.

**Fonctionnalités** :
- Liste de tous les médecins avec leurs informations
- Création de nouveaux comptes médecins
- Modification des informations des médecins
- Activation/Désactivation des comptes médecins (toggle status)
- Suppression de comptes médecins
- Consultation du profil détaillé d'un médecin
- Filtrage et recherche de médecins

### 1.3 Gestion des Utilisateurs
**Description** : Administration de tous les utilisateurs de la plateforme.

**Fonctionnalités** :
- Liste paginée de tous les utilisateurs (médecins, patients, pharmacies, donateurs)
- Affichage du nombre de rendez-vous par utilisateur
- Affichage du nombre de consultations par utilisateur
- Tri par date de création
- Consultation des profils utilisateurs

### 1.4 Statistiques Détaillées
**Description** : Analyses approfondies de l'activité de la plateforme.

**Fonctionnalités** :
- Répartition des médecins par spécialité
- Répartition des rendez-vous par statut
- Évolution des revenus par mois (graphiques)
- Statistiques d'utilisation par période
- Export de données statistiques

### 1.5 Rapports
**Description** : Génération et consultation de rapports administratifs.

**Fonctionnalités** :
- Génération de rapports personnalisés
- Rapports d'activité mensuels/annuels
- Rapports financiers
- Rapports d'utilisation de la plateforme

---

## 2. ACTEUR : MÉDECIN

### 2.1 Dashboard Médecin
**Description** : Tableau de bord personnalisé avec vue d'ensemble de l'activité médicale.

**Fonctionnalités** :
- **Statistiques principales** :
  - Consultations (aujourd'hui, cette semaine, ce mois)
  - Patients suivis (total, nouveaux du mois)
  - Rendez-vous en attente de confirmation
- **Consultations à venir** avec filtres avancés :
  - Filtre par période (Aujourd'hui, Demain, Cette semaine, Semaine prochaine, Ce mois)
  - Filtre par date spécifique
  - Filtre par période personnalisée
  - Affichage des cartes patients avec informations complètes
- **Dossiers médicaux** : Liste des dossiers médicaux des patients suivis
- **Actions rapides** : Accès direct aux principales fonctionnalités

### 2.2 Gestion des Rendez-vous
**Description** : Gestion complète de l'agenda et des rendez-vous.

**Fonctionnalités** :
- **Agenda interactif** (FullCalendar) :
  - Vue jour, semaine, mois
  - Vue liste des événements
  - Glisser-déposer pour modifier les rendez-vous
  - Affichage des créneaux disponibles/occupés
- **Gestion des rendez-vous** :
  - Consultation de la liste des rendez-vous
  - Détails d'un rendez-vous (patient, date, heure, type, statut)
  - Modification d'un rendez-vous (date, heure, statut)
  - Annulation d'un rendez-vous
  - Confirmation/Rejet des demandes de rendez-vous
  - Création de liens de téléconsultation sécurisés
  - Mise à jour du statut des rendez-vous
- **Filtres et recherche** :
  - Filtrage par statut (pending, confirmed, cancelled, completed)
  - Filtrage par date
  - Recherche par patient

### 2.3 Gestion des Patients
**Description** : Consultation et gestion des dossiers des patients.

**Fonctionnalités** :
- Liste de tous les patients suivis
- Consultation du profil patient
- Accès aux dossiers médicaux complets
- Historique des consultations par patient
- Informations de contact des patients

### 2.4 Dossiers Médicaux
**Description** : Gestion complète des dossiers médicaux électroniques.

**Fonctionnalités** :
- Consultation des dossiers médicaux des patients
- Mise à jour des informations du dossier :
  - Groupe sanguin
  - Allergies
  - Antécédents médicaux
  - Antécédents familiaux
  - Informations de contact
- Consultation de l'historique médical complet
- Affichage des documents médicaux associés
- Export des dossiers médicaux

### 2.5 Consultations
**Description** : Création et gestion des consultations médicales.

**Fonctionnalités** :
- **Création de consultation** :
  - Saisie des informations de consultation (motif, symptômes, examen physique)
  - Enregistrement des signes vitaux (tension, température, fréquence cardiaque, saturation O2)
  - Diagnostic présumé
  - Traitement prescrit
  - Médicaments prescrits
  - Propositions de suivi
  - Niveau de gravité
- **Modification de consultation** :
  - Édition des informations saisies
  - Mise à jour du diagnostic
  - Modification du traitement
- **Consultation des consultations** :
  - Liste de toutes les consultations
  - Détails complets d'une consultation
  - Historique des consultations par patient
- **Génération de documents** :
  - Comptes-rendus de consultation
  - Ordonnances médicales
  - Lettres de sortie

### 2.6 Génération IA - Comptes-rendus
**Description** : Assistance à la rédaction de comptes-rendus médicaux par intelligence artificielle.

**Fonctionnalités** :
- **Génération gratuite de comptes-rendus** :
  - Interface de génération IA
  - Génération automatique de compte-rendu structuré
  - Génération de résumé de consultation
  - Génération de lettre de sortie
  - Visualisation du compte-rendu généré
  - Export en PDF
- **Génération IA médecine ambulatoire** :
  - Interface de génération téléconsultation
  - Génération de rapport de téléconsultation complet
  - Génération de recommandations écrites
  - Génération de résumé en ligne pour le patient
  - Génération de plan de suivi
  - Génération de document complet
  - Export PDF des documents générés
- **Modèles IA supportés** :
  - OpenAI GPT-4
  - Claude (Anthropic)
  - Google Gemini
  - Mistral (via Hugging Face)
- **Fonctionnalités avancées** :
  - Analyse intelligente des données de consultation
  - Filtrage automatique des champs remplis
  - Normalisation JSON des réponses IA
  - Traçabilité (modèle utilisé, date de génération)

### 2.7 Ordonnances
**Description** : Création et gestion des ordonnances médicales.

**Fonctionnalités** :
- Création d'ordonnances liées aux consultations
- Ajout/Modification/Suppression d'ordonnances
- Visualisation des ordonnances
- Téléchargement des ordonnances en PDF
- Gestion des médicaments prescrits

### 2.8 Traitements & Suivis
**Description** : Suivi des traitements prescrits aux patients.

**Fonctionnalités** :
- Liste des traitements en cours
- Suivi de l'évolution des traitements
- Planification des suivis
- Rappels de suivi

### 2.9 Gestion de Prestations
**Description** : Gestion des prestations médicales proposées.

**Fonctionnalités** :
- Définition des prestations
- Tarification des prestations
- Gestion des types de consultations

### 2.10 Communication
**Description** : Outils de communication avec les patients.

**Fonctionnalités** :
- Messagerie avec les patients
- Notifications aux patients
- Envoi de rappels

### 2.11 Timeline
**Description** : Vue chronologique des événements médicaux.

**Fonctionnalités** :
- Affichage de la timeline des consultations
- Chronologie des rendez-vous
- Historique des interactions avec les patients

---

## 3. ACTEUR : PATIENT

### 3.1 Dashboard Patient
**Description** : Tableau de bord personnel avec vue d'ensemble de la santé du patient.

**Fonctionnalités** :
- **Statistiques personnelles** :
  - Nombre de rendez-vous confirmés
  - Nombre de rendez-vous payés
  - Nombre de consultations terminées
- **Prochains rendez-vous** :
  - Affichage des 3 prochains rendez-vous
  - Informations du médecin (photo, nom, spécialité)
  - Date et heure du rendez-vous
  - Statut du rendez-vous
  - Adresse du cabinet
- **Médecins disponibles** :
  - Liste des médecins avec filtres avancés :
    - Filtre par spécialité
    - Filtre par prix (min/max)
    - Filtre par score minimum
    - Recherche par nom
  - Affichage des profils médecins :
    - Photo de profil
    - Nom et spécialité
    - Score et nombre d'avis
    - Prix de consultation
    - Disponibilité
    - Bouton "Prendre RDV"

### 3.2 Gestion des Rendez-vous
**Description** : Prise et gestion des rendez-vous médicaux.

**Fonctionnalités** :
- **Recherche de médecin** :
  - Recherche par spécialité
  - Recherche par localisation
  - Filtrage par disponibilité
  - Affichage des médecins avec leurs disponibilités
- **Prise de rendez-vous** :
  - Sélection d'un médecin
  - Consultation des créneaux disponibles
  - Sélection de la date et de l'heure
  - Choix du type de consultation (présentiel, téléconsultation)
  - Confirmation du rendez-vous
- **Gestion des rendez-vous** :
  - Liste de tous les rendez-vous (passés et à venir)
  - Détails d'un rendez-vous
  - Annulation d'un rendez-vous
  - Modification d'un rendez-vous (si autorisé)
- **Paiement en ligne** :
  - Confirmation de paiement
  - Redirection vers la plateforme Paymee
  - Suivi du statut de paiement
  - Confirmation de paiement réussi
  - Gestion des annulations de paiement

### 3.3 Dossier Médical
**Description** : Consultation du dossier médical personnel.

**Fonctionnalités** :
- Consultation du dossier médical complet
- Historique des consultations
- Consultation des ordonnances :
  - Visualisation des ordonnances
  - Téléchargement des ordonnances en PDF
- Consultation des documents médicaux
- Informations personnelles de santé :
  - Groupe sanguin
  - Allergies
  - Antécédents médicaux

### 3.4 Notation des Médecins
**Description** : Évaluation et notation des médecins après consultation.

**Fonctionnalités** :
- Notation d'un médecin après consultation
- Publication d'un avis écrit
- Consultation des notes et avis des autres patients
- Affichage du score moyen des médecins

### 3.5 Chatbot Médical
**Description** : Assistant virtuel pour l'orientation et les questions médicales.

**Fonctionnalités** :
- **Interface de chat** :
  - Conversation en temps réel avec le chatbot
  - Historique des conversations
  - Réinitialisation de la conversation
- **Fonctionnalités du chatbot** :
  - Réponses aux questions médicales générales
  - Orientation vers les spécialités appropriées
  - Conseils de santé préventifs
  - Détection automatique de la langue
  - Suggestions de prise de rendez-vous
- **Modèles IA utilisés** :
  - Mistral-7B-Instruct (via Hugging Face Router API)
  - Format OpenAI-compatible
  - Support multilingue

### 3.6 Messages
**Description** : Communication avec les médecins.

**Fonctionnalités** :
- Messagerie avec les médecins
- Historique des conversations
- Notifications de nouveaux messages
- Envoi de messages liés aux rendez-vous

### 3.7 Médicaments
**Description** : Gestion des médicaments et ordonnances.

**Fonctionnalités** :
- Consultation des médicaments prescrits
- Liste des ordonnances actives
- Rappels de prise de médicaments
- Informations sur les médicaments

### 3.8 Commandes
**Description** : Gestion des commandes de médicaments.

**Fonctionnalités** :
- Consultation des commandes passées
- Suivi des commandes
  - Statut de la commande (en attente, validée, expédiée, livrée)
  - Détails des produits commandés
- Historique des commandes

### 3.9 Achat de Médicaments
**Description** : Achat de médicaments en ligne.

**Fonctionnalités** :
- Catalogue de médicaments disponibles
- Ajout au panier
- Passer commande
- Paiement en ligne

### 3.10 Dons Médicaux
**Description** : Participation aux dons médicaux.

**Fonctionnalités** :
- Consultation des demandes de dons
- Faire un don médical
- Suivi des dons effectués
- Historique des contributions

### 3.11 Notifications
**Description** : Réception de notifications en temps réel.

**Fonctionnalités** :
- Notifications de création de rendez-vous
- Notifications de modification de rendez-vous
- Notifications de changement de statut
- Notifications de nouveaux messages
- Badge avec compteur de notifications non lues
- Marquer les notifications comme lues
- Suppression de notifications

---

## 4. FONCTIONNALITÉS PARTAGÉES

### 4.1 Authentification et Profil
**Description** : Gestion de l'authentification et du profil utilisateur.

**Fonctionnalités communes** :
- Inscription (selon le type d'utilisateur)
- Connexion/Déconnexion
- Gestion du profil personnel
- Modification des informations personnelles
- Changement de mot de passe
- Upload de photo de profil
- Activation de compte (pour les médecins, validation par l'admin)

### 4.2 Notifications en Temps Réel
**Description** : Système de notifications bidirectionnel.

**Fonctionnalités** :
- Notifications WebSocket en temps réel
- Notifications persistantes en base de données
- Dropdown de notifications avec badge
- Marquer comme lu / Tout marquer comme lu
- Suppression de notifications
- Historique des notifications

### 4.3 Responsive Design
**Description** : Interface adaptée à tous les appareils.

**Fonctionnalités** :
- Design responsive (mobile, tablette, desktop)
- Interface moderne avec Tailwind CSS
- Navigation intuitive
- Sidebar rétractable (médecin et patient)

---

## 5. RÉSUMÉ DES DROITS PAR ACTEUR

### Admin
- ✅ Accès complet à toutes les données
- ✅ Gestion des utilisateurs (création, modification, suppression)
- ✅ Gestion des médecins
- ✅ Consultation de toutes les statistiques
- ✅ Génération de rapports
- ❌ Pas d'accès aux consultations médicales détaillées
- ❌ Pas de création de rendez-vous

### Médecin
- ✅ Gestion de son agenda
- ✅ Consultation et modification des dossiers médicaux de ses patients
- ✅ Création et gestion des consultations
- ✅ Génération de comptes-rendus par IA
- ✅ Création d'ordonnances
- ✅ Communication avec ses patients
- ✅ Consultation de ses statistiques personnelles
- ❌ Pas d'accès aux dossiers médicaux des autres médecins
- ❌ Pas de gestion des autres utilisateurs

### Patient
- ✅ Prise de rendez-vous
- ✅ Consultation de son dossier médical
- ✅ Consultation de ses ordonnances
- ✅ Paiement en ligne
- ✅ Notation des médecins
- ✅ Utilisation du chatbot médical
- ✅ Communication avec ses médecins
- ✅ Consultation de ses commandes et dons
- ❌ Pas d'accès aux dossiers médicaux d'autres patients
- ❌ Pas de modification des consultations

---

## 6. ARCHITECTURE TECHNIQUE

### Technologies Utilisées
- **Backend** : Laravel 11, PHP 8.2
- **Frontend** : Blade Templates, Tailwind CSS, Alpine.js, JavaScript
- **Base de données** : MySQL/SQLite
- **WebSockets** : Laravel Reverb
- **IA** : OpenAI GPT-4, Claude, Google Gemini, Mistral (Hugging Face)
- **Paiement** : Paymee API
- **Visioconférence** : Jitsi Meet
- **Calendrier** : FullCalendar

### Sécurité
- Authentification Laravel Sanctum
- Middleware de contrôle d'accès par rôle
- Chiffrement des données sensibles
- Validation des entrées utilisateur
- Protection CSRF
- Rate limiting

---

**Document généré le** : 2025-01-XX  
**Version** : 1.0  
**Projet** : MedicalPlatform

