<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur;
use App\Models\skills;
use App\Models\Prestataire;
class UtilisateurController extends Controller
{
    // ================= AFFICHER LE FORMULAIRE DE CONNEXION =================
    public function showLogin()
    {
        return view('auth.login');
    }

    // ================= TRAITER LA CONNEXION =================
    public function login(Request $request)
    {
        // 1. Valider les champs envoyés
        $request->validate([
            'email'        => 'required|email',
            'mot_de_passe' => 'required|string|min:6',
        ], [
            'email.required'        => 'L\'adresse email est obligatoire.',
            'email.email'           => 'L\'adresse email n\'est pas valide.',
            'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
            'mot_de_passe.min'      => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        // 2. Chercher l'utilisateur par email dans la base de données
        $utilisateur = Utilisateur::where('email', $request->email)->first();

        // 3. Vérifier si l'utilisateur existe
        if (!$utilisateur) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Aucun compte trouvé avec cet email.']);
        }

        // 4. Vérifier si le mot de passe est correct
        if (!Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['mot_de_passe' => 'Le mot de passe est incorrect.']);
        }

        // 5. Connexion réussie → stocker l'utilisateur en session
        session([
            'utilisateur_id'     => $utilisateur->idutilisateur,
            'utilisateur_nom'    => $utilisateur->nom,
            'utilisateur_prenom' => $utilisateur->prenom,
            'utilisateur_role'   => $utilisateur->role,
            'utilisateur_email'  => $utilisateur->email,
        ]);

        // 6. Rediriger vers l'accueil
        if ($utilisateur->role === 'freelancer') {
            return redirect()->route('dashboard')
                ->with('success', 'Bienvenue, ' . $utilisateur->nom . ' !');
        }

        return redirect()->route('client.dashboard')
            ->with('success', 'Bienvenue, ' . $utilisateur->nom . ' !');
    }

    // ================= AFFICHER LE FORMULAIRE D'INSCRIPTION =================
    public function showRegister()
    {
        return view('auth.register');
    }

    // ================= TRAITER L'INSCRIPTION =================
    public function register(Request $request)
    {
        // 1. Valider les champs
        $request->validate([
            'nom'       => 'required|string|max:45',
            'prenom'    => 'required|string|max:45',
            'email'     => 'required|email|max:45|unique:utilisateur,email',
            'password'  => 'required|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:45',
            'adresse'   => 'nullable|string|max:45',
            'role'      => 'required|in:client,freelancer',
        ], [
            'nom.required'       => 'Le nom est obligatoire.',
            'prenom.required'    => 'Le prénom est obligatoire.',
            'email.required'     => 'L\'adresse email est obligatoire.',
            'email.email'        => 'L\'adresse email n\'est pas valide.',
            'email.unique'       => 'Cet email est déjà utilisé.',
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les deux mots de passe ne correspondent pas.',
            'role.required'      => 'Veuillez choisir un rôle.',
            'role.in'            => 'Le rôle doit être client ou freelancer.',
        ]);

        // 2. Créer l'utilisateur dans la base de données
        $utilisateur = Utilisateur::create([
            'nom'              => $request->nom,
            'prenom'           => $request->prenom,
            'email'            => $request->email,
            'mot_de_passe'     => Hash::make($request->password),
            'telephone'        => $request->telephone,
            'adresse'          => $request->adresse,
            'date_inscription' => now()->toDateString(),
            'role'             => $request->role,
        ]);

        // 3. Si c'est un freelancer, créer AUTOMATIQUEMENT son profil prestataire
        if ($utilisateur->role === 'freelancer') {
            \App\Models\prestataire::create([
                'utilisateur_idutilisateur' => $utilisateur->idutilisateur,
                'type_prestataire'          => 'Développement Web',
                'niveau'                    => 'junior',
                'note_moyenne'              => 5.0,
                'inscription_payee'         => 0,
            ]);
        }

        // 4. Envoyer le code OTP d'activation par email
    app(AuthController::class)->sendActivationOtp($utilisateur->email);

    // 5. Stocker temporairement les infos en session (connexion finalisée après activation)
    session([
        'pending_utilisateur_id'   => $utilisateur->idutilisateur,
        'pending_utilisateur_nom'  => $utilisateur->nom,
        'pending_utilisateur_role' => $utilisateur->role,
    ]);

    // 6. Rediriger vers le formulaire de vérification du code
    return redirect()->route('otp.verify.form')
        ->with('email', $utilisateur->email)
        ->with('activating', true);
    }

    // ================= DÉCONNEXION =================
    public function logout()
{
    // 1. Récupérer l'ID de l'utilisateur connecté avant de détruire la session
    $utilisateur_id = session('utilisateur_id');

    if ($utilisateur_id) {
        // 2. Mettre à jour sa dernière activité à null (ou une date passée) pour le déconnecter immédiatement
        $utilisateur = Utilisateur::find($utilisateur_id);
        if ($utilisateur) {
            $utilisateur->derniere_activite = null; // En mettant à null, il sera vu comme hors ligne immédiatement
            $utilisateur->save();
        }
    }

    // 3. Procéder à la déconnexion habituelle
    session()->forget(['utilisateur_id', 'utilisateur_nom', 'utilisateur_prenom', 'utilisateur_role']);
    
    return redirect()->route('home')->with('statut', 'Vous êtes déconnecté.');
}

