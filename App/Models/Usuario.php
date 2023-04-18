<?php
namespace App\Models;

use MF\Model\Model;
use App\Connection;

class Usuario extends Model {
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $data_criacao;
    private $data_alteracao;
    private $data_nascimento;
    private $telefone;
    private $usuario_img;
    private $usuario_img_nome;

    public function __get($name) {
        return $this->$name;
    }
    public function __set($name,$value ) {
        $this->$name = $value;
    }

    public function importarUsuario()
    {
        $csv = $_FILES['arquivoUsuarioImportacao'];
        $data = fopen($csv['tmp_name'], 'r');
        $row = 0;
        $data_altercao = (date('Y-m-d H:i:s'));
        $expected_header = ['id', 'nome','email', 'telefone','data_nascimento','usuario_img','senha'];
        while ($line = fgetcsv($data, 0, ";")) {
            if ($row++ == 0) {
                if (count($line) !== count($expected_header)) {
                    die();
                }
                if ($line !== $expected_header) {
                    die();
                }
                continue;
            }
            $id = $line[0];
            $nome = $line[1];
            $img = $line[5];
            $senha = $line[6];
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $line[5],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json"
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $picname = "picture" . date('YmdHis') . '.png';
                $filePath = './usuarios/' . $picname;
                $fp = fopen($filePath, 'x');
                if (!$fp) {
                    echo "Failed to open file $filePath";
                } else {
                    fwrite($fp, $response);
                    fclose($fp);
                    echo "File saved to $filePath";
                }
            if ($row > 0) {
                $select_query = "SELECT COUNT(*) as count FROM usuario WHERE id = :id";
                $select_stmt = $this->db->prepare($select_query);
                $select_stmt->bindValue(':id', $id);
                $select_stmt->execute();
                $count = $select_stmt->fetch(\PDO::FETCH_ASSOC)['count'];

                $select_query_2 = "SELECT id FROM usuario WHERE nome = :nome";
                $select_stmt_2 = $this->db->prepare($select_query_2);
                $select_stmt_2->bindValue(':nome', $nome);
                $select_stmt_2->execute();
                $id = $select_stmt_2->fetch(\PDO::FETCH_ASSOC)['id'];
 
                if((!$count > 0 || $id ) && empty($img)) {
                    continue; 
                }
                if(empty($senha) && empty($img) && !$count > 0) {
                    continue; 
                }
                if (($count > 0 || $id)) {
                    $nome = "";
                    if ($line[1] != null) {
                        $nome = "nome = :nome,";
                    }
                    $email = "";
                    if ($line[2] != null) {
                        $email = "email = :email,";
                    }
                    $telefone = "";
                    if ($line[3] != null) {
                        $telefone = "telefone = :telefone,";
                    }
                    $data_nascimento = "";
                    if ($line[4] != null) {
                        $data_nascimento = "data_nascimento = :data_nascimento,";
                    }
                    $usuario_img = "";
                    $usuario_img_nome = "";
                    if ($line[5] != null) {
                        $usuario_img = "usuario_img = :usuario_img,";
                        $usuario_img_nome = "usuario_img_nome = :usuario_img_nome,";
                    }
                    $senha = "";
                    if ($line[6] != null) {
                        $senha = "senha = :senha";
                    }
                    $update_query = "UPDATE usuario SET data_alteracao = :data_alteracao, $nome $telefone $email $data_nascimento
                                    $usuario_img  $usuario_img_nome $senha
                                    WHERE id = :id";
                    
                    $update_stmt = $this->db->prepare($update_query);
                    $update_stmt->bindValue(':data_alteracao', $data_altercao);
                    if ($line[1] != null) {
                        $update_stmt->bindValue(':nome', $line[1]);
                    }
                    if ($line[2] != null) {
                        $update_stmt->bindValue(':email', $line[2]);
                    }
                    if ($line[3] != null) {
                        $update_stmt->bindValue(':telefone', $line[3]);
                    }
                    if ($line[4] != null) {
                        $update_stmt->bindValue(':data_nascimento', date('Y-m-d',strtotime($line[4])));
                    }
                    if ($line[5] != null) {
                        $update_stmt->bindValue(':usuario_img', $filePath);
                        $update_stmt->bindValue(':usuario_img_nome', $picname);
                    }
                    if ($line[6] != null) {
                        $update_stmt->bindValue(':senha', md5($line[6]));
                    }
                    $update_stmt->bindValue(':id', $id);
                    $update_stmt->execute();
                } else {
                    
                    $insert_query = "INSERT INTO usuario (nome, email, telefone,data_nascimento, usuario_img, usuario_img_nome,senha) VALUES
            (:nome, :email, :telefone,:telefone, :usuario_img, :usuario_img_nome,:senha)";
                    $insert_stmt = $this->db->prepare($insert_query);
                    $insert_stmt->bindValue(':nome', $line[1]);
                    $insert_stmt->bindValue(':email', $line[2]);
                    $insert_stmt->bindValue(':telefone', $line[3]);
                    $insert_stmt->bindValue(':data_nascimento', $line[4]);
                    $insert_stmt->bindValue(':usuario_img_nome', $picname);
                    $insert_stmt->bindValue(':usuario_img', $filePath);
                    $insert_stmt->bindValue(':senha', md5($line[6]));
                    $insert_stmt->execute();
                } 
            }
            $row++;
        }
    }

