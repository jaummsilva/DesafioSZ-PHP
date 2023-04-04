<?php

namespace App\Models;
use MF\Model\Model;
use App\Connection;

class Produto extends Model {

    private $id;
    private $nome;
    private $preco;
    private $descricao;
    private $data_criacao;
    private $data_alteracao;
    private $produto_img;
    private $produto_img_2;
    private $produto_img_3;
    private $produto_img_4;

    public function __get($name) {
        return $this->$name;
    }
    public function __set($name,$value ) {
        $this->$name = $value;
    }

    public function cadastrarProduto() {
        $query = "insert into produto(nome,preco,descricao,produto_img,produto_img_2,produto_img_3,produto_img_4)
        values (:nome,:preco,:descricao,:produto_img,:produto_img_2,:produto_img_3,:produto_img_4)";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':nome',$this->__get('nome'));
        $smtm->bindValue(':preco',$this->__get('preco'));
        $smtm->bindValue(':descricao',$this->__get('descricao'));
        $smtm->bindValue(':produto_img',$this->__get('produto_img'));
        $smtm->bindValue(':produto_img_2',$this->__get('produto_img_2'));
        $smtm->bindValue(':produto_img_3',$this->__get('produto_img_3'));
        $smtm->bindValue(':produto_img_4',$this->__get('produto_img_4'));
        $smtm->execute();
        return $this;
    }
    public function validarProduto() {
        $valide = true;

        if(strlen($this->__get('nome')) < 3) {
            return false;
        }
        if(strlen($this->__get('preco')) < 1) {
            return false;
        }
        if(strlen($this->__get('descricao')) < 5) {
            return false;
        }
        if(empty($this->__get('produto_img'))) {
            return false;
        }
        return $valide;
    }
    public function getTodosProdutos() {
        $query = "select * from produto";
        $smtm = $this->db->prepare($query);
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getProdutoPesquisa() {
        $query = "select * from produto where nome like :nome or descricao like :descricao";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':nome',$this->__get('nome'));
        $smtm->bindValue(':descricao',$this->__get('nome'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getProduto() {
        $query = "select * from produto where id = :id";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id',$this->__get('id'));
        $smtm->execute();
        return $smtm->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function editarProduto() {
        $query = "update produto set nome = :nome,
        preco = :preco,
        descricao = :descricao,
        data_alteracao = :data_alteracao,
        produto_img = :produto_img,
        produto_img_2 = :produto_img_2,
        produto_img_3 = :produto_img_3,
        produto_img_4 = :produto_img_4
        where id = :id";
        
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id',$this->__get('id'));
        $smtm->bindValue(':nome',$this->__get('nome'));
        $smtm->bindValue(':preco',$this->__get('preco'));
        $smtm->bindValue(':descricao',$this->__get('descricao'));
        $smtm->bindValue(':data_alteracao',$this->__get('data_alteracao'));
        $smtm->bindValue(':produto_img',$this->__get('produto_img'));
        $smtm->bindValue(':produto_img_2',$this->__get('produto_img_2'));
        $smtm->bindValue(':produto_img_3',$this->__get('produto_img_3'));
        $smtm->bindValue(':produto_img_4',$this->__get('produto_img_4'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function deletarProduto() {
        $query = "delete from produto where id = :id ";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id', $this->__get('id'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
}

?>