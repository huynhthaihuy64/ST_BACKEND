<?php

namespace App\Services\Common;

use Illuminate\Http\UploadedFile;
use Storage;

class FileService
{
    /**
     * @var Storage
     */
    private $disk;

    /**
     * FileService constructor.
     *
     * @param string $disk
     */
    public function __construct(string $disk)
    {
        $this->disk = Storage::disk($disk);
    }

    /**
     * @param string $directory
     * @param string $filename
     *
     * @return bool
     */
    public function exists(string $directory, string $filename): bool
    {
        if (!empty($directory)) {
            $filename = $directory . '/' . $filename;
        }

        return $this->disk->exists($filename);
    }

    /**
     * @param string $directory
     * @param string $filename
     * @param string $contents
     *
     * @return bool
     */
    public function uploadFile(string $directory, string $filename, $contents): bool
    {
        if (!empty($directory)) {
            $filename = $directory . '/' . $filename;
        }

        return $this->disk->put($filename, $contents, "public");
    }

    /**
     * @param string $directory
     *
     * @return mixed
     */
    public function getFiles(string $directory)
    {
        return $this->disk->allFiles($directory);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getUrl(string $filename)
    {
        return $this->disk->url($filename);
    }

    /**
     * @param string $directory
     * @param string $filename
     *
     * @return mixed
     */
    public function getFile(string $directory, string $filename)
    {
        if (!empty($directory)) {
            $filename = $directory . '/' . $filename;
        }

        return $this->disk->get($filename);
    }


    /**
     * @param string $directory
     * @param string $filename
     *
     * @return mixed
     */
    public function downloadFile(string $directory, string $filename)
    {
        if (!empty($directory)) {
            $filename = $directory . '/' . $filename;
        }

        return $this->disk->download($filename);
    }

    /**
     * @param string $directory
     * @param string $filename
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function uploadImageFile(string $directory, string $filename, UploadedFile $file): bool
    {
        return $this->disk->putFileAs($directory, $file, $filename);
    }
}
