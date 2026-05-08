<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    /**
     * Répondre aux requêtes du chatbot IA
     */
    public function respond(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $message = $request->input('message');

        // Vérifier que le chatbot est activé
        if (!$user->ai_chatbot_enabled) {
            return response()->json([
                'error' => 'Le chatbot IA est désactivé.',
                'success' => false,
            ], 403);
        }

        // Générer une réponse basée sur le contexte
        $response = $this->generateResponse($message, $user);

        return response()->json([
            'response' => $response,
            'success' => true,
        ]);
    }

    /**
     * Toggler l'activation du chatbot
     */
    public function toggle(Request $request)
    {
        $user = Auth::user();
        $user->ai_chatbot_enabled = !$user->ai_chatbot_enabled;
        $user->save();

        return response()->json([
            'enabled' => $user->ai_chatbot_enabled,
            'message' => $user->ai_chatbot_enabled ? 'Chatbot IA activé' : 'Chatbot IA désactivé',
        ]);
    }

    /**
     * Générer une réponse intelligente
     */
    private function generateResponse($message, $user)
    {
        $message = strtolower($message);

        // Réponses contextuelles pour la gestion de pharmacie
        $responses = [
            // Accueil et aide
            'hello|bonjour|hi|salut' => 'Bonjour ! 👋 Je suis votre assistant IA. Je peux vous aider avec la gestion de votre pharmacie. Que puis-je faire pour vous ?',
            'aide|help|aide-moi|quoi faire' => 'Je peux vous aider avec :
- 💊 Gestion des médicaments (ajout, modification, suppression)
- 👥 Gestion des patients et prescriptions
- 🛍️ Enregistrement des ventes
- 🏭 Gestion des fournisseurs
- ⏰ Alertes d\'expiration
- 📊 Statistiques et rapports

Posez-moi une question spécifique !',

            // Médicaments
            'medicament|ajouter.*medicament|nouveau.*medicament' => 'Pour ajouter un médicament, allez dans le menu "Médicaments" et cliquez sur "+ Ajouter Médicament". Vous devrez remplir :
- DCI (Dénomination Commune Internationale)
- Nom du médicament
- Forme galénique (comprimé, sirop, etc.)
- Dosage
- Numéro de lot
- Quantité en stock
- Prix unitaire
- Date d\'expiration',

            // Patients
            'patient|gestion.*patient|ajouter.*patient' => 'Pour gérer les patients :
1. Accédez à la section "Patients"
2. Vous pouvez ajouter un nouveau patient avec son nom, téléphone, email et adresse
3. Chaque patient a un système de points de fidélité
4. Vous pouvez consulter leurs prescriptions directement',

            // Prescriptions
            'prescription|ordonnance|ajouter.*ordonnance' => 'Pour enregistrer une ordonnance :
1. Allez dans "Ordonnances"
2. Cliquez sur "+ Nouvelle Ordonnance"
3. Sélectionnez le patient
4. Entrez le nom du médecin
5. Listez les médicaments prescrits
6. Confirmez le statut (en attente, délivrée, archivée)',

            // Ventes
            'vente|enregistrer.*vente|caisse' => 'Pour enregistrer une vente :
1. Allez dans "Ventes & Caisse"
2. Cliquez sur "+ Nouvelle Vente"
3. Sélectionnez le médicament
4. Indiquez la quantité
5. Appliquez une remise si nécessaire
6. Choisissez le mode de paiement (espèces, carte, chèque)
7. Confirmez la vente',

            // Fournisseurs
            'fournisseur|supplier|ajouter.*fournisseur' => 'Pour gérer les fournisseurs :
1. Accédez à "Fournisseurs"
2. Ajoutez les informations : nom, personne de contact, téléphone, email, adresse
3. Indiquez les conditions de paiement (ex: Net 30 jours)
4. Vous pouvez modifier ou supprimer à tout moment',

            // Alertes expiration
            'alerte.*expiration|medicament.*expire|verifier.*expiration' => 'Pour vérifier les expirations :
1. Allez dans "Alertes d\'expiration"
2. Cliquez sur "🔄 Vérifier les Expirations"
3. Les alertes s\'affichent en code couleur :
   - 🟢 Vert : Normal
   - 🟡 Jaune : Critique (moins de 30 jours)
   - 🔴 Rouge : Expiré
4. Vous pouvez marquer une alerte comme résolue',

            // Statistiques
            'statistique|rapport|chiffre|bilan' => 'Vous pouvez consulter vos statistiques depuis le tableau de bord. Les informations disponibles incluent :
- Nombre de médicaments en stock
- Nombre de patients
- Total des ventes du jour
- Alertes d\'expiration actives',

            // Paramètres
            'parametre|parametres|settings|preference' => 'Vous pouvez personnaliser votre expérience en gérant vos préférences utilisateur. Je peux également être désactivé si vous le souhaitez !',

            // Par défaut
            'default' => 'Je n\'ai pas bien compris votre question. 🤔 Pouvez-vous être plus précis ? Vous pouvez demander de l\'aide sur :
- Les médicaments
- Les patients et prescriptions
- Les ventes
- Les fournisseurs
- Les alertes d\'expiration',
        ];

        // Chercher une correspondance
        foreach ($responses as $pattern => $response) {
            $patterns = explode('|', $pattern);
            foreach ($patterns as $p) {
                if (strpos($message, $p) !== false) {
                    return $response;
                }
            }
        }

        return $responses['default'];
    }
}
