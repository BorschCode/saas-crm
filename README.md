# Filament CRM & Blog Platform

Comprehensive **CRM, project management, and blogging SaaS platform** built with **Laravel 12** and **Filament**, featuring AI-powered content workflows and modern frontend architecture.

## 🌐 Live Demo

- **Landing Page**: [https://borschcode.github.io/](https://borschcode.github.io/)
- **Admin Dashboard**: [https://saas-crm-upo6.onrender.com/](https://saas-crm-upo6.onrender.com/)

---

## 🧩 Tech Stack & Badges

[![Tests](https://github.com/BorschCode/filament-playground/actions/workflows/tests.yml/badge.svg)](https://github.com/BorschCode/filament-playground/actions/workflows/tests.yml)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-4.x-FDAE4B)](https://filamentphp.com)
[![Vue](https://img.shields.io/badge/Vue-3-4FC08D?logo=vue.js&logoColor=white)](https://vuejs.org)
[![Inertia](https://img.shields.io/badge/Inertia.js-2-9553E9)](https://inertiajs.com)
[![MongoDB](https://img.shields.io/badge/MongoDB-7.0-47A248?logo=mongodb&logoColor=white)](https://mongodb.com)
[![Tailwind](https://img.shields.io/badge/Tailwind-4.0-06B6D4?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Docker](https://img.shields.io/badge/Docker-Laravel%20Sail-2496ED?logo=docker&logoColor=white)](https://laravel.com/docs/sail)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 🧠 Platform Overview

An **all-in-one business platform** that allows you to:

- Manage clients, deals, projects, and tasks
- Track time and team productivity
- Publish and manage blog content
- Convert PDF/CSV files into blog posts
- Use AI for content, analysis, and automation
- Secure the system with Fortify & 2FA

Designed as a **real SaaS architecture**, not a demo.

---

## 📸 Screenshots

### Core Views

| Landing Page | Admin Dashboard |
|-------------|----------------|
| ![Landing](docs/index.png) | ![Admin](docs/admin.png) |

| CRM – Client Edit | Save Confirmation |
|------------------|------------------|
| ![Client Edit](docs/edit_client.png) | ![Save](docs/save.png) |

---

### Blog System

| Blog Listing | Blog Search |
|-------------|-------------|
| ![Blog](docs/blog.png) | ![Blog Search](docs/blog-search1.png) |

---

### AI Integration (Neuron AI)

| AI Chat – Success | AI Chat – Error Handling |
|------------------|-------------------------|
| ![AI Success](docs/chat%20with%20ai%20food.png) | ![AI Error](docs/chat%20with%20ai%20fail.png) |

---

## 🚀 Key Features

### CRM & Business
- Client & contact management
- Deal pipeline and sales tracking
- Project & task management
- Time entry tracking
- Team-based multi-tenancy

### Blog & Content
- Blog posts with categories & tags
- Full-text search (Laravel Scout)
- SEO-friendly slugs
- Draft & publish workflow
- PDF / CSV → Blog conversion
- Export to PDF

### Platform & Security
- Laravel Fortify authentication
- Email verification & password reset
- Two-factor authentication (2FA)
- Role-based permissions
- Async queues (Redis)

---

## ⚙️ Technology Stack

- **Backend**: Laravel 12, PHP 8.4
- **Admin Panel**: Filament 4
- **Frontend**: Inertia.js v2 + Vue 3
- **Database**: MongoDB 7
- **Search**: Laravel Scout
- **Queues**: Redis
- **Styling**: Tailwind CSS v4
- **Containers**: Docker / Laravel Sail
- **AI**: Neuron AI

---

## 🎯 Project Goals

- Demonstrate real-world SaaS architecture
- Showcase advanced Filament usage
- Combine CRM + content + AI in one system
- Apply clean code & scalable design principles


## 📦 Installation (First Run with Docker Compose)

### 1. Clone repository
```bash
git clone <repository-url>
cd AccountPilot
```

### 2. Prepare environment file
```bash
cp .env.example .env
```

If needed, adjust ports in `.env` (default: `APP_PORT=8099`, `VITE_PORT=5117`).

### 3. Build and start containers

If you use Laravel Sail wrapper:
```bash
./vendor/bin/sail up -d --build
```

If Sail is not yet installed (first run), use Docker Compose directly:
```bash
docker compose up -d --build
```

This will:
- Build PHP container
- Start PostgreSQL
- Start Node container
- Create network and volumes

### 4. Install backend dependencies (inside container)
```bash
docker compose exec filament-admin-app composer install
```

Or with Sail:
```bash
./vendor/bin/sail composer install
```

### 5. Generate application key
```bash
docker compose exec filament-admin-app php artisan key:generate
```

### 6. Run database migrations
```bash
docker compose exec filament-admin-app php artisan migrate
```

### 7. Install frontend dependencies
```bash
docker compose exec filament-admin-app npm install
```

### 8. Build frontend assets

Production build:
```bash
docker compose exec filament-admin-app npm run build
```

---

## 📄 License

MIT — use, modify, and adapt freely.

---

**Built with Laravel 12 · Filament · Vue · AI**
