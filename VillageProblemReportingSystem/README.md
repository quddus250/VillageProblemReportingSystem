# Village Problem Reporting System

This is a web-based application built for a 2nd-year Community Service Project. It allows citizens to report local village problems online (e.g., road damage, water supply issues) and enables administrators to track and update the status of these complaints.

## Technologies Used
- HTML, CSS (Custom Design)
- JavaScript
- PHP
- MySQL

## How to Run in XAMPP

1. **Install XAMPP**: Download and install XAMPP from Apache Friends.
2. **Start Services**: Open the XAMPP Control Panel and start the **Apache** and **MySQL** modules.
3. **Copy Project Folder**: Copy this entire folder (`VillageProblemReportingSystem`) and paste it inside the `htdocs` directory of your XAMPP installation (usually `C:\xampp\htdocs\`).
4. **Database Setup**:
   - Open your browser and go to `http://localhost/phpmyadmin/`.
   - Click on the "Import" tab at the top.
   - Choose the `database.sql` file located in the project folder and click "Import" at the bottom to create the database and tables.
5. **Run the Project**:
   - Open your browser and visit: `http://localhost/VillageProblemReportingSystem/`
6. **Admin Login**:
   - URL: `http://localhost/VillageProblemReportingSystem/admin/login.php`
   - Default Username: `admin`
   - Default Password: `admin123`

## Features
- **User**: Register, Login, Report Problem (with image), Track Status, View Dashboard, Provide Feedback.
- **Admin**: Login, View Dashboard Analytics, Update Complaint Status & Remarks, Manage Users, View Feedback.

## Note
Ensure the `uploads` folder exists inside the project directory and has write permissions so that users can upload images successfully.
