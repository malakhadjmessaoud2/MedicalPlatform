# Système de Notifications - Documentation

> 📚 **Documentation Complète** : Pour une description détaillée complète, consultez [docs/NOTIFICATIONS_TEMPS_REEL_COMPLETE.md](./docs/NOTIFICATIONS_TEMPS_REEL_COMPLETE.md)

## Vue d'ensemble

Le système de notifications a été implémenté pour gérer les notifications liées aux rendez-vous entre médecins et patients. Il s'intègre parfaitement avec le système WebSocket existant.

**Architecture** : WebSocket (Laravel Reverb) + Notifications Persistantes (Base de données)

## Composants implémentés

### 1. Base de données
- ✅ Table `notifications` créée avec `php artisan notifications:table`
- ✅ Migration exécutée avec `php artisan migrate`

### 2. Classes de notification
- ✅ `RendezVousCreatedNotification` - Notification de création de rendez-vous
- ✅ `RendezVousModifiedNotification` - Notification de modification de rendez-vous
- ✅ `RendezVousStatusChangedNotification` - Notification de changement de statut

### 3. Contrôleurs
- ✅ `NotificationController` - Gestion des notifications via API
- ✅ Intégration dans `RendezVousController` (Patient et Médecin)

### 4. Routes API
- ✅ `GET /notifications` - Liste des notifications
- ✅ `GET /notifications/recent` - Notifications récentes
- ✅ `GET /notifications/unread-count` - Compteur de notifications non lues
- ✅ `PATCH /notifications/{id}/mark-as-read` - Marquer comme lue
- ✅ `PATCH /notifications/mark-all-as-read` - Marquer toutes comme lues
- ✅ `DELETE /notifications/{id}` - Supprimer une notification

### 5. Frontend
- ✅ JavaScript mis à jour (`resources/js/dashboard/notifications.js`)
- ✅ Navbar médecin mise à jour (`resources/views/dashMedecin/navbar.blade.php`)
- ✅ Navbar patient mise à jour (`resources/views/dashPatient/navbar.blade.php`)

## Flux de notifications

### Patient → Médecin
1. Patient crée un rendez-vous via `RendezVousController@store`
2. Événement `RendezVousCreate` est déclenché (WebSocket)
3. Notification `RendezVousCreatedNotification` est envoyée au médecin
4. Notification stockée en base de données
5. Notification affichée en temps réel ET persistante

### Médecin → Patient
1. Médecin modifie un rendez-vous via `RendezVousController@update`
2. Événement `RendezVousModifie` est déclenché (WebSocket)
3. Notification `RendezVousModifiedNotification` est envoyée au patient
4. Notification stockée en base de données
5. Notification affichée en temps réel ET persistante

### Changement de statut
1. Médecin change le statut d'un rendez-vous
2. Notification `RendezVousStatusChangedNotification` est envoyée au patient
3. Détails du changement inclus dans la notification

## Types de notifications

### RendezVousCreatedNotification
- **Type** : `rendez_vous_created`
- **Couleur** : Bleu
- **Icône** : `calendar-plus`
- **Données** : Patient, médecin, dates, type, statut

### RendezVousModifiedNotification
- **Type** : `rendez_vous_modified`
- **Couleur** : Jaune
- **Icône** : `calendar-edit`
- **Données** : Changements détaillés, médecin, dates

### RendezVousStatusChangedNotification
- **Type** : `rendez_vous_status_changed`
- **Couleur** : Variable selon le statut
- **Icône** : Variable selon le statut
- **Données** : Ancien/nouveau statut, libellés

## Interface utilisateur

### Dropdown de notifications
- Affichage des notifications persistantes
- Badge avec compteur de notifications non lues
- Bouton "Tout marquer comme lu"
- Indicateurs visuels pour les notifications non lues
- Animations d'ouverture/fermeture

### Intégration WebSocket
- Notifications temps réel conservées
- Rechargement automatique des notifications persistantes
- Synchronisation entre temps réel et persistant

## Configuration requise

### Variables d'environnement
```env
# WebSocket (déjà configuré)
VITE_REVERB_APP_KEY=your-key
VITE_REVERB_HOST=localhost
VITE_REVERB_PORT=8080

# Queue (pour les notifications)
QUEUE_CONNECTION=database
```

### Commandes à exécuter
```bash
# Migration
php artisan migrate

# Compilation des assets
npm run build

# Démarrage des services
php artisan reverb:start
php artisan queue:work
```

## Test du système

### Test manuel
1. Connectez-vous en tant que patient
2. Créez un rendez-vous
3. Vérifiez que le médecin reçoit la notification
4. Connectez-vous en tant que médecin
5. Modifiez le rendez-vous
6. Vérifiez que le patient reçoit la notification

### Test API
```bash
# Notifications récentes
curl -H "Authorization: Bearer TOKEN" http://localhost:8000/notifications/recent

# Compteur de notifications non lues
curl -H "Authorization: Bearer TOKEN" http://localhost:8000/notifications/unread-count
```

## Avantages du système

1. **Double notification** : Temps réel + persistant
2. **Historique complet** : Toutes les notifications sont sauvegardées
3. **Interface intuitive** : Dropdown avec indicateurs visuels
4. **Performance** : Notifications en queue pour éviter les blocages
5. **Extensibilité** : Facile d'ajouter de nouveaux types de notifications
6. **Compatibilité** : S'intègre parfaitement avec le système existant

## Prochaines étapes

1. Tester le système en conditions réelles
2. Ajouter des notifications pour d'autres événements (paiements, annulations, etc.)
3. Implémenter des préférences de notification par utilisateur
4. Ajouter des notifications par email
5. Créer une page dédiée aux notifications
