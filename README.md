# Constant Contact Block

CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Recommended modules
 * Installation
 * Configuration
 * Troubleshooting & Feature requests
 * Maintainers

INTRODUCTION
------------
 
 This module integrates Drupal 8 with Constant Contact an email marketing platform. Currently the 
 following is supported:
 * extraction of email lists from Constant Contract
 * registration of first name, last name, company name and email to an email list through a block
 * Auth2 support including saving the access token as part of the Constant Contact Configuration object.
 * supports storing email lists locally
 * Importation of email lists locally into Drupal

 * For a full description of the module, visit the project page:
   https://drupal.org/project/constant_contact_block

 * To submit bug reports and feature suggestions, or to track changes:
   https://drupal.org/project/issues/constant_contact_block

RECOMMENDED MODULES
-------------------

 * No extra module is required.

INSTALLATION
------------

 * Install as usual, see
   https://www.drupal.org/docs/8/extending-drupal-8/installing-contributed-modules-find-import-enable-configure-drupal-8 for further
   information.

CONFIGURATION
-------------

To achieve successful configuration and usage, a Constant Contact account will be necessary. 
Set the following configuration settings to enable Drupal 8 to Constant Contact integration. 
 
 1. Constant contact base url: https://api.constantcontact.com/v2/
 2. Create an account on Constant contact if you don't have one and get an <b>api key</b> and a <b>client secret</b> which
 will be added to the module's configuration object through (/admin/config/constant_contact_block/constantcontantconfig).
 3. Set the redirect url on your developer's account to: site_baseurl/constant_contact_block/getCode (This api can also be found on the Constant Contact settings page)
 4. Set Auth request url to: https://oauth2.constantcontact.com/oauth2/oauth/siteowner/authorize
 
 Once the settings 1 - 4 have been made, an access authorization link
 site_baseurl/constant_contact_block/get_auth to give the app permission. An access token will be issued which will be saved as part of the module's configuration.
 

TROUBLESHOOTING & FEATURE REQUESTS
----------------------------------

 * If you have issues setting up the module or feature requests, create an issue on the following github page (https://github.com/mmwebaze/constant_contact_block/issues).


MAINTAINERS
-----------

Current maintainers:

 * Michael Mwebaze (https://drupal.org/user/3201071)

