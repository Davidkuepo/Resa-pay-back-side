<?php
namespace App\Mail;

use App\Models\StudentProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $studentProfile;
    public $password;

    public function __construct(StudentProfile $studentProfile, string $password)
    {
        $this->studentProfile = $studentProfile;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Votre compte élève GuimsEduc')
                    ->view('emails.student_account_created'); 
    }
}