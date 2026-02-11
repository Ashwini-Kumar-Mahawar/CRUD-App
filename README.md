# CRUD-App

A simple PHP web application demonstrating basic **Create, Read, Update, and Delete (CRUD)** operations using a MySQL database.

This project showcases how to build a complete CRUD system using core PHP, MySQL, HTML, and CSS.

---

## 📌 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Installation](#-installation)
- [Database Setup](#-database-setup)
- [Usage](#-usage)
- [Project Structure](#-project-structure)
- [Screenshots](#-screenshots)
- [Contributing](#-contributing)
- [License](#-license)

---

## 📖 Overview

CRUD-App allows users to:

- Create new records  
- View all records  
- Update existing records  
- Delete records  

It is built using core PHP and MySQL, making it a great beginner-friendly project to understand backend development fundamentals.

---

## ✨ Features

- ➕ Add new records (Create)
- 📄 View all records (Read)
- ✏️ Edit existing records (Update)
- ❌ Delete records (Delete)
- 🗄️ MySQL database integration
- 🎨 Clean and simple UI

---

## 🛠 Tech Stack

| Layer      | Technology |
|------------|------------|
| Backend    | PHP        |
| Database   | MySQL      |
| Frontend   | HTML, CSS  |
| Server     | Apache (XAMPP/WAMP/MAMP) |

---

## 🚀 Installation

### Prerequisites

Make sure you have the following installed:

- PHP (7.x or later)
- MySQL or MariaDB
- Local server (XAMPP, WAMP, or MAMP)

---

### Clone the Repository

```bash
git clone https://github.com/Ashwini-Kumar-Mahawar/CRUD-App.git
cd CRUD-App
```

Move the project folder to your server directory:
- XAMPP: htdocs
- WAMP: www

Start Apache and MySQL from your control panel.

Open in browser:

```bash
http://localhost/CRUD-App/
```

---

# 🗄 Database Setup

 ## 1️⃣ Create Database
 
 ```bash
CREATE DATABASE crud_app;
```

 ## 2️⃣ Create Table
 
 ```bash
CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

 ## 3️⃣ Configure Database Connection
  Update your config.php file:

 ```bash
<?php
$conn = new mysqli('localhost', 'your_username', 'your_password', 'crud_app');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

---

 ## ▶️ Usage
 
  1. Open the application in your browser.
  2. Add new entries using the form.
  3. Edit existing records using the edit option.
  4. Delete records using the delete button.

All operations directly interact with the MySQL database.

---

## 📂 Project Structure

 ```bash
CRUD-App/
│
├── config.php          # Database connection
├── index.php           # Display all records
├── add_task.php        # Create record
├── edit_task.php       # Update record
├── delete_task.php     # Delete record
├── style.css           # Styling
└── README.md           # Project documentation
```

---

 ## 📸 Screenshots

<img width="1217" height="567" alt="Screenshot 2026-02-11 233905" src="https://github.com/user-attachments/assets/b9f9b0c6-6b5a-45b6-9a3f-7cf57814a2c8" />
<img width="1325" height="822" alt="Screenshot 2026-02-11 233935" src="https://github.com/user-attachments/assets/7e358998-1d11-4df0-a91d-c1aec960d3c0" />

---

 ## 🤝 Contributing
 Contributions are welcome.
 
 If you’d like to improve this project:

 1. Fork the repository
 2. Create a new branch
 3. Commit your changes
 4. Submit a pull request

---
