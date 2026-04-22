<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Service untuk mengelola upload, resize, dan optimasi gambar produk.
 * Menggunakan PHP GD library bawaan (tidak perlu dependency eksternal).
 */
class ImageService
{
    /**
     * Ukuran maksimum gambar (pixel).
     */
    private const MAX_WIDTH = 800;

    private const MAX_HEIGHT = 800;

    /**
     * Kualitas kompresi JPEG (0-100).
     */
    private const JPEG_QUALITY = 85;

    /**
     * Upload dan proses gambar produk.
     * Gambar akan di-resize jika dimensi melebihi batas dan di-compress.
     *
     * @param  string|null  $oldPath  Path gambar lama yang akan dihapus
     * @return string Path relatif gambar yang tersimpan
     */
    public function upload(UploadedFile $file, ?string $oldPath = null): string
    {
        // Hapus gambar lama jika ada
        if ($oldPath) {
            $this->delete($oldPath);
        }

        // Baca informasi gambar
        $imageInfo = getimagesize($file->getPathname());
        if ($imageInfo === false) {
            // Fallback: simpan tanpa resize
            return $file->store('produk', 'public');
        }

        [$width, $height, $type] = $imageInfo;

        // Jika sudah kecil, tidak perlu resize
        if ($width <= self::MAX_WIDTH && $height <= self::MAX_HEIGHT) {
            return $file->store('produk', 'public');
        }

        // Resize gambar
        return $this->resizeAndStore($file, $width, $height, $type);
    }

    /**
     * Resize gambar sehingga kita mempertahankan aspect ratio dan simpan.
     *
     * @param  int  $type  IMAGETYPE_* constant
     * @return string Path relatif gambar
     */
    private function resizeAndStore(UploadedFile $file, int $origWidth, int $origHeight, int $type): string
    {
        // Hitung dimensi baru
        $ratio = min(self::MAX_WIDTH / $origWidth, self::MAX_HEIGHT / $origHeight);
        $newWidth = (int) round($origWidth * $ratio);
        $newHeight = (int) round($origHeight * $ratio);

        // Load image berdasarkan tipe
        $source = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($file->getPathname()),
            IMAGETYPE_PNG => imagecreatefrompng($file->getPathname()),
            IMAGETYPE_WEBP => imagecreatefromwebp($file->getPathname()),
            IMAGETYPE_GIF => imagecreatefromgif($file->getPathname()),
            default => null,
        };

        if (! $source) {
            return $file->store('produk', 'public');
        }

        // Buat canvas baru
        $destination = imagecreatetruecolor($newWidth, $newHeight);

        // Karena disimpan sebagai JPEG, kita isi background transparan dengan warna putih
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP || $type === IMAGETYPE_GIF) {
            $white = imagecolorallocate($destination, 255, 255, 255);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $white);
        }

        // Resize
        imagecopyresampled(
            $destination, $source,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $origWidth, $origHeight
        );

        // Generate filename
        $filename = 'produk/'.uniqid('img_').'_'.$newWidth.'x'.$newHeight.'.jpg';
        $fullPath = storage_path('app/public/'.$filename);

        // Pastikan directory ada
        if (! is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        // Simpan sebagai JPEG (ukuran lebih kecil, universal support)
        imagejpeg($destination, $fullPath, self::JPEG_QUALITY);

        // Bersihkan memori
        imagedestroy($source);
        imagedestroy($destination);

        return $filename;
    }

    /**
     * Upload dan simpan gambar dari string Base64 (dihasilkan oleh Cropper.js).
     */
    public function uploadBase64(string $base64Data, ?string $oldPath = null): string
    {
        // Hapus gambar lama jika ada
        if ($oldPath) {
            $this->delete($oldPath);
        }

        // Ekstrak tipe ekstensi dan data base64
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]);

            $data = base64_decode($data);

            // Format filename
            $filename = 'produk/'.uniqid('img_').'_'.self::MAX_WIDTH.'x'.self::MAX_HEIGHT.'.'.($type === 'jpeg' ? 'jpg' : $type);
            $fullPath = storage_path('app/public/'.$filename);

            // Pastikan directory produk ada
            if (! is_dir(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            // Simpan gambar secara langsung
            file_put_contents($fullPath, $data);

            return $filename;
        }

        throw new \InvalidArgumentException('Format gambar base64 tidak valid.');
    }

    /**
     * Hapus file gambar dari storage.
     */
    public function delete(string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
