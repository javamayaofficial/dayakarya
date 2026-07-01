<?php

namespace App\Services\Email;

interface EmailSender
{
    public function send(string $to, string $subject, string $htmlBody): bool;
}
