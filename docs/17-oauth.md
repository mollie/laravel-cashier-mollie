# OAuth

You have the possibility to use OAuth (optional).

This could be useful when you want to give customers the option to process payments within your application.

## Setting up the model

Your billable model needs to implement the `Laravel\Cashier\Contracts\ProvidesOauthInformation` interface.

```php
namespace App\Models;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;

class User extends Model implements ProvidesOauthInformation;
{
    ... your code

    /**
     * Get the OAuth token.
     */
    public function getOauthToken(): string
    {
        return $user->mollie_access_token;
    }

    /**
     * Get the Mollie Profile ID.
     */
    public function getMollieProfile(): string
    {
        return $user->mollie_profile_id;
    }

    /**
     * Get whether the Mollie API is in test mode.
     */
    public function isMollieTestmode(): bool
    {
        return ! app()->environment('production');
    }
}
```

After implementing the interface on your billable models, let the magic happen :sparkles:
