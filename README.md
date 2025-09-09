# E-commerce Platform

A modern e-commerce platform built with Laravel and Orchid Platform for the admin dashboard.

## Features

- 🛍️ **Product Management**
  - Categories and subcategories
  - Brand management
  - Product variations
  - Image handling with optimization
  - Offers and discounts

- 📊 **Admin Dashboard (Orchid Platform)**
  - User and role management
  - Site settings control
  - Appearance customization
  - Slide management
  - Performance monitoring

- 🎨 **Frontend Features**
  - Responsive design
  - Image optimization
  - Advanced caching system
  - Performance optimizations

## Requirements

- PHP ^8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Redis (optional, for enhanced caching)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/YusufJojeh/e-commerce.git
cd e-commerce
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Configure your .env file with database and application settings:
```env
APP_NAME="Your Store Name"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Generate application key:
```bash
php artisan key:generate
```

7. Run migrations and seeders:
```bash
php artisan migrate --seed
```

8. Create storage link:
```bash
php artisan storage:link
```

9. Build assets:
```bash
npm run build
```

## Admin Access

After running the seeders, two admin accounts will be created:

### Main Admin
- Email: admin@example.com
- Password: Admin@123456

### Backup Admin
- Email: backup@example.com
- Password: Backup@123456

**Important:** Change these passwords immediately after first login!

## Development

1. Start the development server:
```bash
php artisan serve
```

2. Watch for asset changes:
```bash
npm run dev
```

## Deployment

1. Set production environment variables
2. Install dependencies:
```bash
composer install --optimize-autoloader --no-dev
npm install && npm run build
```

3. Run deployment script:
```bash
bash deploy.sh
```

## Performance Optimization

The platform includes several performance optimization features:

- Image optimization and caching
- Response caching
- Database query optimization
- Asset minification and bundling

## Security

- CSRF protection
- XSS prevention
- SQL injection prevention
- Role-based access control
- Two admin accounts for backup access

## Cache Management

Clear various caches:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## Backup

The system includes automated backup functionality for:
- Database
- Uploaded files
- Configuration

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## Support

For support, please raise an issue in the GitHub repository.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Credits

Built with:
- [Laravel](https://laravel.com)
- [Orchid Platform](https://orchid.software)
- [Intervention Image](https://image.intervention.io/)
- Other packages listed in composer.json and package.json
