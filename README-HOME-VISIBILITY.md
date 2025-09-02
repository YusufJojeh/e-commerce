# Home Visibility Controller

## Overview

The Home Visibility Controller is a comprehensive system that manages the visibility of sections on the home page. It provides both backend and frontend APIs for dynamic section management.

## Features

### ✅ **Section Visibility Management**
- **Hero Section**: Main hero banner with slide
- **Slider Section**: Carousel of promotional slides
- **Offers Section**: Special offers and promotions
- **Categories Section**: Shop by category display
- **Featured Products**: Special/featured products
- **Latest Products**: New arrivals
- **External Brands**: Premium brand products

### ✅ **Section Limits Management**
- Control how many items to display in each section
- Real-time updates without page refresh
- Cached for performance

### ✅ **API Endpoints**
- RESTful API for all operations
- JSON responses with success/error messages
- CSRF protection

## Backend Implementation

### Controller: `HomeVisibilityController`

**Location**: `app/Http/Controllers/HomeVisibilityController.php`

**Key Methods**:
- `getVisibilitySettings()` - Get all visibility settings
- `isSectionVisible($section)` - Check if specific section is visible
- `getSectionLimits()` - Get all section limits
- `updateVisibility($request)` - Update section visibility
- `updateLimits($request)` - Update section limits
- `toggleVisibility($section)` - Toggle section visibility
- `bulkUpdateVisibility($request)` - Bulk update multiple sections
- `clearCache()` - Clear cached settings

### Integration Points

**HomeController**: Updated to use visibility controller
**HomeSettingsScreen**: Orchid admin screen integration
**Setting Model**: Enhanced with `set()` method

## Frontend Implementation

### JavaScript API: `home-visibility.js`

**Location**: `public/js/home-visibility.js`

**Features**:
- Async/await API calls
- Error handling
- Notification system
- Automatic page refresh
- Event listeners for form controls

### Home View Integration

**Location**: `resources/views/home.blade.php`

**Visibility Checks**:
```blade
@if(isset($visibility['hero']) && $visibility['hero'] && isset($mainSlide))
  {{-- Hero Section --}}
@endif

@if(isset($visibility['slider']) && $visibility['slider'] && isset($sliderSlides) && $sliderSlides->count())
  {{-- Slider Section --}}
@endif
```

## API Endpoints

### GET `/api/home/settings`
Returns all settings including visibility and limits.

**Response**:
```json
{
  "visibility": {
    "hero": true,
    "slider": true,
    "offers": true,
    "categories": true,
    "special": true,
    "latest": true,
    "external": true
  },
  "limits": {
    "slider": 6,
    "categories": 8,
    "special": 8,
    "latest": 12,
    "external": 8
  },
  "site_name": "Crystal Store",
  "theme_mode": "auto"
}
```

### POST `/api/home/visibility/update`
Update section visibility.

**Request**:
```json
{
  "section": "hero",
  "visible": true
}
```

**Response**:
```json
{
  "success": true,
  "message": "Section 'hero' visibility updated to visible",
  "section": "hero",
  "visible": true
}
```

### POST `/api/home/limits/update`
Update section limits.

**Request**:
```json
{
  "section": "special",
  "limit": 12
}
```

**Response**:
```json
{
  "success": true,
  "message": "Section 'special' limit updated to 12",
  "section": "special",
  "limit": 12
}
```

### POST `/api/home/visibility/toggle/{section}`
Toggle section visibility.

**Response**:
```json
{
  "success": true,
  "message": "Section 'hero' toggled to hidden",
  "section": "hero",
  "visible": false
}
```

### POST `/api/home/visibility/bulk`
Bulk update multiple sections.

**Request**:
```json
{
  "sections": ["hero", "slider", "offers"],
  "visible": false
}
```

**Response**:
```json
{
  "success": true,
  "message": "3 sections updated to hidden",
  "sections": ["hero", "slider", "offers"],
  "visible": false
}
```

## Usage Examples

### Backend Usage

```php
// Get visibility controller
$controller = new HomeVisibilityController();

// Check if hero section is visible
if ($controller->isSectionVisible('hero')) {
    // Show hero section
}

// Get section limit
$limit = $controller->getSectionLimit('special');

// Clear cache
$controller->clearCache();
```

### Frontend Usage

```javascript
// Initialize controller
const controller = new HomeVisibilityController();

// Update visibility
const result = await controller.updateVisibility('hero', false);
if (result.success) {
    console.log(result.message);
}

// Toggle visibility
const toggleResult = await controller.toggleVisibility('slider');

// Get all settings
const settings = await controller.getAllSettings();
console.log(settings.visibility);
```

### Admin Panel Integration

The visibility settings are integrated into the Orchid admin panel:

1. **Home & Site Settings** screen
2. **Section toggles** for each section
3. **Limits** configuration
4. **Real-time updates** with cache clearing

## Caching

The system uses Laravel's cache system for performance:

- **Cache Key**: `home.visibility.settings`
- **Cache Duration**: 1 hour (3600 seconds)
- **Auto-clear**: When settings are updated

## Error Handling

- **Validation**: All inputs are validated
- **CSRF Protection**: All POST requests require CSRF token
- **Error Responses**: JSON error messages
- **Fallbacks**: Default values for missing settings

## Performance Benefits

1. **Cached Settings**: Reduces database queries
2. **Efficient Queries**: Only loads visible sections
3. **Lazy Loading**: Images load only when needed
4. **Minimal DOM**: Hidden sections don't render

## Future Enhancements

- [ ] A/B testing for different layouts
- [ ] Time-based visibility (show/hide at specific times)
- [ ] User role-based visibility
- [ ] Analytics tracking for section performance
- [ ] Drag-and-drop section reordering

## Troubleshooting

### Common Issues

1. **Sections not showing/hiding**
   - Clear cache: `php artisan cache:clear`
   - Check database settings
   - Verify visibility values

2. **API errors**
   - Check CSRF token
   - Verify route registration
   - Check validation rules

3. **Performance issues**
   - Clear cache
   - Check database indexes
   - Monitor query performance

### Debug Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Test controller
php artisan tinker --execute="echo App\Http\Controllers\HomeVisibilityController::getVisibilitySettings();"

# Check routes
php artisan route:list --name=api.home
```
