<?php
declare(strict_types=1);

namespace App\Libs;

use GuzzleHttp\Exception\ConnectException;

use App\Book;

use App\Exceptions\TimeoutException;

class NDL
{
    protected $endpoint = 'http://iss.ndl.go.jp/api/opensearch';
    protected $regexp = [
        '/(dc((ndl)?|(terms)?)|rdfs?|xsi|openSearch):/',
        '/(.+?) \[?(著?|共著?)\]?/',
    ];
    protected $timeout = [
        'code' => 1,
        'title' => 10,
    ];
    protected $client;
    protected $type;
    protected $obj;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->endpoint,
        ]);
    }

    public function query(string $payload, string $type): array
    {
        $items = [];
        $this->type = $type;

        foreach ($this->getItems($payload) as $obj) {
            $this->obj = $obj;

            array_push($items, (new Book([
                'title'     => $this->getTitle(),
                'volume'    => $this->getVolume(),
                'authors'   => $this->getAuthors(),
                'isbn'      => $this->getISBN(),
                'jpno'      => $this->getJPNO(),
                'publisher' => $this->getPublisher(),
                'price'     => $this->getPrice(),
                'ndl_url'   => $this->getBookUrl(),
            ]))->toArray());
        }

        return $items;
    }

    protected function getQueryString(string $payload): string
    {
        $type = $this->type;
        if ($type === 'code') {
            $type = $this->searchType($payload);
        }

        return '?' . http_build_query([ $type => $payload ]);
    }

    protected function getContent(string $path): string
    {
        $retry = 0;

        while ($retry <= 3) {
            try {
                return (string)$this->client
                    ->request('GET', $path, [ 'timeout' => $this->timeout[$this->type] ])
                    ->getBody();
            } catch (ConnectException $e) {
                $retry++;
            }
        }

        throw new TimeoutException('Retry limit reached');
    }

    protected function getChannel(string $path): object
    {
        $content = $this->getContent($path);
        $xml = preg_replace($this->regexp[0], '', $content);

        return simplexml_load_string($xml)->channel;
    }

    public function getItems(string $payload): array
    {
        $channel = $this->getChannel($this->getQueryString($payload));
        $items = [];
        for ($i = 0; $i < $channel->totalResults; $i++) {
            $item = $channel->item[$i];
            if (isset($item->category) && (string)$item->category !== '障害者向け資料' && isset($item->pubDate)) {
                array_push($items, $channel->item[$i]);
            }
        }

        return $items;
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
        $len = strlen($num);
        if ($len === 8) {
            return 'jpno';
        } elseif ($len === 10 || $len === 13) {
            return 'isbn';
        }
    }

    public function verifyCheckDigit(string $isbn): bool
    {
        switch (strlen($isbn)) {
            case 10:
                return $this->getCheckDigit10($isbn) === $isbn[9];
            case 13:
                return $this->getCheckDigit13($isbn) === $isbn[12];
            default:
                return true;
        }
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
