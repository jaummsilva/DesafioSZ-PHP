<?php

namespace App\Models;

use MF\Model\Model;
use App\Connection;

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

        $header = fgetcsv($data);
        $expected_header = ['id', 'nome', 'preco', 'descricao', 'data_criacao', 'data_alteracao', 'produto_img', 'produto_img_2', 'produto_img_3', 'produto_img_4', 'produto_img_nome', 'produto_img_2_nome', 'produto_img_3_nome', 'produto_img_4_nome'];

        // Valida se o número de colunas do header é o esperado
        if (count($header) !== count($expected_header)) {
            die("Número de colunas inesperado no arquivo CSV. Esperado: " . count($expected_header) . ". Encontrado: " . count($header) . ".");
        }

        // Valida se o header está correto
        if ($header !== $expected_header) {
            die("Header inesperado no arquivo CSV.");
        }

        while ($line = fgetcsv($data)) {
            if ($row > 0) {
                $id = $line[0];

                $select_query = "SELECT COUNT(*) as count FROM produto WHERE id = :id";
                $select_stmt = $this->db->prepare($select_query);
                $select_stmt->bindValue(':id', $id);
                $select_stmt->execute();
                $count = $select_stmt->fetch(\PDO::FETCH_ASSOC)['count'];

                if ($count > 0) {
                    $update_query = "UPDATE produto SET nome = :nome, data_alteracao = :data_alteracao, preco = :preco, descricao = :descricao,
            produto_img = :produto_img, produto_img_2 = :produto_img_2, produto_img_3 = :produto_img_3,
            produto_img_4 = :produto_img_4, produto_img_nome = :produto_img_nome, produto_img_2_nome = :produto_img_2_nome,
            produto_img_3_nome = :produto_img_3_nome, produto_img_4_nome = :produto_img_4_nome WHERE id = :id";
                    $update_stmt = $this->db->prepare($update_query);
                    $update_stmt->bindValue(':data_alteracao', $data_altercao);
                    $update_stmt->bindValue(':nome', $line[1]);
                    $update_stmt->bindValue(':preco', $line[2]);
                    $update_stmt->bindValue(':descricao', $line[3]);
                    $update_stmt->bindValue(':produto_img', $line[6]);
                    $update_stmt->bindValue(':produto_img_2', $line[7]);
                    $update_stmt->bindValue(':produto_img_3', $line[8]);
                    $update_stmt->bindValue(':produto_img_4', $line[9]);
                    $update_stmt->bindValue(':produto_img_nome', $line[10]);
                    $update_stmt->bindValue(':produto_img_2_nome', $line[11]);
                    $update_stmt->bindValue(':produto_img_3_nome', $line[12]);
                    $update_stmt->bindValue(':produto_img_4_nome', $line[13]);
                    $update_stmt->bindValue(':id', $id);
                    $update_stmt->execute();
                } else {
                    $insert_query = "INSERT INTO produto (nome, preco, descricao, produto_img, produto_img_2, produto_img_3,
            produto_img_4, produto_img_nome, produto_img_2_nome, produto_img_3_nome, produto_img_4_nome) VALUES
            (:id, :nome, :preco, :descricao, :produto_img, :produto_img_2, :produto_img_3, :produto_img_4,
            :produto_img_nome, :produto_img_2_nome, :produto_img_3_nome, :produto_img_4_nome)";
                    $insert_stmt = $this->db->prepare($insert_query);
                    $insert_stmt->bindValue(':nome', $line[1]);
                    $insert_stmt->bindValue(':preco', $line[2]);
                    $insert_stmt->bindValue(':descricao', $line[3]);
                    $insert_stmt->bindValue(':produto_img', $line[4]);
                    $insert_stmt->bindValue(':produto_img_2', $line[5]);
                    $insert_stmt->bindValue(':produto_img_3', $line[6]);
                    $insert_stmt->bindValue(':produto_img_4', $line[7]);
                    $insert_stmt->bindValue(':produto_img_nome', $line[8]);
                    $insert_stmt->bindValue(':produto_img_2_nome', substr($line[9], 0, 50));
                    $insert_stmt->bindValue(':produto_img_3_nome', $line[10]);
                    $insert_stmt->bindValue(':produto_img_4_nome', $line[11]);
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
