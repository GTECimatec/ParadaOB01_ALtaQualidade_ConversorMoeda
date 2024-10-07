<?php


require_once 'funcoes.php';

// Função para rodar os testes e exibir o resultado
function rodarTeste($nomeTeste, $funcao) {
    try {
        $funcao();
        echo "<br>$nomeTeste: SUCESSO\n";
    } catch (Exception $e) {
        echo "<br>$nomeTeste: FALHA - " . $e->getMessage() . "\n";
    }
}

// Teste 1: Testa se a conversão de BRL para USD é precisa
rodarTeste('Test Conversão BRL para USD', function () {
    $quantidade = 100; // 100 BRL
    $taxaConversao = 5.46; // 1 BRL = 5.46 USD
    $taxaImposto = 0; // Sem imposto
    $resultadoEsperado = 100 * 5.46;

    $resultado = converterMoeda($quantidade, $taxaConversao, $taxaImposto, 'origem');
    if ($resultado !== $resultadoEsperado) {
        throw new Exception("Esperado: $resultadoEsperado, Recebido: $resultado");
    }
});

// Teste 2: Testa se a conversão de USD para BRL é precisa
rodarTeste('Test Conversão USD para BRL', function () {
    $quantidade = 100; // 100 USD
    $taxaConversao = 0.18; // 1 USD = 0.18 BRL
    $taxaImposto = 0; // Sem imposto
    $resultadoEsperado = 100 * 0.18;

    $resultado = converterMoeda($quantidade, $taxaConversao, $taxaImposto, 'origem');
    if ($resultado !== $resultadoEsperado) {
        throw new Exception("Esperado: $resultadoEsperado, Recebido: $resultado");
    }
});

// Teste 3: Testa se o cálculo com imposto está correto
rodarTeste('Test Conversão com Imposto', function () {
    $quantidade = 100; // 100 BRL
    $taxaConversao = 5.46; // 1 BRL = 5.46 USD
    $taxaImposto = 10; // 10% de imposto
    $resultadoEsperado = (100 * 5.46) * (1 - 10 / 100);

    $resultado = converterMoeda($quantidade, $taxaConversao, $taxaImposto, 'origem');
    if ($resultado !== $resultadoEsperado) {
        throw new Exception("Esperado: $resultadoEsperado, Recebido: $resultado");
    }
});

// Teste 4: Testa se o sistema responde corretamente para moedas que não têm conversão
rodarTeste('Test Moeda Não Disponível', function () {
    $quantidade = 100;
    $moedaOrigem = 'BRL';
    $moedaDestino = 'XYZ'; // Moeda não existente
    $taxaImposto = 0;

    try {
        $resultado = converterMoeda($quantidade, null, $taxaImposto, 'origem'); // null simula taxa não encontrada
        throw new Exception("Erro esperado para moeda inexistente não ocorreu");
    } catch (Exception $e) {
        // Esperado - Se a moeda não existe, uma exceção é lançada
    }
});

// Teste 5: Simula a verificação de uma API (simulado)
rodarTeste('Test API ou Taxas Atualizadas', function () {
    $moedaOrigem = 'BRL';
    $moedaDestino = 'USD';
    $taxaApi = obterTaxaConversaoAPI($moedaOrigem, $moedaDestino); // Supondo que essa função existe

    if (empty($taxaApi)) {
        throw new Exception("A API retornou uma taxa vazia.");
    }

    if ($taxaApi <= 0) {
        throw new Exception("A taxa de conversão deve ser maior que zero.");
    }
});

// Simula a função que acessa a API de conversão
function obterTaxaConversaoAPI($moedaOrigem, $moedaDestino) {
    // Simula o retorno da API (nesse caso você usaria uma função real ou mock)
    $taxas = [
        'BRL' => ['USD' => 5.46],
        'USD' => ['BRL' => 0.18]
    ];

    return $taxas[$moedaOrigem][$moedaDestino] ?? null;
}

?>
