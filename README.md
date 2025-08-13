# Auth Readme

## Install 

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
}
```