<?php

declare(strict_types=1);

class MeteobridgeService
{
    private array $config;
    private string $url;
    private string $username;
    private string $password;
    private int $timeout;
    private string $template;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config.php';

        if (!isset($config['meteobridge'])) {
            throw new RuntimeException(
                'Configurazione Meteobridge non trovata.'
            );
        }

        $this->config = $config['meteobridge'];
        $this->url = rtrim($this->config['url'], '/');
        $this->username = $this->config['username'];
        $this->password = $this->config['password'];
        $this->timeout = (int)($this->config['timeout'] ?? 10);
        $this->template = $this->config['template'];
    }

    private function buildUrl(): string
    {
        return $this->url .
            '/cgi-bin/template.cgi?template=' .
            urlencode($this->template);
    }

    /**
 * Esegue la richiesta HTTP alla Meteobridge.
 */
private function request(string $url): string
{
    $ch = curl_init($url);

    if ($ch === false) {
        throw new RuntimeException(
            'Impossibile inizializzare cURL.'
        );
    }

    curl_setopt_array($ch, [

        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => $this->timeout,
        CURLOPT_TIMEOUT => $this->timeout,
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_USERPWD => $this->username . ':' . $this->password,
        CURLOPT_FAILONERROR => false

    ]);

    $response = curl_exec($ch);

    if ($response === false) {

        $error = curl_error($ch);

        curl_close($ch);

        throw new RuntimeException(
            'Errore cURL: ' . $error
        );
    }

    $httpCode = (int) curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    curl_close($ch);

    if ($httpCode !== 200) {

        throw new RuntimeException(
            'Meteobridge ha restituito HTTP ' . $httpCode
        );
    }

    return trim($response);
}

    /**
 * Converte la risposta della Meteobridge in un array associativo.
 */
private function parse(string $response): array
{
    if ($response === '') {

        throw new RuntimeException(
            'La Meteobridge ha restituito una risposta vuota.'
        );
    }

    $values = explode('|', trim($response));

    if (count($values) !== 11) {

        throw new RuntimeException(
            sprintf(
                'Numero di campi non valido. Attesi 11, ricevuti %d.',
                count($values)
            )
        );
    }

    return [

        'temperature' => (float) $values[0],
        'humidity'    => (int) $values[1],
        'pressure'    => (float) $values[2],
        'wind'        => (float) $values[3],
        'gust'        => (float) $values[4],
        'winddir'     => (int) $values[5],
        'rain'        => (float) $values[6],
        'uv'          => (float) $values[7],
        'solar'       => (float) $values[8],
        'dew'         => (float) $values[9],
        'feels'       => (float) $values[10]

    ];
}
    /**
 * Valida i dati ricevuti dalla Meteobridge.
 */
private function validate(array $data): array
{
    $ranges = [

        'temperature' => [-50, 60],
        'humidity'    => [0, 100],
        'pressure'    => [800, 1100],
        'wind'        => [0, 300],
        'gust'        => [0, 300],
        'winddir'     => [0, 360],
        'rain'        => [0, 500],
        'uv'          => [0, 20],
        'solar'       => [0, 2000],
        'dew'         => [-50, 60],
        'feels'       => [-50, 80]

    ];

    foreach ($ranges as $field => [$min, $max]) {

        if (!array_key_exists($field, $data)) {

            throw new RuntimeException(
                "Campo mancante: {$field}"
            );
        }

        if ($data[$field] < $min || $data[$field] > $max) {

            throw new RuntimeException(
                sprintf(
                    'Valore non valido per %s (%s)',
                    $field,
                    $data[$field]
                )
            );
        }
    }

    return $data;
}

    /**
 * Recupera e valida i dati dalla Meteobridge.
 */
public function fetch(): array
{
    $url = $this->buildUrl();

    $response = $this->request($url);

    $data = $this->parse($response);

    return $this->validate($data);
}
}