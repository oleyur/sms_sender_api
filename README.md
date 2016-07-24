# sms_sender_api #
======================

API was written using Yii 2.0 Framework.
http://www.yiiframework.com/doc-2.0/guide-intro-yii.html

It requires PHP 5.4.0 or above.

Installation
-------------------

- ``` cd /var/www/ ```

- ``` git clone https://oleyur@bitbucket.org/oleyur/sms_sender_api.git ./<path to project> ```

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