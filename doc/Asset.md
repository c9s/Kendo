# Asset

Phifty web framework uses AssetKit to install assets via `assetport` file.

## Install AssetKit

To use assetkit, you must install assetkit from pear or git repo:

	git clone git@git.corneltek.com:php/AssetKit.git
	cd AssetKit
	pear install -f package.xml

## Phifty Assets

In the phifty root directory, you should see the `.assetkit` file, which is an asset manifest file.

The asset manifest file contains asset names, filters, compressor, manifest informations.

To use asset from web front-end, the *AssetWriter* load assets from the `.assetkit` file, and use their specific filters and compressors to process files of assets.

Application, Plugins can have its assets, you can simply put your asset in the `assets` directory of the directory of microapp.

### Asset Manifest

For exmplae, to put jquery asset in plugin A:

	mkdir -p plugins/A/assets
	mkdir -p plugins/A/assets/jquery
  vim plugins/A/assets/jquery/manifest.yml

Edit the manifest yml file, the yml content is like below:

---
assets:
  - stylesheet: 1
		files:
		  - jquery/css/jquery.css
  - javascript: 1
    files:
      - jquery/dist/jquery.js

And your jquery.js file should be located at:

	plugins/A/assets/jquery/jquery/dist/jquery.js

### Register plugin asset

To register your jquery asset (which belongs to a plugin), you can run `asset init` command to register the asset.

	bin/phifty asset init

the `asset init` command, loads applications, plugins and tries to find assets from their `assets` directory.

NOTE: you can also register your asset via `assetkit` command.

	assetkit add plugins/A/assets/jquery

### Install plugin asset

Once this done, you can simply run `asset install` command to install asset files into `webroot/assets`.

there are two modes for installation, **symlink** and **copy**

**symlink** is for asset development, it links asset files from `webroot/assets`, so you don't need to copy files by yourself.

**copy** mode is for production mode, it copies asset files into `webroot/assets`.

To install assets with **symlink** mode:

	bin/phifty asset install --link

To install assets with **copy** mode:

	bin/phifty asset install










