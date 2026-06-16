<?php

namespace App\Http\Controllers;

use App\Models\OtpCode;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Étape 1: L'utilisateur entre son email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:utilisateur,email'
        ]);

        $code = rand(100000, 999999);

        OtpCode::where('email', $request->email)->delete();
        OtpCode::create([
            'email'      => $request->email,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
            'type'       => 'login',
        ]);

        Mail::raw("Votre code de connexion: $code", function($msg) use ($request) {
            $msg->to($request->email)->subject('Code de connexion');
        });

        return redirect()->route('otp.verify.form')
                         ->with('email', $request->email);
    }

    // Envoyer un OTP d'activation après inscription
    public function sendActivationOtp(string $email)
    {
        $code = rand(100000, 999999);

        OtpCode::where('email', $email)->delete();
        OtpCode::create([
            'email'      => $email,
            'code'       => $code,
            'expires_at' => now()->addMinutes(30),
            'type'       => 'activation',
        ]);

        Mail::raw("Votre code d'activation EtudServ : $code", function($msg) use ($email) {
            $msg->to($email)->subject('Activez votre compte EtudServ');
        });
    }

    // Étape 2: L'utilisateur entre le code (connexion)
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required',
        ]);

        $otp = OtpCode::where('email', $request->email)
                      ->where('code', $request->code)
                      ->where('expires_at', '>', now())
                      ->first();

        if (!$otp) {
            return back()->withErrors([
                'code' => 'Code incorrect ou expiré'
            ]);
        }

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        session([
            'utilisateur_id'     => $utilisateur->idutilisateur,
            'utilisateur_nom'    => $utilisateur->nom,
            'utilisateur_prenom' => $utilisateur->prenom,
            'utilisateur_role'   => $utilisateur->role,
            'utilisateur_email'  => $utilisateur->email,
        ]);

        $otp->delete();

        if ($utilisateur->role === 'freelancer') {
            return redirect()->route('dashboard');
        }

        return redirect()->route('client.dashboard');
    }

    // Vérification du code d'activation après inscription
    // Vérification du code d'activation après inscription
    public function verifyActivation(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|digits:6',
        ]);

        $otp = OtpCode::where('email', $request->email)
                      ->where('code', $request->code)
                      ->where('type', 'activation')
                      ->where('expires_at', '>', now())
                      ->first();

        if (!$otp) {
            return back()->withErrors(['code' => 'Code incorrect ou expiré.'])->with('email', $request->email)->with('activating', true);
        }

        $otp->delete();

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        // Activer le compte
        $utilisateur->est_active = 1;
        $utilisateur->save();

        session([
            'utilisateur_id'     => $utilisateur->idutilisateur,
            'utilisateur_nom'    => $utilisateur->nom,
            'utilisateur_prenom' => $utilisateur->prenom,
            'utilisateur_role'   => $utilisateur->role,
            'utilisateur_email'  => $utilisateur->email,
        ]);
        session()->forget(['pending_utilisateur_id', 'pending_utilisateur_nom', 'pending_utilisateur_role']);

        // --- CORRECTION DE LA REDIRECTION ICI ---
        if ($utilisateur->role === 'freelancer') {
            // Utilise 'auth.Profile1' qui correspond exactement au ->name('auth.Profile1') de votre web.php
            return redirect()->route('auth.Profile1')
                ->with('success', 'Compte activé avec succès ! Veuillez compléter votre profil pour commencer.');
        }

        return redirect()->route('client.dashboard')
            ->with('success', 'Compte activé avec succès, bienvenue ' . $utilisateur->nom . ' !');
    }

    // Afficher le formulaire email (connexion OTP)
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Afficher le formulaire de vérification OTP
    public function showVerifyForm()
    {
        return view('auth.verify-otp');
    }
       //Pour l'envoi d'un nouveau code
    public function resendOtp(Request $request)
    {
    // 1. Récupérer l'email (depuis le formulaire ou la session)
    $email = $request->input('email') ?? session('email');

    if (!$email) {
        return back()->withErrors(['code' => 'Impossible de renvoyer le code. Session expirée.']);
    }

    // 2. Générer et renvoyer le code d'activation (réutilise ta méthode existante)
    $this->sendActivationOtp($email);

    // 3. Rediriger vers la même page en maintenant l'état 'activating' et l'email
    return back()->with('email', $email)
                 ->with('activating', true)
                 ->with('success_resend', 'Un nouveau code d\'activation vous a été envoyé.');
    }
}