    public function cadastrarUsuario() {
        $query = "insert into usuario(nome,email,senha,data_nascimento,telefone,usuario_img,usuario_img_nome)
        values (:nome,:email,:senha,:data_nascimento,:telefone,:usuario_img,:usuario_img_nome)";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':nome',$this->__get('nome'));
        $smtm->bindValue(':email',$this->__get('email'));
        $smtm->bindValue(':senha',$this->__get('senha'));
        $smtm->bindValue(':data_nascimento',$this->__get('data_nascimento'));
        $smtm->bindValue(':usuario_img',$this->__get('usuario_img'));
        $smtm->bindValue(':usuario_img_nome',$this->__get('usuario_img_nome'));
        $smtm->bindValue(':telefone',$this->__get('telefone'));
        $smtm->execute();
        return $this;
    }
    public function validarCadastro() {
        $valide = true;

        if(strlen($this->__get('nome')) < 3) {
            return false;
        }
        if(strlen($this->__get('email')) < 3) {
            return false;
        }
        if(strlen($this->__get('senha')) < 3) {
            return false;
        }
        return $valide;
    }
    public function autenticar() {
        $query = "select id, nome, email from usuario where email = :email AND senha = :senha";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':email',$this->__get('email'));
        $smtm->bindValue(':senha',$this->__get('senha'));
        $smtm->execute();

        $usuario = $smtm->fetch(\PDO::FETCH_ASSOC);
        if(!empty($usuario['id']) && (!empty($usuario['nome']))) {
            $this->__set('id', $usuario['id']);
            $this->__set('nome', $usuario['nome']);
        }
        return $this;
    }
    public function getUsuario() {
        $query = "select * from usuario where id = :id";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id',$this->__get('id'));
        $smtm->execute();
        return $smtm->fetch(\PDO::FETCH_ASSOC);
    }
    public function getTodosUsuariosExportacao()
    {
        $query = "select id,nome,email,telefone,data_nascimento,usuario_img from usuario";
        $smtm = $this->db->prepare($query);
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getTodosUsuarios() {
        $query = "select * from usuario";
        $smtm = $this->db->prepare($query);
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getTodosUsuariosEmail($email) {
        $query = "select email from usuario where email = :email";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':email',$email);
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function editarUsuario() {
        $img = "";
        $imgNome = "";
        if($this->__get('usuario_img') != "") {
            $img = "usuario_img = :usuario_img,";
            $imgNome = "usuario_img_nome = :usuario_img_nome,";
        }
        $query = "update usuario set nome = :nome,
        $img
        $imgNome
        email = :email,
        senha = :senha,
        telefone = :telefone,
        data_nascimento = :data_nascimento,
        data_alteracao = :data_alteracao
        where id = :id;";
        
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id',$this->__get('id'));
        $smtm->bindValue(':nome',$this->__get('nome'));
        if($img != "" && $imgNome != '') {
            $smtm->bindValue(':usuario_img', $this->__get('usuario_img'));
            $smtm->bindValue(':usuario_img_nome', $this->__get('usuario_img_nome'));
        }
        $smtm->bindValue(':email',$this->__get('email'));
        $smtm->bindValue(':senha',$this->__get('senha'));
        $smtm->bindValue(':data_nascimento',$this->__get('data_nascimento'));
        $smtm->bindValue(':data_alteracao',$this->__get('data_alteracao'));
        $smtm->bindValue(':telefone',$this->__get('telefone'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function deletarUsuario() {
        $query = "delete from usuario where id = :id ";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id', $this->__get('id'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }

}

?>