# College Transport Management System (CTMS)

A web-based application to manage college transportation, including student allocations, driver management, route tracking, and fee status.

## 🚀 Features
- **Admin Dashboard**: Manage vehicles, routes, drivers, and students.
- **Driver Module**: View assigned trips and salary history.
- **Student Module**: Track bus, view fee status, and submit feedback.
- **Responsive Design**: Fully responsive UI for tablet and mobile devices.
- **Feedback System**: Interactive rating and feedback system for students.

## 🛠️ Technology Stack
- **Backend**: PHP (PDO for database interactions)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Design**: Google Fonts (Outfit), FontAwesome icons, SweetAlert2 for notifications

## 💻 Installation (Local Setup)
1. **Clone the repository**:
   ```bash
   git clone https://github.com/YOUR_USERNAME/college-tms.git
   ```
2. **Setup Database**:
   - Open XAMPP Control Panel and start Apache and MySQL.
   - Go to `http://localhost/phpmyadmin`.
   - Create a new database named `college_tms`.
   - Import the `database.sql` file provided in the root directory.
3. **Configure Database**:
   - Open `config/db.php` and update your database credentials if different from default.
4. **Run Application**:
   - Move the folder to your `htdocs` directory.
   - Access via `http://localhost/college-tms`.

## 📜 License
This project is open-source and available for use.
