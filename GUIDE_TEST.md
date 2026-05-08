# 🧪 GUIDE DE TEST - VÉRIFICATION COMPLÈTE

## ⚡ Commandes à exécuter avant le lancement

### 1️⃣ Migrer la base de données
```bash
cd c:\Users\lenevo\Documents\Cours-Wed\examf
php artisan migrate
```

**Résultat attendu:**
```
Migrating: 2026_05_04_173308_add_ai_settings_to_users
Migrated:  2026_05_04_173308_add_ai_settings_to_users (0.xx ms)
```

### 2️⃣ Vérifier les migrations avec tinker
```bash
php artisan tinker
```

Puis exécuter:
```php
// Vérifier les colonnes users
DB::select("PRAGMA table_info(users)")
// Ou pour MySQL:
// DB::select("DESCRIBE users")
```

**À vérifier:**
- ✅ Colonne `ai_chatbot_enabled` (boolean)
- ✅ Colonne `ai_notifications_enabled` (boolean)
- ✅ Colonne `ai_preferences` (text/json)

Taper `exit` pour quitter.

---

## 🧬 Tests de Fonctionnalité

### ✅ TEST 1 - CRÉER UNE VENTE
1. Se connecter au dashboard
2. Aller à "Ventes" → "Nouvelle Vente"
3. Sélectionner un médicament
4. Entrer une quantité
5. Cliquer "Enregistrer"

**Vérifier:**
- Message de succès apparaît
- Stock diminue de la quantité
- Vente listée dans l'index

### ✅ TEST 2 - VOIR DÉTAILS D'UNE VENTE
1. Aller à "Ventes"
2. Cliquer sur "Voir" (ou sur le lien de la vente)
3. Page sales/{id} affichée avec tous les détails

**Vérifier:**
- Tous les détails affichés correctement
- Boutons "Modifier" et "Supprimer" présents
- Calculer manuellement: (quantité × prix) - remise = total

### ✅ TEST 3 - MODIFIER UNE VENTE
1. Depuis la page détails, cliquer "Modifier"
2. Changer quelques valeurs
3. Cliquer "Enregistrer les modifications"

**Vérifier:**
- Stock restauré de l'ancienne quantité
- Nouveau stock décrémenté
- Page redirection vers la liste
- Modifications sauvegardées

**Cas limites à tester:**
- Vendre plus que le stock disponible (devrait afficher erreur)
- Remise supérieure au prix (devrait accepter)
- Changement de médicament (stock géré correctement)

### ✅ TEST 4 - SUPPRIMER UNE VENTE
1. Depuis la page détails, cliquer "Supprimer"
2. Confirmer la suppression

**Vérifier:**
- Stock remis intégralement
- Vente disparait de la liste
- Message de succès

### ✅ TEST 5 - CHATBOT IA
1. Aller au dashboard
2. Bouton chatbot visible en bas à droite

**Tester:**
```
a) Bouton toggle (✓ sur le bouton)
   → Vérifier: ai_chatbot_enabled basculé dans la base
   
b) Envoyer un message
   → Vérifier: Réponse IA reçue
   → Vérifier: Condition ai_chatbot_enabled correcte
   
c) Activer/Désactiver notifications (si formulaire present)
   → Vérifier: ai_notifications_enabled mis à jour
```

### ✅ TEST 6 - EXPORTS
```bash
# Test via Postman/Insomnia ou directement dans le code:

POST /prescriptions/1/pdf
POST /prescriptions/1/csv  
POST /sales/csv
GET /patients/csv
POST /prescriptions/1/whatsapp
```

**Vérifier:**
- Fichiers générés sans erreur 500
- Format correct (PDF valide, CSV lisible)
- Contenu cohérent

---

## 🔍 Vérifications Importantes

### Base de Données

```sql
-- Pour SQLite (Laravel default)
SELECT * FROM users LIMIT 1;
```

**Colonnes AI présentes:**
```
| id | name | email | ... | ai_chatbot_enabled | ai_notifications_enabled | ai_preferences |
| 1  | ... | ...   | ... | 1                  | 1                        | NULL           |
```

### Routes (Via artisan)
```bash
php artisan route:list | grep -E "sales|chatbot|export"
```

**Routes attendues:**
```
GET|HEAD  /sales .......................... sales.index
POST      /sales .......................... sales.store
GET|HEAD  /sales/create ................... sales.create
GET|HEAD  /sales/{sale} ................... sales.show ✅ NOUVELLE
GET|HEAD  /sales/{sale}/edit .............. sales.edit ✅ NOUVELLE
PUT|PATCH /sales/{sale} ................... sales.update ✅ NOUVELLE
DELETE    /sales/{sale} ................... sales.destroy ✅ NOUVELLE
POST      /api/chatbot/respond ............ chatbot.respond
POST      /api/chatbot/toggle ............ chatbot.toggle
POST      /prescriptions/{prescription}/pdf .. export.prescription.pdf ✅ NOUVELLE
GET|HEAD  /prescriptions/{prescription}/csv .. export.prescription.csv ✅ NOUVELLE
POST      /prescriptions/{prescription}/whatsapp .. export.whatsapp ✅ NOUVELLE
POST      /sales/csv ....................... export.sales.csv ✅ NOUVELLE
GET|HEAD  /patients/csv .................... export.patients.csv ✅ NOUVELLE
```

---

## 🐛 Dépannage

### ❌ Erreur 500 sur sales/{id}
**Cause:** Vues manquantes  
**Solution:** Vérifier que `resources/views/sales/show.blade.php` existe

### ❌ Erreur "SQLSTATE[HY000]"
**Cause:** Migration non exécutée  
**Solution:** `php artisan migrate`

### ❌ Chatbot ne répond pas
**Cause:** `ai_chatbot_enabled` à false  
**Solution:** Vérifier les colonnes user et la valeur

### ❌ Stock mal calculé
**Cause:** Logique de mise à jour incorrecte  
**Solution:** Vérifier la méthode `update()` dans SaleController

### ❌ Export génère erreur
**Cause:** Dépendance manquante (ex: DOMPDF)  
**Solution:** `composer require barryvdh/laravel-dompdf`

---

## 📋 Checklist de Validation Final

| Test | Statut | Notes |
|------|--------|-------|
| Migration exécutée | ⬜ | `php artisan migrate` |
| Colonnes AI présentes | ⬜ | Vérifier avec Tinker |
| Créer vente | ⬜ | Stock décrémenté? |
| Voir vente | ⬜ | Page affichée? |
| Modifier vente | ⬜ | Stock restauré/mis à jour? |
| Supprimer vente | ⬜ | Stock rétabli? |
| Chatbot affiche | ⬜ | Visible en bas à droite? |
| Chatbot répond | ⬜ | API fonctionne? |
| Export PDF | ⬜ | Fichier généré? |
| Export CSV | ⬜ | Format correct? |
| Permissions | ⬜ | Utilisateurs isolés? |

---

## ✨ Tout est prêt!

Une fois tous les tests passants, votre application est **prête pour la production**.

**Points clés vérifiés:**
- ✅ Toutes les routes de ventes fonctionnent
- ✅ Chatbot configuré et accessible
- ✅ Exports disponibles
- ✅ Stock géré correctement
- ✅ Permissions appliquées
