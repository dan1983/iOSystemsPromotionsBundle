# iOSystemsPromotionsBundle [![Dependencies Status](https://depending.in/iosystems/iOSystemsPromotionsBundle.png)](http://depending.in/iosystems/iOSystemsPromotionsBundle)
Symfony 2 bundle for managing sales promotions (time/roles/expression language based).

## Installation
Add `iosystems/promotions-bundle` in your `composer.json`:

```js
{
    "require": {
        "iosystems/promotions-bundle": "dev-master"
    }
}
```

Enable the bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new iOSystems\ShippingBundle\iOSystemsPromotionsBundle(),
    );
}
```

Finally update your vendors using `composer update`.

## Configuration
No configuration needed.

## How it works
![Class view](https://raw.githubusercontent.com/iosystems/iOSystemsPromotionsBundle/master/Resources/doc/diagram.png)

**Loaders**: load promotions. **Promotions** implements either `FixedAmountPromotionInterface` or `PercentagePromotionInterface`, and one or more marker interface (`RoleBasedPromotionInterface`, `TimeBasedPromotionInterface`,  `ExpressionBasedPromotionInterface `).

**Voters** vote for promotions they can handle (a single vote can be `true`, `false` and `null`). **Election decision maker** approves or reject the promotions based on votes and mode (all positive, any positive).

**Promotion pickers:** choose one promotion among applicable promotions they support. *All supported promotions can be handled only by a single picker (and one promotion can be handled only by a single picker)*.

## Usage
TODO.
