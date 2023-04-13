<?php

namespace App\Models;

use MF\Model\Model;
use App\Connection;

class Favorito extends Model
{
  private $id;
  private $produtoId;
  private $usuarioId;
  public function __get($name)
  {
    return $this->$name;
  }
  public function __set($name, $value)
  {
    $this->$name = $value;
  }

  public function getFavorito()
  {
    $query = "select * from carrinho where usuario_id = :usuarioId and produto_id = :produtoId";
    $smtm = $this->db->prepare($query);
    $smtm->bindValue(':usuarioId', $this->__get('usuarioId'));
    $smtm->bindValue(':produtoId', $this->__get('produtoId'));
    $smtm->execute();
    return $smtm->fetchAll(\PDO::FETCH_ASSOC);
  }
  public function getMaisFavoritos()
  {
    $query = 'SELECT p.produto_img, p.nome,p.descricao,p.preco, produto_id, COUNT(*) AS total,
    ROW_NUMBER() OVER (ORDER BY COUNT(*) DESC) AS enumeracao
    FROM favorito as f
    inner join produto as p on (f.produto_id = p.id)
    GROUP BY produto_id
    ORDER BY total DESC
    LIMIT 3;';
    $smtm = $this->db->prepare($query);
    $smtm->execute();
    return $smtm->fetchAll(\PDO::FETCH_ASSOC);
  }
  public function getFavoritos()
  {
    $query = "select f.id, p.nome,u.nome as usuario_nome from favorito as f inner join produto as p on (f.produto_Id = p.id) inner join usuario as u on (f.usuario_id = u.id)";
    $smtm = $this->db->prepare($query);
    $smtm->execute();
    return $smtm->fetchAll(\PDO::FETCH_ASSOC);
  }
  public function getFavoritoUsuario()
  {
    $query = "select p.id,p.preco,p.nome, p.produto_img,p.produto_img_nome from favorito as f inner join produto as p on(f.produto_Id = p.id) where f.usuario_id = :usuarioId;";
    $smtm = $this->db->prepare($query);
    $smtm->bindValue(':usuarioId', $this->__get('usuarioId'));
    $smtm->execute();
    return $smtm->fetchAll(\PDO::FETCH_ASSOC);
  }
  public function inserirFavorito()
  {
    $query = "insert into favorito(produto_id,usuario_id)
        values(:produtoId,:usuarioId)";
    $smtm = $this->db->prepare($query);
    $smtm->bindValue(':usuarioId', $this->__get('usuarioId'));
    $smtm->bindValue(':produtoId', $this->__get('produtoId'));
    $smtm->execute();
    return $this;
  }
  public function deleteFavorito()
  {
    $query = "delete from favorito where usuario_id = :usuarioId and produto_id = :produtoId";
    $smtm = $this->db->prepare($query);
    $smtm->bindValue(':usuarioId', $this->__get('usuarioId'));
    $smtm->bindValue(':produtoId', $this->__get('produtoId'));
    $smtm->execute();
    return $this;
  }
}
