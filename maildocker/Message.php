<?php

namespace Maildocker;

class Mail
{
    public function __construct()
    {
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();
        $this->images = array();
        $this->attachments = array();
        $this->merge_vars = array();
    }

    public function set_from($from_email, $from_name = null)
    {
        $this->from = array('email' => $from_email);
        if($from_name) $this->from['name'] = $from_name;
        return $this;
    }

    protected function add_mail($field, $mail, $name = null, $merge_vars)
    {
        if(is_string($mail))
        {
            $mail = array('email' => $mail);
            if($name) $mail['name'] = $name;
            $this->{$field}[] = $mail;
        }
        elseif(is_array($mail))
        {
            foreach($mail as $email) $this->add_mail($field, $email);
        }
    }

    public function add_to($to, $name = null, $merge_vars = null)
    {
        $this->add_mail('to', $to, $name, $merge_vars);
        return $this;
    }

    public function add_cc($cc, $name = null, $merge_vars = null)
    {
        $this->add_mail('cc', $cc, $name, $merge_vars);
        return $this;
    }

    public function add_bcc($bcc, $name = null, $merge_vars = null)
    {
        $this->add_mail('bcc', $bcc, $name, $merge_vars);
        return $this;
    }

    public function set_subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function set_template($template)
    {
        $this->template = $template;
        return $this;
    }

    public function set_text($text)
    {
        $this->text = $text;
        return $this;
    }

    public function set_html($html)
    {
        $this->html = $html;
        return $this;
    }

    public function set_replyto($replyto)
    {
        $this->reply_to = $replyto;
        return $this;
    }

    public function set_date($date)
    {
        $this->date = $date;
        return $this;
    }

    public function add_vars($merge_vars)
    {
        $this->merge_vars = array_merge($this->merge_vars, $merge_vars);
        return $this;
    }

    public function set_headers($headers)
    {
        $this->headers = is_string($headers) ? $headers : json_encode($headers);
        return $this;
    }

    protected function add_file($field, $file)
    {
        if(is_array($file)) $this->{$field}[] = $file;
        else
        {
            $handle = fopen($file, 'rb');
            $this->{$field}[] = array(
                'name' => basename($file),
                'type' => mime_content_type($file),
                'content' => base64_encode(fread($handle, filesize($file)))
            );
        }
    }

    public function add_attachment($attachment)
    {
        if(!is_array($attachment)) $attachment = array($attachment);
        foreach($attachment as $file) $this->add_file('attachments', $file);
        return $this;
    }

    public function add_image($image)
    {
        if(!is_array($image)) $image = array($image);
        foreach($image as $file) $this->add_file('images', $file);
        return $this;
    }
}
