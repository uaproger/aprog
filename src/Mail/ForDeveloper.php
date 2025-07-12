<?php

namespace Aprog\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForDeveloper extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $header;
    public string $content;
    public ?string $mail = null;

    public function __construct(string $name, string $header, string $content, string $mail = null)
    {
        $this->name = $name;
        $this->header = $header;
        $this->content = $content;
        $this->mail = $mail;
    }

    public function build(): ForDeveloper
    {
        return $this->from(config('mail.from.address'), $this->name)
            ->to(config('mail.developer', $this->mail))
            ->subject($this->header)
            ->view('emails.error_log')
            ->with([
                'header' => $this->header,
                'content' => $this->content,
            ]);
    }
}
