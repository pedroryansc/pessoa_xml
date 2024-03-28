<?php

    /*

    $xmlString = "<administradores><titulo>Administradores</titulo><administrador numeroCNS='2023261023'><nome>Igor Kammer Grahl</nome><dataNascimento>2005-02-14</dataNascimento><cpf>180.719.870-00</cpf><senha>12345</senha></administrador></administradores>";
    $dom = new DOMDocument;
    $dom->loadXML($xmlString);
    $dom->save("teste.xml");

    $dom->formatOutput = true;
    echo $dom->saveXML();

    $pessoas = simplexml_load_file("teste.xml");
    $teste = $pessoas->xpath("/lotes");
    var_dump($teste[0]);
    echo $teste[0]["titulo"];

    */

    $xml = simplexml_load_file("teste.xml");
    $list = $xml->lote;
    for($i = 0; $i < count($list); $i++){
        echo "Código: " .$list[$i]->attributes()->codigo ."<br>";
        echo "Nome: " .$list[$i]->nomeVacina ."<br>";
        echo "Laboratório: " .$list[$i]->laboratorio ."<br>";
        echo "Unidade de Saúde: " .$list[$i]->unidadeSaude ."<br><br>";
    }

    // Criando um arquivo e adicionando uma pessoa

    $pessoa = array("id"=>2023004505, "nome"=>"Pedro Ryan Coelho Iplinski", "peso"=>63, "altura"=>1.86);

    define("ARQUIVO", "pessoas.xml");

    $dom = new DOMDocument;

    /*

    $dom->encoding = "UTF-8";
    $dom->xmlVersion = "1.0";
    $dom->formatOutput = true;

    $pessoa_node = $dom->createElement("pessoa");
    $attr_codigo = new DOMAttr("codigo", $pessoa["id"]);
    $pessoa_node->setAttributeNode($attr_codigo);

    $child_node_nome = $dom->createElement("nome", $pessoa["nome"]);
    $pessoa_node->appendChild($child_node_nome);

    $child_node_peso = $dom->createElement("peso", $pessoa["peso"]);
    $pessoa_node->appendChild($child_node_peso);

    $child_node_altura = $dom->createElement("altura", $pessoa["altura"]);
    $pessoa_node->appendChild($child_node_altura);

    $dom->appendChild($pessoa_node);
    $dom->save(ARQUIVO);

    */

    // Adicionando uma pessoa em um arquivo já existente

    $dom->load(ARQUIVO);

    $pessoa = array("id"=>2023261023, "nome"=>"Igor Kammer Grahl", "peso"=>72, "altura"=>1.83);

    $pessoa_node = $dom->createElement("pessoa");
    $attr_codigo = new DOMAttr("codigo", $pessoa["id"]);
    $pessoa_node->setAttributeNode($attr_codigo);

    $child_node_nome = $dom->createElement("nome", $pessoa["nome"]);
    $pessoa_node->appendChild($child_node_nome);

    $child_node_peso = $dom->createElement("peso", $pessoa["peso"]);
    $pessoa_node->appendChild($child_node_peso);

    $child_node_altura = $dom->createElement("altura", $pessoa["altura"]);
    $pessoa_node->appendChild($child_node_altura);

    $dom->appendChild($pessoa_node);
    $dom->save(ARQUIVO);

?>