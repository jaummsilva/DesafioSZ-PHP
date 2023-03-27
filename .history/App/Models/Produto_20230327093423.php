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
    private $imagemId;

    public function __get($name) {
        return $this->$name;
    }
    public function __set($name,$value ) {
        $this->$name = $value;
    }

    public function cadastrarProduto() {
        $query = "insert into produto(nome,preco,descricao,imagem_Id)
        values (:nome,:preco,:descricao,:imagemId)";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':nome',$this->__get('nome'));
        $smtm->bindValue(':preco',$this->__get('preco'));
        $smtm->bindValue(':descricao',$this->__get('descricao'));
        $smtm->bindValue(':imagemId',$this->__get('imagemId'));
        $smtm->execute();
        return $this;
    }
    public function validarProduto() {
        $valide = true;

        if(strlen($this->__get('nome')) < 3) {
            return false;
        }
        if(strlen($this->__get('preco')) < 5) {
            return false;
        }
        if(strlen($this->__get('descricao')) < 10) {
            return false;
        }
        return $valide;
    }
}

?>