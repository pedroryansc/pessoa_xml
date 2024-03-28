<?php 
/*
* Código adaptado a partir do código do professor Rodrigo Curvello
* Página reponsável pela listagem da entidade Pessoa
* @author Wesley R. Bezerra <wesley.bezerra@ifc.edu.br>
* @version 0.1
*
*/
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<?php include 'cabecalho.php'; ?>

<body>
    <main class="container">
        <?php include 'menu.php'; ?>
        <table role="grid">
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Altura</th>
                <th>Peso</th>
                <th>Alterar</th>
                <th>Excluir</th>
            </tr>
            <?php
            $dados = json_decode(file_get_contents('pessoa.json'), true);
            foreach ($dados as $key)
                echo "<tr><td>{$key['id']}</td>
                  <td>{$key['nome']}</td>
                  <td>{$key['altura']}</td>
                  <td>{$key['peso']}</td>
                  <td align='center'><a role='button' href='pessoa_cad.php?id=" . $key['id'] . "';>A</a></td>
                  <td align='center'><a role='button' href=javascript:excluirRegistro('pessoa_acao.php?acao=excluir&id=" . $key['id'] . "');>E</a></td>
              </tr>";
            ?>
        </table>
    </main>
    <!-- funcao de confirmacacao em javascript para a exclusao-->
    <script>
        function excluirRegistro(url) {
            if (confirm("Confirmar Exclusão?"))
                location.href = url;
        }
    </script>
</body>

</html>