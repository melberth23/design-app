<?php
  
namespace App\Mail;
  
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
  
class DigitalMail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $details;
  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }
  
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $build = $this;
        if(!empty($this->details['fromemail'])) {
            $build->from($this->details['fromemail'], $this->details['fromname']);
        }
        $build->subject($this->details['subject']);
        $build->view('emails.'. $this->details['template']);
        
        return $build;
    }
}