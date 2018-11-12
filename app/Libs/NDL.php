<?php
declare(strict_types=1);

namespace App\Libs;

class NDL
{
    protected $endpoint = 'http://iss.ndl.go.jp/api/opensearch';
    protected $curlopt = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 3,
    ];
    protected $regexp = [
        '/(dc((ndl)?|(terms)?)|rdfs?|xsi|openSearch):/',
        '/(.+?) \[?(著?|共著?)\]?/',
    ];
    protected $obj;

    public function query($code): array
    {
        $this->obj = $this->getItem($this->getRequestURL($code));
        if ($this->obj === null) {
            return [];
        }

        return [
            'title'          => $this->getTitle(),
            'volume'         => $this->getVolume(),
            'authors'        => $this->getAuthors(),
            'isbn'           => $this->getISBN(),
            'jpno'           => $this->getJPNO(),
            'ndl_url'        => $this->getBookUrl(),
        ];
    }

    protected function getRequestURL(string $code): string
    {
        return $this->endpoint.'?'.http_build_query([
            $this->searchType($code) => $code,
        ]);
    }

    protected function getChannel(string $url): ?object
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, $this->curlopt);

        $retry = -1;
        $content = null;
        $errorNo = null;
        while ($errorNo !== CURLE_OK && $retry < 3) {
            $content = curl_exec($ch);
            $errorNo = curl_errno($ch);
            $retry++;
        }

        if ($content !== null) {
            $xml = preg_replace($this->regexp[0], '', $content);

            return simplexml_load_string($xml)->channel;
        } else {
            throw new \Exception('Retry limit reached');
        }

        return null;
    }

    public function getItem(string $url): ?object
    {
        $channel = $this->getChannel($url);
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
                    return $this->isbn10to13($isbn);
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

    public function getBookUrl(): string
    {
        return (string)$this->obj->link;
    }

    protected function searchType($num): string
    {
        $type = 'any';
        switch (strlen($num)) {
            case 8: $type = 'jpno'; break;
            case 10: $type = 'isbn'; break;
            case 13: $type = 'isbn'; break;
        }

        return $type;
    }

    public function isbn10to13(string $isbn): string
    {
        $isbn13 = '978' . substr($isbn, 0, 9);
        $digit = 0;

        for ($i = 0; $i < 12; $i++) {
            $digit += ($i + 1) % 2 > 0 ? $isbn13[$i] * 1 : $isbn13[$i] * 3;
        }
        $n = $digit % 10;

        return $n ? $isbn13 . (10 - $n) : $isbn13 . '0';
    }

    public function isbn13to10(string $isbn): string
    {
        $isbn10 = substr($isbn, 3, -1);
        $digit = 0;

        for ($i = 0; $i < 9; $i++) {
            $digit += $isbn10[$i] * (10 - $i);
        }

        $n = $digit % 11;
        if ($n) {
            $n = 11 - $n;
        }

        return $isbn10 . ($n < 10 ? $n : 'X');
    }
}
