# 🛍️ Full-Stack eCommerce App

A modern full-stack eCommerce application built with **React.js** (frontend), **GraphQL**, and **PHP** (backend).

## 🚀 Tech Stack

- **Frontend**: React.js, TypeScript, Apollo Client, Tailwind CSS
- **Backend**: PHP 8.x, GraphQL (`webonyx/graphql-php`), Doctrine DBAL
- **Database**: MySQL
- **Tooling**: Vite, Composer, dotenv

## 📂 Project Structure
├── backend/              # Backend API (PHP + GraphQL)

│   ├── script/           # Migration and seed scripts
│   ├── src/              # Source code (models, etc.)
│   └── public/           # Public web root for backend

├── frontend/             # React.js frontend application
│   ├── src/              # React components, pages, assets
│   └── public/           # Static files (index.html, etc.)

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

# Edit your .env file to configure your database connection

php script/migration.php
php script/seed.php

# To run the backend locally for testing (optional):
php -S localhost:8000 -t public

```

### 3. Setup Frontend

```bash
cd frontend
npm install
npm run dev
```

### 4. Access the App

- Frontend: https://davit-ecommerce.store

### API Endpoint

- Backend: https://api.davit-ecommerce.store
