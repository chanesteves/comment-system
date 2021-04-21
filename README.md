<p align="center">
    <h1>Comment System</h1>
</p>

## Features

- Adding a comment to a post
- Replying to a comment (up to 3 layers)

## Installation

<ol>
    <li>
        Run the commands below to download the repository and install the dependencies <br/>
        <code>git clone https://github.com/chanesteves/comment-system.git // clone the repo</code><br/>
        <code>composer update // install dependencies</code><br/>
        <code>npm install // install packages</code><br/>
    </li>
    <li>
        Update your database connections in <code>.env</code> file <br/>
        <code>DB_CONNECTION=mysql</code><br/>
        <code>DB_HOST=127.0.0.1</code><br/>
        <code>DB_PORT=3306</code><br/>
        <code>DB_DATABASE=comment_system</code><br/>
        <code>DB_USERNAME=username</code><br/>
        <code>DB_PASSWORD=secret</code><br/>
    </li>
    <li>
        Run the commands below to generate a new key, clear the configuration cache, and run the migration files <br/>
        <code>php artisan key:generate</code><br/>
        <code>php artisan config:cache</code><br/>
        <code>php artisan migrate</code><br/>
    </li>
</ol>

## Others
Demo site: http://www.mycommentsystem.tk
