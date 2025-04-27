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

- Weekly Commits: Updates tracked in metrics.md.

- Code Quality: PHPMD (PHP Mess Detector) reports.

- Performance Monitoring: Query execution time tracking.

- Test Coverage: PHPUnit for backend tests.

- Lines of Code Counter: Automated GitHub workflow that monitors project size:
  - Tracks total lines of code in PHP, HTML, CSS, and JavaScript files
  - Runs automatically on every push and pull request
  - Helps track project growth and complexity
  - Configuration in `.github/workflows/loc.yml`

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
halsteadAnalyzer.js implements Halsted complexity model3. Search for professionals based on skill and location.

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
halsteadAnalyzer.js implements Halsted complexity model


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

## Reliability Practices in SkillDrop

To ensure the reliability of SkillDrop, we have implemented the following practices:

### Logging for Failure Tracking
We have added a logging mechanism to track failures, their context, and execution time. This helps us identify and analyze issues effectively. The logs are stored in `logs/system.log`.

### Reliability Metrics Calculation
A script (`scripts/reliabilityMetrics.php`) has been added to calculate key reliability metrics:
- **MTTF (Mean Time To Failure)**: Average time the system operates correctly before a failure.
- **MTTR (Mean Time To Repair)**: Average time taken to fix a fault after a failure.
- **MTBF (Mean Time Between Failures)**: Average time between consecutive failures.

### Usage Instructions
1. **Logging Failures**: Use the `Logger` class in `includes/logger.php` to log failures. Example:
   ```php
   $logger = new Logger();
   $logger->logFailure('Database connection failed', ['operation' => 'fetchUser', 'userId' => 123]);

   Here's a concise summary for your README file that connects your PHP code analysis pipeline to the SENG 421 lecture requirements on empirical software engineering investigation:

---

### Empirical Software Engineering Investigation

It conducts a case study to evaluate PHP code quality in `auth/`, `employers/`, and `workers/` directories using static analysis tools (PHPMD, PHP_CodeSniffer, PHPLoc, PHPStan). Key metrics like cyclomatic complexity, style violations, and potential bugs are analyzed and presented in `analysis_report.html`. The pipeline, automated via `generate_metrics_report.bat`, follows empirical SE principles by defining a research question ("Can static analysis tools improve code quality?"), collecting data from source code (third-degree contact), and addressing practical challenges (e.g., PHPMD failure for `auth/`). It bridges research and practice by validating tools for industrial use, meeting the lecture's goals of improving SE processes through empirical evaluation.
