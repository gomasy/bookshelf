<?php
declare(strict_types=1);

namespace App\Libs;

use \GuzzleHttp\Exception\ConnectException;

class NDL
{
    protected $endpoint = 'http://iss.ndl.go.jp/api/opensearch';
    protected $regexp = [
        '/(dc((ndl)?|(terms)?)|rdfs?|xsi|openSearch):/',
        '/(.+?) \[?(著?|共著?)\]?/',
    ];
    protected $client;
    protected $obj;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->endpoint,
            'timeout' => 1,
        ]);
    }

    public function query(string $code): array
    {
        $this->obj = $this->getItem($code);
        if ($this->obj === null) {
            return [];
        }

        return [
            'title'     => $this->getTitle(),
            'volume'    => $this->getVolume(),
            'authors'   => $this->getAuthors(),
            'isbn'      => $this->getISBN(),
            'jpno'      => $this->getJPNO(),
            'publisher' => $this->getPublisher(),
            'price'     => $this->getPrice(),
            'ndl_url'   => $this->getBookUrl(),
        ];
    }

    protected function getQueryString(string $code): string
    {
        return '?' . http_build_query([
            $this->searchType($code) => $code,
        ]);
    }

    protected function getChannel(string $path): ?object
    {
        $content = null;
        $retry = 0;

        while ($retry <= 3) {
            try {
                $content = (string)$this->client->request('GET', $path)->getBody();
                break;
            } catch (ConnectException $e) {
                $retry++;
            }
        }

        if ($content !== null) {
            $xml = preg_replace($this->regexp[0], '', $content);

            return simplexml_load_string($xml)->channel;
        } else {
            throw new \Exception('Retry limit reached');
        }

        return null;
    }

    public function getItem(string $code): ?object
    {
        $channel = $this->getChannel($this->getQueryString($code));
        for ($i = 0; $i < $channel->totalResults; $i++) {
            if ((string)$channel->item[$i]->category !== '障害者向け資料' && isset($channel->item[$i]->pubDate)) {
                return $channel->item[$i];
            }
        }

        return null;
    }

    public function getISBN(): ?string
    {
        foreach ($this->obj->identifier as $obj) {
            if ((string)$obj['type'] === 'ISBN') {
                $isbn = (string)$obj;
                if (strlen($isbn) === 10) {
                    return $this->toISBN13($isbn);
                }

                return $isbn;
            }
        }

        return null;
    }

    public function getJPNO(): ?string
    {
        foreach ($this->obj->identifier as $obj) {
            if ((string)$obj['type'] === 'JPNO') {
                return (string)$obj;
            }
        }

        return null;
    }

    public function getTitle(): string
    {
        return (string)$this->obj->title;
    }

    public function getVolume(): string
    {
        return (string)$this->obj->volume;
    }

    public function getAuthors(): string
    {
        $authors = rtrim((string)$this->obj->author, ',');

        return preg_match($this->regexp[1], $authors, $str) ? $str[1] : $authors;
    }

    public function getPublisher(): string
    {
        return (string)$this->obj->publisher;
    }

    public function getPrice(): string
    {
        return (string)$this->obj->price;
    }

    public function getBookUrl(): string
    {
        return (string)$this->obj->link;
    }

    protected function searchType(string $num): string
    {
        switch (strlen($num)) {
            case 8:
                return 'jpno';
            case 10:
                return 'isbn';
            case 13:
                return 'isbn';
            default:
                return 'any';
        }
    }

    public function verifyCheckDigit(string $isbn): bool
    {
        switch (strlen($isbn)) {
            case 10:
                return $this->getCheckDigit10($isbn) === $isbn[9];
            case 13:
                return $this->getCheckDigit13($isbn) === $isbn[12];
        }

        return true;
    }

    public function getCheckDigit13(string $isbn13): string
    {
        $digit = 0;
        for ($i = 0; $i < strlen($isbn13) - 1; $i++) {
            $digit += ($i + 1) % 2 ? $isbn13[$i] * 1 : $isbn13[$i] * 3;
        }
        $n = $digit % 10;

        return $n ? (string)(10 - $n) : '0';
    }

    public function getCheckDigit10(string $isbn10): string
    {
        $digit = 0;
        for ($i = 0; $i < strlen($isbn10) - 1; $i++) {
            $digit += $isbn10[$i] * (10 - $i);
        }

        $n = $digit % 11;
        if ($n) {
            $n = 11 - $n;
        }

        return $n < 10 ? (string)$n : 'X';
    }

    public function toISBN13(string $isbn10): string
    {
        $isbn13 = '978' . substr($isbn10, 0, 9);

        return $isbn13 . $this->getCheckDigit13($isbn13 . '0');
    }

    public function toISBN10(string $isbn13): string
    {
        $isbn10 = substr($isbn13, 3, -1);

        return $isbn10 . $this->getCheckDigit10($isbn10 . '0');
    }
}
