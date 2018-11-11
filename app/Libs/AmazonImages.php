<?php
declare(strict_types=1);

namespace App\Libs;

class AmazonImages
{
    protected $endpoint = 'http://images-jp.amazon.com';
    protected $countryCode = '09';
    protected $types = [
        'thumb' => 'THUMBZZZ',
        'small' => 'TZZZZZZZ',
        'medium' => 'MZZZZZZZ',
        'large' => 'LZZZZZZZ',
    ];

    public function all($isbn10, $endpoint = null): array
    {
        $url = [];
        foreach (array_keys($this->types) as $type) {
            $url = array_merge($url, [ $type => $this->single($isbn10, $type, $endpoint) ]);
        }

        return $url;
    }

    public function single($isbn10, $type, $endpoint = null): string
    {
        if ($endpoint === null) {
            $endpoint = $this->endpoint;
        }

        return "{$endpoint}/images/P/{$isbn10}.{$this->countryCode}.{$this->types[$type]}";
    }

    public function fetch($path): string
    {
        return file_get_contents("{$this->endpoint}/{$path}");
    }
}
