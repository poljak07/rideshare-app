# 🚗 Rideshare App

**Rideshare App** is a web application for ride-sharing, built using **Laravel**, **Vue.js**, and **Inertia.js**.  
It allows users to create and manage rides, connecting **drivers** and **passengers** through a user-friendly interface.

---

## 🛠️ Technologies

- ⚙️ [Laravel 11](https://laravel.com/) – Backend PHP framework  
- 🖼️ [Vue.js 3](https://vuejs.org/) – Frontend JavaScript framework  
- 🔗 [Inertia.js](https://inertiajs.com/) – Seamless Laravel-Vue integration  
- ⚡ [Vite](https://vitejs.dev/) – Modern frontend tooling  
- 🎨 [Tailwind CSS](https://tailwindcss.com/) – Utility-first CSS framework  

---

## 🚀 Getting Started

### ✅ Requirements

- PHP >= 8.3  
- Node.js >= 18  
- Composer  
- MySQL (or any supported DB)

### 📦 Installation

```bash
# Clone the repository
git clone https://github.com/poljak07/rideshare-app.git
cd rideshare-app

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy .env and configure
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Start Laravel server
php artisan serve

# Start Vite dev server
npm run dev

```
# 📁 Project Structure
```
rideshare-app/
├── app/                  # Laravel backend logic
├── resources/js/         # Vue.js components
├── routes/web.php        # Web routes
├── database/migrations/  # Database migrations
└── public/               # Public files (entry point)
```


# 🤝Contributing

Contributions are welcome!
Feel free to open an issue or submit a pull request with improvements.
