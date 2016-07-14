# Canvas LMS Provider for OAuth 2.0 Client

[![Latest Version](https://img.shields.io/packagist/v/smtech/oauth2-canvaslms.svg)](https://packagist.org/packages/smtech/oauth2-canvaslms)

This package provides Canvas LMS OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Install

In your `composer.json`, include:

```JSON
"require": {
    "smtech/oauth2-canvaslms": "~1.0"
}
```

## Use

Same as the League's OAuth client, using `\smtech\OAuth2\Client\Provider\CanvasLMS` as the provider. Note that you can (and really should) include a `purpose` option parameter and you will need to include your `canvasInstanceUrl`.

Per the [Canvas OAUth docs](https://canvas.instructure.com/doc/api/file.oauth.html#oauth2-flow-0):

> For Canvas Cloud (hosted by Instructure), you can request a client ID and secret from http://instructure.github.io/ in the Dev Key Signup section.
>
> For open source Canvas users, you can generate a client ID and secret in the Site Admin account of your Canvas install. There will be a "Developer Keys" tab on the left navigation sidebar.

A small example:

```PHP
use smtech\OAuth2\Client\Provider\CanvasLMS;

session_start();

/* anti-fat-finger constant definitions */
define('CODE', 'code');
define('STATE', 'state');
define('STATE_LOCAL', 'oauth2-state');

$provider = new CanvasLMS([
    'clientId' => '160000000000127',
    'clientSecret' => 'z4RUroeMI0uuRAA8h7dZy6i4QS4GkBqrWUxr9jUdgcZobpVMCEBmOGMNa2D3Ab4A',
    'purpose' => 'My App Name',
    'redirectUri' => 'https://' . $_SERVER['SERVER_NAME'] . '/' . $_SERVER['SCRIPT_NAME'],
    'canvasInstanceUrl' => 'https://canvas.instructure.com'
]);

/* if we don't already have an authorization code, let's get one! */
if (!isset($_GET[CODE])) {
    $authorizationUrl = $provider->getAuthorizationUrl();
    $_SESSION[STATE_LOCAL] = $provider->getState();
    header("Location: $authorizationUrl");
    exit;

/* check that the passed state matches the stored state to mitigate cross-site request forgery attacks */
} elseif (empty($_GET[STATE]) || $_GET[STATE] !== $_SESSION[STATE_LOCAL]) {
    unset($_SESSION[STATE_LOCAL]);
    exit('Invalid state');

} else {
    /* try to get an access token (using our existing code) */
    $token = $provider->getAccessToken('authorization_code', [CODE => $_GET[CODE]]);

    /* do something with that token... (probably not just print to screen, but whatevs...) */
    echo $token->getToken();
    exit;
}
```
