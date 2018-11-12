<?php
declare(strict_types=1);

namespace App\Libs;

class AmazonImages
{
    protected $endpoint = 'http://images-jp.amazon.com';
    protected $path = '/images/P/';
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

        if ($isbn10 === null) {
            return "{$endpoint}{$this->path}missing.jpg";
        }

        return "{$endpoint}{$this->path}{$isbn10}.{$this->countryCode}.{$this->types[$type]}";
    }

    public function fetch($path): ?string
    {
        if ($path !== "{$this->path}missing.jpg") {
            $image = file_get_contents("{$this->endpoint}/{$path}");
            $size = getimagesizefromstring($image);

            if ($size[0] <= 1 || $size[1] <= 1) {
                return $this->missing();
            }

            return $image;
        }

        return $this->missing();
    }

    protected function missing()
    {
        $image = imagecreate(112, 160);
        imagecolorallocate($image, 200, 200, 200);
        $text_color = imagecolorallocate($image, 100, 100, 100);
        imagestring($image, 5, 22, 70, 'NO IMAGE', $text_color);
        imagejpeg($image);

        return null;
    }
}
