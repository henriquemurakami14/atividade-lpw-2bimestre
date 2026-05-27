<?php

require_once("util/Connection.php");

$connection = Connection::getConnection();

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
    <a href="form.php" class="btn-link">Cadastrar novo carro</a>
    <hr>

    <?php if (empty($carros)): ?>
        <p class="empty-msg">Nenhum carro cadastrado</p>
    <?php else: ?>

        <div id="cards-container">

            <?php foreach ($carros as $c): ?>

                <div class="car-card">

                    <img class="car-card__img" src="<?= $c['imagem'] ?>" alt="<?= $c['marca'] . ' ' . $c['modelo'] ?>" width="300">
                    
                    <p class="car-card__field"><strong>ID:</strong> <?= $c['id'] ?></p>
                    
                    <p class="car-card__field"><strong>Marca:</strong> <?= $c['marca'] ?></p>
                    
                    <p class="car-card__field"><strong>Modelo:</strong> <?= $c['modelo'] ?></p>
                    
                    <p class="car-card__field"><strong>Ano de Fabricação:</strong> <?= $c['ano_fabricacao'] ?></p>
                    
                    <p class="car-card__field"><strong>Câmbio:</strong> <?= $c['cambio'] == 'M' ? 'Manual' : 'Automática' ?></p>
                    
                    <p class="car-card__field"><strong>Combustível:</strong>
                    
                    <?php
                        $combustiveis = ["G" => "Gasolina", "E" => "Elétrico", "D" => "Diesel", "F" => "Flex"];
                        echo $combustiveis[$c['combustivel']] ?? $c['combustivel'];
                    ?>
                    </p>

                    <a class="btn-delete" href="delete.php?id=<?= $c['id'] ?>" onclick="return confirm('Tem certeza que você quer deletar o carro com ID <?= $c['id'] ?>?')">Excluir</a>
                
                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</body>
</html>