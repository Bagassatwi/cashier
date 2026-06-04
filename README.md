# Mini Cashier System

A simple web-based cashier system built with HTML, Tailwind CSS, and PHP. This is a frontend-focused implementation with mock data ready for backend integration.

## Project Structure

```
cashier/
├── index.php          # Dashboard/Homepage
├── products.php       # Product Management
├── customers.php      # Customer Management
├── transactions.php   # New Transaction Form
├── reports.php        # Transaction History & Reports
├── admin.php          # Admin Settings
├── logout.php         # Session Logout
└── README.md          # This file
```

## Features

### 1. **Dashboard** (index.php)
- Overview with key statistics:
  - Total Products
  - Total Customers
  - Total Transactions
  - Total Sales
- Recent transactions table
- Quick access to all modules

### 2. **Products Management** (products.php)
- View all products
- Search functionality
- Add new product button
- Edit/Delete actions for each product
- Displays: Product Name, Price, Stock

### 3. **Customers Management** (customers.php)
- View all customers
- Search functionality
- Add new customer button
- Edit/Delete actions for each customer
- Displays: Customer Name, Phone Number

### 4. **Transactions** (transactions.php)
- Create new transactions
- Select customer and transaction date
- Add multiple products with quantity
- Real-time calculation of subtotal and total
- Transaction summary sidebar
- Payment method selection
- Save transaction functionality

### 5. **Reports** (reports.php)
- Transaction history with date filtering
- Summary statistics:
  - Total Transactions
  - Total Sales
  - Average Transaction Value
- Detailed transaction table
- View individual transaction details

### 6. **Admin Panel** (admin.php)
- Profile management
- Settings menu with options for:
  - Profile settings
  - Change password
  - Store settings
  - System settings
  - User management
- Edit profile information

## Technologies Used

- **Frontend**: HTML5, Tailwind CSS (via CDN)
- **Icons**: Font Awesome 6.4.0
- **Backend**: PHP (ready for integration)
- **Styling**: Responsive design with Tailwind CSS

## Installation & Setup

1. Extract files to your web directory (e.g., `d:/laragon/www/cashier/`)
2. Access the application in your browser: `http://localhost/cashier/`
3. No database setup required initially (using mock data)

## Getting Started

### For Frontend Development:
- All pages are already styled and responsive
- Modify colors and styles in the Tailwind classes
- Update mock data in HTML tables as needed

### For Backend Integration:
1. Replace mock data with database queries
2. Implement CRUD operations for:
   - Products (create, read, update, delete)
   - Customers (create, read, update, delete)
   - Transactions (create, read)
3. Add form submission handling and validation
4. Implement user authentication
5. Add database error handling

## Key Files to Modify for Backend

- **All PHP files**: Replace mock data with database queries
- **transactions.php**: Implement product addition logic and calculations
- **reports.php**: Add date filtering and statistics calculation
- Add a `config.php` file for database connection
- Create `functions.php` for reusable database functions

## Suggested Next Steps

1. Set up a database (MySQL/MariaDB recommended)
2. Create necessary tables:
   - `products` (id, name, price, stock)
   - `customers` (id, name, phone, email)
   - `transactions` (id, customer_id, date, total)
   - `transaction_items` (id, transaction_id, product_id, quantity, price)
   - `users` (id, username, password, email, role)

3. Implement database functions in `functions.php`
4. Create API endpoints for CRUD operations
5. Add user authentication system
6. Implement input validation and error handling
7. Add PDF export functionality for reports
8. Implement payment gateway integration

## Color Scheme

- **Primary**: Blue (`bg-blue-600`)
- **Success**: Green (`bg-green-600`)
- **Warning**: Yellow (`bg-yellow-500`)
- **Danger**: Red (`bg-red-600`)
- **Sidebar**: Dark Gray (`bg-gray-900`)

## Responsive Breakpoints

The system uses Tailwind's responsive grid system:
- Mobile-first approach
- Responsive tables
- Responsive forms
- Mobile-friendly navigation

## Notes

- All data is currently mock/hardcoded for UI demonstration
- Session handling is basic (ready for expansion)
- No authentication system implemented yet
- Forms do not submit (ready for backend)
- Calculations on transactions.php are static examples

## License

This is a template system ready for customization and backend implementation.
