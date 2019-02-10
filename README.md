# yii2-floating-menu
Floating menu positioned at the left side of screen with expand/collapse ability depending what is needed in specific case. 

# Instalation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist srdjan92/yii2-floating-menu "^1.0.0"
```

or add

```
"srdjan92/yii2-floating-menu": "^1.0.0"
```

to the require section of your `composer.json` file.

# Usage
```php
<?php

// basic example of floating widget

echo \srdjan\floatingmenu\FloatingMenu::widget([
    'toggleOptions' => [
        'collapseOptions' => ['class' => 'fal fa-arrow-alt-left'],
        'expandOptions' => ['class' => 'fal fa-arrow-alt-right']
    ],
    'items' => [
        [
            'url' => ['site/index'],
            'label' => "<i class='fal fa-envelope'></i> <span>Messages</span>"
        ],
        [
            'url' => ['/notification/index'],
            'label' => "<i class='fal fa-bell'></i> <span>Notifications</span>"
        ],
        [
            'url' => ['/account/profile'],
            'label' => "<i class='fal fa-user'></i> <span>Profile</span>"
        ],
        [
            'url' => ['/settings/disapprove'],
            'label' => "<i class='fal fa-users'></i> <span>Fans</span>"
        ],
        [
            'url' => ['/settings/approve'],
            'label' => "<i class='fal fa-user'></i> <span>Club</span>"
        ],
        [
            'url' => ['/settings/index'],
            'label' => "<i class='fal fa-cog'></i> <span>Settings</span>"
        ],
    ]
]); ?>
```

In example above are used fontawesome icons, feel free to use any icon library that fit your needs.
Floating widget library does not have dependency on any icon library.
