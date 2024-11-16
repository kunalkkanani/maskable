### Install the package via Composer:

```bash
composer require kunalkanani/maskable
php artisan vendor:publish --tag="config"
```

### Usage
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use KunalKanani\Maskable\Maskable;
use KunalKanani\Maskable\Rules\EmailMaskRule;
use App\MaskRules\CustomMaskRule;

class ExampleModel extends Model
{
    use Maskable;

    protected $maskable = [
        'email' => EmailMaskRule::class,
        'first_name' => CustomMaskRule:class
    ];
}
```

```bash
php artisan tinker
```
```php
$exampleModel = ExampleModel::findOrFail(1);
$exampleModel->email;
'K****@test.com'
$exampleModel->first_name;
'Ku***'

$exampleModel->unmasked();
$exampleModel->email;
'Kunal@test.com'
$exampleModel->first_name;
'Kunal'
```
#### Create custom masking rules
```php
<?php

namespace App\MaskRules;

use Illuminate\Support\Str;
use KunalKanani\Maskable\Rules\MaskRuleInterface;

class CustomMaskRule implements MaskRuleInterface
{
    public function apply(string $value): string
    {
        return Str::mask($value, '*', 2);
    }
}

```