    // ================= SUPPRESSION =================
// ================= SUPPRESSION DE COMPTE COMPLETEMEMT =================
public function delete(Request $request)
{
    $email = $request->input('email');
    $user = Utilisateur::where('email', $email)->first();
    
    if ($user) {
        // 1. Gérer le profil prestataire et ses dépendances s'il existe
        $prestataire = \App\Models\Prestataire::where('utilisateur_idutilisateur', $user->idutilisateur)->first();
        
        if ($prestataire) {
            // Récupérer les identifiants de tous les services de ce prestataire
            $serviceIds = \App\Models\Service::where('prestataire_idprestataire', $prestataire->idprestataire)
                ->pluck('idservice');

            if ($serviceIds->isNotEmpty()) {
                // A. Supprimer d'abord les conversations liées à ces services (car configurées en RESTRICT)
                \App\Models\Conversion::whereIn('service_idservice', $serviceIds)->delete();
                
                // B. Maintenant, on peut supprimer en toute sécurité les services
                \App\Models\Service::whereIn('idservice', $serviceIds)->delete();
            }
            
            // C. Supprimer ensuite le profil prestataire lui-même
            $prestataire->delete();
        }

        // 2. Gérer les conversations où l'utilisateur a participé en tant que Client
        // (Pour éviter que les contraintes RESTRICT sur les services d'autres personnes ne bloquent)
        \App\Models\Conversion::where('utilisateur_idutilisateur', $user->idutilisateur)->delete();

        // 3. Supprimer enfin l'utilisateur de la table principale
        // (Les messages, les profils freelances restants s'effaceront via le ON DELETE CASCADE global)
        $user->delete();

        // 4. Déconnecter la session si l'utilisateur connecté vient de supprimer son propre compte
        if (session('utilisateur_id') == $user->idutilisateur) {
            session()->flush();
        }

        return redirect()->route('login')->with("success", "Votre compte ainsi que l'intégralité de vos services, messages et données associés ont été supprimés avec succès.");
    }
    
    return redirect()->back()->withInput()->withErrors(['non_supprimer' => "L'adresse email est introuvable."]);
}

    public function search(Request $request)
    {
        $q    = trim($request->get('q', ''));
        $type = $request->get('type', 'freelancer');

        $results = collect();

        if ($type === 'freelancer') {
            $results = Utilisateur::where('role', 'freelancer')
                ->when($q, fn($query) =>
                    $query->where(fn($sub) =>
                        $sub->where('nom',     'LIKE', "%{$q}%")
                            ->orWhere('prenom', 'LIKE', "%{$q}%")
                            ->orWhere('adresse','LIKE', "%{$q}%")
                    )
                )
                ->get(['idutilisateur','nom','prenom','email','adresse'])
                ->map(fn($u) => [
                    'nom'    => $u->nom . ' ' . $u->prenom,
                    'detail' => $u->adresse ?? 'Mauritanie',
                    'email'  => $u->email,
                ]);
        }

        if ($type === 'skill') {
            $results = skills::when($q, fn($query) =>
                    $query->where(fn($sub) =>
                        $sub->where('nom',         'LIKE', "%{$q}%")
                            ->orWhere('description','LIKE', "%{$q}%")
                    )
                )
                ->get()
                ->map(fn($s) => [
                    'nom'    => $s->nom,
                    'detail' => $s->description ?? 'Catégorie',
                ]);
        }

        return response()->json([
            'results' => $results,
            'total'   => $results->count(),
        ]);
    } 

    public function index()
    {
        $utilisateurs = Utilisateur::where('role', 'freelancer')
            ->with(['prestataire.services.skill'])
            ->get();

        $freelancersData = $utilisateurs->map(function($u) {
            $p = $u->prestataire;
            $firstService = $p && $p->services->first() ? $p->services->first() : null;
            
            $category = $p && $p->type_prestataire ? $p->type_prestataire : 'Développement Web';

            $tags = [];
            if ($p) {
                foreach ($p->services as $service) {
                    if ($service->skill) {
                        $tags[] = $service->skill->nom;
                    }
                }
            }
            if (empty($tags)) {
                $tags = ['Freelancer'];
            }

            $colors = ['#4A90E2', '#50E3C2', '#F5A623', '#E2849A', '#9B51E0', '#45C486'];
            $avatarColor = $colors[$u->idutilisateur % count($colors)];

            return [
                'id'       => $u->idutilisateur,
                'name'     => $u->nom . ' ' . $u->prenom,
                'role'     => $firstService ? $firstService->titre : 'Prestataire Indépendant',
                'service_id' => $firstService ? $firstService->idservice : null, 
                'category' => $category,
                'type'     => ($p && $p->niveau === 'etudiant') ? 'etudiant' : 'pro',
                'tags'     => array_unique($tags),
                'desc'     => $firstService ? $firstService->description : 'Aucune description disponible pour le moment.',
                'rating'   => '⭐ ' . ($p ? number_format($p->note_moyenne, 1) : '5.0') . ' (Nouveau)',
                'price'    => 'À partir de ' . ($firstService ? $firstService->prix_base : '15') . '€/h',
                'color'    => $avatarColor,
                'email'    => $u->email
            ];
        });
        dd($utilisateurs->first()->prestataire);
        return view('Projet', ['dbFreelancers' => $freelancersData]);
    }


}