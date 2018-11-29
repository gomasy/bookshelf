<?php
declare(strict_types=1);

namespace App\Libs;

class AmazonImages
{
    protected $endpoint = 'http://images-jp.amazon.com';
    protected $path = 'images/P/';
    protected $countryCode = '09';
    protected $types = [
        'thumb'  => 'THUMBZZZ',
        'small'  => 'TZZZZZZZ',
        'medium' => 'MZZZZZZZ',
        'large'  => 'LZZZZZZZ',
    ];
    protected $sizes = [
        'thumb'  => [  52,  75, 2,   3,  29 ],
        'small'  => [  77, 110, 4,   7,  47 ],
        'medium' => [ 112, 160, 5,  21,  72 ],
        'large'  => [ 349, 500, 5, 140, 235 ],
    ];

    protected function regexp(string $subject, string $regexpBase): ?string
    {
        $regexp = '/^' . preg_quote($this->path, '/') . $regexpBase . '$/';
        if (preg_match($regexp, $subject, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function all(?string $isbn10, string $endpoint = ''): array
    {
        $url = [];
        foreach (array_keys($this->types) as $type) {
            $url = array_merge($url, [
                $type => $this->single($isbn10, $type, $endpoint),
            ]);
        }

        return $url;
    }

    public function single(?string $isbn10, string $type, string $endpoint): string
    {
        if ($isbn10 === null) {
            return "{$endpoint}/{$this->path}missing.{$type}.jpg";
        }

        return "{$endpoint}/{$this->path}{$isbn10}.{$this->countryCode}.{$this->types[$type]}";
    }

    public function fetch(string $path): string
    {
        $size = $this->regexp($path, 'missing\.(.+?)\.jpg');
        if ($size) {
            return $this->missing($this->sizes[$size]);
        }

        $image = (string)(new \GuzzleHttp\Client())
            ->request('GET', "{$this->endpoint}/{$path}")
            ->getBody();
        $size = getimagesizefromstring($image);

        if ($size[0] <= 1 || $size[1] <= 1) {
            $type = $this->regexp($path, '.+\.\d{2}\.(.+?)');

            return $this->missing($this->sizes[array_search($type, $this->types)]);
        }

        return $image;
    }

    protected function missing(array $size): string
    {
        $canvas = imagecreate($size[0], $size[1]);
        imagecolorallocate($canvas, 200, 200, 200);
        $text_color = imagecolorallocate($canvas, 100, 100, 100);
        imagestring($canvas, $size[2], $size[3], $size[4], "NO IMAGE", $text_color);

        // 画像ストリームを変数に落とし込む
        ob_start();
        imagejpeg($canvas, null, 100);
        $image = ob_get_contents();
        imagedestroy($canvas);
        ob_end_clean();

        return $image;
    }
}
