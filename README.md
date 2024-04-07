# Task Manager Application

This is a brief guide on how to run the Task Manager application.

## Setup

1. **Clone the Repository**
    ```
    git clone https://github.com/rasheedy97/Task-Manager
    ```

2. **Install Composer Dependencies**
    Navigate to the project directory:
    ```
    cd Task-Manager
    ```
    Then run:
    ```
    composer install
    ```

3. **Create a copy of the .env file**
    ```
    cp .env.example .env
    ```

4. **Generate an app encryption key**
    ```
    php artisan key:generate
    ```

5. **Migrate and Seed the Database**
    ```
    php artisan migrate —seed
    ```
    This will create 10 users, 3 of which are managers and 7 are users. The users are:
    - manager[1,2,3]@example.com password123
    - user[4,5,6,7,8,9]@example.com Passwords: password123
     

    It will also create 10 tasks and assign them randomly.

6. **Start the Server**
    ```
    php artisan serve
    ```

Now, you should be able to access the application by the postman collection on http://localhost:8000.

A more detailed explanation is found in the postman collection.
