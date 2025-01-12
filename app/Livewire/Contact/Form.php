<?php

namespace App\Livewire\Contact;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class Form extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';
    public $consent = false;

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'email' => 'required|email|max:100',
        'subject' => 'required',
        'message' => 'required|min:10|max:1000',
        'consent' => 'accepted'
    ];

    protected $messages = [
        'name.required' => 'Le nom est obligatoire',
        'name.min' => 'Le nom doit contenir au moins 2 caractères',
        'name.max' => 'Le nom ne peut pas dépasser 100 caractères',
        'email.required' => 'L\'email est obligatoire',
        'email.email' => 'L\'email n\'est pas valide',
        'email.max' => 'L\'email ne peut pas dépasser 100 caractères',
        'subject.required' => 'Le sujet est obligatoire',
        'message.required' => 'Le message est obligatoire',
        'message.min' => 'Le message doit contenir au moins 10 caractères',
        'message.max' => 'Le message ne peut pas dépasser 1000 caractères',
        'consent.accepted' => 'Vous devez accepter le traitement de vos données'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        $validatedData = $this->validate();

        // Envoi de l'email
        try {
            Mail::to('contact@lagrottedesgeeks.fr')
                ->send(new \App\Mail\Contact($validatedData));

            session()->flash('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
            
            // Réinitialisation du formulaire
            $this->reset(['name', 'email', 'subject', 'message', 'consent']);
        } catch (\Exception $e) {
            session()->flash('error', 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard.');
        }
    }

    public function render()
    {
        return view('livewire.contact.form');
    }
}
