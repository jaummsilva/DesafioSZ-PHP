<?php

namespace App\Controllers;

use Exception;
use MF\Controller\Action;
use MF\Model\Container;

class AdminController extends Action
{
    // Autenticação
    public function loginAdmin()
    {
        $this->view->errosLogin = [];
        $this->renderDeslogado('loginAdmin');
    }
    public function homeAdmin()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        $this->renderAdmin('homeAdmin');
    }
    public function autenticarAdmin()
    {
        $this->view->errosLogin = [];

        // Usuario
        $usuario = Container::getModel('Usuario');
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', sha1($_POST['senha']));
        $usuario->autenticar();
        // Se usuario existir
        if ($usuario->__get('id') != '' && $usuario->__get('nome')) {
            session_start();

            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');

            header('Location: /homeAdmin');
        } else {
            $this->view->errosLogin[] = "Email ou senha incorretos";
            $this->renderDeslogado('loginAdmin');
        }
    }

    // Usuario 
    public function cadastroUsuarioAdmin()
    {
        // Views de Erro e Pegar o Usuario
        $this->view->errosUsuario = [];
        $this->view->getUsuario = [];
        session_start();
        // Usuario
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        $this->renderAdmin('cadastroUsuarioAdmin');
    }
    public function registrarUsuario()
    {
        // Views de Erro e Pegar o Usuario
        $this->view->getUsuario = [];
        // Usuario
        $usuario = Container::getModel('Usuario');
        // View que busca o email informado da global POST no banco de dados 
        $this->view->getUsuariosEmail = $usuario->getTodosUsuariosEmail($_POST['email']);
        // Se email ja existir , retorna com erro
        if (!empty($this->view->getUsuariosEmail[0])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['mensagem' => 'Email ja existe, tente novamente com outro']);
            return;
        }
        // Usuario
        $usuario = Container::getModel('Usuario');
        // Setters 
        $usuario->__set('nome', $_POST['nome']);
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', sha1($_POST['senha']));
        $usuario->__set('data_nascimento', $_POST['nascimento']);
        $usuario->__set('telefone', $_POST['telefone']);
        $usuario->__set('usuario_img', $_POST['img']);
        $usuario->__set('usuario_img_nome', $_POST['imgNome']);
        // Se o cadastro for validado
        if ($usuario->validarCadastro()) {
            $usuario->cadastrarUsuario();
            header('Location: /listagemUsuarioAdmin?sucesso=Usuario cadastrado com sucesso');
        } else {
            header('Location: /cadastroUsuarioAdmin');
        }
    }
    public function listagemUsuarioAdmin()
    {
        session_start();
        // Usuario
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);
        // View que retorna todos os usuarios
        $this->view->getUsuarios = $usuario->getTodosUsuarios();

        $this->renderAdmin('listagemUsuarioAdmin');
    }
    public function editarUsuarioAdmin($id)
    {
        session_start();
        // Usuario
        $usuario = Container::getModel('Usuario');
        // Setters
        $usuario->__set('id', $id);
        // View que retorna usuario por id buscado da rota
        $this->view->getUsuario = $usuario->getUsuario();
        $this->renderAdmin('editarUsuarioAdmin');
    }
    public function editarUsuario()
    {
        $usuario = Container::getModel('Usuario');
        // Views
        $this->view->getUsuario = [];
        // Setters
        $usuario->__set('id', $_POST['id']);
        // View que retorna usuario por id buscado da rota
        $this->view->getUsuario = $usuario->getUsuario();
        // Setters
        $usuario->__set('nome', $_POST['nome']);
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', sha1($_POST['senha']));
        $usuario->__set('data_nascimento', $_POST['nascimento']);
        $usuario->__set('data_alteracao', date('Y-m-d H:i:s'));
        $usuario->__set('telefone', $_POST['telefone']);
        // se a imagem for alterada
        if (!empty($_POST['img'])) {
            $usuario->__set('usuario_img', $_POST['img']);
            $usuario->__set('usuario_img_nome', $_POST['imgNome']);
        }
        // Se a edição for validada
        if ($usuario->validarCadastro()) {
            $usuario->editarUsuario();
            header("Location: /listagemUsuarioAdmin");
        } else {
            return;
        }
    }
    public function exportarXlsUsuario()
    {
        $timestamp = time();
        $filename = 'usuariosXLS_' . $timestamp . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Adicionar estilo
        echo '<html>';
        echo '<head>';
        echo '<style>';
        echo 'table { border-collapse: collapse; }';
        echo 'td, th { border: 1px solid black; padding: 5px; }';
        echo '</style>';
        echo '</head>';

        $isPrintHeader = false;
        $usuario = Container::getModel('Usuario');
        // View que retorna todos os usuarios
        $usuarios = $this->view->getUsuarios = $usuario->getTodosUsuarios();

        // Adicionar tabela
        echo '<body>';
        echo '<table>';

        foreach ($usuarios as $row) {
            if (!$isPrintHeader) {
                echo '<tr><th>' . implode('</th><th>', array_keys($row)) . '</th></tr>';
                $isPrintHeader = true;
            }
            echo '<tr><td>' . implode('</td><td>', array_values($row)) . '</td></tr>';
        }

        // Fechar tabela e corpo
        echo '</table>';
        echo '</body>';

        echo '</html>';

        exit();
    }
    public function exportarCsvUsuario()
    {
        session_start();
        // Usuario
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);
        // View que retorna todos os usuarios
        $usuarios = $this->view->getUsuarios = $usuario->getTodosUsuarios();
        if ($usuarios === false) {
            throw new Exception('Não foi possível obter os usuários.');
        }
        $filename = 'usuariosCSV_' . date('Y-m-d') . '.csv';
        $fp = fopen($filename, 'w');
        if ($fp === false) {
            throw new Exception('Não foi possível criar o arquivo CSV.');
        }

        foreach ($usuarios as $fields) {
            fputcsv($fp, $fields);
        }
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        readfile($filename);
        unlink($filename);
        fclose($fp);
    }
    public function deletarUsuario()
    {
        // Deletação de usuario
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_REQUEST['idUsuario']);
        $usuario->deletarUsuario();
        header("Location: /listagemUsuarioAdmin");
    }
    // Produto

    public function listagemProdutoAdmin()
    {
        $produto = Container::getModel('Produto');
        // View que retorna todos os produtos
        $this->view->getProdutos = $produto->getTodosProdutos();
        $this->renderAdmin('listagemProdutoAdmin');
    }
    public function editarProdutoAdmin($id)
    {
        session_start();
        // Produto
        $produto = Container::getModel('Produto');
        $produto->__set('id', $id);
        // View que retorna o produto por id buscado da rota
        $this->view->getProduto = $produto->getProduto();
        $this->renderAdmin('editarProdutoAdmin');
    }
    
    public function editarProduto()
    {
        // Produto
        $produto = Container::getModel('Produto');
        // Setters
        $produto->__set('id', $_POST['id']);
        $produto->__set('nome', $_POST['nome']);
        $produto->__set('preco', number_format($_POST['preco'], 2, '.', ''));
        $produto->__set('data_alteracao', date('Y-m-d H:i:s'));
        $produto->__set('descricao', $_POST['descricao']);
        // se a imagem for alterada
        if (!empty($_POST['img'])) {
            $produto->__set('produto_img', $_POST['img']);
            $produto->__set('produto_img_nome', $_POST['imgNome']);
        }
        // se a imagem 2 for alterada 
        if (!empty($_POST['img2'])) {
            $produto->__set('produto_img_2', $_POST['img_2']);
            $produto->__set('produto_img_2_nome', $_POST['imgNome2']);
        }
        // se a imagem 3 for alterada 
        if (!empty($_POST['img3'])) {
            $produto->__set('produto_img_3', $_POST['img3']);
            $produto->__set('produto_img_3_nome', $_POST['imgNome3']);
        }
        // se a imagem 4 for alterada 
        if (!empty($_POST['img4'])) {
            $produto->__set('produto_img_4', $_POST['img4']);
            $produto->__set('produto_img_4_nome', $_POST['imgNome4']);
        }
        // se o produto for validado
        if ($produto->validarProduto()) {
            $produto->editarProduto();
            header("Location: /listagemProdutoAdmin");
        } else {
            return;
        }
    }
    public function cadastroProdutoAdmin()
    {
        $this->view->getProduto = [];
        $this->renderAdmin('cadastroProdutoAdmin');
    }
    public function registrarProduto()
    {
        // Produto
        $produto = Container::getModel('Produto');
        // Setters
        $produto->__set('nome', $_POST['nome']);
        $produto->__set('preco', number_format($_POST['preco'], 2, '.', ''));
        $produto->__set('descricao', $_POST['descricao']);
        $produto->__set('produto_img', $_POST['img']);
        $produto->__set('produto_img_nome', $_POST['imgNome']);
        if (!empty($_POST['img2'])) {
            $produto->__set('produto_img_2', $_POST['img2']);
            $produto->__set('produto_img_2_nome', $_POST['imgNome2']);
        }
        if (!empty($_POST['img3'])) {
            $produto->__set('produto_img_3', $_POST['img3']);
            $produto->__set('produto_img_3_nome', $_POST['imgNome3']);
        }
        if (!empty($_POST['img4'])) {
            $produto->__set('produto_img_4', $_POST['img4']);
            $produto->__set('produto_img_4_nome', $_POST['imgNome4']);
        }
        // Se o cadastro for validado , registra o produto
        if ($produto->validarProduto()) {
            $produto->cadastrarProduto();
            header('Location: /listagemProdutoAdmin');
        }
    }
    public function deletarProduto()
    {
        // Deletação do produto
        $produto = Container::getModel('Produto');
        $produto->__set('id', $_REQUEST['idProduto']);
        $produto->deletarProduto();
        header("Location: /listagemProdutoAdmin");
    }
    public function exportarXlsProduto()
    {
        $timestamp = time();
        $filename = 'produtosXLS_' . $timestamp . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Adicionar estilo
        echo '<html>';
        echo '<head>';
        echo '<style>';
        echo 'table { border-collapse: collapse; }';
        echo 'td, th { border: 1px solid black; padding: 5px; }';
        echo '</style>';
        echo '</head>';

        $isPrintHeader = false;
        $produto = Container::getModel('Produto');
        // View que retorna todos os produtos
        $produtos =  $this->view->getProdutos = $produto->getTodosProdutos();

        // Adicionar tabela
        echo '<body>';
        echo '<table>';

        foreach ($produtos as $row) {
            if (!$isPrintHeader) {
                echo '<tr><th>' . implode('</th><th>', array_keys($row)) . '</th></tr>';
                $isPrintHeader = true;
            }
            echo '<tr><td>' . implode('</td><td>', array_values($row)) . '</td></tr>';
        }

        // Fechar tabela e corpo
        echo '</table>';
        echo '</body>';

        echo '</html>';

        exit();
    }
    public function exportarCsvProduto()
    {
        session_start();
        // Usuario
        $produto = Container::getModel('Produto');
        // View que retorna todos os produtos
        $produtos =  $this->view->getProdutos = $produto->getTodosProdutos();
        if ($produtos === false) {
            throw new Exception('Não foi possível obter os produtos.');
        }
        $filename = 'produtosCSV_' . date('Y-m-d') . '.csv';
        $fp = fopen($filename, 'w');
        if ($fp === false) {
            throw new Exception('Não foi possível criar o arquivo CSV.');
        }
        $header = array('ID', 'Nome', 'Descrição', 'Preço', 'Data_criacao', 'Data_alteracao', 'Produto_img', 'Produto_img_nome');
        fputcsv($fp, $header);
        foreach ($produtos as $fields) {
            fputcsv($fp, $fields);
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        readfile($filename);
        unlink($filename);
        fclose($fp);
    }
    // Produto Recomendado
    public function listagemProdutoRecomendadoAdmin()
    {
        $produto_recomendado = Container::getModel('ProdutoRecomendado');
        $this->view->getProdutosRecomendados = $produto_recomendado->getTodosProdutosRecomendados();
        $this->renderAdmin('listagemProdutoRecomendadoAdmin');
    }
    public function cadastroProdutoRecomendadoAdmin()
    {
        $this->view->getProduto = [];
        $produto = Container::getModel('Produto');
        $this->view->getProdutos = $produto->getTodosProdutos();
        $this->renderAdmin('cadastroProdutoRecomendadoAdmin');
    }
    public function registrarProdutoRecomendado()
    {
        // Produto Recomendado
        $produto_recomendado = Container::getModel('ProdutoRecomendado');
        // Setters
        $produto_recomendado->__set('nome_imagem', $_FILES['img']['name']);
        $produto_recomendado->__set('numero_sequencia', $_POST['numero_sequencia']);
        $produto_recomendado->__set('produto_id', $_POST['idProduto']);

        $destino = $_SERVER['DOCUMENT_ROOT'] . '\img/' . $_FILES['img']['name'];
        if (move_uploaded_file($_FILES['img']['tmp_name'], $destino)) {
        }
        $caminho_relativo = '/public/img/' . $_FILES['img']['name'];
        $produto_recomendado->__set('arquivo', $caminho_relativo);
        $produto_recomendado->cadastrarProdutoRecomendado();
    }
    public function editarProdutoRecomendadoAdmin($id)
    {
        session_start();
        // Produto
        $produto = Container::getModel('Produto');
        $produto_recomendado = Container::getModel('ProdutoRecomendado');
        $produto_recomendado->__set('id', $id);
        // View que retorna o produto por id buscado da rota
        $this->view->getProdutos = $produto->getTodosProdutos();
        $this->view->getProdutoRecomendado = $produto_recomendado->getProdutoRecomendado();
        $this->renderAdmin('editarProdutoRecomendadoAdmin');
    }
    public function editarProdutoRecomendado()
    {
        // Produto Recomendado
        $produto_recomendado = Container::getModel('ProdutoRecomendado');
        // Setters
        $produto_recomendado->__set('id', $_POST['id']);
        $produto_recomendado->__set('nome_imagem', $_FILES['img']['name']);
        $produto_recomendado->__set('numero_sequencia', $_POST['numero_sequencia']);
        $produto_recomendado->__set('data_alteracao', date('Y-m-d H:i:s'));
        $produto_recomendado->__set('produto_id', $_POST['idProduto']);

        if (!empty($_FILES['img'])) {
            $destino = $_SERVER['DOCUMENT_ROOT'] . '\img/' . $_FILES['img']['name'];
            if (move_uploaded_file($_FILES['img']['tmp_name'], $destino)) {
            }
            $caminho_relativo = '/public/img/' . $_FILES['img']['name'];
            $produto_recomendado->__set('arquivo', $caminho_relativo);
        }

        $produto_recomendado->editarProdutoRecomendado();
    }
    public function deletarProdutoRecomendado()
    {
        // Deletação do produto recomendado
        $produto_recomendado = Container::getModel('ProdutoRecomendado');
        $produto_recomendado->__set('id', $_REQUEST['idProdutoRecomendado']);
        unlink($_SERVER['DOCUMENT_ROOT'] . '\img/' . $_REQUEST['nomeProdutoRecomendado']);
        $produto_recomendado->deletarProdutoRecomendado();
        header("Location: /listagemProdutoRecomendadoAdmin");
    }
    public function exportarXlsProdutoRecomendado()
    {
        $timestamp = time();
        $filename = 'produtos_recomendado_XLS_' . $timestamp . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Adicionar estilo
        echo '<html>';
        echo '<head>';
        echo '<style>';
        echo 'table { border-collapse: collapse; }';
        echo 'td, th { border: 1px solid black; padding: 5px; }';
        echo '</style>';
        echo '</head>';

        $isPrintHeader = false;
        $produto_recomendado = Container::getModel('ProdutoRecomendado');
        $produtos = $this->view->getProdutosRecomendados = $produto_recomendado->getTodosProdutosRecomendados();

        // Adicionar tabela
        echo '<body>';
        echo '<table>';

        foreach ($produtos as $row) {
            if (!$isPrintHeader) {
                echo '<tr><th>' . implode('</th><th>', array_keys($row)) . '</th></tr>';
                $isPrintHeader = true;
            }
            echo '<tr><td>' . implode('</td><td>', array_values($row)) . '</td></tr>';
        }

        // Fechar tabela e corpo
        echo '</table>';
        echo '</body>';

        echo '</html>';

        exit();
    }
    public function exportarCsvProdutoRecomendado()
    {
        session_start();
        
        $produto_recomendado = Container::getModel('ProdutoRecomendado');
        $produtos = $this->view->getProdutosRecomendados = $produto_recomendado->getTodosProdutosRecomendados();
        if ($produtos === false) {
            throw new Exception('Não foi possível obter os produtos.');
        }
        $filename = 'produtos_recomendados_CSV_' . date('Y-m-d') . '.csv';
        $fp = fopen($filename, 'w');
        if ($fp === false) {
            throw new Exception('Não foi possível criar o arquivo CSV.');
        }
        $header = array('ID', 'Nome', 'Descrição', 'Preço', 'Data_criacao', 'Data_alteracao', 'Produto_img', 'Produto_img_nome');
        fputcsv($fp, $header);
        foreach ($produtos as $fields) {
            fputcsv($fp, $fields);
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        readfile($filename);
        unlink($filename);
        fclose($fp);
    }
    
    // Pedido
    public function listagemPedidoAdmin()
    {
        $pedido = Container::getModel("Pedido");
        $this->view->getPedidos = $pedido->getPedidos();
        $this->renderAdmin('listagemPedidoAdmin');
    }
    public function listagemPedidoProdutoAdmin()
    {
        $pedido = Container::getModel("Pedido");
        $pedido->__set('id', $_REQUEST['pedidoId']);
        $this->view->getPedidoProduto = $pedido->getPedidoId();
        $this->renderAdmin('listagemPedidoProdutoAdmin');
    }
    public function exportarXlsPedido()
    {
        $timestamp = time();
        $filename = 'pedidosXLS_' . $timestamp . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Adicionar estilo
        echo '<html>';
        echo '<head>';
        echo '<style>';
        echo 'table { border-collapse: collapse; }';
        echo 'td, th { border: 1px solid black; padding: 5px; }';
        echo '</style>';
        echo '</head>';

        $isPrintHeader = false;
        $pedido = Container::getModel("Pedido");
        $pedidos = $this->view->getPedidos = $pedido->getPedidos();

        // Adicionar tabela
        echo '<body>';
        echo '<table>';

        foreach ($pedidos as $row) {
            if (!$isPrintHeader) {
                echo '<tr><th>' . implode('</th><th>', array_keys($row)) . '</th></tr>';
                $isPrintHeader = true;
            }
            echo '<tr><td>' . implode('</td><td>', array_values($row)) . '</td></tr>';
        }

        // Fechar tabela e corpo
        echo '</table>';
        echo '</body>';
        echo '</html>';
        exit();
    }
    public function exportarCsvPedido()
    {
        session_start();
        // Usuario
        $pedido = Container::getModel("Pedido");
        $pedidos = $this->view->getPedidos = $pedido->getPedidos();
        if ($pedidos === false) {
            throw new Exception('Não foi possível obter os pedidos.');
        }
        $filename = 'pedidosCSV_' . date('Y-m-d') . '.csv';
        $fp = fopen($filename, 'w');
        if ($fp === false) {
            throw new Exception('Não foi possível criar o arquivo CSV.');
        }

        foreach ($pedidos as $fields) {
            fputcsv($fp, $fields);
        }
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        readfile($filename);
        unlink($filename);
        fclose($fp);
    }
    // Favorito
    public function listagemFavoritoAdmin()
    {
        $favorito = Container::getModel("Favorito");
        $this->view->getFavoritos = $favorito->getFavoritos();
        $this->view->getMaisFavoritos = $favorito->getMaisFavoritos();
        $this->renderAdmin('listagemFavoritoAdmin');
    }
    public function exportarXlsFavorito()
    {
        $timestamp = time();
        $filename = 'favoritosXLS_' . $timestamp . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Adicionar estilo
        echo '<html>';
        echo '<head>';
        echo '<style>';
        echo 'table { border-collapse: collapse; }';
        echo 'td, th { border: 1px solid black; padding: 5px; }';
        echo '</style>';
        echo '</head>';

        $isPrintHeader = false;
        $favorito = Container::getModel("Favorito");
        $favoritos = $this->view->getFavoritos = $favorito->getFavoritos();

        // Adicionar tabela
        echo '<body>';
        echo '<table>';

        foreach ($favoritos as $row) {
            if (!$isPrintHeader) {
                echo '<tr><th>' . implode('</th><th>', array_keys($row)) . '</th></tr>';
                $isPrintHeader = true;
            }
            echo '<tr><td>' . implode('</td><td>', array_values($row)) . '</td></tr>';
        }

        // Fechar tabela e corpo
        echo '</table>';
        echo '</body>';

        echo '</html>';

        exit();
    }
    public function exportarCsvFavorito()
    {
        session_start();
        $favorito = Container::getModel("Favorito");
        $favoritos = $this->view->getFavoritos = $favorito->getFavoritos();
        if ($favorito === false) {
            throw new Exception('Não foi possível obter os produtos favoritos.');
        }
        $filename = 'favoritosCSV_' . date('Y-m-d') . '.csv';
        $fp = fopen($filename, 'w');
        if ($fp === false) {
            throw new Exception('Não foi possível criar o arquivo CSV.');
        }

        foreach ($favoritos as $fields) {
            fputcsv($fp, $fields);
        }
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        readfile($filename);
        unlink($filename);
        fclose($fp);
    }
}
