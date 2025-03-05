# SkillDrop - README

## Project Overview  
SkillDrop is a web-based platform that connects service seekers with skilled professionals in their local areas. The system allows users to register, search for professionals, rate services, and communicate securely. Built using PHP (Laravel) and MySQL, SkillDrop ensures efficiency, scalability, and security for seamless user experiences.

## Available Features 
- User Authentication: Registration, Login, and Role-Based Access Control (Admin, Workers, Employers).

- Profile Management: Users can list their skills, availability, and location.
  
- The admin manages all accounts in the system and is resposible for approving, suspending and deletein accounts if necessary.
  
- Workers are able to list all the skills they have acquired and edit to make changes any time
  
- The system logs all changes made and the admin is notified
  
- Different users have independent dashboards depending on their roles in the system

- Search & Match: Geolocation-based search for professionals.

- Ratings & Reviews: Users can rate and review professionals.

- Messaging System: Secure in-app messaging. 

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

