Readme: http://ru.stackoverflow.com/questions/301326/yii2-%D0%B8-%D0%BC%D0%BE%D0%B8-%D0%BF%D0%BE%D0%BF%D1%8B%D1%82%D0%BA%D0%B8-%D0%B7%D0%B0%D0%BF%D1%83%D1%81%D1%82%D0%B8%D1%82%D1%8C-%D0%BF%D0%B5%D1%80%D0%B2%D1%8B%D0%B9-unit-%D1%82%D0%B5%D1%81%D1%82

This directory contains various tests for the advanced applications.

Tests in `codeception` directory are developed with [Codeception PHP Testing Framework](http://codeception.com/).

After creating and setting up the advanced application, follow these steps to prepare for the tests:

1. Install Codeception if it's not yet installed:

   ```
   composer global require "codeception/codeception=2.0.*" "codeception/specify=*" "codeception/verify=*"
   ```

   If you've never used Composer for global packages run `composer global status`. It should output:

   ```
   Changed current directory to <directory>
   ```

   Then add `<directory>/vendor/bin` to you `PATH` environment variable. Now you're able to use `codecept` from command
   line globally.

   ```
    echo 'export PATH="$PATH:~/.composer/vendor/bin"' >> ~/.bashrc
   ```
2. Install faker extension by running the following from template root directory where `composer.json` is:

   ```
   composer require --dev yiisoft/yii2-faker:*
   ```

3. Create `yii2_advanced_tests` database then update it by applying migrations:

   ```
   codeception/bin/yii migrate
   ```

4. In order to be able to run acceptance tests you need to start a webserver. The simplest way is to use PHP built in
   webserver. In the root directory where `common`, `frontend` etc. are execute the following:

   ```
   php -S localhost:8080
   ```

5. Now you can run the tests with the following commands, assuming you are in the `tests/codeception` directory:

   ```
   # frontend tests
   cd frontend
   codecept build
   codecept run
   
   # backend tests
   
   cd backend
   codecept build
   codecept run
    
   # etc.
   ```

  If you already have run `codecept build` for each application, you can skip that step and run all tests by a single `codecept run`.
