<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ImageService
{
    /**
     * Default disk for image storage
     */
    protected string $disk = 'public';

    /**
     * Maximum file size in bytes (5MB)
     */
    protected int $maxFileSize = 5242880;

    /**
     * Allowed image mime types
     */
    protected array $allowedMimeTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
        'image/gif'
    ];

    /**
     * Upload and process an image
     */
    public function upload(UploadedFile $file, string $directory, array $options = []): array
    {
        try {
            // Validate file
            $this->validateFile($file);

            // Generate unique filename
            $filename = $this->generateFilename($file);

            // Store original file
            $path = $file->storeAs($directory, $filename, $this->disk);

            // Set public visibility
            Storage::disk($this->disk)->setVisibility($path, 'public');

            // Process image if optimization is enabled and Intervention Image is available
            $processedPath = $this->processImage($path, $options);

            return [
                'success' => true,
                'path' => $processedPath ?: $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'url' => $this->getUrl($processedPath ?: $path)
            ];

        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'directory' => $directory,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete an image from storage
     */
    public function delete(?string $path): bool
    {
        if (!$path) {
            return true;
        }

        try {
            if (Storage::disk($this->disk)->exists($path)) {
                return Storage::disk($this->disk)->delete($path);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Image deletion failed: ' . $e->getMessage(), [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

        /**
     * Get the public URL for an image
     */
    public function getUrl(?string $path, ?string $fallback = null): ?string
    {
        if (!$path) {
            return $fallback ? asset($fallback) : null;
        }

        try {
            $url = Storage::disk($this->disk)->url($path);
            
            // Always use 127.0.0.1:8000 for local development
            if (str_contains($url, 'localhost')) {
                $url = str_replace('localhost', '127.0.0.1:8000', $url);
            }
            
            return $url;
        } catch (\Exception $e) {
            Log::error('Image URL generation failed: ' . $e->getMessage(), [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return $fallback ? asset($fallback) : null;
        }
    }

    /**
     * Check if an image exists
     */
    public function exists(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \Exception('Invalid file upload');
        }

        if ($file->getSize() > $this->maxFileSize) {
            throw new \Exception('File size exceeds maximum limit of ' . ($this->maxFileSize / 1024 / 1024) . 'MB');
        }

        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            throw new \Exception('File type not allowed. Allowed types: ' . implode(', ', $this->allowedMimeTypes));
        }
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);

        return "{$name}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Process image (resize, optimize, etc.)
     * This method is optional and will gracefully handle missing Intervention Image
     */
    protected function processImage(string $path, array $options = []): ?string
    {
        // Check if Intervention Image is available
        if (!class_exists('Intervention\Image\Facades\Image')) {
            Log::info('Intervention Image not available, skipping image processing');
            return null;
        }

        try {
            $fullPath = Storage::disk($this->disk)->path($path);

            // Use Intervention Image facade
            $image = Image::make($fullPath);

            // Apply options
            if (isset($options['max_width']) && $image->width() > $options['max_width']) {
                $image->resize($options['max_width'], null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            if (isset($options['max_height']) && $image->height() > $options['max_height']) {
                $image->resize(null, $options['max_height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Save optimized image
            $image->save($fullPath, $options['quality'] ?? 85);

            return $path;

        } catch (\Exception $e) {
            Log::warning('Image processing failed, using original: ' . $e->getMessage(), [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get validation rules for image uploads
     */
    public function getValidationRules(string $fieldName = 'image', bool $required = false): array
    {
        $rules = [
            $fieldName => [
                $required ? 'required' : 'nullable',
                'file',
                'image',
                'mimes:jpeg,jpg,png,webp,gif',
                'max:' . ($this->maxFileSize / 1024) // Convert to KB
            ]
        ];

        return $rules;
    }

    /**
     * Get directory-specific upload options
     */
    public function getUploadOptions(string $directory): array
    {
        $options = [
            'products' => [
                'max_width' => 1200,
                'max_height' => 1200,
                'quality' => 85
            ],
            'categories' => [
                'max_width' => 800,
                'max_height' => 600,
                'quality' => 80
            ],
            'brands' => [
                'max_width' => 400,
                'max_height' => 200,
                'quality' => 85
            ],
            'slides' => [
                'max_width' => 1920,
                'max_height' => 800,
                'quality' => 85
            ],
            'offers' => [
                'max_width' => 800,
                'max_height' => 400,
                'quality' => 80
            ],
            'branding' => [
                'max_width' => 500,
                'max_height' => 200,
                'quality' => 90
            ]
        ];

        return $options[$directory] ?? [];
    }
}
