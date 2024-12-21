<?php

/**
 * Executes a request to the Pokémon TCG API
 *
 * @param string $url The API URL to call
 * @return array Decoded result from the JSON response
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
 * @param int $count Number of cards in the pack
 * @return array Card data
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
 * @param array $card Card data
 */
function printCardDetails(array $card): void
{
    echo "<div class='card animated'>";
    echo "<img src='" . htmlspecialchars($card['images']['large'] ?? '') . "'>";
    echo "</div>";
}

// Generate cards if the button has been pressed
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
    <style>
        /* Loading screen */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%; /* Use the width of the viewport */
            height: 100%; /* Use the height of the viewport */
            background-color: #333844; /* Dark translucent background */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999; /* Ensure it is above everything else */
        }

        .loading-container {
            text-align: center;
            color: white;
        }

        .loading-img {
            width: 100px; /* You can choose the desired size */
            margin-bottom: 20px;
        }

        /* Poké Ball animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .loading-img {
            animation: spin 2s linear infinite; /* Rotate the Poké Ball infinitely */
        }
    </style>
</head>
<body>
    <!-- Loading screen -->
    <div id="loading-screen" class="loading-screen">
        <div class="loading-container">
            <img src="res/loading.gif" alt="Loading..." class="loading-img">
            <p>Loading Pokémon Pack...</p>
        </div>
    </div>

    <main id="app">
        <h1>Pokémon Pack Generator</h1>
        <form method="post">
            <button type="submit"> Generate a random pack</button>
        </form>
        <section class="cards">
            <?php if (!empty($cards)): ?>
                <?php foreach ($cards as $card): ?>
                    <?php printCardDetails($card); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <script>
        // Manage the loading screen
        window.addEventListener('load', function() {
            // Hide the loading screen after 3 seconds
            setTimeout(function() {
                document.getElementById('loading-screen').style.display = 'none';
            }, 800); // 800 milliseconds
        });
    </script>
</body>
</html>
