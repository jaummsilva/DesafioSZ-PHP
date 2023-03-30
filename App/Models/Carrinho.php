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
    private $status;
    private $data_criacao;
    private $data_alteracao;

    public function __get($name) {
        return $this->$name;
    }
    public function __set($name,$value ) {
        $this->$name = $value;
    }
    
    public function getCarrinho() {
        $query = "select * from carrinho where usuario_id = :usuarioId and produto_id = :produtoId and status = 'ABERTO' ";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':usuarioId',$this->__get('usuarioId'));
        $smtm->bindValue(':produtoId',$this->__get('produtoId'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getCarrinhoUsuario() {
        $query = "select c.id, c.usuario_id, c.produto_id, c.preco,c.quantidade_produto, p.nome from carrinho as c inner join produto as p on(c.produto_Id = p.id) where c.usuario_id = :usuarioId and c.status = 'ABERTO';";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':usuarioId',$this->__get('usuarioId'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getPreçoTotalCarrinho() {
        $query = "select sum(preco * quantidade_produto) as total_produto from carrinho where usuario_id = :usuarioId and status = 'ABERTO';";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':usuarioId',$this->__get('usuarioId'));
        $smtm->execute();
        return $smtm->fetch(\PDO::FETCH_ASSOC);
    }
    public function getPreçoTotalProduto() {
        $query = "select sum(preco * quantidade_produto) as total_produto from carrinho where usuario_id = :usuarioId and produto_id = :produtoId and status = 'ABERTO';";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':usuarioId',$this->__get('usuarioId'));
        $smtm->bindValue(':produtoId',$this->__get('produtoId'));
        $smtm->execute();
        return $smtm->fetch(\PDO::FETCH_ASSOC);
    }
    public function inserirCarrinho() {
        $query = "insert into carrinho(preco,produto_id,usuario_id,quantidade_produto)
        values (:preco,:produtoId,:usuarioId,:quantidadeProduto)";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':preco',$this->__get('preco'));
        $smtm->bindValue(':usuarioId',$this->__get('usuarioId'));
        $smtm->bindValue(':produtoId',$this->__get('produtoId'));
        $smtm->bindValue(':quantidadeProduto',$this->__get('quantidade_Produto'));
        $smtm->execute();
        return $this;
    }
    public function updateCarrinho() {
        $query = "update carrinho set quantidade_produto = (quantidade_produto + :quantidade_Produto) where usuario_id = :usuarioId and produto_id = :produtoId ";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':usuarioId',$this->__get('usuarioId'));
        $smtm->bindValue(':produtoId',$this->__get('produtoId'));
        $smtm->bindValue(':quantidade_Produto',$this->__get('quantidade_Produto'));
        $smtm->execute();
        return $this;
    }
    public function updateCarrinhoFinalizado() {
        $query = "update carrinho set status = 'FINALIZADO' where usuario_id = :usuarioId and produto_id = :produtoId ";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':usuarioId',$this->__get('usuarioId'));
        $smtm->bindValue(':produtoId',$this->__get('produtoId'));
        $smtm->execute();
        return $this;
    }
    public function deleteCarrinho() {
        $query = "delete from carrinho where usuario_id = :usuarioId and produto_id = :produtoId";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':usuarioId',$this->__get('usuarioId'));
        $smtm->bindValue(':produtoId',$this->__get('produtoId'));
        $smtm->execute();
        return $this;
    }

}

?>