<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\pres_skilsController;
use App\Http\Controllers\PrestataireController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\ProfilController;

// ================= ACCUEIL ET ETUDSERV =================
Route::get('/', function () {
    return view('auth.welcome');
});

// ======= ROUTE DIRECTE VERS HOME =======
Route::get('/home', function () {
    $prestataires = \App\Models\prestataire::with(['utilisateur', 'skills', 'services'])->get();

    $freelancersData = $prestataires->map(function ($p) {
        $colors = ['#52D6F4', '#7B61FF', '#45C486', '#F59E0B', '#FF7AA8', '#FF6B6B', '#35C4E6', '#8B7BFF'];
        $color = $colors[$p->idprestataire % count($colors)];
        $firstService = $p->services->first();
        
        $name = trim(($p->utilisateur->prenom ?? '') . ' ' . ($p->utilisateur->nom ?? ''));
        if (empty($name)) { $name = "Prestataire #" . $p->idprestataire; }

        return [
            'id'       => $p->idprestataire,
            'user_id'  => $p->utilisateur->idutilisateur ?? null,   // ← AJOUTER
            'name'     => $name,
            'avatar'   => ($p->utilisateur && $p->utilisateur->photo) ? asset('storage/' . $p->utilisateur->photo) : null,
            'role'     => $firstService ? $firstService->titre : $p->type_prestataire,
            'type'     => ($p->utilisateur->role ?? '') === 'freelancer' && ($p->niveau ?? '') === 'etudiant' ? 'etudiant' : 'pro',
            'category' => $p->type_prestataire ?? 'Développement',
            'tags'     => $p->skills->pluck('nom')->toArray() ?: [$p->type_prestataire ?? 'Freelancer'],
            'desc'     => $firstService ? $firstService->description : "Prestataire de services.",
            'rating'   => '⭐ ' . number_format($p->note_moyenne ?? 5.0, 1) . ' (' . (($p->idprestataire * 7) % 30 + 12) . ')',
            'price'    => number_format($firstService ? $firstService->prix_base : 5000, 0, ',', ' ') . ' MRU',
            'color'    => $color,
            'service_id' => $firstService ? $firstService->idservice : null,
            'membre_depuis' => $p->utilisateur && $p->utilisateur->date_inscription
                ? \Carbon\Carbon::parse($p->utilisateur->date_inscription)->format('d/m/Y')
                : null,
        ];
    });

    return view('auth.Projet', compact('freelancersData'));
})->name('home');

// Alias pour garder l'ancienne URL active sans dupliquer le code
Route::get('/etudserv', function () {
    return redirect()->route('home');
});


// ================= CONNEXION / DÉCONNEXION =================
Route::get('/login', [UtilisateurController::class, 'showLogin'])->name('login');
Route::post('/login', [UtilisateurController::class, 'login'])->name('login.post');
Route::post('/logout', [UtilisateurController::class, 'logout'])->name('logout');

// ================= INSCRIPTION =================
Route::get('/register', [UtilisateurController::class, 'showRegister'])->name('register');
Route::post('/register', [UtilisateurController::class, 'register'])->name('register.post');
Route::delete('login', [UtilisateurController::class, 'delete'])->name('users.delete');

// ================= SUPRESSION =================
Route::delete('/utilisateur/supprimer', [UtilisateurController::class, 'supprimerCompletement'])->name('users.delete');

// ================= SOUUMISSIONS FORMULAIRES =================
Route::post('/creer-conversation', [ConversationController::class, 'démarrer']);
Route::post('/envoyer-message', [MessageController::class, 'envoyer']);
Route::post('/ajouter-competence', [pres_skilsController::class, 'lierCompetence']);
Route::post('/devenir-prestataire', [PrestataireController::class, 'devenirPrestataire'])->name('devenir.prestataire');

// ================= OTP CONNEXION & ACTIVATION =================
Route::get('/login/verify',  [AuthController::class, 'showVerifyForm'])->name('otp.verify.form');
Route::post('/login/send',   [AuthController::class, 'sendOtp'])->name('otp.send');
Route::post('/login/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::get('/profile/create', function () {
    if (!session('utilisateur_id')) return redirect()->route('login');
    return view('auth.Profile1');
})->name('auth.Profile1');Route::post('/activate',     [AuthController::class, 'verifyActivation'])->name('otp.activate');
Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');

// ================= CONTACT =================
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::post('/contact/reply', [ContactController::class, 'reply'])->name('contact.reply');

// ================= ROUTE VERS FREELANCER DASHBOARDS  =================
Route::get('/dashboard', function () {
    if (!session('utilisateur_id')) {
        return redirect()->route('login');
    }
    
    // Si c'est un client qui atterrit ici par erreur, on le redirige sur le bon dashboard
    if (session('role') === 'client') {
        return redirect()->route('client.dashboard');
    }

    return view('auth.dashboard');
})->name('dashboard');

