# PHP Message Board

A simple message board web application using PHP, PostgreSQL, and Bootstrap.

## Features

- **User Authentication**: Sign up, log in, and log out functionalities.
- **Message Posting**: Users can post messages and view messages posted by others.
- **Comments**: Users can comment on messages.
- **Likes**: Users can like and unlike messages and comments.
- **Latest Updates**: Display latest images, hottest messages, latest comments, and newest users on the homepage.

## Tech Stack

- **Backend**: PHP
- **Database**: PostgreSQL
- **Frontend**: Bootstrap, JavaScript, CSS

## Installation

1. **Clone the repository**:
    ```bash
    git clone https://github.com/alexandrubunea/php-message-board.git
    ```
2. **Navigate to the project directory**:
    ```bash
    cd php-message-board
    ```
3. **Database Setup**:
    - Create a PostgreSQL database.
    - Import the database schema from `database.sql` file.
    - Update database connection settings in `config.php`.

4. **Start the server**:
    Use a local PHP server or configure a web server (Apache, Nginx) to serve the application.

## Configuration

- Update the `config.php` file with your database credentials and other configurations.

## Usage

- **Home Page**: Displays latest images, hottest messages, latest comments, and newest users.
- **Message Page**: View and interact with individual messages, post comments, and like/unlike functionalities.


## License

This project is licensed under the MIT License. See the [LICENSE](https://github.com/alexandrubunea/php-message-board/blob/main/LICENSE) file for details.
