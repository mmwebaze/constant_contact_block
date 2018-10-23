# constant_contact_block

This module integrates Drupal 8 with Constant Contact. Currently the 
following is supported:
* extraction of email lists from Constant Contract
* registration of email to an email list through a block
* Auth2 support including saving the access token as part of the Constant Contact Configuration object.
* supports storing email lists locally

## Installation

* with drush: drush en constant_contact_block -y
* with drupal console: drupal module:install constant_contact_block
* with composer: composer require drupal/constant_contact_block

## Usage
Set the following configuration settings to enable Drupa 8 to Constant
Contact integration

1. Constant contact base url: https://api.constantcontact.com/v2/
2. Create a developer's account on Constant contact if you don't have one and get an <b>api key</b> and a <b>client secret</b>.
3. Set the redirect url on your developer's account to: site_baseurl/constant_contact_block/getCode (This api can also be found on the Constant Contact settings page)
4. Set Auth request url to: https://oauth2.constantcontact.com/oauth2/oauth/siteowner/authorize

Once the settings 1 - 4 have been made, access authorization link
site_baseurl/constant_contact_block/get_auth to give the app permission. An access token will be issued which will be saved as part of the module's configuration.

## Still to be done

* better error handling
* checkbox in config form for turning on/off sync
** sending out campaigns using nodes that have been published (campaign subject = node title and campaign message = body field)
* add support for extra fields (first name, last name, address e.t.c)