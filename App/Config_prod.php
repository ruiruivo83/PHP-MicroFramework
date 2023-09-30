<?php

namespace App;

/**
 * Application configuration
 * 
 * PHP version 7.4
 */
class Config_prod
{

  /**
   * Database host
   * @var string
   */
  const PROD_DB_HOST = 'jonfenmutr123.mysql.db';

  /**
   * Database name
   * @var string
   */
  const PROD_DB_NAME = 'jonfenmutr123';

  /**
   * Database user
   * @var string
   */
  const PROD_DB_USER = 'jonfenmutr123';

  /**
   * Database password
   * @var string
   */
  const PROD_DB_PASSWORD = 'm7jxvs92EH4QLcGBqqyr';

  /**
   * Secret key for hashing
   * @var boolean
   */
  const PROD_SECRET_KEY = 'tsDiAwgwoxAPe8Y6jN2t9DTkXGocnDH9';

  /**
   * SMTP CLIENT - SMTP ADRESS
   * 
   * @var string
   */
  const prod_smtpAdminClient = "smtp-relay.sendinblue.com";

  /**
   * SMTP CLIENT - ADMIN EMAIL
   * 
   * @var string
   */
  const prod_smtpAdminEMail = "ruivo.rui@gmail.com";

  /**
   * SMTP CLIENT - ADMIN EMAIL PASSWORD
   * 
   * @var string
   */
  const prod_smtpAdminEMailPassword = "3AL5a0QTJKzYhy6B";

  /**
   * Show or hide error messages on screen
   * 
   * FOR DEBUG MODE
   * 
   * @var boolean
   */
  const SHOW_ERRORS = true;

}