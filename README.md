# 🛍️ Full-Stack eCommerce App

A modern full-stack eCommerce application built with **React.js** (frontend), **GraphQL**, and **PHP** (backend).

## 🚀 Tech Stack

- **Frontend**: React.js, TypeScript, Apollo Client, Tailwind CSS
- **Backend**: PHP 8.x, GraphQL (`webonyx/graphql-php`), Doctrine DBAL
- **Database**: MySQL
- **Tooling**: Vite, Composer, dotenv

## 📂 Project Structure
frontend/
└── src/
backend/
└── src/
└── public/

## 📦 Features

- Product listings with categories
- Product details with swatches and attributes
- Add to cart and manage orders
- Session-based cart (localStorage)
- GraphQL API with custom schema
- Order mutation support

## 🔧 Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/DavitHarutyunyan2005/eCommerce-React.js-PHP-.git ecommerce-app
cd ecommerce-app
```

### 1. Setup backend

```bash
cd backend
composer install
cp .env.example .env
# Configure DB settings in .env
php migrations.php
php seed.php
php -S localhost:8000 -t public
```

### 3. Setup Frontend

```bash
cd frontend
npm install
npm run dev
```

### 4. Access the App

Frontend: https://davit-ecommerce.store

Backend: https://api.davit-ecommerce.store
