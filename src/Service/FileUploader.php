<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private string $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file): string
    {
        // Tạo tên file duy nhất
        $newFilename = uniqid() . '.' . $file->guessExtension();

        // Di chuyển file vào thư mục đích
        $file->move($this->targetDirectory, $newFilename);
        $url = 'https://symfony-9z0y.onrender.com/images/' . $newFilename;
        // Trả về tên file
        return $url;
    }
}
