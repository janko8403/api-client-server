<?php

namespace Notifications\Mail;

use Mailgun\Mailgun;
use Rollbar\Rollbar;

class MailService
{
    private $sender = null;

    private $bcc = [];

    /**
     * MailService constructor.
     *
     * @param array $config
     */
    public function __construct(private array $config)
    {
    }

    /**
     * Sends an email with Mailgun API.
     *
     * @param string $subject
     * @param string $content
     * @param        $to
     * @param array  $attachments
     * @return bool
     */
    public function send(string $subject, string $content, $to, array $attachments = []): bool
    {
        $to = $this->config['notifications']['catch_all']['email'] ?: (array)$to;

        $params = [
            'from' => $this->sender ?? $this->config['mailgun']['from'],
            'subject' => $subject,
            'to' => implode(',', $to),
            'html' => $content,
        ];

        if (!empty($this->bcc) && is_array($this->bcc)) {
            $params['bcc'] = implode(',', $this->bcc);
        }

        if (!empty($attachments)) {
            $params['attachment'] = [];

            foreach ($attachments as $attachment) {
                $filename = str_replace('/', '_', $attachment['filename']);
                $params['attachment'][] = ['fileContent' => $attachment['body'], 'filename' => $filename];
            }
        }

        try {
            $mg = Mailgun::create($this->config['mailgun']['key'], $this->config['mailgun']['endpoint']);
            $mg->messages()->send($this->config['mailgun']['domain'], $params);

            return true;
        } catch (\Exception $e) {
            Rollbar::error($e);

            return false;
        }
    }
}