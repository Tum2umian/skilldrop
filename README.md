# SkillDrop - README

## Project Overview  
SkillDrop is a web-based platform that connects service seekers with skilled professionals in their local areas. The system allows users to register, search for professionals, rate services, and communicate securely. Built using PHP (Laravel) and MySQL, SkillDrop ensures efficiency, scalability, and security for seamless user experiences.

## Available Features 
- User Authentication: Registration, Login, and Role-Based Access Control (Admin, Professional, Seeker).

- Profile Management: Professionals can list their skills, availability, and location.

- Search & Match: Geolocation-based search for professionals.

- Ratings & Reviews: Users can rate and review professionals.

- Messaging System: Secure in-app messaging.

- Admin Controls: Manage users and listings. 

## Technology Stack Used  
- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP
- **Database:** MySQL 
- **Other:** Docker, GCP

## Installation Guide

1. Clone the Repository

 git clone https://github.com/your-repo/skilldrop.git
 cd skilldrop

2. Setup Database

Start MySQL from XAMPP or system service.

Import the database:

 mysql -u root -p < database/skilldrop.sql

3. Configure Environment

4. Rename .env.example to .env and update database settings:

 DB_CONNECTION=mysql
 
 DB_HOST=127.0.0.1
 
 DB_PORT=3306
 
 DB_DATABASE=skilldrop
 
 DB_USERNAME=root
 
 DB_PASSWORD=""

5. Run migrations (if needed):

 php artisan migrate

6. Run the Application

 php artisan serve

Access SkillDrop at: http://localhost:8000

## Deployment on VPS

1. Install LAMP Stack

 sudo apt update && sudo apt install apache2 mysql-server php php-mysql -y

2. Deploy Code

 cd /var/www/html/
 git clone https://github.com/your-repo/skilldrop.git

3. Configure Apache

Edit Apache Virtual Host and restart Apache:

 sudo systemctl restart apache2

4. Setup Database & Migrations

 mysql_secure_installation
 php artisan migrate

## Usage

1. Visit http://localhost:8000 for local setup.

2. Register an account as a Professional or Service Seeker.

3. Search for professionals based on skill and location.

4. Leave reviews after service completion.

## Security Best Practices

- Data Encryption: SSL/TLS enabled.

- Authentication: Secure login with hashed passwords.

- SQL Injection Protection: Input validation and prepared statements.

## Software Metrics Tracking

- Weekly Commits: Updates 
tracked in metrics.md.

- Code Quality: PHPMD (PHP Mess Detector) reports.

- Performance Monitoring: Query execution time tracking.

- Test Coverage: PHPUnit for backend tests.

## 📈 Software Testing Metrics Applied

## 🛡️ SkillDrop Testing Dashboard

This module introduces automated quality checks across the project, including real-time calculations of software metrics, defect estimation, workflow validations, and persistent test logging.
This module implements concepts such as **Lines of Code (LOC) analysis**, **defect density estimation**, **workflow integrity verification**, and **historical tracking of results**, directly applying principles from **Lecture 10**.
Every test operates on live project data—without assumptions—providing a practical, lightweight framework to ensure SkillDrop's ongoing software quality and reliability.

## 🧩 OO Metrics Visualization

This enhancement introduces dynamic color-coded visualization for object-oriented metrics across SkillDrop classes, covering **WMC (Weighted Methods per Class)**, **DIT (Depth of Inheritance Tree)**, **CBO (Coupling Between Objects)**, and **LCOM (Lack of Cohesion in Methods)**.  
This implementation leverages real-time project data—no simulations—to immediately reveal structural or design anomalies.  
Each metric result is mapped to a visual color code (green, red, orange, gray) based on carefully selected thresholds, making code quality issues easily identifiable at a glance.  
This practical quality control directly applies analysis principles from **Lecture 11**, embedding software metrics interpretation seamlessly into the SkillDrop testing workflow for maintainability, auditing, and better project reliability.

## Halstead Analyzer

The project includes a JavaScript-based code complexity analyzer (`halsteadAnalyzer.js`) that helps identify complex code sections that might need refactoring. The analyzer processes multiple file types and calculates Halstead complexity metrics:

- **Supported File Types:**
  - PHP files (.php)
  - JavaScript files (.js)
  - CSS files (.css)
  - HTML files (.html)

- **Complexity Metrics:** 
  - Unique operators (n1) and operands (n2)
  - Total operators (N1) and operands (N2)
  - Program vocabulary (n) and length (N)
  - Program volume (V) and difficulty (D)
  - Programming effort (E)

To analyze your codebase:

 node halsteadAnalyzer.js

halsteadAnalyzer.js implements Halsted complexity model




## Security Best Practices

- Data Encryption: SSL/TLS enabled.

- Authentication: Secure login with hashed passwords.

- SQL Injection Protection: Input validation and prepared statements.

## Software Metrics Tracking

- Weekly Commits: Updates tracked in metrics.md.

- Code Quality: PHPMD (PHP Mess Detector) reports.

- Performance Monitoring: Query execution time tracking.

- Test Coverage: PHPUnit for backend tests.

## Contributing

1. Fork the repository.

2. Create a feature branch (git checkout -b feature-name).

3. Commit changes (git commit -m 'Add feature').

4. Push to the branch (git push origin feature-name).

5. Open a Pull Request.

## License
This in open-source project.

## Contact Us

For any inquiries or issues, contact us through the group admin. 
halsteadAnalyzer.js implements Halsted complexity model

