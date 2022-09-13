# README

**This plugin is inspired by the [pdc-internal-products plugin](https://bitbucket.org/openwebconcept/plugin-pdc-internal-products)**

## Core functionality

The plugin adds private fields to openpub items and includes them with authenticated requests.

```json
// ...
"internal-data": [
    {
        "title": "This is internal data, only visible when authenticated",
        "content": "<p>With html, editable in gutenberg</p>\n"
    }
],
"links": [],
// ...
```

### **Notable difference!**

The plugin does not create endpoints for `/internal` products, and merely acts as a proxy for the base plugin. The plugin handles any incoming openpub request, and if the request is authenticated the plugin will include internal data.

### Setup

You will have to edit the **portal** codebase to include basic auth credentials when a user is logged in.
One of the methods is to create a new `singleton` looking like this:

```php
// OpenPubServiceProvider.php

$this->app->singleton('openpub.items.internal', function ($app) {
    $config = [
        'base_uri' => env('OPENPUB_ENDPOINT'),
    ];

    if (env('OPENPUB_APPLICATION_USERNAME') && env('OPENPUB_APPLICATION_PASSWORD')) {
        $config['auth'] = [
            env('OPENPUB_APPLICATION_USERNAME'),
            env('OPENPUB_APPLICATION_PASSWORD'),
        ];
    }

    return new Repository(new Client($config));
});
```

And make sure to use this when the user is authenticated, for example like this:

```php
// OpenPubController.php

public function show(Request $request, $title)
    {
        if (\is_user_logged_in()) {
            $repository = app()->make('openpub.items.internal');
        } else {
            $repository = app()->make('openpub.items');
        }

        // etc
```

## Auth

Similar to `pdc-internal-products` this plugin uses `application passwords` to validate authenticated users. You can create an application password in the admin dashboard, see [the wp docs](https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/)

Provide the credentials in the `.env` file of the portal using

```bash
OPENPUB_APPLICATION_USERNAME=
OPENPUB_APPLICATION_PASSWORD=
```

**Make sure your credentials are valid**, else the request will result in a `401` and the pub item will not be displayed.
