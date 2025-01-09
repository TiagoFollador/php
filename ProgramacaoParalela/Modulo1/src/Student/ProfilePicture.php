<?php

namespace Alura\Threads\Student;

class ProfilePicture
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->setFilePath($filePath);
    }

    private function setFilePath(string $filePath): void
    {
        $isImage = strpos(mime_content_type($filePath), 'image/jp') === 0;
        if (!is_file($filePath) || !$isImage) {
            throw new \InvalidArgumentException('Invalid image file path');
        }

        $this->filePath = $filePath;
    }

    public function filePath(): string
    {
        return $this->filePath;
    }
}
