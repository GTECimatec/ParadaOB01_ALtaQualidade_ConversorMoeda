<?php

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

?>