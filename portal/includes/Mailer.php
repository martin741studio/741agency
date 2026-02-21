<?php
// includes/Mailer.php

class Mailer
{
    private $host = 'smtp.gmail.com';
    private $port = 587;
    private $username;
    private $password;
    private $debug = false;

    public function __construct()
    {
        $config = require __DIR__ . '/../mail_config.php';
        $this->username = $config['username'];
        $this->password = $config['password'];
    }

    public function send($to, $subject, $body)
    {
        try {
            // Create context with SSL verification disabled
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]);

            $socket = stream_socket_client(
                "tcp://{$this->host}:{$this->port}",
                $errno,
                $errstr,
                30,
                STREAM_CLIENT_CONNECT,
                $context
            );

            if (!$socket)
                throw new Exception("Could not connect to SMTP host: $errstr");

            $this->read($socket); // Greeting

            $this->cmd($socket, 'EHLO ' . gethostname());
            $this->cmd($socket, 'STARTTLS');

            // Upgrade to TLS using the SAME socket/context
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT)) {
                throw new Exception("TLS Handshake Failed");
            }

            $this->cmd($socket, 'EHLO ' . gethostname());

            // Auth
            $this->cmd($socket, 'AUTH LOGIN');
            $this->cmd($socket, base64_encode($this->username));
            $this->cmd($socket, base64_encode($this->password));

            // Mail
            $this->cmd($socket, "MAIL FROM: <{$this->username}>");
            $this->cmd($socket, "RCPT TO: <$to>");
            $this->cmd($socket, 'DATA');

            // Headers & Body
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: 741 Portal <{$this->username}>\r\n";
            $headers .= "To: $to\r\n";
            $headers .= "Subject: $subject\r\n";

            $this->cmd($socket, "$headers\r\n$body\r\n.");
            $this->cmd($socket, 'QUIT');

            fclose($socket);
            return true;

        } catch (Exception $e) {
            error_log("SMTP Error: " . $e->getMessage());
            echo "DEBUG SMTP:P " . $e->getMessage();
            return false;
        }
    }

    private function cmd($socket, $cmd)
    {
        fwrite($socket, $cmd . "\r\n");
        $response = $this->read($socket);
        if ($this->debug)
            echo "CMD: $cmd\nRESP: $response\n";

        // Check for error codes (4xx or 5xx)
        if ((int) substr($response, 0, 3) >= 400) {
            throw new Exception("SMTP Command '$cmd' failed: $response");
        }
        return $response;
    }

    private function read($socket)
    {
        $response = '';
        while ($str = fgets($socket, 515)) {
            $response .= $str;
            if (substr($str, 3, 1) == ' ')
                break;
        }
        return $response;
    }
}
?>