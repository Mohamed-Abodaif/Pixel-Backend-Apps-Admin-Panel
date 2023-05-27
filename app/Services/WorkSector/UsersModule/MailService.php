<?php

namespace App\Services\WorkSector\UsersModule;

use SendGrid;

class MailService
{

    public function send($from, $user, $subject, $body)
    {
        $from = new \SendGrid\Mail\From($from);

        $to = new \SendGrid\Mail\To(json_decode($user)->email, json_decode($user)->first_name);

        /* Sent subject of mail */
        $subject = new \SendGrid\Mail\Subject($subject);

        /* Set mail body */
        $htmlContent = new \SendGrid\Mail\HtmlContent($body);

        $email = new \SendGrid\Mail\Mail(
            $from,
            $to,
            $subject,
            null,
            $htmlContent
        );

        /* Create instance of Sendgrid SDK */
        $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

        /* Send mail using sendgrid instance */
        $response = $sendgrid->send($email);

        return  in_array($response->statusCode(), [201, 202]);
    }

    public function sendEmailVerification($from, $user, $subject, $body, $email)
    {

        $from = new \SendGrid\Mail\From($from);

        $to = new \SendGrid\Mail\To($email, json_decode($user)->first_name);

        /* Sent subject of mail */
        // $subject = new \SendGrid\Mail\Subject($subject);

        /* Set mail body */
        $htmlContent = new \SendGrid\Mail\HtmlContent($body);

        $email = new \SendGrid\Mail\Mail(
            $from,
            $to,
            $subject,
            null,
            $htmlContent
        );

        /* Create instance of Sendgrid SDK */
        $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

        /* Send mail using sendgrid instance */
        $response = $sendgrid->send($email);
        return  in_array($response->statusCode(), [201, 202]);
    }
}
