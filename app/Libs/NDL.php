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
    protected $regexp = [
        '/(dc((ndl)?|(terms)?)|rdfs?|xsi|openSearch):/',
        '/(.+?) \[?(著?|共著?)\]?/',
    ];
    protected $obj;

    public function query($code) {
        $this->obj = $this->getItem($this->getChannel($code));

        if (isset($this->obj)) {
            return [
                'title'          => $this->getTitle(),
                'title_ruby'     => $this->getTitleRuby(),
                'volume'         => $this->getVolume(),
                'authors'        => $this->getAuthors(),
                'isbn'           => $this->getISBN(),
                'jpno'           => $this->getJPNO(),
                'published_date' => $this->getPublishedDate(),
                'ndl_url'        => $this->getBookUrl(),
            ];
        }
    }

    protected function getRequestURL($code) {
        return $this->endpoint.'?'.http_build_query([
            $this->searchType($code) => $code,
        ]);
    }

    protected function getChannel($code) {
        $content = file_get_contents($this->getRequestURL($code));
        $xml = preg_replace($this->regexp[0], '', $content);

        return simplexml_load_string($xml)->channel;
    }

    protected function getItem($channel) {
        for ($i = 0; $i < $channel->totalResults; $i++) {
            if ((string)$channel->item[$i]->category !== '障害者向け資料' && isset($channel->item[$i]->pubDate))
                return $channel->item[$i];
        }
    }

    protected function getISBN() {
        foreach ($this->obj->identifier as $val) {
            switch (strlen($val)) {
                case 13:
                    return $val;
                case 10:
                    return $this->isbn10to13($val);
            }
        }
    }

    protected function getJPNO() {
        foreach ($this->obj->identifier as $val) {
            if (strlen($val) === 8) return $val;
        }
    }

    protected function getTitle() {
        return (string)$this->obj->title;
    }

    protected function getTitleRuby() {
        return (string)$this->obj->titleTranscription;
    }

    protected function getVolume() {
        return (string)$this->obj->volume;
    }

    protected function getAuthors() {
        $authors = rtrim((string)$this->obj->author, ',');

        return preg_match($this->regexp[1], $authors, $str) ? $str[1] : $authors;
    }

    protected function getPublishedDate() {
        return date('Y-m-d', strtotime((string)$this->obj->pubDate));
    }

    protected function getBookUrl() {
        return (string)$this->obj->link;
    }

    protected function searchType($num) {
        switch (strlen($num)) {
            case 8:
                return 'jpno';
            case 10:
                return 'isbn';
            case 13:
                return 'isbn';
        }
    }

    public function isbn10to13($isbn) {
        $isbn13 = '978'.substr($isbn, 0, 9);
        $digit = 0;

        for ($i = 0; $i < 12; $i++)
            $digit += ($i + 1) % 2 > 0 ? $isbn13[$i] * 1 : $isbn13[$i] * 3;
        $n = $digit % 10;

        return $n ? $isbn13.(10 - $n) : $isbn13.'0';
    }
}
