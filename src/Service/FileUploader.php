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
        $url = 'http://127.0.0.1:3000/images/' . $newFilename;
        // Trả về tên file
        return $url;
    }
}
