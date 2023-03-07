<?php
declare(strict_types=1);

namespace App\Libs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use App\Book;

class AmazonImages
{
    protected $endpoint = 'http://images.amazon.com';
    protected $path = 'images/P/';
    protected $countryCode = '09';
    protected $types = [
        'thumb'  => 'THUMBZZZ',
        'small'  => 'TZZZZZZZ',
        'medium' => 'MZZZZZZZ',
        'large'  => 'LZZZZZZZ',
    ];

    // 幅, 高さ, フォントサイズ
    protected $sizes = [
        'thumb'  => [  52,  75,  7 ],
        'small'  => [  77, 110, 10 ],
        'medium' => [ 112, 160, 15 ],
        'large'  => [ 349, 500, 40 ],
    ];

    protected function regexp(string $subject, string $regexpBase): ?string
    {
        $regexp = '/^' . preg_quote($this->path, '/') . $regexpBase . '$/';
        if (preg_match($regexp, $subject, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function all(Book $book, string $endpoint = ''): array
    {
        $url = [];
        foreach (array_keys($this->types) as $type) {
            $url = array_merge($url, [
                $type => $this->single($book, $type, $endpoint),
            ]);
        }

        return $url;
    }

    public function single(Book $book, string $type, string $endpoint): string
    {
        $baseUri = "{$endpoint}/{$this->path}";
        $query = '?text=' . urlencode($book->title);

        if ($book->isbn10 === null) {
            return "${baseUri}missing.{$type}.jpg{$query}";
        }

        return "{$baseUri}{$book->isbn10}.{$this->countryCode}.{$this->types[$type]}{$query}";
    }

    public function getImage(string $path): ?string
    {
        try {
            $image = (string)(new Client())
                ->request('GET', "{$this->endpoint}/{$path}")
                ->getBody();

        } catch (ClientException $e) {
            $image = null;
        }

        return $image;
    }

    public function getImageOrMissing(string $path, ?string $text): string
    {
        $image = $this->getImage($path);
        if ($image !== null) {
            $size = getimagesizefromstring($image);
        }

        if (!isset($size) || ($size[0] <= 1 || $size[1] <= 1)) {
            $type = $this->regexp($path, '.+\.\d{2}\.(.+?)');

            return $this->missing($text, $this->sizes[array_search($type, $this->types)]);
        }

        return $image;
    }

    public function fetch(string $path, ?string $text): string
    {
        $size = $this->regexp($path, 'missing\.(.+?)\.jpg');
        if ($size) {
            return $this->missing($text, $this->sizes[$size]);
        }

        return $this->getImageOrMissing($path, $text);
    }

    protected function insertLineFeeds(string $str, int $per): string
    {
        for ($i = 0; ($i + 1) * $per < mb_strlen($str, 'UTF-8'); $i++) {
            $current = $per + $i * (1 + $per);
            $str = preg_replace("/^.{0,$current}+\K/us", "\n", $str);
        }

        return $str;
    }

    protected function getTextBoxSize(string $text, array $size): array
    {
        // テキストボックスの幅と高さを取得
        $box = imagettfbbox($size[2], 0, config('app.font'), $text);
        $width = $box[2] - $box[6];
        $height = $box[3] - $box[7];

        // 画像内のテキストボックスの左上始点を算出
        $x = (int)(($size[0] - $width) / 2);
        $y = (int)(($size[1] - $height) / 2) + $size[2];

        return [ $x, $y ];
    }

    protected function imageToJpeg($canvas, int $quality): string
    {
        // 画像ストリームを変数に落とし込む
        ob_start();
        imagejpeg($canvas, null, $quality);
        $image = ob_get_contents();
        imagedestroy($canvas);
        ob_end_clean();

        return $image;
    }

    protected function missing(?string $text, array $size): string
    {
        // 描画領域を初期化
        $canvas = imagecreate($size[0], $size[1]);
        imagecolorallocate($canvas, 200, 200, 200);
        $text_color = imagecolorallocate($canvas, 100, 100, 100);
        $text = $text !== null ? $this->insertLineFeeds($text, 5) : 'NO IMAGE';

        // 文字を描画
        $xy = $this->getTextBoxSize($text, $size);
        imagettftext($canvas, $size[2], 0, $xy[0], $xy[1], $text_color, config('app.font'), $text);

        return $this->imageToJpeg($canvas, 100);
    }
}
