<?php

require_once("util/Connection.php");

$connection = Connection::getConnection();

$php_errormsg = "";
$marca = "";
$modelo = "";
$ano_fabricacao = "";
$cambio = "";
$combustivel = "";
$imagem = "";

if (isset($_POST['marca'])) {
    $marca = trim($_POST['marca']) ? trim($_POST['marca']) : null;
    $modelo = trim($_POST['modelo']) ? trim($_POST['modelo']) : null;
    $ano_fabricacao = is_numeric($_POST['ano_fabricacao']) ? $_POST['ano_fabricacao'] : null;
    $cambio = trim($_POST['cambio']) ? trim($_POST['cambio']) : null;
    $combustivel = trim($_POST['combustivel']) ? trim($_POST['combustivel']) : null;
    $imagem = trim($_POST['imagem']) ? trim($_POST['imagem']) : null;

    $php_errormsg = [];

    if (!$marca) {
        array_push($php_errormsg, "Preencha a Marca");
    }
    if (!$modelo) {
        array_push($php_errormsg, "Preencha o Modelo");
    }
    if (!is_numeric($ano_fabricacao) || $ano_fabricacao < 1900 || $ano_fabricacao > date('Y')) {
        array_push($php_errormsg, "Ano inválido");
    }
    $cambiosPermitidos = ["M", "A"];
    $combustiveisPermitidos = ["G", "E", "D", "F"];
    if (!in_array($cambio, $cambiosPermitidos)) {
        array_push($php_errormsg, "Preencha o Câmbio");
    }
    if (!in_array($combustivel, $combustiveisPermitidos)) {
        array_push($php_errormsg, "Preencha o Combustivel");
    }

    if (!$imagem) {
        array_push($php_errormsg, "Preencha o Link da Imagem");
    }

    if (!$php_errormsg) {
        $sqlCheck = "SELECT COUNT(*) FROM carros WHERE marca = ? AND modelo = ? AND ano_fabricacao = ?";
        $stmCheck = $connection->prepare($sqlCheck);
        $stmCheck->execute([$marca, $modelo, $ano_fabricacao]);
        $existe = $stmCheck->fetchColumn();

        if ($existe > 0) {
            $php_errormsg = "Já existe um carro com essa Marca, Modelo e Ano cadastrado.";
        } else {
            $sql = "INSERT INTO carros (marca, modelo, ano_fabricacao, cambio, combustivel, imagem)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stm = $connection->prepare($sql);
            $stm->execute([$marca, $modelo, $ano_fabricacao, $cambio, $combustivel, $imagem]);

            header("location: form.php");
        }
    } else {
        $php_errormsg = implode("<br>", $php_errormsg);
    }

}

$sql = "SELECT * FROM carros";
$stm = $connection->prepare($sql);
$stm->execute();
$carros = $stm->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Cars</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page">
    <h1 class="page-title">Data Cars</h1>
    <a href="card.php" class="btn-link">Clique aqui para ver nosso catálogo</a>
    <hr>

    <h3 class="section-title">Listing</h3>

    <table id="cars-table" border="1px solid black">

    <!-- Header -->
    <tr>
        <th>ID</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Ano de fabricação</th>
        <th>Câmbio</th>
        <th>Tipo de Combustível</th>
        <th>Imagem</th>
        <th>🚗</th>
    </tr>

    <!-- Data -->      
    <?php 
        foreach ($carros as $c): ?>
            <tr class="car-row">
                <td><?= $c["id"] ?></td>
                <td><?= $c["marca"] ?></td>
                <td><?= $c["modelo"] ?></td>
                <td><?= $c["ano_fabricacao"] ?></td>
                <td>
                    <?php 
                    
                        if ($c["cambio"] == "M") {
                            print "Manual";
                        }elseif ($c["cambio"] == "A") {
                            print "Automática";
                        }

                    ?>
                </td>
                <td>
                    <?php 
                    
                        if ($c["combustivel"] == "G") {
                            print "Gasolina";
                        }elseif ($c["combustivel"] == "E") {
                            print "Elétrico";
                        }elseif ($c["combustivel"] == "D") {
                            print "Diesel";
                        }elseif ($c["combustivel"] == "F") {
                            print "Flex";
                        }

                    ?>
                </td> 
                <td><img class="car-thumb" src="<?= $c['imagem'] ?>" alt="<?= $c['marca'] . ' ' . $c['modelo'] ?>" width="200"></td>
                <td>
                    <a class="btn-delete" href="delete.php?id=<?= $c['id'] ?>" onclick="if(! confirm('Tem certeza que você quer deletar o carro com ID <?= $c['id'] ?>?')) return false">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <hr>
    <h3 class="section-title">Form</h3>

        <form id="car-form" action="" method="POST">

            <label for="marca">Marca</label>
            <input type="text" name="marca" id="marca" placeholder="Digite a marca..." class="form-input" value="<?= $marca ?>">
            <br><br>

            <label for="modelo">Modelo</label>
            <input type="text" name="modelo" id="modelo" placeholder="Digite o modelo..." class="form-input" value="<?= $modelo ?>">
            <br><br>

            <label for="ano_fabricacao">Ano de Fabricação</label>
            <input type="number" name="ano_fabricacao" id="ano_fabricacao" placeholder="Digite o ano de fabricação..." class="form-input" value="<?= $ano_fabricacao ?>">
            <br><br>

            <label for="cambio">Câmbio</label>
            <select name="cambio" id="cambio" class="form-select">
                <option value="">Selecione a opção</option>
                <option value="M" <?= $cambio == 'M' ? 'selected' : '' ?>>Manual</option>
                <option value="A" <?= $cambio == 'A' ? 'selected' : '' ?>>Automática</option>
            </select>
            <br><br>

            <label for="combustivel">Tipo de Combustível</label>
            <select name="combustivel" id="combustivel" class="form-select">
                <option value="">Selecione a opção</option>
                <option value="G" <?= $combustivel == 'G' ? 'selected' : '' ?>>Gasolina</option>
                <option value="E" <?= $combustivel == 'E' ? 'selected' : '' ?>>Elétrico</option>
                <option value="D" <?= $combustivel == 'D' ? 'selected' : '' ?>>Diesel</option>
                <option value="F" <?= $combustivel == 'F' ? 'selected' : '' ?>>Flex</option>
            </select>
            <br><br>

            <label for="imagem">Imagem</label>
            <input type="text" name="imagem" id="imagem" placeholder="Cole o link da imagem..." class="form-input" value="<?= $imagem ?>">
            <br><br>

            <button class="btn-submit">Send</button>
            
            <div id="error_message" class="error">
                <?php if (isset($_GET['erro']) == 1){echo "Erro: ID not found for deletion";} ?>
            </div>

            <div class="error" id="form-error">
                <?= $php_errormsg ?>
            </div>
            
        </form>
</body>
</html>