# tawk.to Live Chat

Free live chat widget for your site

## Description

The tawk.to Live Chat app makes it easy to monitor and chat with visitors on your website. Be there when they need you with unlimited messaging, ticketing and your own Knowledge Base — all 100% FREE.

Compatible with all modern browsers, tawk.to was created in response to the growing need for businesses to respond in real time, with real people.

Never lose another lead or sale again — tawk.to offers iOS, Android, Windows and Mac OSX apps to keep you connected wherever you go.

Don’t have a tawk.to account yet? [Create one here.](https://tawk.to/?utm_source=zencart&utm_medium=link&utm_campaign=signup)

## Installation

### Pre-requisites
- Be sure Composer is installed. You can install it by entering in your website root directory and executing this command: `curl -sS https://getcomposer.org/installer | php`
- If you already have Composer installed globally, you may replace `php composer.phar` with `composer`.
- If you have previously installed this extension from the archived [repository](https://github.com/tawk/tawk-magento-2), start by [removing the old extension](#removing-the-old-extension).

### Standard Installation (Recommended)
Install the extension by executing `php composer.phar require tawk/tawk-magento-2`

### Manual Installation
1. Download the latest zip file [here](https://github.com/tawk/tawk-magento-2-package/releases).
2. Extract the package.
3. Copy the contents to `<magento-installation-root-folder>/app/code/Tawk/Widget` folder of your website (create a new folder if necessary).
4. Install the dependency `tawk/url-utils` by executing `php composer.phar require tawk/url-utils`

### Removing the old extension
#### Standard/Artifact Installation
1. Remove the extension by executing `php composer.phar remove tawk/widget`
2. Remove repositories from your Composer by executing the following commands:
	- `php composer.phar config --unset repositories.tawk-url-utils`
	- `php composer.phar config --unset repositories.tawk`
3. Clear Composer cache by executing `php composer.phar clearcache`

#### Manual Installation
Remove the repository link for `tawk/url-utils`, execute the following:
- `php composer.phar config --unset repositories.tawk-url-utils`
- `php composer.phar clearcache`

## Enabling the Extension
Once the extension is installed, you will need to execute the following command lines from your website root directory:
```
php bin/magento module:enable Tawk_Widget
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
php bin/magento cache:clean
```

## Widget Configuration
1. Go to `Dashboard` -> `System` -> `tawk.to widget` -> `Select your widget`.
2. Log in to your tawk.to account.
3. Select the property and the widget you want to place on your store and click `Use selected widget`.
4. The widget will now appear on your store.

## Frequently Asked Questions
Visit our [Help Center](https://help.tawk.to/) for answers to FAQs
