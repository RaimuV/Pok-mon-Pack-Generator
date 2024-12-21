<?php
// Configura l'API Key
define("API_KEY", "YOUR_API_KEY"); // Sostituisci con la tua chiave API

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
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-Api-Key: " . API_KEY
    ]);

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
function getRandomPokemonPack(int $count = 10): array
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
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px; display: inline-block; text-align: center;'>";
    echo "<h3>" . htmlspecialchars($card['name'] ?? "Sconosciuto") . "</h3>";
    echo "<img src='" . htmlspecialchars($card['images']['large'] ?? '') . "' alt='Carta Pokémon' style='width: 250px; height: auto;'><br>";
    echo "<p><strong>HP:</strong> " . htmlspecialchars($card['hp'] ?? "N/A") . "</p>";
    echo "<p><strong>Tipo:</strong> " . implode(", ", $card['types'] ?? []) . "</p>";
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
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Generatore di Pacchetti Pokémon Casuali</h1>
    <form method="post">
        <button type="submit">Genera un pacchetto casuale</button>
    </form>
    <div>
        <?php if (!empty($cards)): ?>
            <h2>Le tue carte Pokémon</h2>
            <?php foreach ($cards as $card): ?>
                <?php printCardDetails($card); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
