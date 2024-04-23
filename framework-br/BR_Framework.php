<?php
include "Config_Framework_DataBase.php";
include "Config_Framework_Geral.php";
require_once '../vendor/autoload.php';
use Ramsey\Uuid\Uuid;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class BR_Framework {
    private static $instance;
    private $config;

    private function __construct() {
        $this->config = include "Config_Framework_DataBase.php";
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConfig($key) {
        return $this->config[$key] ?? null;
    }

    public function vardumpsession() {
        var_dump($_SESSION);
    }

    public function ip() {
        echo $_SERVER['REMOTE_ADDR'];
    }

    public function conexaobd() {
        $server = $this->getConfig('server');
        $user = $this->getConfig('user');
        $senha = $this->getConfig('senha');
        $bd = $this->getConfig('bd');

        $conn = new mysqli($server, $user, $senha, $bd);
        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        } else {
            echo "Conexão bem-sucedida!";
        }
    }

    public function authuser() {
        if (!isset($_SESSION['user'])) {
            echo 'Você não está conectado';
        } else {
            echo 'Você está conectado como ' . $_SESSION['user'];
        }
    }

    public function echovar() {
        echo $this->getConfig('config_var');

    }

        public function sessionstart() {
         session_start();

    }

    public function sessionkill() {
        session_destroy();

    }

    public function uid() {
        $uuid = Uuid::uuid4();
        echo $uuid->toString();

    }
    public function browser() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        echo $user_agent;

    }

    public function location() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $api_url = "http://ip-api.com/json/$ip";
        $response = file_get_contents($api_url);
        $data = json_decode($response, true);
        if ($data && $data['status'] == 'success') {
            $loc = $data['city'] . ', ' . $data['regionName'] . ', ' . $data['country'];
            echo $loc;
        } else {
            echo "Não foi possível obter a localização do usuário.";
        }
    }
    public function memory() {
        $memory_usage = memory_get_usage(true);
        echo $memory_usage;
    }

    public function logout() {
        session_start();
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: $location_logout");
    }
    public function validateemail(){
        $email = "examp287676343567777e@exam2323ple.com";
        $validator = new EmailValidator();
        if ($validator->isValid($email, new RFCValidation())) {
            echo 'O endereço de e-mail é válido.';
        } else {
            echo 'O endereço de e-mail é inválido.';
        }
    }
    public function sendemail(){
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.titan.email'; // Endereço do servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'suporte@brazilarc.com.br'; // Seu endereço de e-mail
            $mail->Password = 'brgust123'; // Sua senha de e-mail
            $mail->SMTPSecure = 'SSL/TLS';
            $mail->Port = 465;


            $mail->setFrom('suporte@brazilarc.com.br', 'Brazil Arc');
            $mail->addAddress('brgustavo648@gmail.com', 'Nome do Destinatário');


            $mail->Subject = 'Assunto do E-mail';
            $mail->Body = 'Corpo do E-mail';


            $mail->send();
            echo 'E-mail enviado com sucesso!';
        } catch (Exception $e) {
            echo 'Erro ao enviar e-mail: ' . $mail->ErrorInfo;
        }
    }
}



function br() {
    return BR_Framework::getInstance();
}
?>
