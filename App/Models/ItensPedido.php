<?php

namespace App\Models;
use MF\Model\Model;
use App\Connection;

class ItensPedido extends Model {

    private $id;
    private $produtoId;
    private $pedidoId;
    private $data_criacao;
    private $data_alteracao;
    private $quantidade_produto;
    private $preco_por_produto;

    public function __get($name) {
        return $this->$name;
    }
    public function __set($name,$value ) {
        $this->$name = $value;
    }

    public function cadastrarItensPedido() {
        $query = "insert into itenspedido(produto_id,pedido_id,quantidade_produto,preco_total_produto)
        values (:produto_id,:pedido_id,:quantidade_produto,:preco_por_produto)";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':produto_id',$this->__get('produtoId'));
        $smtm->bindValue(':pedido_id',$this->__get('pedidoId'));
        $smtm->bindValue(':quantidade_produto',$this->__get('quantidade_produto'));
        $smtm->bindValue(':preco_por_produto',$this->__get('preco_por_produto'));
        $smtm->execute();
        return $this;
    }
}

?>