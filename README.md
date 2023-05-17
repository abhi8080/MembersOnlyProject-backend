# MembersOnlyProject-backend

This is the backend application built with Laravel for the Members Only Application. It provides the API endpoints and handles data storage and retrieval.

## Prerequisites

PHP and Composer must be installed on the local system.

## Installation

1. Clone the repository to your local machine:

    ```bash
    git clone https://github.com/abhi8080/MembersOnlyProject-backend.git
    ```

2. Navigate to the project directory:

    ```bash
    cd MembersOnlyProject-backend
    ```

3. Install the dependencies using Composer:

    ```bash
    composer install
    ```

4. Configure the environment variables:

    - Rename the `.env.example` file to `.env`.
    - Open the `.env` file and update the database connection details, including the database name, username, and password.
    - Generate an application key:

        ```bash
        php artisan key:generate
        ```

5. Run the database migrations:

    ```bash
    php artisan migrate
    ```

6. Start the development server:

    ```bash
    php artisan serve
    ```

    The backend server will now be running at `http://127.0.0.1:8000`.

## Usage

The backend provides the following API endpoints:

-   **Endpoint 1**: `/api/auth/register`

    -   `POST`: Creates a new user.

-   **Endpoint 2**: `/api/auth/login`

    -   `POST`: Logs in a user.

-   **Endpoint 3**: `/api/messages`

    -   `GET`: Retrieves all messages.
    -   `POST`: Creates a new message.

-   **Endpoint 4**: `/api/messages/{id}`

    -   `DELETE`: Deletes a specific message by ID.

-   **Endpoint 5**: `/api/user/{id}`

    -   `PUT`: Updates a specific user by ID.
    -   `GET`: Retrieves the admin status of a specific user by ID.

### Transactions

Transactions are used to ensure data consistency and integrity when performing database operations. The backend application utilizes Laravel's built-in transaction feature to wrap database operations in a transaction block. This helps to maintain the atomicity and consistency of multiple database operations.

### PHPUnit Tests

The backend includes PHPUnit tests to ensure the correctness of the application's functionality. The tests cover different scenarios and edge cases to verify that the API endpoints and database operations are working as expected.

To run the PHPUnit tests, use the following command:

```bash
php artisan test
```

The tests will be executed, and the results will be displayed in the terminal.
