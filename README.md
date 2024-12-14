# Real-Estate Website secure

This is a website which manages real estate properties for users to rent and buy. Here, builders can register, add and modify flats, lands, properties, etc. Normal users can login, view and search for registered properties to buy or rent properties.

The website is secure, with brute-force attack prevention. Your passwords are well encrypted. The session cookies are configured with necessary security options to prevent session hijacking, XSS attacks and CSRF attacks.

The website opens with three webpages; home (index.html), about us and contact us. In the top bar, there are registration and login form (loginuser.php) pages. There are different registration forms; 1 for normal user (register.php) nd other one for builders (reg_builder.php). Thus, there are 2 types of users with different privileges.

In the login page, there are 2 security functionalities implemented. First one : google Recaptcha. Significance- it re-assures that legitimate user only trying to login. The automated brute-force scripts does not work in the presence of the feature. A legitimate user has to fill details and click on "I'm not a robot" checkbox, then only he can log in succcessfully.
Second : password hash. : While registering the user (both normal & builder), the password entered is encrypted with the 256bit Secure Hash algorithm. Then this encrypted value is stored in the database. Significance- it ensures that if ny attacker gets access to DB, passwords cannot be leaked. Also, if any attacker intercepts the packet containing login credentials, he cannot see actual password but it's hash value.

Third: After logging in, when the user session starts, the session cookies are set with the ideal security configurations. Which ensures the cookies are sent over only https (secure) connections, blocked cross origin cookies transfer, which prevents CSRF and XSS attacks during the session. 

Fourth: Lock out policy. When a user fails to enter wrong password for 3 times, the account gets locked out for some time.

Below are the key files in the application:
-homepage: index.html
-login page: loginuser.php
-User registration pages: 1. Builder user: reg_builder.php, 2. Normal User: register.php
-About page: about.html
-Contact us page: contact.html
-After login: 1. Builder user: builderhome.php, 2. Normal user: (default: normalHomeSale.php), normalHomrent.php



