# Web-Crawling (Task-1)

### Satisfying elavuation criteria

- [x] __Create the first page (home/index page) along with five other web pages__
  - Created an `index.html` page with `login` and `register` buttons.
  - Created other relevant pages (`.php` files)
    - login.php
    - logout.php
    - register.php
    - user.php
    - admin.php
    - scraper.php (*main functionality*)
    - connection.php
    - check.php
    - forgot_password.php
    - reset_password.php
  
- [x] __Effective use of color, images and font and proper page markup language__
  - Used CSS along with bootstrap for better customization and flexibility. 
  - Used image in `index.html` page.
  - Used different font style, weights, sizes with CSS.
  - Added comments wherever necessary.
  
- [x] __Utility of html, CSS, javascript and any other languages in implementation__
  - Used `HTML` for constructing the base site.
  - Used `CSS` for arranging and structuring them.
  - Used `javascript` for event handlers, validation, alerts and debugging.
  - Used `Bootstrap`, a framework to customize the HTML elements even better.
  
- [x] __Interactive features over the website__
  - Setup a contact form in `user.php` page where a user can send his query or suggestion. 
  - The messages are stored in the database.
  - Admin has the privilage to view the message from `admin.php` site.
  - The time at which the message has been sent is also recorded.
 
- [x] __Validating the HTML code__
  - Validated my `index.html` file. 
  
- [x] __User registration, Authentication and Page/Form Validation features across the website__
  - The user has to register first before accessing the main data page. 
  - Validation features
    - Username checked for availability.
    - Minimum length of password is set to 6.
    - Confirm password field is added.
    - Phone number field to accept only 10 digit numbers.
  - The user data along with time of registration is stored in a separate table.
  - Password is encrypted using `md5()` function in PHP.
  
- [x] __Separate pages for user and admin__
  - While saving the user data, an attribute `type` is either set to A(admin) or U(user).
  - While logging in, he/she is redirected to relevant page based on the type.
  - User features
    - Reset password
    - Send a feedback message.
    - Get weather data. 
  - Admin features
    - Create new user.
    - Delete an existing user.
    - Show the users.
    - Display the messages.
    - Download user data for analysis.
    - All other features of a user.
    
- [x] __Functional website__
  - The website is fully functional with all the link working.
  - All the redirections are taken care.
  - Additional features and security is also added for user experience
  
- [x] __Implementation of Ajax, jQuery and JSON features__
  - Ajax
    - Ajax is used to validate if a username is available for registering or not without reloadin the whole page. 
    - A get request is sent with the username field.
    ```php
    xmlhttp.open("GET", "check.php?username="+str, true);
    xmlhttp.send();
    ```
    - After the result is fetched, it is checked an appropriate statement is displayed iun relevant colour.
    ```php
    if (result == "Username Available") {
        document.getElementById("txtHint").style.color = "green";
    } else {
        document.getElementById("txtHint").style.color = "red";
    }
    document.getElementById("txtHint").innerHTML = result;
    ```
  - jQuery
    - Used in the welcome page for animation purpose and redirection purpose.
    ```js
    $("h1").mouseenter(function() {
          $(this).animate({fontSize: '4em'}, 400);
    })
    $("h1").mouseleave(function() {
          $(this).animate({fontSize: '3em'}, 200);
    })
    ```
    - If the user clicks the login button, the user is redirected to login page.
    ```js
    $("button").click(function() {
         if ($(this).text() == "Login") {
              window.location.href = "login.php";
         } else {
              window.location.href = "register.php";
         }
    });
  - JSON
    - The user can download user data for analysis. This data is dowloaded in json format as `file.json`.
    - With this file, we can access the values using keys.
    ```php
    fwrite($myfile, json_encode($x));
    ```
    - With the above code, it is encoded in json format and written to the file. 
    - Data is stored as
    ```js
    [
      {"id":"1","username":"test1","phone":"999999999","type":"A","created_at":"2020-11-19 11:22:00"},
      {"id":"2","username":"test2","phone":"888888888","type":"U","created_at":"2020-11-19 11:26:23"}
    ]
    ```
    
### Working of the website

A sample walkthrough can be found [here.](https://drive.google.com/file/d/1icZe2KHUYj5BK9tF0ZJ7kNJQct-0N_Gj/view?usp=sharing) (*can be viewed only with uohyd account*)

- __index.html__

- __login.php__

- __register.php__

- __user.php__

- __admin.php__
