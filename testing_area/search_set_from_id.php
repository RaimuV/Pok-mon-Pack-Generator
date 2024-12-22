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
 * Gets all cards from a specific Pokémon TCG set
 *
 * @param string $setId The ID of the set
 * @return array The card data
 */
function getCardsFromSet(string $setId): array
{
    $url = "https://api.pokemontcg.io/v2/cards?q=set.id:$setId&pageSize=250";
    $response = executeApiRequest($url);

    return $response['data'] ?? [];
}

// Fetch cards if a set ID is provided
$cards = [];
$setId = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_id'])) {
    $setId = htmlspecialchars($_POST['set_id']);
    $cards = getCardsFromSet($setId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon TCG Set Viewer</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background-color: #f2f2f2;
            text-align: left;
        }

        img {
            width: 150px;
            height: auto;
        }

        a img:hover {
            transform: scale(1.1);
            transition: transform 0.2s;
        }
    </style>
</head>
<body>
    <main id="app">
        <h1>Pokémon TCG Set Viewer</h1>
        <form method="post">
            <label for="set_id">Enter Set ID:</label>
            <input type="text" id="set_id" name="set_id" placeholder="e.g., base1" required>
            <button type="submit">Show Cards</button>
        </form>

        <?php if (!empty($cards)): ?>
            <h2>Cards in Set: <?= htmlspecialchars($setId) ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Rarity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cards as $card): ?>
                        <tr>
                            <td>
                                <a href="<?= htmlspecialchars($card['images']['large'] ?? '#') ?>" target="_blank">
                                    <img src="<?= htmlspecialchars($card['images']['large'] ?? '') ?>" alt="<?= htmlspecialchars($card['name'] ?? 'Card Image') ?>">
                                </a>
                            </td>
                            <td><?= htmlspecialchars($card['name'] ?? 'Unknown') ?></td>
                            <td><?= htmlspecialchars($card['rarity'] ?? 'Unknown') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p>No cards found for the set ID: <?= htmlspecialchars($setId) ?></p>
        <?php endif; ?>
    </main>
</body>
</html>
