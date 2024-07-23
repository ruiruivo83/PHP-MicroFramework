<?php

namespace App;

/**
 * Application configuration
 * 
 * PHP version 7.4
 */
class Config
{

  /**
   * Database host
   * @var string
   */
  const DB_HOST = 'localhost';

  /**
   * Database name
   * @var string
   */
  const DB_NAME = 'php_microframework_db';

  /**
   * Database user
   * @var string
   */
  const DB_USER = 'root';

  /**
   * Database password
   * @var string
   */
  const DB_PASSWORD = '';

  /**
   * Secret key for hashing
   * @var boolean
   */
  const SECRET_KEY = 'tsDiAwgwoxA0e8Y6kN2t9DTKxGocNdH9';

  /**
   * SMTP CLIENT - SMTP ADRESS
   * 
   * @var string
   */
  const smtpAdminClient = "smtp-relay.sendinblue.com";

  /**
   * SMTP CLIENT - ADMIN EMAIL
   * 
   * @var string
   */
  const smtpAdminEMail = "ruivo.rui@gmail.com";

  /**
   * SMTP CLIENT - ADMIN EMAIL PASSWORD
   * 
   * @var string
   */
  const smtpAdminEMailPassword = "3AL5a0QTJKzYhy6B";

  /**
   * Show or hide error messages on screen
   * 
   * FOR DEBUG MODE
   * 
   * @var boolean
   */
  const SHOW_ERRORS = true;
}
