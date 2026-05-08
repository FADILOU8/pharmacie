# 🚀 COMMANDES RAPIDES - LANCEMENT

## Pour démarrer le projet corrigé:

### Option 1: Lancement rapide (Développement)
```bash
cd c:\Users\lenevo\Documents\Cours-Wed\examf

# Exécuter les migrations
php artisan migrate

# Lancer le serveur
php artisan serve
```

Puis ouvrir: **http://localhost:8000**

---

### Option 2: Avec Vite (Modern Development)
```bash
cd c:\Users\lenevo\Documents\Cours-Wed\examf

# Terminal 1: Migrations
php artisan migrate

# Terminal 2: Vite (compilation assets)
npm run dev

# Terminal 3: Serveur Laravel
php artisan serve
```

---

### Option 3: Avec Docker
```bash
docker-compose up -d
docker-compose exec app php artisan migrate
```

---

## ✅ Vérification rapide après lancement

### 1. Accéder au dashboard
```
URL: http://localhost:8000
Action: Se connecter (ou créer un compte)
```

### 2. Créer une vente
```
Ventes → Nouvelle Vente → Remplir → Enregistrer
Vérifier: Stock mis à jour
```

### 3. Voir les détails
```
Ventes → Cliquer sur une vente
Vérifier: Page affichée correctement
```

### 4. Modifier une vente
```
Détails → Modifier → Changer quelque chose → Enregistrer
Vérifier: Stock restauré puis mis à jour
```

### 5. Tester le Chatbot
```
Bas à droite: Bouton Chatbot
Envoyer: Un message test
Vérifier: Réponse IA reçue
```

### 6. Télécharger un export
```
Depuis une prescription ou ventes
Cliquer: PDF, CSV ou WhatsApp
Vérifier: Fichier généré
```

---

## 🔄 Si vous devez réinitialiser la base:

```bash
# Supprimer et recréer
php artisan migrate:reset
php artisan migrate

# Ajouter des données de test
php artisan db:seed (si DatabaseSeeder configuré)
```

---

## 📱 Accès au projet

**URL locale:** http://localhost:8000  
**Port Laravel:** 8000 (configurable)  
**Base de données:** SQLite (database/database.sqlite)

---

## 🎯 Tous les problèmes ont été résolus!

✅ Ventes - Toutes les actions fonctionnent  
✅ Chatbot - Configuré et opérationnel  
✅ Exports - Routes connectées  
✅ Stock - Gestion correcte  
✅ Routes - Aucune route cassée  
✅ Vues - Aucune vue manquante  

**Vous êtes prêt à lancer! 🚀**
