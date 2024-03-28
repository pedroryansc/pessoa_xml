<?php

/*
* Código adaptado a partir do código do professor Rodrigo Curvello
* Controlador reponsável pela manutenção do cadastro da entidade Pessoa
* @author Wesley R. Bezerra <wesley.bezerra@ifc.edu.br>
* @version 0.1
*
*/

/* definição de constantes */
define("DESTINO", "index.php");
define("ARQUIVO_XML", "pessoa.xml");

$DOMDocument = new DOMDocument();

/* escolha da ação que processará a requisição */
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
function tela2array()
{
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
* Método que converte array para XML
* @return String XML
*/
function array2xml($array_dados, $xml_dados)
{
    $xml_dados->id = $array_dados['id'];
    $xml_dados->nome = $array_dados['nome'];
    $xml_dados->peso = $array_dados['peso'];
    $xml_dados->altura = $array_dados['altura'];

    return $xml_dados;
}
/*
* Método que salva os dados no formato xml no arquivo em disco
* @param $dados String dados codificados no formato xml
* @param $arquivo String nome do arquivo onde serão salvos os dados
* @return void
*/
function salvar_xml($dados, $arquivo)
{
    $fp = fopen($arquivo, "w");
    fwrite($fp, $dados);
    fclose($fp);
}
/*
* Método que lê os dados no formato xml do arquivo em disco
* @param $arquivo String nome do arquivo onde serão salvos os dados
* @return String dados codificados no formato xml
*/
function ler_xml($arquivo)
{
    if($arquivo == null)
        salvar_xml($arquivo);
    $arquivo = file_get_contents($arquivo);
    // $xml = xml_decode($arquivo);
    return $arquivo;
    // return $xml;
}
/*
* Método que lê os dados e os carrega em um variável chamada xml
* @param $id int identificador numérico do registro
* @return String dados codificados no formato xml
*/
function carregar($id)
{
    $xml = ler_xml(ARQUIVO_XML);

    foreach ($xml as $key) {
        if ($key->id == $id)
            return (array) $key;
    }
}

/*
* Método que altera os dados de um registro
* @return void
*/
function alterar()
{
    $novo = tela2array();

    $xml = ler_xml(ARQUIVO_XML);

    for ($x = 0; $x < count($xml); $x++) {
        if ($xml[$x]->id == $novo['id']) {
            array2xml($novo, $xml[$x]);
        }
    }

    // salvar_xml(xml_encode($xml), ARQUIVO_XML);

    header("location:" . DESTINO);

}


/*
1 - abrir json em formato php;
2 - percorrer e achar o item pelo ID;
3 - estratégia de deletar;
4 - gravar em json novamente, sem o item;
5 - redirecionar para a página index.php
*/

/*
* Método exclui um registro
* @return void
*/
function excluir()
{
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $xml = ler_xml(ARQUIVO_XML);
    if ($xml == null)
        $xml = array();

    $novo = array();
    for ($x = 0; $x < count($xml); $x++) {
        var_dump($xml[$x]);
        if ($xml[$x]->id != $id)

            array_push($novo, $xml[$x]);
    }
    salvar_xml(xml_encode($novo), ARQUIVO_XML);

    header("location:" . DESTINO);

}
/*
* Método salva alterações feitas em um registro
* @return void
*/
function salvar()
{
    $xml = NULL;
    $pessoa = tela2array();

    $xml = ler_xml(ARQUIVO_XML);

    if ($xml == NULL) {
        $xml = array();
    }

    array_push($xml, $pessoa);

    salvar_xml($xml, ARQUIVO_XML);

    header("location:" . DESTINO);
}

?>