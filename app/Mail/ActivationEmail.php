<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ActivationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $activationToken;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $activationToken)
    {
        $this->user = $user;
        $this->activationToken = $activationToken;  // Store the activation token
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.activation')
        ->with([
            'user' => $this->user,
            'activationUrl' => url('/api/activate-account?token=' . $this->activationToken),  // Activation link
        ]);
    }
}
