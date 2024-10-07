<?php
// Array de moedas com suas taxas de conversão. Eventualmente as taxas serão calculadas por uma API que forneça os valores atualizados, mas por enquanto, para testa da plataforma, ficarão neste array
$moedas = [
    'BRL' => [
        'USD' => 5.46,
        'EUR' => 6.20,
        'GBP' => 7.34,
        'CAD' => 4.12,
        'AUD' => 3.91,
        'JPY' => 0.039
    ],
    'USD' => [
        'BRL' => 0.18,
        'EUR' => 0.91,
        'GBP' => 0.77,
        'CAD' => 1.26,
        'AUD' => 1.41,
        'JPY' => 110.51
    ],
    'EUR' => [
        'BRL' => 0.16,
        'USD' => 1.10,
        'GBP' => 0.85,
        'CAD' => 1.38,
        'AUD' => 1.54,
        'JPY' => 121.95
    ],
    'GBP' => [
        'BRL' => 0.14,
        'USD' => 1.30,
        'EUR' => 1.18,
        'CAD' => 1.62,
        'AUD' => 1.80,
        'JPY' => 143.54
    ],
    'CAD' => [
        'BRL' => 0.24,
        'USD' => 0.79,
        'EUR' => 0.72,
        'GBP' => 0.62,
        'AUD' => 1.11,
        'JPY' => 88.42
    ],
    'AUD' => [
        'BRL' => 0.26,
        'USD' => 0.71,
        'EUR' => 0.65,
        'GBP' => 0.56,
        'CAD' => 0.90,
        'JPY' => 79.51
    ],
    'JPY' => [
        'BRL' => 25.58,
        'USD' => 0.009,
        'EUR' => 0.0082,
        'GBP' => 0.0070,
        'CAD' => 0.011,
        'AUD' => 0.013
    ]
];

/* Função de conversão de moedas:
Recebe a quantidade, taxa de conversão, taxa de imposto e se a quantidade definida é baseada na moeda de origem ou destino, devolvendo o valor calculado
*/
function converterMoeda($quantidade, $taxa, $taxaImposto, $tipoQuantidade) {
    // Se a quantidade é da moeda de destino, reverter a taxa de conversão
    if ($tipoQuantidade == 'destino') {
        $taxa = 1 / $taxa;
    }
    return ($quantidade * $taxa) * (1 - $taxaImposto / 100);
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $moedaOrigem = $_POST['moeda_origem'];
    $moedaDestino = $_POST['moeda_destino'];
    $quantidade = $_POST['quantidade'];
    $taxaImposto = $_POST['taxa_imposto'];
    $tipoQuantidade = $_POST['tipo_quantidade'];

    // Verifica se existe a taxa de conversão para as moedas escolhidas
    if (isset($moedas[$moedaOrigem][$moedaDestino])) {
        $taxa = $moedas[$moedaOrigem][$moedaDestino];
        $resultado = converterMoeda($quantidade, $taxa, $taxaImposto, $tipoQuantidade);
        $mensagem = "Conversão: $quantidade $moedaOrigem para $moedaDestino<br>";
        $mensagem .= "Taxa de conversão: 1 $moedaOrigem = " . number_format($taxa, 2) . " $moedaDestino<br>";
        $mensagem .= "Taxa de imposto: $taxaImposto%<br>";
        $mensagem .= "Resultado: " . number_format($resultado, 2) . " $moedaDestino";
    } else {
        $mensagem = "Conversão entre $moedaOrigem e $moedaDestino não está disponível.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor de Moedas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            text-align: center;
            padding: 20px;
        }
        h1 {
            color: #2e8b57;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        select, input[type="number"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #2e8b57;
            color: white;
            cursor: pointer;
            border: none;
        }
        input[type="submit"]:hover {
            background-color: #267147;
        }
        p {
            background-color: #e6f4ea;
            border: 1px solid #c1e1c1;
            padding: 15px;
            border-radius: 8px;
            max-width: 400px;
            margin: 20px auto;
            color: #2e8b57;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Conversor de Moedas</h1>
    
    <form method="POST" action="">
        <label for="moeda_origem">Moeda de Origem:</label>
        <select name="moeda_origem" id="moeda_origem" required>
            <?php
            // Gerar dinamicamente as opções da moeda de origem
            foreach ($moedas as $moedaOrigem => $destinos) {
                echo "<option value=\"$moedaOrigem\">$moedaOrigem</option>";
            }
            ?>
        </select>

        <label for="moeda_destino">Moeda de Destino:</label>
        <select name="moeda_destino" id="moeda_destino" required>
            <?php
            // Gerar dinamicamente as opções da moeda de destino com base nas moedas disponíveis
            foreach ($moedas as $moedaDestino => $origem) {
                echo "<option value=\"$moedaDestino\">$moedaDestino</option>";
            }
            ?>
        </select>

        <label for="quantidade">Quantidade:</label>
        <input type="number" step="0.01" name="quantidade" id="quantidade" required>

        <label for="tipo_quantidade">Quantidade é de:</label>
        <select name="tipo_quantidade" id="tipo_quantidade" required>
            <option value="origem">Moeda de Origem</option>
            <option value="destino">Moeda de Destino</option>
        </select>

        <label for="taxa_imposto">Taxa de Imposto (%):</label>
        <input type="number" step="0.01" name="taxa_imposto" id="taxa_imposto" required>

        <input type="submit" value="Converter">
    </form>

    <?php if (isset($mensagem)): ?>
        <p><?php echo $mensagem; ?></p>
    <?php endif; ?>
</body>
</html>
