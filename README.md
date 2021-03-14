"# laravel-wordpress" 
This application is inspired from the code of wordpress and https://github.com/corcel/corcel.
The most important things in this package is The User--> Roles, that well not found in corcel, the user roles is copied from the code source of wordpress and adapted to laravel.
to use this app add to your user the HasMeta trait:

```php
   /**
 * Class User
 * @package App\Models
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasApiTokens, HasMeta, HasRoles;
    
 }
 ```
 
 
 
 Then in your routes us the middleware <strong>has_cap</strong> with capabilites or roles created in your wordpress:
 
 ```php
$router->group([
        'middleware' => ['auth:api','has_cap:customer|shop_manager|New_Roles|edit_post']], function () use ($router) {}]);
 ```
