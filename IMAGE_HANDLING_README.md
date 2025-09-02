# Image Handling System Documentation

## Overview

This Laravel e-commerce application has been refactored with a comprehensive, centralized image handling system that ensures consistency, reliability, and best practices across all image operations.

## Architecture

### Core Components

1. **ImageService** (`app/Services/ImageService.php`)
   - Centralized service for all image operations
   - Handles upload, validation, processing, and deletion
   - Supports image optimization and resizing
   - Provides consistent error handling and logging

2. **HasImages Trait** (`app/Traits/HasImages.php`)
   - Reusable trait for models that need image handling
   - Provides consistent methods across different models
   - Automatically handles image deletion on model deletion

3. **ImageHelper** (`app/Support/ImageHelper.php`)
   - Static helper methods for common image operations
   - Provides fallback images and utility functions

### Models Using Image Handling

- **ProductImage**: Multiple images per product with primary image support
- **Category**: Single image per category
- **Brand**: Logo image per brand
- **Slide**: Banner images for sliders
- **Offer**: Banner images for promotional offers

## Features

### Image Upload & Processing

- **Automatic Optimization**: Images are automatically resized and optimized based on directory-specific settings
- **Unique Filenames**: Prevents conflicts with timestamp and random string generation
- **File Validation**: Comprehensive validation for file type, size, and format
- **Error Handling**: Graceful error handling with detailed logging

### Directory-Specific Settings

Each image type has optimized settings:

```php
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
```

### Supported File Types

- JPEG/JPG
- PNG
- WebP
- GIF

### File Size Limits

- Maximum file size: 5MB per image
- Configurable per directory type

## Usage Examples

### In Models

```php
use App\Traits\HasImages;

class Category extends Model
{
    use HasImages;
    
    // Override trait methods for custom configuration
    protected function getImageDirectory(): string
    {
        return 'categories';
    }
    
    protected function getImageFallback(): ?string
    {
        return 'images/placeholder-category.png';
    }
}

// Usage
$category = Category::find(1);
$category->uploadImage($request->file('image'));
$category->deleteImage();
$url = $category->image_url;
```

### In Controllers/Screens

```php
use App\Services\ImageService;

class ProductEditScreen extends Screen
{
    public function addImage(Request $request, Product $product)
    {
        $imageService = app(ImageService::class);
        $validationRules = $imageService->getValidationRules('image', true);
        
        $validated = $request->validate($validationRules);
        $file = $request->file('image');
        $uploadOptions = $imageService->getUploadOptions('products');
        
        $result = $imageService->upload($file, 'products', $uploadOptions);
        
        if ($result['success']) {
            // Handle successful upload
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $result['path'],
                // ... other fields
            ]);
        } else {
            // Handle upload error
            Toast::error('Upload failed: ' . $result['error']);
        }
    }
}
```

### Using ImageHelper

```php
use App\Support\ImageHelper;

// Get image URL with fallback
$url = ImageHelper::url($model->image_path, 'images/placeholder.png');

// Check if image exists
if (ImageHelper::exists($model->image_path)) {
    // Image exists
}

// Delete image
ImageHelper::delete($model->image_path);

// Get placeholder
$placeholder = ImageHelper::placeholder('product');
```

## Database Schema

### Product Images Table

```sql
CREATE TABLE product_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    path VARCHAR(255) NOT NULL,
    alt VARCHAR(255) NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

### Other Models

All other models store image paths in their respective tables:
- `categories.image_path`
- `brands.logo_path`
- `slides.image_path`
- `offers.banner_image`

## Storage Configuration

### File System

Images are stored in the `public` disk under organized directories:
- `storage/app/public/products/`
- `storage/app/public/categories/`
- `storage/app/public/brands/`
- `storage/app/public/slides/`
- `storage/app/public/offers/`
- `storage/app/public/branding/`

### URL Generation

Images are accessible via:
```
https://yourdomain.com/storage/{path}
```

## Error Handling

### Upload Errors

- Invalid file type
- File size exceeds limit
- File corruption
- Storage disk issues

### Automatic Cleanup

- Images are automatically deleted when models are deleted
- Orphaned files are cleaned up via model events
- Failed uploads are logged for debugging

## Performance Optimizations

### Image Processing

- Images are processed asynchronously when possible
- Optimized file sizes reduce bandwidth usage
- Cached URLs improve response times

### Storage Optimization

- Unique filenames prevent conflicts
- Organized directory structure
- Automatic cleanup prevents storage bloat

## Security Features

### File Validation

- MIME type validation
- File size limits
- Extension validation
- Malware scanning (if configured)

### Access Control

- Public visibility for web-accessible images
- Private storage for sensitive images
- URL generation with proper permissions

## Migration Guide

### From Old System

If migrating from the previous image handling system:

1. **Update Models**: Add the `HasImages` trait to models
2. **Update Controllers**: Replace direct storage calls with `ImageService`
3. **Update Views**: Use the new URL generation methods
4. **Test Thoroughly**: Verify all image operations work correctly

### Database Changes

No database schema changes are required. The existing `image_path` fields are compatible with the new system.

## Troubleshooting

### Common Issues

1. **Images not displaying**
   - Check if storage link exists: `php artisan storage:link`
   - Verify file permissions on storage directory
   - Check if image path is correct in database

2. **Upload failures**
   - Check file size limits
   - Verify supported file types
   - Check storage disk configuration

3. **Image processing errors**
   - Ensure Intervention Image is installed
   - Check GD or Imagick extension availability
   - Review error logs for specific issues

### Debugging

Enable debug mode and check logs:
```bash
tail -f storage/logs/laravel.log
```

## Best Practices

1. **Always validate uploads** using the provided validation rules
2. **Use appropriate image sizes** for different contexts
3. **Implement proper error handling** for upload failures
4. **Regularly clean up** orphaned image files
5. **Monitor storage usage** and implement cleanup strategies
6. **Use CDN** for production environments
7. **Implement image caching** for better performance

## Future Enhancements

- Cloud storage integration (AWS S3, Google Cloud Storage)
- Advanced image processing (watermarks, filters)
- Image compression optimization
- WebP conversion for better performance
- Image CDN integration
- Bulk image operations
- Image metadata extraction and storage
