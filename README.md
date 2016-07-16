
# sms_sender_api #
======================


Installation
-------------------

- ``` cd /var/www/ ```

- ``` git clone <path to repository> ./<path to project> ```

- ``` cd <path to project>/ ```

- 
*Install composer if not installed*

 ``` curl -s https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer && composer global require "fxp/composer-asset-plugin:~1.1.1" ```

- ``` composer create-project ```

- ``` cd _protected/ ```

- ``` ./init ```

- Change the parameters to be correct for your database in file:
 ``` _protected/common/config/main-local.php ``` 
 
- ``` ./yii migrate ```