

# Dice Game API REST
This is a REST API for a dice game application developed with Laravel PHP.

## Game Description
The game consists of rolling two dice and winning the round if the sum of the two dice is equal to 7.

**Players can:**
- Dice rolls.
- View game list.
- Delete game list.
- See success rate.
- Update nickname.

**Admin can:**
- View all players and their games.
- View player success rates.
- Winners ranking.
- Losers ranking.


## Application Features

### Roles
There are two types of users in this application:

- Players: created by default.
- Administrators: defined in the database.

### Authentication
Authentication is done using Laravel Passport, enabling token-based authentication.

### Database
MySQL is used as the database management system to store application data.

### Testing
Testing is performed using PHPUnit, a PHP unit testing tool.

### Technologies Used
Laravel 10
Laravel Passport
PHPUnit
MySQL

## Installation

1.  Clone this repository on your local machine.
 
      `git clone https://github.com/yourusername/yourrepository.git` 
    
2.  Navigate to the project directory.
    
    `cd yourrepository` 
    
3.  Install Composer dependencies.
     
    `composer install` 
    
4.  Copy the example configuration file and set your own environment variables.

    `cp .env.example .env` 
    
5.  Generate a unique application key.
    
    `php artisan key:generate` 
    
6.  Configure the database in the `.env` file with your credentials.
    
7.  Run migrations to create the database tables.
    
    `php artisan migrate`
    `php artisan db:seed`
    
9.  Start the development server.
    
    `php artisan serve`

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
