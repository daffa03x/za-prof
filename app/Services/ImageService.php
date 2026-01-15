<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

/**
 * Class ImageService
 *
 * Handles image compression and optimization for uploaded images.
 */
class ImageService
{
    /**
     * Default compression quality (0-100).
     */
    protected int $quality = 75;

    /**
     * Thumbnail dimensions.
     */
    protected int $thumbnailWidth = 400;
    protected int $thumbnailHeight = 300;

    /**
     * The Image Manager instance.
     */
    protected ImageManager $manager;

    /**
     * Create a new ImageService instance.
     */
    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Set compression quality.
     */
    public function setQuality(int $quality): self
    {
        $this->quality = max(0, min(100, $quality));
        return $this;
    }

    /**
     * Set thumbnail dimensions.
     */
    public function setThumbnailSize(int $width, int $height): self
    {
        $this->thumbnailWidth = $width;
        $this->thumbnailHeight = $height;
        return $this;
    }

    /**
     * Compress and save an uploaded image.
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory The target directory (relative to public path)
     * @param string|null $filename Custom filename (without extension)
     * @return array{original: string, thumbnail: string} Paths to saved images
     */
    public function compressAndSave(UploadedFile $file, string $directory, ?string $filename = null): array
    {
        // Generate filename if not provided
        $filename = $filename ?? date('Y-m-d_His') . '_' . uniqid();
        
        // Ensure directory exists
        $fullPath = public_path($directory);
        if (!File::isDirectory($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }

        // Get original extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $extension = 'jpg';
        }

        // Read the image
        $image = $this->manager->read($file->getPathname());

        // Save compressed original
        $originalFilename = $filename . '.' . $extension;
        $originalPath = $directory . '/' . $originalFilename;
        
        $image->toJpeg($this->quality)->save(public_path($originalPath));

        // Create and save thumbnail
        $thumbnailFilename = $filename . '_thumb.' . $extension;
        $thumbnailPath = $directory . '/' . $thumbnailFilename;

        $image->cover($this->thumbnailWidth, $this->thumbnailHeight)
              ->toJpeg($this->quality)
              ->save(public_path($thumbnailPath));

        return [
            'original' => $originalPath,
            'thumbnail' => $thumbnailPath,
        ];
    }

    /**
     * Compress a single image without creating thumbnail.
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory The target directory (relative to public path)
     * @param string|null $filename Custom filename (without extension)
     * @return string Path to saved image
     */
    public function compress(UploadedFile $file, string $directory, ?string $filename = null): string
    {
        // Generate filename if not provided
        $filename = $filename ?? date('Y-m-d_His') . '_' . uniqid();
        
        // Ensure directory exists
        $fullPath = public_path($directory);
        if (!File::isDirectory($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }

        // Get original extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $extension = 'jpg';
        }

        // Read and compress the image
        $image = $this->manager->read($file->getPathname());

        // Save compressed image
        $outputFilename = $filename . '.' . $extension;
        $outputPath = $directory . '/' . $outputFilename;
        
        $image->toJpeg($this->quality)->save(public_path($outputPath));

        return $outputPath;
    }

    /**
     * Delete an image and its thumbnail if exists.
     *
     * @param string $path Path to the original image
     */
    public function delete(string $path): void
    {
        if (!$path) {
            return;
        }

        // Delete original
        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }

        // Delete thumbnail
        $thumbnailPath = $this->getThumbnailPath($path);
        $fullThumbnailPath = public_path($thumbnailPath);
        if (File::exists($fullThumbnailPath)) {
            File::delete($fullThumbnailPath);
        }
    }

    /**
     * Get thumbnail path from original path.
     *
     * @param string $originalPath Original image path
     * @return string Thumbnail path
     */
    public function getThumbnailPath(string $originalPath): string
    {
        $info = pathinfo($originalPath);
        return $info['dirname'] . '/' . $info['filename'] . '_thumb.' . ($info['extension'] ?? 'jpg');
    }

    /**
     * Check if a thumbnail exists for the given image.
     *
     * @param string $originalPath Original image path
     * @return bool
     */
    public function thumbnailExists(string $originalPath): bool
    {
        $thumbnailPath = $this->getThumbnailPath($originalPath);
        return File::exists(public_path($thumbnailPath));
    }
}
