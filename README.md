# Getting started with Yii2-material-dashboard

## 1. Install via composer

Yii2-material-dashboard can be installed using composer. Run following command to install:

```bash
php composer.phar require vasadibt/yii2-user
```

## 2. Configure backend

> **NOTE:** Make sure that you don't have `auth` component configuration in your config files.

Add following lines to your `frontend` os `backend` configuration file:

```php
return [
    // ...
    'bootstrap' => [
        // ...
        'auth',
    ],
    // ...
    'modules' => [
        // ...
        'auth' => [
            'class' => 'vasadibt\materialdashboard\Module',
        ],
    ],
    // ...
    
    'as access' => [
        'class' => \yii\filters\AccessControl::class,
        'rules' => [
            // ...    
            [
                'controllers' => [
                    'auth/*',
                ],
                'allow' => true,
            ],
            // ...
        ],
    ],
];
```

## 3. User model

Change user model IdentityInterface to ExtendedIdentityInterface

```php
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    // ....
}
```

change to

```php
class User extends \yii\db\ActiveRecord implements \vasadibt\materialdashboard\models\ExtendedIdentityInterface
{
    // todo: add missing functions
}
```


