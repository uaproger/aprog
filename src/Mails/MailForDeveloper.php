<?php

namespace Aprog\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Aprog Mail
 *
 * --------------------------------------------------------------------------
 *                               MailForDeveloper
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
class MailForDeveloper extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $header;
    public string $content;
    public ?string $mail = null;

    /**
     * @param string $name
     * @param string $header
     * @param string $content
     * @param string|null $mail
     */
    public function __construct(string $name, string $header, string $content, ?string $mail = null)
    {
        $this->name = $name;
        $this->header = $header;
        $this->content = $content;
        $this->mail = $mail;
    }

    /**
     * @return MailForDeveloper
     */
    public function build(): MailForDeveloper
    {
        return $this->from(config('mail.from.address'), $this->name)
            ->to(config('mail.developer', $this->mail))
            ->subject($this->header)
            ->view('emails.for_developer')
            ->with([
                'header' => $this->header,
                'content' => $this->content,
            ]);
    }
}
