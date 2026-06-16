<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $destinataire = $request->query('to');
        return view('auth.contact', compact('destinataire'));
    }

    public function send(Request $request)
    {
        if (!session('utilisateur_id')) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Vous devez être connecté pour envoyer un message.']);
        }

        $request->validate([
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        $nomExpediteur = trim(session('utilisateur_nom', '') . ' ' . session('utilisateur_prenom', ''));

        Message::create([
            'utilisateur_idutilisateur' => session('utilisateur_id'),
            'email'                     => $request->email,
            'destinataire'              => $request->destinataire,
            'expediteur'                => $nomExpediteur,
            'contenu'                   => $request->message,
            'date_envoi'                => now(),
            'lu'                        => 0,
        ]);

        return redirect()->route('home')->with('success', 'Message envoyé avec succès !');
    }
    public function reply(Request $request)
    {
        if (!session('utilisateur_id')) {
            return redirect()->route('login');
        }

        $request->validate([
            'destinataire' => 'required|string',
            'email'        => 'required|email',
            'message'      => 'required|string',
        ]);

        $nomExpediteur = trim(session('utilisateur_nom', '') . ' ' . session('utilisateur_prenom', ''));

        Message::create([
            'utilisateur_idutilisateur' => session('utilisateur_id'),
            'email'                     => session('utilisateur_email'),
            'destinataire'              => $request->destinataire,
            'expediteur'                => $nomExpediteur,
            'contenu'                   => $request->message,
            'date_envoi'                => now(),
            'lu'                        => 0,
        ]);

        return redirect()->back()->with('success', 'Réponse envoyée !');
    }
}