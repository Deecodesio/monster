<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer;
use DB;

class Mailer extends Controller
{
    public function send()
    {
        $mail_enable = DB::table('settings')->where('key_Word', 'email_enable')->first();
        if ($mail_enable->value == 1) {

            $debug = DB::table('settings')->where('key_word', 'mail_debug')->first();
            $enc = DB::table('settings')->where('key_word', 'mail_encryption')->first();
            $host = DB::table('settings')->where('key_word', 'mail_host')->first();
            $port = DB::table('settings')->where('key_word', 'mail_port')->first();
            $uname = DB::table('settings')->where('key_word', 'mail_username')->first();
            $pass = DB::table('settings')->where('key_word', 'mail_password')->first();
            $m_from = DB::table('settings')->where('key_word', 'mail_from')->first();
            $m_from_name = DB::table('settings')->where('key_word', 'mail_from_name')->first();
            try {

                $mail = new PHPMailer\PHPMailer();
                $mail->SMTPDebug = $this->debug ?? $debug->value ?? 0;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = $enc->value;
                $mail->Host = $host->value;
                $mail->Port = $port->value;
                $mail->IsHTML(true);
                $mail->isSMTP();
                $mail->Username = $uname->value;
                $mail->Password = $pass->value;
                $mail->SetFrom($m_from->value, $m_from_name->value);
                $mail->Subject = $this->subject;
                $mail->Body = $this->body;
                $mail->AddAddress($this->to, $this->toName);

                if (isset($this->addBCC)) {
                    if (is_array($this->addBCC)) {
                        foreach ($this->addBCC as $thisBCC) {
                            $mail->addBCC($thisBCC);
                        }
                    } else {
                        $mail->addBCC($this->addBCC);
                    }
                }
                if ($mail->Send()) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
            }
        } else {
            return true;
        }
    }
}
