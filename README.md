# E-Commerce Project
This is a full-stack e-commerce project built with Laravel for the backend and React.js for the frontend. The project allows users to browse products, rate them, and add them to the shopping cart.

# Features
Product Listing: Display a list of products with details such as price, description, and images.
Product Rating: Logged-in users can rate products with a 1-5 star system.
Shopping Cart: Users can add products to their cart and modify the quantity.
Admin Panel: Admins can manage product categories, products, and orders from a dedicated admin panel.
Technologies Used
Backend: Laravel
Frontend: React.js
Database: MySQL
CSS: Custom Styles
Authentication: Laravel Auth (for logged-in user features)
API: Axios for data fetching between frontend and backend
Setup Instructions
1. Clone the repository:
bash
Copy code
git clone https://github.com/your-repository/ecommerce.git
cd ecommerce
2. Install Laravel dependencies:
Run the following command to install Laravel's dependencies:

bash
Copy code
composer install
3. Set up the environment file:
Copy .env.example to .env:

bash
Copy code
cp .env.example .env
4. Generate the application key:
bash
Copy code
php artisan key:generate
5. Set up the database:
You need to create a MySQL database for the project.

Create a database in MySQL (e.g., ecommerce_db).
Update the .env file with your database credentials:
env
Copy code
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=
6. Import the database schema:
The database schema is provided in the ecommerce_db.sql file.

Open ecommerce_db.sql and import it into your MySQL database (e.g., using PhpMyAdmin or the MySQL command line):
bash
Copy code
mysql -u root -p ecommerce_db < ecommerce_db.sql
7. Run Laravel migrations:
If you want to create any missing tables from migrations, you can run:

bash
Copy code
php artisan migrate
8. Install frontend dependencies:
Navigate to the resources/js folder and install React dependencies:

bash
Copy code
cd resources/js
npm install
9. Build the frontend:
To compile the frontend assets, use:

bash
Copy code
npm run dev
10. Start the Laravel server:
Finally, run the Laravel development server:

bash
Copy code
php artisan serve
This will start the application at http://localhost:8000.

Database Schema
The database schema used for this project is included in the ecommerce_db.sql file. The schema includes tables for products, categories, users, orders, ratings, and more.

Products Table: Stores product information such as name, price, description, image, and category.
Categories Table: Stores product categories like electronics, clothing, etc.
Orders Table: Stores information about customer orders.
Ratings Table: Stores product ratings given by users.
Admin Panel
To access the admin panel, log in as an admin user, and navigate to /admin. Here, you can manage product categories, products, and more.

Authentication
Authentication is handled via Laravel's built-in authentication system. Registered users can log in, add products to the cart, and rate products.

Contribution
Feel free to fork the repository and submit pull requests. Contributions are welcome!

License
This project is licensed under the MIT License - see the LICENSE file for details.

