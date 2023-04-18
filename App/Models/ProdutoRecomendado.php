<?php
namespace App\Models;

use MF\Model\Model;
use App\Connection;

class ProdutoRecomendado extends Model {
    private $id;
    private $nome_imagem;
    private $arquivo;
    private $numero_sequencia;
    private $data_criacao;
    private $data_alteracao;
    private $produto_id;


    public function __get($name) {
        return $this->$name;
    }
    public function __set($name,$value ) {
        $this->$name = $value;
    }

    public function cadastrarProdutoRecomendado() {
        
        $query = "insert into produto_recomendado(nome_imagem,arquivo,numero_sequencia,produto_id)
        values (:nome_imagem,:arquivo,:numero_sequencia,:produto_id)";
        
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':nome_imagem',$this->__get('nome_imagem'));
        $smtm->bindValue(':produto_id',$this->__get('produto_id'));
        $smtm->bindValue(':arquivo',$this->__get('arquivo'));
        $smtm->bindValue(':numero_sequencia',$this->__get('numero_sequencia'));
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
        return $valide;
    }
    public function getTodosProdutosRecomendados() {
        $query = "select pr.id, pr.data_alteracao, pr.data_criacao, pr.numero_sequencia, pr.produto_id,pr.arquivo, pr.nome_imagem,pt.nome as produto_nome, pt.preco as produto_preco from produto_recomendado pr left join  produto as pt on
        pr.produto_id = pt.id  order by numero_sequencia asc;";
        $smtm = $this->db->prepare($query);
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getProdutoRecomendado() {
        $query = "select pr.id, pr.data_alteracao, pr.data_criacao, pr.numero_sequencia, pr.produto_id,pr.arquivo, pr.nome_imagem,pt.nome as produto_nome from produto_recomendado pr left join  produto as pt on
        pr.produto_id = pt.id where pr.id = :id";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id',$this->__get('id'));
        $smtm->execute();
        return $smtm->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function editarProdutoRecomendado() {
        $img = "";
        $imgNome = "";
        if($this->__get('arquivo') != "") {
            $img = "arquivo = :arquivo,";
            $imgNome = "nome_imagem = :nome_imagem,";
        }
  
        $query = "update produto_recomendado set numero_sequencia = :numero_sequencia,
        produto_id = :produto_id,
        $img
        $imgNome
        data_alteracao = :data_alteracao
        where id = :id";
        
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id',$this->__get('id'));
        $smtm->bindValue(':numero_sequencia',$this->__get('numero_sequencia'));
        $smtm->bindValue(':data_alteracao',$this->__get('data_alteracao'));
        $smtm->bindValue(':produto_id',$this->__get('produto_id'));
        if($img != "" && $imgNome != '') {
            $smtm->bindValue(':arquivo', $this->__get('arquivo'));
            $smtm->bindValue(':nome_imagem', $this->__get('nome_imagem'));
        }
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function deletarProdutoRecomendado() {
        $query = "delete from produto_recomendado where id = :id ";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id', $this->__get('id'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function deletarProdutoRecomendadoPorProduto() {
        $query = "delete from produto_recomendado where produto_id = :produto_id ";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':produto_id', $this->__get('produto_id'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getProdutoRecomendadoPorProduto() {
        $query = "select id from produto_recomendado where produto_id = :produto_id  ";
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':produto_id', $this->__get('produto_id'));
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_ASSOC);
    }
}

?>