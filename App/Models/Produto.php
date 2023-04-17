<?php

namespace App\Models;

use MF\Model\Model;
use App\Connection;
use MF\Controller\Action;
use MF\Model\Container;

class Produto extends Model
{
    private $id;
    private $nome;
    private $preco;
    private $descricao;
    private $data_criacao;
    private $data_alteracao;
    private $produto_img;
    private $produto_img_nome;
    private $produto_img_2;
    private $produto_img_2_nome;
    private $produto_img_3;
    private $produto_img_3_nome;
    private $produto_img_4;
    private $produto_img_4_nome;

    public function __get($name)
    {
        return $this->$name;
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function importarProduto()
    {
        $csv = $_FILES['arquivoProdutoImportacao'];
        $data = fopen($csv['tmp_name'], 'r');
        $row = 0;
        $data_altercao = (date('Y-m-d H:i:s'));
        $expected_header = ['id', 'nome', 'preco', 'descricao', 'produto_img', 'produto_img_2', 'produto_img_3', 'produto_img_4'];
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
            if(empty($line[4])) {
                die();
            }
            $id = $line[0];
            $nome = $line[1];
            for ($i = 4; $i <= 7; $i++) {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $line[$i],
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
                $picname[$i] = "picture" . $i . date('YmdHis') . '.png';
                $filePath[$i] = './img/' . $picname[$i];
                $fp = fopen($filePath[$i], 'x');
                if (!$fp) {
                    echo "Failed to open file $filePath[$i]";
                } else {
                    fwrite($fp, $response);
                    fclose($fp);
                    echo "File saved to $filePath[$i]";
                }
            }
            if ($row > 0) {
                $select_query = "SELECT COUNT(*) as count FROM produto WHERE id = :id";
                $select_stmt = $this->db->prepare($select_query);
                $select_stmt->bindValue(':id', $id);
                $select_stmt->execute();
                $count = $select_stmt->fetch(\PDO::FETCH_ASSOC)['count'];

                $select_query_2 = "SELECT id FROM produto WHERE nome = :nome";
                $select_stmt_2 = $this->db->prepare($select_query_2);
                $select_stmt_2->bindValue(':nome', $nome);
                $select_stmt_2->execute();
                $id = $select_stmt_2->fetch(\PDO::FETCH_ASSOC)['id'];

                if ($count > 0 || $id) {
                    $nome = "";
                    if ($line[1] != null) {
                        $nome = "nome = :nome,";
                    }
                    $preco = "";
                    if ($line[2] != null) {
                        $preco = "preco = :preco,";
                    }
                    $descricao = "";
                    if ($line[3] != null) {
                        $descricao = "descricao = :descricao,";
                    }
                    $produto_img = "";
                    $produto_img_nome = "";
                    if ($line[4] != null) {
                        $produto_img = "produto_img = :produto_img,";
                        $produto_img_nome = "produto_img_nome = :produto_img_nome,";
                    }
                    $produto_img_2 = "";
                    $produto_img_2_nome = "";
                    if ($line[5] != null) {
                        $produto_img_2 = "produto_img_2 = :produto_img_2,";
                        $produto_img_2_nome = "produto_img_2_nome = :produto_img_2_nome,";
                    }
                    $produto_img_3 = "";
                    $produto_img_3_nome = "";
                    if ($line[6] != null) {
                        $produto_img_3 = "produto_img_3 = :produto_img_3,";
                        $produto_img_3_nome = "produto_img_3_nome = :produto_img_3_nome,";
                    }
                    $produto_img_4 = "";
                    $produto_img_4_nome = "";
                    if ($line[7] != null) {
                        $produto_img_4_nome = "produto_img_4_nome = :produto_img_4_nome";
                        $produto_img_4 = "produto_img_4 = :produto_img_4,";
                    }
                    
                    $update_query = "UPDATE produto SET data_alteracao = :data_alteracao, $nome $preco $descricao
                                    $produto_img  $produto_img_2 $produto_img_3
                                    $produto_img_4 $produto_img_nome $produto_img_2_nome $produto_img_3_nome $produto_img_4_nome WHERE id = :id";
                    
                    $update_stmt = $this->db->prepare($update_query);
                    $update_stmt->bindValue(':data_alteracao', $data_altercao);
                    if ($line[1] != null) {
                        $update_stmt->bindValue(':nome', $line[1]);
                    }
                    if ($line[2] != null) {
                        $update_stmt->bindValue(':preco', $line[2]);
                    }
                    if ($line[3] != null) {
                        $update_stmt->bindValue(':descricao', $line[3]);
                    }
                    if ($line[4] != null) {
                        $update_stmt->bindValue(':produto_img', $filePath[4]);
                        $update_stmt->bindValue(':produto_img_nome', $picname[4]);
                    }
                    if ($line[5] != null) {
                        $update_stmt->bindValue(':produto_img_2', $filePath[5]);
                        $update_stmt->bindValue(':produto_img_2_nome', $picname[5]);
                    }
                    if ($line[6] != null) {
                        $update_stmt->bindValue(':produto_img_3', $filePath[6]);
                        $update_stmt->bindValue(':produto_img_3_nome', $picname[6]);
                    }
                    if ($line[7] != null) {
                        $update_stmt->bindValue(':produto_img_4', $filePath[7]);
                        $update_stmt->bindValue(':produto_img_4_nome', $picname[7]);
                    }
                    $update_stmt->bindValue(':id', $id);
                    $update_stmt->execute();
                } else {
                    $produto_img_2 = "";
                    $produto_img_2_2 = "";
                    $produto_img_2_nome = "";
                    $produto_img_2_2_nome = "";
                    if ($line[5] != null) {
                        $produto_img_2 = "produto_img_2,";
                        $produto_img_2_2 = ":produto_img_2,";
                        $produto_img_2_nome = "produto_img_2_nome,";
                        $produto_img_2_2_nome = ":produto_img_2_nome,";
                    }
                    $produto_img_3 = "";
                    $produto_img_3_3 = "";
                    $produto_img_3_nome = "";
                    $produto_img_3_3_nome = "";
                    if ($line[6] != null) {
                        $produto_img_3 = "produto_img_3,";
                        $produto_img_3_3 = ":produto_img_3,";
                        $produto_img_3_nome = "produto_img_3_nome,";
                        $produto_img_3_3_nome = ":produto_img_3_nome,";
                    }
                    $produto_img_4 = "";
                    $produto_img_4_4 = "";
                    $produto_img_4_nome = "";
                    $produto_img_4_4_nome = "";
                    if ($line[7] != null) {
                        $produto_img_4 = "produto_img_4,";
                        $produto_img_4_4 = ":produto_img_4,";
                        $produto_img_4_nome = "produto_img_4_nome";
                        $produto_img_4_4_nome = ":produto_img_4_nome,";
                    }
                    $insert_query = "INSERT INTO produto (nome, preco, descricao, produto_img, $produto_img_2 $produto_img_3
            $produto_img_4 $produto_img_2_nome $produto_img_3_nome $produto_img_4_nome produto_img_nome) VALUES
            (:nome, :preco, :descricao, :produto_img, $produto_img_2_2  $produto_img_3_3  $produto_img_4_4 
             $produto_img_2_2_nome $produto_img_3_3_nome $produto_img_4_4_nome :produto_img_nome)";
                    $insert_stmt = $this->db->prepare($insert_query);
                    $insert_stmt->bindValue(':nome', $line[1]);
                    $insert_stmt->bindValue(':preco', $line[2]);
                    $insert_stmt->bindValue(':descricao', $line[3]);
                    $insert_stmt->bindValue(':produto_img', $filePath[4]);
                    if ($line[5] != null) {
                        $insert_stmt->bindValue(':produto_img_2', $filePath[5]);
                        $insert_stmt->bindValue(':produto_img_2_nome', $picname[5]);
                    }
                    if ($line[6] != null) {
                        $insert_stmt->bindValue(':produto_img_3', $filePath[6]);
                        $insert_stmt->bindValue(':produto_img_3_nome', $picname[6]);
                    }
                    if ($line[7] != null) {
                        $insert_stmt->bindValue(':produto_img_4', $filePath[7]);
                        $insert_stmt->bindValue(':produto_img_4_nome', $picname[7]);
                    }
                    $insert_stmt->bindValue(':produto_img_nome', $picname[4]);
                    $insert_stmt->execute();
                } 
            }
            $row++;
        }
    }
    public function cadastrarProduto()
    {


        $img2 = "";
        $img2_2 = "";
        $imgNome2 = "";
        $imgNome2_2 = "";
        if ($this->__get('produto_img_2') != "") {
            $img2 = "produto_img_2,";
            $img2_2 = ":produto_img_2,";
            $imgNome2 = "produto_img_2_nome,";
            $imgNome2_2 = ":produto_img_2_nome,";
        }
        $img3 = "";
        $img3_3 = "";
        $imgNome3 = "";
        $imgNome3_3 = "";
        if ($this->__get('produto_img_3') != "") {
            $img3 = "produto_img_3,";
            $img3_3 = ":produto_img_3,";
            $imgNome3 = "produto_img_3_nome,";
            $imgNome3_3 = ":produto_img_3_nome,";
        }
        $img4 = "";
        $img4_4 = "";
        $imgNome4 = "";
        $imgNome4_4 = "";
        if ($this->__get('produto_img_4') != "") {
            $img4 = "produto_img_4,";
            $img4_4 = ":produto_img_4,";
            $imgNome4 = "produto_img_4_nome,";
            $imgNome4_4 = ":produto_img_4_nome,";
        }

        $query = "insert into produto
        (nome,preco,descricao,produto_img,
        $img2
        $img3
        $img4
        $imgNome2
        $imgNome3
        $imgNome4
        produto_img_nome)
        values 
        (:nome,:preco,:descricao,:produto_img,
        $img2_2
        $img3_3
        $img4_4
        $imgNome2_2
        $imgNome3_3
        $imgNome4_4
        :produto_img_nome);";

        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':nome', $this->__get('nome'));
        $smtm->bindValue(':preco', $this->__get('preco'));
        $smtm->bindValue(':descricao', $this->__get('descricao'));
        $smtm->bindValue(':produto_img', $this->__get('produto_img'));
        $smtm->bindValue(':produto_img_nome', $this->__get('produto_img_nome'));
        if ($img2 != "" && $imgNome2 != '') {
            $smtm->bindValue(':produto_img_2', $this->__get('produto_img_2'));
            $smtm->bindValue(':produto_img_2_nome', $this->__get('produto_img_2_nome'));
        }
        if ($img3 != "" && $imgNome3 != '') {
            $smtm->bindValue(':produto_img_3', $this->__get('produto_img_3'));
            $smtm->bindValue(':produto_img_3_nome', $this->__get('produto_img_3_nome'));
        }
        if ($img4 != "" && $imgNome4 != '') {
            $smtm->bindValue(':produto_img_4', $this->__get('produto_img_4'));
            $smtm->bindValue(':produto_img_4_nome', $this->__get('produto_img_4_nome'));
        }
        $smtm->execute();
        return $this;
    }
    public function validarProduto()
    {
        $valide = true;

        if (strlen($this->__get('nome')) < 3) {
            return false;
        }
        if (strlen($this->__get('preco')) < 1) {
            return false;
        }
        if (strlen($this->__get('descricao')) < 5) {
            return false;
        }
        return $valide;
    }
    public function getTodosProdutos()
    {
        $query = "select * from produto";
        $smtm = $this->db->prepare($query);
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getTodosProdutosExportacao()
    {
        $query = "select id,nome,preco,descricao,produto_img,produto_img_2,produto_img_3,produto_img_4 from produto";
        $smtm = $this->db->prepare($query);
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getProdutoPesquisa()
    {
        $query = "select * from produto where nome like :nome or descricao like :descricao";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':nome', '%' . $this->__get('nome') . '%');
        $smtm->bindValue(':descricao', '%' . $this->__get('descricao') . '%');
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getProduto()
    {
        $query = "select * from produto where id = :id";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id', $this->__get('id'));
        $smtm->execute();
        return $smtm->fetch(\PDO::FETCH_ASSOC);
    }

    public function editarProduto()
    {
        $img = "";
        $imgNome = "";
        if ($this->__get('produto_img') != "") {
            $img = "produto_img = :produto_img,";
            $imgNome = "produto_img_nome = :produto_img_nome,";
        }
        $img2 = "";
        $imgNome2 = "";
        if ($this->__get('produto_img_2') != "") {
            $img2 = "produto_img_2 = :produto_img_2,";
            $imgNome2 = "produto_img_2_nome = :produto_img_2_nome,";
        }
        $img3 = "";
        $imgNome3 = "";
        if ($this->__get('produto_img_3') != "") {
            $img3 = "produto_img_3 = :produto_img_3,";
            $imgNome3 = "produto_img_3_nome = :produto_img_3_nome,";
        }
        $img4 = "";
        $imgNome4 = "";
        if ($this->__get('produto_img_4') != "") {
            $img4 = "produto_img_4 = :produto_img_4,";
            $imgNome4 = "produto_img_4_nome = :produto_img_4_nome,";
        }

        $query = "update produto set nome = :nome,
        preco = :preco,
        descricao = :descricao,
        $img
        $imgNome
        $img2
        $imgNome2
        $img3
        $imgNome3
        $img4
        $imgNome4
        data_alteracao = :data_alteracao
        where id = :id";

        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id', $this->__get('id'));
        $smtm->bindValue(':nome', $this->__get('nome'));
        $smtm->bindValue(':preco', $this->__get('preco'));
        $smtm->bindValue(':descricao', $this->__get('descricao'));
        $smtm->bindValue(':data_alteracao', $this->__get('data_alteracao'));
        if ($img != "" && $imgNome != '') {
            $smtm->bindValue(':produto_img', $this->__get('produto_img'));
            $smtm->bindValue(':produto_img_nome', $this->__get('produto_img_nome'));
        }
        if ($img2 != "" && $imgNome2 != '') {
            $smtm->bindValue(':produto_img_2', $this->__get('produto_img_2'));
            $smtm->bindValue(':produto_img_2_nome', $this->__get('produto_img_2_nome'));
        }
        if ($img3 != "" && $imgNome3 != '') {
            $smtm->bindValue(':produto_img_3', $this->__get('produto_img_3'));
            $smtm->bindValue(':produto_img_3_nome', $this->__get('produto_img_3_nome'));
        }
        if ($img4 != "" && $imgNome4 != '') {
            $smtm->bindValue(':produto_img_4', $this->__get('produto_img_4'));
            $smtm->bindValue(':produto_img_4_nome', $this->__get('produto_img_4_nome'));
        }
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function deletarProduto()
    {
        $query = "delete from produto where id = :id ";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id', $this->__get('id'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
}
