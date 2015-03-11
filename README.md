# EnvironmentCrazy
##Crazy about environment validations?

EnvironmentCrazy is a practical class that simplifies working with your different application environments and environment variables.

# Usages (so far)

##Before:
###The (unfortunate) usual way.

[ ! ] No validations. Too much writing. Missing scopes. String-based validations leave space for catastrofic errors.

```php
// No validations. Missing scopes:
if( $_SERVER['ENVIRONMENT'] == 'productive' )
{
  $message = 'We are in PRODUCTIVE.';
}
// Missing scopes. Too much writing:
else if( isset($_SERVER['ENVIRONMENT']) && $_SERVER['ENVIRONMENT'] == 'private' )
{
  $message = 'We are in PRIVATE.';
}
// Even much more writing:
else if( isset($_SERVER['ENVIRONMENT']) && $_SERVER['ENVIRONMENT'] == 'staging' || isset($_ENV['ENVIRONMENT']) && $_ENV['ENVIRONMENT'] == 'staging' )
{
  $message = 'We are in STAGING.';
}
// A bit smarter. But oh oh, there's a tyop!
else getenv('ENVIRONMENT') == 'locaol' )
{
  $message = 'We are in LOCAL... not really, hopefully we are not doing something serious here.';
}
else
{
  $message = 'We are in the LIMBO!';
}

echo $message;
```


##After:
###For the environment crazies

- The 1-liner:

```php
$message = EnvironmentCrazy::setIf(['productive'=>'PRODUCTIVE', ..., 'local'=>'LOCAL', 'else'=>'the LIMBO!']);
```

- Readability crazy? (same as above):
```php
$message = EnvironmentCrazy::setIf([
                  'productive' => 'PRODUCTIVE', 
                  'private'    => 'PRIVATE', 
                  'staging'    => 'STAGING', 
                  'local'      => 'LOCAL', 
                  'else'       => 'the LIMBO!'
                ]);

echo 'We are in ' . $message;
```

- With classic structures:

```php
if( EnvironmentCrazy::isProductive() )
{
  $message = 'We are in PRODUCTIVE.';
}
else if( EnvironmentCrazy::isStaging() )
{
  $message = 'We are in STAGING.';
}
else
{
  $message = 'We are in the LIMBO!';
}
```

- Go specific:
```php
$message = 'We are in the LIMBOOO! D:';

$message = EnvironmentCrazy::setIfProductive("No. We're in PRODUCTIVE 8)");

// Supports default fallback value:
$subdomain = EnvironmentCrazy::setIfProductive('www.', 'local.');
```

- Get environment variables:
```php
$token = EnvironmentCrazy::get('API_TOKEN', 'optional-default-value-if-the-variable-is-not-set');
```

- When getting a variable, you can avoid automatic type casting (supports boolean, empty and null):
```php
EnvironmentCrazy::castTypes(false); // or true, by default.

// and avoid striping quote values '"'
EnvironmentCrazy::stripQuotes(false); // or true, by default.
```
