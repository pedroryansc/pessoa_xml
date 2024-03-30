<?php

    /*
    * Código adaptado a partir do código do professor Rodrigo Curvêllo
    * Controlador reponsável pela manutenção do cadastro da entidade Pessoa
    * @author Wesley R. Bezerra <wesley.bezerra@ifc.edu.br>
    * @version 0.1
    *
    */

    // Definição de constantes
    define("DESTINO", "index.php");
    define("ARQUIVO_XML", "pessoas.xml");

    // Escolha da ação que processará a requisição

    $acao = "";
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $acao = isset($_GET['acao']) ? $_GET['acao'] : "";
            break;
        case 'POST':
            $acao = isset($_POST['acao']) ? $_POST['acao'] : "";
            break;
    }

    switch ($acao) {
        case 'Salvar':
            salvar();
            break;
        case 'Alterar':
            alterar();
            break;
        case 'excluir':
            excluir();
            break;
    }

    /*
    * Método que converte formulário html para array com respectivos dados
    * @return array
    */

    function tela2array(){
        $novo = array(
            'id' => isset($_POST['id']) ? $_POST['id'] : date("YmdHis"),
            'nome' => isset($_POST['nome']) ? $_POST['nome'] : "",
            'peso' => isset($_POST['peso']) ? $_POST['peso'] : "",
            'altura' => isset($_POST['altura']) ? $_POST['altura'] : ""
        );
        if ($novo['id'] == "0") {
            $novo['id'] = date("YmdHis");
        }
        return $novo;
    }

    /*
     * Método que cria e configura um documento DOM
     * @return DOMDocument 
     */

    function gerarDom(){
        $dom = new DOMDocument();

        $dom->xmlVersion = "1.0";
        $dom->encoding = "UTF-8";
        $dom->formatOutput = true;

        return $dom;
    }

    /*
    * Método que lê os dados no formato XML do arquivo em disco
    * @return Vetor de dados codificados no formato XML
    */

    function ler_xml(){
        $xml = simplexml_load_file(ARQUIVO_XML);
        $pessoas = $xml->pessoa;
        return $pessoas;
    }

    /*
    * Método que lê os dados e os carrega em um variável chamada pessoa
    * @param $id int identificador numérico do registro
    * @return String dados codificados no formato xml
    */

    function carregar($id){
        $pessoas = ler_xml();
        foreach ($pessoas as $pessoa) {
            if($pessoa->attributes()->id == $id)
                return $pessoa;
        }
    }

    /*
    * Método que altera os dados de um registro com SimpleXML
    * @return void
    */

    function alterar(){
        $dom = gerarDom();

        $pessoa = tela2array();
        
        $xml = simplexml_load_file(ARQUIVO_XML);
        for($i = 0; $i < count($xml->pessoa); $i++){
            if($xml->pessoa[$i]->attributes()->id == $pessoa["id"]){
                $xml->pessoa[$i]->nome = $pessoa["nome"];
                $xml->pessoa[$i]->peso = $pessoa["peso"];
                $xml->pessoa[$i]->altura = $pessoa["altura"];
            }
        }

        file_put_contents(ARQUIVO_XML, $xml->asXML());

        header("location:" . DESTINO);
    }

    /*
    * Método que exclui um registro
    * @return void
    */

    function excluir(){
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        $dom = gerarDom();

        $dom->load(ARQUIVO_XML);
        $pessoas = $dom->getElementsByTagName("pessoas");
        $pessoas = $pessoas->item(0);
        $pessoa = $pessoas->getElementsByTagName("pessoa");
        for($i = 0; $i < count($pessoa); $i++){
            if($pessoa->item($i)->getAttribute("id") == $id)
                $pessoas->removeChild($pessoa->item($i));
        }

        $dom->appendChild($pessoas);
        $dom->save(ARQUIVO_XML);

        header("location:" . DESTINO);
    }

    /*
    * Método que salva um registro no arquivo XML
    * @return void
    */

    function salvar(){
        $dom = gerarDom();

        if(file_exists(ARQUIVO_XML)){
            $dom->load(ARQUIVO_XML);
            $pessoas = $dom->getElementsByTagName("pessoas");
            $pessoas = $pessoas->item(0);
        } else
            $pessoas = $dom->createElement("pessoas");
        
        $pessoa = tela2array();

        $pessoa_node = $dom->createElement("pessoa");
        $id = new DOMAttr("id", $pessoa["id"]);
        $pessoa_node->setAttributeNode($id);
    
        $nome = $dom->createElement("nome", $pessoa["nome"]);
        $pessoa_node->appendChild($nome);
    
        $peso = $dom->createElement("peso", $pessoa["peso"]);
        $pessoa_node->appendChild($peso);
    
        $altura = $dom->createElement("altura", $pessoa["altura"]);
        $pessoa_node->appendChild($altura);

        $pessoas->appendChild($pessoa_node);

        $dom->appendChild($pessoas);
        $dom->save(ARQUIVO_XML);

        header("location:" . DESTINO);
    }

?>