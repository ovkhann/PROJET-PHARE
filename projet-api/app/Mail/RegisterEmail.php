<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
        //   
    }

    public function build()
    {
        return $this->subject('Vérifie ton adresse email')
            ->view('email-register', [
                'user' => $this->user
            ]);
    }


    /**
     * Sujet + autres infos d’en-tête
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Confirmation d’inscription à Revolve Realm'
    //     );
    // }

    /**
     * Vue à utiliser pour le mail
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'email-register',
    //         with: [
    //             'user' => $this->user
    //         ]
    //     );
    // }


    /**
     * Si tu veux ajouter des fichiers joints plus tard
     */
    public function attachments(): array
    {
        return [];
    }
}
