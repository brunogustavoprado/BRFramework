<?php

class BR_Framework {
    private static $instance;
    private $config = [
        'server' => 'localhost',
        'user' => 'root',
        'senha' => '',
        'bd' => '',
        'config_var' => 'valor_da_config'
    ];

    private function __construct() {  }

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
}

function br() {
    return BR_Framework::getInstance();
}

?>
