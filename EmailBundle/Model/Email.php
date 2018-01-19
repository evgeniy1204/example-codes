<?php

namespace BetaOmega\EmailBundle\Model;

class Email
{
    private $subject;

    private $text;

    private $emails;

    private $template;

    private $attachment;

    private $urlReturn;

    public function __construct()
    {
        $this->emails = [];
        $this->template = "default";
        $this->attachment['path'] = '';
        $this->attachment['files'] = [];
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getUrlReturn()
    {
        return $this->urlReturn;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getEmails(): array
    {
        return $this->emails;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getAttachment()
    {
        return $this->attachment;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    public function setUrlReturn($url)
    {
        $this->urlReturn = $url;

        return $this;
    }

    public function setEmails(array $emails)
    {
        $this->emails = $emails;

        return $this;
    }

    public function addEmail($email)
    {
        $this->emails[] = $email;

        return $this;
    }

    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    public function setAttachment(array $attachment)
    {
        if (key_exists('path', $attachment) && key_exists('files', $attachment)) {
            $this->attachment = $attachment;
        }
        return $this;
    }
}