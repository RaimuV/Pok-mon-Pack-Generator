<?php
/**
 * Esegue una richiesta all'API Pokémon TCG
 *
 * @param string $url URL dell'API da chiamare
 * @return array Risultato decodificato dalla risposta JSON
 */
function executeApiRequest(string $url): array
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "Errore nella richiesta: " . curl_error($ch);
        curl_close($ch);
        return [];
    }

    curl_close($ch);
    return json_decode($response, true);
}

/**
 * Ottiene un pacchetto casuale di carte Pokémon
 *
 * @param int $count Numero di carte nel pacchetto
 * @return array Dati delle carte
 */
function getRandomPokemonPack(int $count = 5): array
{
    // Calcola una pagina casuale (simulando casualità)
    $totalCards = 12000; // Stima del numero totale di carte disponibili nell'API
    $maxPage = ceil($totalCards / $count);
    $randomPage = rand(1, $maxPage);

    $url = "https://api.pokemontcg.io/v2/cards?page=$randomPage&pageSize=$count";
    $response = executeApiRequest($url);

    return $response['data'] ?? [];
}

/**
 * Stampa i dettagli di una carta
 *
 * @param array $card Dati della carta
 */
function printCardDetails(array $card): void
{
    echo "<div class='card animated'>";
    echo "<img src='". htmlspecialchars($card['images']['large'] ?? '') . "'>";
    echo "</div>";
}

// Genera le carte se il pulsante è stato premuto
$cards = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cards = getRandomPokemonPack();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generatore di Pacchetti Pokémon</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main id="app">
        <h1>Pokémon Pack Generator</h1>
        <form method="post">
            <button type="submit">Genera un pacchetto casuale</button>
        </form>
        <section class="cards">
            <?php if (!empty($cards)): ?>
                <?php foreach ($cards as $card): ?>
                    <?php printCardDetails($card); ?>
                    <?php //echo "<br>"; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
