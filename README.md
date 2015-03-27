Yii 2 Starter Kit
================================
This is Yii2 start application template.

It was created and developing as a fast start for building an advanced sites based on Yii2. 

It covers typical use cases for a new project and will help you not to waste your time doing the same work in every project


FEATURES
--------
- Based on yii2-advanced application template
- Beautiful and open source dashboard theme for backend (http://almsaeedstudio.com/AdminLTE)
- I18N + 2 translations: Ukrainian, Russian
- I18N DbMessageSource CRUD module
- Language change action + behavior to choose locale based on browser preferred language 
- `ExtendedMessageController` with ability to replace source code language and migrate messages between message sources
- Sign in, Sign up, profile(avatar, locale, personal data) etc
- OAuth authorization
- User management: CRUD
- RBAC with predefined `guest`, `user`, `manager` and `administrator` roles
- Content management components: articles, categories, static pages, editable menu, editable carousels, text blocks
- File storage component + file upload widget (https://github.com/trntv/yii2-file-kit)
- Key-value storage component
- Useful behaviors (GlobalAccessBehavior, CacheInvalidateBehavior)
- Yii2 log web interface
- Application events component
- System information web interface
- Aceeditor widget (http://ace.c9.io, https://github.com/trntv/yii2-aceeditor), 
- Datetimepicker widget (https://github.com/trntv/yii2-bootstrap-datetimepicker), 
- Imperavi Reactor Widget (http://imperavi.com/redactor, https://github.com/asofter/yii2-imperavi-redactor), 
- Elfinder Extension (http://elfinder.org, https://github.com/MihailDev/yii2-elfinder)
- Xhprof Debug panel (https://github.com/trntv/yii2-debug-xhprof)
- Extended IDE autocompletion
- Nginx config example
- many other features i'm lazy to write about :-)

DEMO
----

http://yii2-starter-kit.terentev.net

http://backend.yii2-starter-kit.terentev.net

`administrator` role account
```
Login: webmaster
Password: webmaster
```

`manager` role account
```
Login: manager
Password: manager
```

`user` role account
```
Login: user
Password: user
```

REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Before installation
If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

Install composer-asset-plugin needed for yii assets management
```
php composer.phar global require "fxp/composer-asset-plugin:1.0.0-beta4"
```


### Install from GitHub (preferred way)

Extract the github archive file or clone this repository.
```
git clone https://github.com/trntv/yii2-starter-kit.git
```

After extraction run
```
php composer.phar install
```

### Install via Composer

You can install this application template with `composer` using the following command:

```
php composer.phar create-project --prefer-dist --stability=dev trntv/yii2-starter-kit
```

### Initialization
Initialise application
```
./init // init.bat for windows
```
Initialization tools will copy config (`*-local`) files where you can override settings specific for application local environment.
**NOTE:** `environments/*-local` files are excluded from git in `.gitignore`

CONFIGURATION
-------------

### Environments 
All configuration files are in `config` directories in each application

Environment specific configuration files stored in `environments/some-environment` directory

`environments/-some environment-/_init` folder contains config templates that will be copied to your config in initialization process, so your can change them to fit your needs on specific environment. 
They are stored under the VCS. 

Application resolves current environment by `YII ENV` environment variable.
You should set it in your web server config or change in `frontend/web/index.php`, `backend/web/index.php`, `console/yii` files.
Default environment is `dev`.

For console application, you can change current environment by setting ``YII_ENV`` variable

```export YII_ENV=prod && php ./path/to/yii controller/action```

### Web Server

You should configure web server with three different web roots:

`example.com` => `path/to/yii2-starter-kit/frontend/web`

`backend.example.com` => `path/to/yii2-starter-kit/backend/web`

`storage.example.com` => `path/to/yii2-starter-kit/storage`

**NOTE:** Preferable web server for me, personally, is nginx, so there is a `nginx.conf` file with an example nginx config.
  
### Database
Edit the file `environments/dev/common/config/base-local.php` with real data, for example:

```php
...
'db' => [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2-starter-kit',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
...
```
**NOTE:** Yii won't create the database for you, this has to be done manually before you can access it.

Also check and edit the other files in the `environments/dev` directory to customize your application.

### Application urls
Set your current application urls in `environments/dev/bootstrap-local.php`

```php
Yii::setAlias('@frontendUrl', 'http://example.com');
Yii::setAlias('@backendUrl', 'http://backend.example.com');
Yii::setAlias('@storageUrl', 'http://storage.example.com');
```
#### Apply migrations

```php
php console/yii migrate
```

### Initialise RBAC config

```php
php console/yii rbac/init
```
**IMPORTANT: without rbac/init you CAN'T LOG IN into backend**

### Demo user
~~~
Login: webmaster
Password: webmaster
~~~

COMPONENTS
-------------
### I18N
If you want to store application messages in DB and to have ability to edit them from backend, run:
```php
php console/yii message/migrate @common/config/messages/php.php @common/config/messages/db.php
```
it will copy all existing messages to database

Then uncomment config for `DbMessageSource` in
```php
common/config/_base.php
```

### KeyStorage
Key storeage is a key-value storage to store different information. Application params for example.
Values can be stored both via api or by backend CRUD component.
```
Yii::$app->keyStorage->set('key', 'value');
Yii::$app->keyStorage->get('articles-per-page');
```

### ExtendedMessageController
This controller extends default MessageController to provide some useful actions

Migrate messages between different message sources:
``yii message/migrate @common/config/messages/php.php @common/config/messages/db.php``

Replace source code language:
``yii message/replace-source-language @path language-LOCALE``

Remove Yii::t from code
``yii message/replace-source-language @path``

### Behaviors
#### CacheInvalidateBehavior
```php
 public function behaviors()
 {
     return [
         [
             'class' => `common\components\behaviors\CacheInvalidateBehavior`,
             'tags' => [
                  'awesomeTag',
                   function($model){
                       return "tag-{$model->id}"
                  }
              ],
             'keys' => [
                  'awesomeKey',
                  function($model){
                      return "key-{$model->id}"
                  }
              ]
         ],
     ];
 }
```
#### GlobalAccessBehavior
usage example in `backend\config\web.php`

### Widgets configurable from backend
#### Carousel
...

#### DbText
...

#### DbMenu
```php
echo common\components\widgets\DbMenu::widget([
      'key'=>'stored-menu-key',
      /* any other option from \yii\widgets\Menu */ 
])
``` 

### Input widgets
- WYSIWYG Redactor widget (https://github.com/asofter/yii2-imperavi-redactor)  
- Datetimepicker - (http://eonasdan.github.io/bootstrap-datetimepicker, https://github.com/trntv/yii2-bootstrap-datetimepicker)
- Ace Editor - (https://github.com/trntv/yii2-aceeditor)

### Many more useful components
- ``common\components\behaviors\GlobalAccessBehavior`` allows to set access rules for your application in application config
- ``common\components\validators\JsonValidator`` validates a value to be a valid json
- ElFinder - (https://github.com/MihailDev/yii2-elfinder)
- RBAC OwnModelRule - simple rule to check if the current user is model owner

OTHER
-----
### Updates
Add remote repository `upstream`.
```
git remote add upstream https://github.com/trntv/yii2-starter-kit.git
```
Fetch latest changes from it
```
git fetch upstream
```
Merge these changes into your repository
```
git merge upstream/master
```
**IMPORTANT: there might be a conflicts between `upstream` and your code. You should resolve conflicts on your own**

### TODO
- Inline code documentation
- Tests
- Various improvements
- New features
- Permanent bug fixing ;)

### Have any questions?
mail to `eugine@terentev.net`

#### NOTE
This template was created mostly for developer NOT for end users. 
This is a point where you can begin your application, rather than creating it from scratch.
Good luck!
