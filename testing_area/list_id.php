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
 * Gets all sets available from the API
 *
 * @return array The set data
 */
function getAllSets(): array
{
    $url = "https://api.pokemontcg.io/v2/sets";
    $response = executeApiRequest($url);

    return $response['data'] ?? [];
}

// Fetch all sets
$sets = getAllSets();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon TCG Sets</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f2f2f2;
        }

        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <main>
        <h1>Pokémon TCG Sets</h1>
        <table>
            <thead>
                <tr>
                    <th>Set Name</th>
                    <th>Set ID</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sets)): ?>
                    <?php foreach ($sets as $set): ?>
                        <tr>
                            <td><?= htmlspecialchars($set['name']) ?></td>
                            <td><?= htmlspecialchars($set['id']) ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($set['images']['logo']) ?>" alt="<?= htmlspecialchars($set['name']) ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No sets found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
