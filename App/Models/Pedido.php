<?php

namespace App\Models;
use MF\Model\Model;
use App\Connection;

class Pedido extends Model {

    private $id;
    private $usuarioId;
    private $precoTotal;

    public function __get($name) {
        return $this->$name;
    }
    public function __set($name,$value ) {
        $this->$name = $value;
    }

    public function cadastrarPedido() {
        $query = "insert into pedido(usuario_id,carrinho_id,preco_total)
        values (:usuario_id,:carrinho_id,:preco_total)";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':usuario_id',$this->__get('usuarioId'));
        $smtm->bindValue(':preco_total',$this->__get('precoTotal'));
        $smtm->execute();
        return $this;
    }
}

?>