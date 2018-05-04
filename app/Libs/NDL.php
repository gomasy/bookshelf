<?php

namespace App\Libs;

/**
 * The wrapper for National Diet Library. (OpenSearch)
 *
 * Copyright(c) 2017 Gomasy.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
class NDL {
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

    public function query($code)
    {
        $this->obj = $this->getItem($this->getRequestURL($code));

        if (isset($this->obj)) {
            return [
                'title'          => $this->getTitle(),
                'volume'         => $this->getVolume(),
                'authors'        => $this->getAuthors(),
                'isbn'           => $this->getISBN(),
                'jpno'           => $this->getJPNO(),
                'published_date' => $this->getPublishedDate(),
                'ndl_url'        => $this->getBookUrl(),
            ];
        }
    }

    protected function getRequestURL($code)
    {
        return $this->endpoint.'?'.http_build_query([
            $this->searchType($code) => $code,
        ]);
    }

    protected function getChannel($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, $this->curlopt);

        $retry = -1;
        $content = NULL;
        $errorNo = NULL;
        while ($errorNo !== CURLE_OK && $retry < 3) {
            $content = curl_exec($ch);
            $errorNo = curl_errno($ch);
            $retry++;
        }

        if ($content !== NULL) {
            $xml = preg_replace($this->regexp[0], '', $content);

            return simplexml_load_string($xml)->channel;
        } else {
            throw new \Exception('Retry limit reached');
        }
    }

    public function getItem($url)
    {
        $channel = $this->getChannel($url);
        for ($i = 0; $i < $channel->totalResults; $i++) {
            if ((string)$channel->item[$i]->category !== '障害者向け資料' && isset($channel->item[$i]->pubDate))
                return $channel->item[$i];
        }
    }

    public function getISBN()
    {
        $isbn = NULL;
        foreach ($this->obj->identifier as $val) {
            switch (strlen($val)) {
                case 13: $isbn = $val; break;
                case 10: $isbn = $this->isbn10to13($val); break;
            }
        }

        return (string)$isbn;
    }

    public function getJPNO()
    {
        $jpno = NULL;
        foreach ($this->obj->identifier as $val)
            if (strlen($val) === 8) $jpno = $val;

        return (string)$jpno;
    }

    public function getTitle()
    {
        return (string)$this->obj->title;
    }

    public function getVolume()
    {
        return (string)$this->obj->volume;
    }

    public function getAuthors()
    {
        $authors = rtrim((string)$this->obj->author, ',');

        return preg_match($this->regexp[1], $authors, $str) ? $str[1] : $authors;
    }

    public function getPublishedDate()
    {
        return date('Y-m-d', strtotime((string)$this->obj->pubDate));
    }

    public function getBookUrl()
    {
        return (string)$this->obj->link;
    }

    protected function searchType($num)
    {
        $type = 'any';
        switch (strlen($num)) {
            case 8: $type = 'jpno'; break;
            case 10: $type = 'isbn'; break;
            case 13: $type = 'isbn'; break;
        }

        return $type;
    }

    public function isbn10to13($isbn)
    {
        $isbn13 = '978'.substr($isbn, 0, 9);
        $digit = 0;

        for ($i = 0; $i < 12; $i++)
            $digit += ($i + 1) % 2 > 0 ? $isbn13[$i] * 1 : $isbn13[$i] * 3;
        $n = $digit % 10;

        return $n ? $isbn13.(10 - $n) : $isbn13.'0';
    }
}
