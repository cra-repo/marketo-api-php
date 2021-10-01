# marketo-api-php
Unofficial Marketo API PHP library.

# Installation

## Composer

```
composer require cra/marketo-api
```

## Manual download

Run `composer install` in the library directory and then require `PATH_TO_LIBRARY/vendor/autoload.php` in your project.

# Usage

First, you need to initialise and authenticate client.

```php
use Cra\MarketoApi\Client;

$client = (new Client($config))->authenticate();
```

`$config` must have the following fields `restBaseUrl`, `identityBaseUrl`, `clientId`, and `clientSecret`.
See Examples section below for more information on the fields.

Then `$client` will be passed to API endpoint classes. E.g.:

```php
use Cra\MarketoApi\Endpoint\Asset\Folder;

$folderEndpoint = new Folder($client);
$folder = $folderEndpoint->queryByName('My awesome folder');
$folders = $folderEndpoint->browse($folder->folderId());
```

## Examples

To run examples from `/examples` directory put the following `config.json` file in the repository root:

```json
{
  "restBaseUrl": "REST BASE URL copied from Marketo dashboard including /rest",
  "identityBaseUrl": "REST BASE URL copied from Marketo dashboard including /identity",
  "clientId": "CLIENT ID from Marketo dashboard",
  "clientSecret": "CLIENT SECRET from Marketo dashboard"
}
```
