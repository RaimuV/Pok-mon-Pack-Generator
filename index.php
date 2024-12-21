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
    <title>Pokémon Pack Generator</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Schermata di caricamento */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%; /* Usa la larghezza della viewport */
            height: 100%; /* Usa l'altezza della viewport */
            background-color: #333844; /* Sfondo traslucido scuro */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999; /* Assicurati che sia sopra a tutto */
        }

        .loading-container {
            text-align: center;
            color: white;
        }

        .loading-img {
            width: 100px; /* Puoi scegliere la dimensione desiderata */
            margin-bottom: 20px;
        }

        /* Animazione della Poké Ball */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

.loading-img {
    animation: spin 2s linear infinite; /* Ruota la Poké Ball all'infinito */
}

    </style>
</head>
<body>
    <!-- Schermata di caricamento -->
    <div id="loading-screen" class="loading-screen">
        <div class="loading-container">
            <img src="res/loading.gif" alt="Loading..." class="loading-img">
            <p>Caricando il Pacchetto Pokémon...</p>
        </div>
    </div>

    <main id="app">
        <h1>Pokémon Pack Generator</h1>
        <form method="post">
            <button type="submit">Genera un pacchetto casuale</button>
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
        // Gestire la schermata di caricamento
        window.addEventListener('load', function() {
            // Nascondi la schermata di caricamento dopo 3 secondi
            setTimeout(function() {
                document.getElementById('loading-screen').style.display = 'none';
            }, 800); // 3000 millisecondi = 3 secondi
        });
    </script>
</body>
</html>
