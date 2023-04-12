<?php

$mail = new \AAbushinov\MailDumper\Mail();

if ($mail->getHandleParam()) {
    function custom_mail($to, $subject, $message, $additional_headers='', $additional_parameters='')
    {
        CModule::IncludeModule('aabushinov.maildumper');
        $match = [];
        preg_match('/X-EVENT_NAME: (.*)/', $additional_headers, $match);
        $mail = new \AAbushinov\MailDumper\Mail();
        $content = [];
        preg_match_all('/(?<=---------)(.*)(?<=---------)(.*)(?=---------\S+--)/s', $message, $content);

        if (isset($content[2][0])) {
            $message = $content[2][0];
        } else {
            $message = '';
        }

        preg_match_all('/(?<=\?B\?)(.*)(?=\?)/', $subject, $subjectMatch);

        if (isset($subjectMatch[0][0])) {
            $subject = $subjectMatch[0][0];
        } else {
            $subject = '';
        }

        $mail->addMessage($to, $subject, $message, trim($match[1]));

        return true;
    }
}
