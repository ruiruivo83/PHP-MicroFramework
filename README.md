# PHP-MicroFramework


# PHP-TicketTickle

CANNOT RUN "COMPOSER UPDATE" ON SERVER


#### DATABASE Instructions 
- Create Database name from file config or config_prod in your PHPmyAdmin.

- MUST create table "_database_updates" manually:

        CREATE TABLE [IF NOT EXISTS] _database_updates (
            id INT AUTO_INCREMENT PRIMARY KEY,
            last_executed_version CHAR(255)
        );

- Verify the database exists before running the webapp, it will try to create the tables and update the database from the sql scripts.
- Create Virtual host in your localhost dev computer.
- Try open the webapp in a browser.