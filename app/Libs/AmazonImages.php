<?php
declare(strict_types=1);

namespace App\Libs;

class AmazonImages
{
    protected $endpoint = 'http://images-jp.amazon.com';
    protected $path = 'images/P/';
    protected $countryCode = '09';
    protected $types = [
        'thumb' => 'THUMBZZZ',
        'small' => 'TZZZZZZZ',
        'medium' => 'MZZZZZZZ',
        'large' => 'LZZZZZZZ',
    ];
    protected $sizes = [
        'thumb' => [ [ 52, 75 ], [ 2, 3, 28 ] ],
        'small' => [ [ 77, 110 ], [ 3, 11, 45 ] ],
        'medium' => [ [ 112, 160 ], [ 5, 22, 70 ] ],
        'large' => [ [ 349, 500 ], [ 5, 140, 235 ] ],
    ];

    public function all(?string $isbn10, ?string $endpoint = null): array
    {
        $url = [];
        foreach (array_keys($this->types) as $type) {
            $url = array_merge($url, [ $type => $this->single($isbn10, $type, $endpoint) ]);
        }

        return $url;
    }

    public function single(?string $isbn10, string $type, ?string $endpoint = null): string
    {
        if ($endpoint === null) {
            $endpoint = $this->endpoint;
        }

        if ($isbn10 === null) {
            return "{$endpoint}/{$this->path}missing.{$type}.jpg";
        }

        return "{$endpoint}/{$this->path}{$isbn10}.{$this->countryCode}.{$this->types[$type]}";
    }

    public function fetch(string $path): ?string
    {
        if (preg_match('/^' . preg_quote($this->path, '/') . 'missing\.(.+)\.jpg$/', $path, $matches)) {
            return $this->missing($this->sizes[$matches[1]]);
        }

        $image = file_get_contents("{$this->endpoint}/{$path}");
        $size = getimagesizefromstring($image);

        if ($size[0] <= 1 || $size[1] <= 1) {
            preg_match('/^' . preg_quote($this->path, '/') . '\d{10}\.\d{2}\.(.+?)$/', $path, $type);

            return $this->missing($this->sizes[array_search($type[1], $this->types)]);
        }

        return $image;
    }

    protected function missing(array $size)
    {
        $image = imagecreate($size[0][0], $size[0][1]);
        imagecolorallocate($image, 200, 200, 200);
        $text_color = imagecolorallocate($image, 100, 100, 100);
        imagestring($image, $size[1][0], $size[1][1], $size[1][2], 'NO IMAGE', $text_color);
        imagejpeg($image);

        return null;
    }
}
