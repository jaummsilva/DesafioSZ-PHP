<?php

namespace App\Models;

use MF\Model\Model;
use App\Connection;

class Pedido extends Model
{
    private $id;
    private $usuarioId;
    private $precoTotal;

    private $data_criacao;
    private $data_alteracao;

    public function __get($name)
    {
        return $this->$name;
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function validarPedido()
    {
        $valide = true;

        if (strlen($this->__get('usuarioId')) < 1) {
            return false;
        }
        if (strlen($this->__get('precoTotal')) < 1) {
            return false;
        }
        return $valide;
    }

    public function cadastrarPedido()
    {
        $query = "insert into pedido(usuario_id,preco_total)
        values (:usuario_id,:preco_total)";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':usuario_id', $this->__get('usuarioId'));
        $smtm->bindValue(':preco_total', $this->__get('precoTotal'));
        $smtm->execute();
        return $this;
    }
    public function getPedido()
    {
        $query = "select * from pedido where usuario_id = :usuarioId order by id desc";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':usuarioId', $this->__get('usuarioId'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getPedidos()
    {
        $query = "
        select p.id, u.email, SUM(ip.quantidade_produto) as quantidade_produtos,p.preco_total  from itensPedido ip
        left join pedido as p on ip.pedido_id = p.id 
        left join usuario as u on p.usuario_id = u.id 
        group by p.id;";
        $smtm = $this->db->prepare($query);
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getPedidoId()
    {
        $query = "
        select ip.quantidade_produto, pt.nome,p.id,p.preco_total  from itensPedido ip
        left join pedido as p on ip.pedido_id = p.id 
        left join produto as pt on ip.produto_id = pt.id
        where p.id = :id;";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id', $this->__get('id'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getPedidoSlack()
    {
        $query = "
        SELECT u.nome as usuario_nome, u.email, p.id AS pedido_id, ip.quantidade_produto, ip.preco_total_produto, pt.nome, 
(SELECT SUM(ip2.preco_total_produto) FROM itensPedido ip2 WHERE ip2.pedido_id = p.id) AS valor_total_pedido
FROM itensPedido ip
LEFT JOIN pedido AS p ON ip.pedido_id = p.id 
LEFT JOIN usuario AS u ON p.usuario_id = u.id
LEFT JOIN produto AS pt ON ip.produto_id = pt.id 
WHERE ip.pedido_id = :id;";

        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id', $this->__get('id'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
}
