ZIMBABWE TOURISM INFORMATION SYSTEM - STARTER PROJECT

REQUIREMENTS
- XAMPP (Apache and MySQL)
- Visual Studio Code
- A modern browser

INSTALLATION
1. Install XAMPP and Visual Studio Code.
2. Extract the folder "zimbabwe-tourism-system" into:
   C:\xampp\htdocs\
   The final path should be C:\xampp\htdocs\zimbabwe-tourism-system
3. Open XAMPP Control Panel. Click Start next to Apache and MySQL.
4. Open http://localhost/phpmyadmin in your browser.
5. Select the Import tab, choose database.sql from this project folder, and click Import/Go.
   This creates tourism_db and sample Zimbabwe destinations.
6. Open the project folder in Visual Studio Code.
7. Check config.php. The default XAMPP MySQL username is root and password is blank.
8. In your browser visit:
   http://localhost/zimbabwe-tourism-system/create-admin.php
9. It creates the first administrator:
   Username: admin
   Password: ChangeMe123!
10. IMPORTANT: Immediately delete create-admin.php from the project folder.
11. Open the public site:
   http://localhost/zimbabwe-tourism-system/
12. Open the admin login:
   http://localhost/zimbabwe-tourism-system/admin-login.php

HOW TO USE
- On the public homepage, search destinations and click View details.
- Log in as administrator to add, edit or delete destinations.
- Use the accommodation form to add hotels and lodges linked to a destination.
- Uploaded pictures are saved in the uploads folder.

IMPORTANT NOTES
- This is a learning/demo starter, not a production-ready public cloud deployment.
- Before deploying, use HTTPS, strong unique admin credentials, remove setup scripts,
  configure secure session cookies, restrict upload execution, and set up backups.
- Keep your PHP, MySQL and hosting software updated.
- Do not upload private or real administrator credentials to public repositories.
