yii2-advanced-template
======================

Start template, based on yii2-advanced-template

 
Features
-------------------

- added rest api app
- added to backend Admin LTE theme


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
