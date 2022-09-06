# README

**This plugin is derived from the [pdc-internal-products plugin](https://bitbucket.org/openwebconcept/plugin-pdc-internal-products)**

## Core functionality

The plugin adds private fields to openpub items and includes them with authenticated requests.

### Notable difference

The most important difference is that the plugin does not create endpoints for `/internal` products, and merely acts as a proxy for the base plugin. The plugin handles any incoming openpub request, and if the request is authenticated the plugin will include internal data.

## Auth

In comparison to the PDC plugin this plugin _actually_ uses HTTP basic auth with a provided application password instead of using `\is_user_logged_in()` which caused issues.
