<?php

class Mail
{

    public static function send($to, $subject, $type, $data = array())
    {
        $from = Config::get('email.from');
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: <" . $from . ">\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        $template = file_get_contents(VIEWS_PATH . '/email/' . $type . '.php');

        foreach ($data as $old => $new) {
            $template = str_replace($old, $new, $template);
        }

        $mail = mail($to, $subject, $template, $headers);
        return $mail;
    }

}