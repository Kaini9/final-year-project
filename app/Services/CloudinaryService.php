<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $cloudinaryUrl = sprintf(
            "cloudinary://%s:%s@%s",
            config('services.cloudinary.api_key'),
            config('services.cloudinary.api_secret'),
            config('services.cloudinary.cloud_name')
        );
        
        $this->cloudinary = new Cloudinary($cloudinaryUrl);
    }

    /**
     * Upload a single file to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return array
     */
    public function upload(UploadedFile $file, string $folder = 'fashionconnect/posts'): array
    {
        try {
            $result = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => $folder,
                    'resource_type' => 'auto',
                    'quality' => 'auto',
                ]
            );

            return [
                'success' => true,
                'url' => $result['secure_url'],
                'public_id' => $result['public_id'],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Upload multiple files to Cloudinary
     *
     * @param array $files
     * @param string $folder
     * @return array
     */
    public function uploadMultiple(array $files, string $folder = 'fashionconnect/posts'): array
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $result = $this->upload($file, $folder);
                if ($result['success']) {
                    $uploadedFiles[] = $result;
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * Delete a file from Cloudinary
     *
     * @param string $publicId
     * @return bool
     */
    public function delete(string $publicId): bool
    {
        try {
            $this->cloudinary->uploadApi()->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete multiple files from Cloudinary
     *
     * @param array $publicIds
     * @return bool
     */
    public function deleteMultiple(array $publicIds): bool
    {
        foreach ($publicIds as $publicId) {
            $this->delete($publicId);
        }
        return true;
    }
}