// ================= ROUTE VERS CLIENT DASHBOARD =================
Route::get('/client-dashboard', function () {
    if (!session('utilisateur_id')) {
        return redirect()->route('login');
    }

    // 1. OBLIGATOIRE : On ajoute 'utilisateur' dans le "with" pour récupérer la photo de profil
    $prestataires = \App\Models\prestataire::with(['utilisateur', 'services'])->get();

    // 2. Transformer les données de manière identique à la page Projet
    $freelancersData = $prestataires->map(function ($p) {
        $colors = ['#52D6F4', '#7B61FF', '#45C486', '#F59E0B', '#FF7AA8', '#FF6B6B', '#35C4E6', '#8B7BFF'];
        $color = $colors[$p->idprestataire % count($colors)];
        $firstService = $p->services->first();
        
        $name = trim(($p->utilisateur->prenom ?? '') . ' ' . ($p->utilisateur->nom ?? ''));
        if (empty($name)) { $name = "Prestataire #" . $p->idprestataire; }

        return [
            'id'         => $p->idprestataire,
            'user_id'    => $p->utilisateur->idutilisateur ?? null,   // ← AJOUTER
            'name'       => $name,
            'avatar'     => ($p->utilisateur && $p->utilisateur->photo) ? asset('storage/' . $p->utilisateur->photo) : null,
            'role'       => $firstService ? $firstService->titre : $p->type_prestataire,
            'category'   => $p->type_prestataire ?? 'Développement',
            'desc'       => $firstService ? $firstService->description : 'Aucune description disponible pour le moment.',
            'price'      => number_format($firstService ? $firstService->prix_base : 1500, 0, ',', ' ') . ' MRU',
            'color'      => $color,
            'service_id' => $firstService ? $firstService->idservice : null,
        ];
    });

    return view('auth.client-dashboard', compact('freelancersData'));
})->name('client.dashboard');



// ================= PUBLICATION ET SUPPRESSION DE PROJET =================
Route::post('/publier-projet', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'titre' => 'required|string|max:255',
        'categorie' => 'nullable|string',
        'description' => 'nullable|string',
        'budget' => 'nullable|numeric|min:0',
    ]);

    $projets = session('projets', []);
    $projets[] = [
        'titre' => $request->titre,
        'categorie' => $request->categorie,
        'description' => $request->description,
        'budget' => $request->budget,
    ];
    session(['projets' => $projets]);

    return redirect()->back()->with('success', 'Votre projet a été publié avec succès !');
})->name('projet.store');

Route::post('/supprimer-projet/{index}', function ($index) {
    $projets = session('projets', []);
    if (isset($projets[$index])) {
        unset($projets[$index]);
        $projets = array_values($projets);
        session(['projets' => $projets]);
    }
    return redirect()->back()->with('success', 'Projet supprimé.');
})->name('projet.delete');


// ================= CHANGEMENT DE LANGUE =================
Route::post('/lang/switch', function (\Illuminate\Http\Request $request) {
    session(['lang' => $request->input('lang', 'fr')]);
    return redirect()->back();
})->name('lang.switch');

// Route pour afficher la liste des messages sans conversation sélectionnée
Route::get('/messagerie', [App\Http\Controllers\MessageController::class, 'index'])->name('messagerie.index');

// Route pour afficher une conversation précise (avec ID)
Route::get('/messagerie/{id}', [App\Http\Controllers\MessageController::class, 'index'])->name('messagerie');

// Route pour démarrer ou ouvrir une conversation avec un prestataire lié à un service
Route::get('/messagerie/demarrer/{service_id}', [App\Http\Controllers\MessageController::class, 'demarrer'])
    ->name('messagerie.demarrer');
    
// ================= ROUTE RE-LIÉE : LISTE DES FREELANCERS =================
Route::get('/liste-freelancers', function () {
    if (!session('utilisateur_id')) {
        return redirect()->route('login');
    }
    // Redirige directement vers l'ancre du bas de page du dashboard
    return redirect('/client-dashboard#section-freelancers');
});
Route::post('/messagerie/envoyer', [App\Http\Controllers\MessageController::class, 'envoyer'])->name('messagerie.envoyer');
// ================= AVIS =================
Route::post('/avis', [AvisController::class, 'store'])->name('avis.store');

// ================= PROFIL =================
Route::get('/profil/{id}', [ProfilController::class, 'show'])->name('profil.show');

// Route pour le Dashboard Client (profil.photo.update)
Route::post('/profile/update-photo', [UtilisateurController::class, 'updatePhoto'])->name('profil.photo.update');

// Route pour le Dashboard Freelancer (profile.update.photo)
Route::post('/profile/update-photo-freelancer', [UtilisateurController::class, 'updatePhoto'])->name('profile.update.photo');

