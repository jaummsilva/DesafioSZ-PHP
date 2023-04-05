<?php
namespace App\Models;

use MF\Model\Model;
use App\Connection;

class Pedido extends Model {
    private $id;
    private $usuarioId;
    private $precoTotal;

    private $data_criacao;
    private $data_alteracao;

    public function __get($name) {
        return $this->$name;
    }
    public function __set($name,$value ) {
        $this->$name = $value;
    }

    public function validarPedido() {
        $valide = true;

        if(strlen($this->__get('usuarioId')) < 1) {
            return false;
        }
        if(strlen($this->__get('precoTotal')) < 1) {
            return false;
        }
        return $valide;
    }

    public function cadastrarPedido() {
        $query = "insert into pedido(usuario_id,preco_total)
        values (:usuario_id,:preco_total)";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':usuario_id',$this->__get('usuarioId'));
        $smtm->bindValue(':preco_total',$this->__get('precoTotal'));
        $smtm->execute();
        return $this;
    }
    public function getPedido() {
        
            $query = "select * from pedido where usuario_id = :usuarioId order by id desc";
            $smtm = $this->db->prepare($query);
            $smtm->bindValue(':usuarioId',$this->__get('usuarioId'));
            $smtm->execute();
            return $smtm->fetchAll(\PDO::FETCH_ASSOC);
        
    }
}
