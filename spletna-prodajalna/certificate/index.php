
        <?php
        require_once("index.php");
        session_start();
        require_once('ViewHelper.php');
        $client_cert = filter_input(INPUT_SERVER, "SSL_CLIENT_CERT");
        
        $cert_data = openssl_x509_parse($client_cert);
        if ($cert_data !== FALSE) {
            $commonname = $cert_data['subject']['CN'];
        }
        if ($commonname == $_SESSION["user"]) {
            if($commonname == "admin") {                    
                ViewHelper::redirect("admin");

            } else if($commonname == "prodajalec") {
                ViewHelper::redirect("prodajalec");

            }
            header("Location: ../admin");

        } else {
            ViewHelper::redirect(BASE_URL . "login");
        }
        
        
        