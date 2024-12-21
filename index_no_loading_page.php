<?php
/**
 * Executes a request to the Pokémon TCG API
 *
 * @param string $url The API URL to call
 * @return array The result decoded from the JSON response
 */
function executeApiRequest(string $url): array
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "Request error: " . curl_error($ch);
        curl_close($ch);
        return [];
    }

    curl_close($ch);
    return json_decode($response, true);
}

/**
 * Gets a random Pokémon pack
 *
 * @param int $count The number of cards in the pack
 * @return array The card data
 */
function getRandomPokemonPack(int $count = 5): array
{
    // Calculate a random page (simulating randomness)
    $totalCards = 12000; // Estimate of the total number of cards available in the API
    $maxPage = ceil($totalCards / $count);
    $randomPage = rand(1, $maxPage);

    $url = "https://api.pokemontcg.io/v2/cards?page=$randomPage&pageSize=$count";
    $response = executeApiRequest($url);

    return $response['data'] ?? [];
}

/**
 * Prints the details of a card
 *
 * @param array $card The card data
 */
function printCardDetails(array $card): void
{
    echo "<div class='card animated'>";
    echo "<img src='" . htmlspecialchars($card['images']['large'] ?? '') . "'>";
    echo "</div>";
}

// Generate cards if the button was pressed
$cards = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cards = getRandomPokemonPack();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Pack Generator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main id="app">
        <h1>Pokémon Pack Generator</h1>
        <form method="post">
            <button type="submit"> Generate a random pack</button>
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
