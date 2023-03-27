<?php

namespace App\Models;
use MF\Model\Model;
use App\Connection;

class Carrinho extends Model {

    private $id;
    private $preco;
    private $produtoId;
    private $usuarioId;
    private $quantidade_Produto;

    public function __get($name) {
        return $this->$name;
    }
    public function __set($name,$value ) {
        $this->$name = $value;
    }
    public function inserirCarrinho() {
        $query = "insert into carrinho(preco,produtoId,usuarioId,quantidade_Produto)
        values (:preco,:produtoId,:usuarioId,:quantidade_Produto)";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':preco',$this->__get('preco'));
        $smtm->bindValue(':usuarioId',$this->__get('usuarioId'));
        $smtm->bindValue(':produtoId',$this->__get('produtoId'));
        $smtm->bindValue(':quantidade_Produto',$this->__get('quantidade_Produto'));
        $smtm->execute();
        return $this;
    }
}

?>