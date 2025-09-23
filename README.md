# Auth Readme

## Install 

### Publish config file
```bash
php artisan vendor:publish --tag=componist-auth-config
```

### Add to User Model

```php
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Componist\Auth\Traits\AddComponistAuthentication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use AddComponistAuthentication;

    //

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];
}
```


### Auth Middlewares alias
```
verify => MustVerifyEmail

twofactor => MustTwoFactor

```

### Publish packages views
```bash
php artisan vendor:publish --tag=componist.auth.publish.views
```


### in your env
```env
AUTH_LOGIN_ROUTE=componist.auth.login
```