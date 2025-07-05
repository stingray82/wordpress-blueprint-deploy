🚀 WordPress Blueprint Deploy
============================

A self-deleting, auto-executing WordPress plugin that sets up a full theme and
plugin stack in seconds — perfect for quickly spinning up new projects with
pre-defined blueprints.

Designed to work with other projects of mine such as
[foundations-for-bricks-builder](https://github.com/stingray82/Foundations-for-Bricks)
and [tasks-after-install](https://github.com/stingray82/WP-Tasks-After-Install)

 

✨ What It Does
--------------

When you activate the plugin:

1.  ✅ Installs a theme from either the WordPress repository **or** a public ZIP
    URL.

2.  ✅ Installs and activates multiple plugins:

    -   From the **WordPress plugin repository**

    -   From **external ZIP URLs** (e.g. GitHub releases)

3.  ✅ Optionally switches to the new theme.

4.  ✅ Automatically deactivates **and deletes itself** once it's finished.

5.   

📂 Project Structure
-------------------

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ bash
blueprint/
├── blueprint.php   # Main plugin logic
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

⚙️ How to Use
------------

 

### 1. Edit the Config

At the top of `blueprint.php`, configure your desired setup:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ php
const IBP_THEME_REPO_SLUG = 'kadence'; // e.g., 'kadence' for repo theme
const IBP_THEME_ZIP_URL = ''; // Or a public ZIP URL for custom themes
const IBP_THEME_SLUG_FOR_ZIP = ''; // Theme slug (e.g. 'bricks') if using ZIP

const IBP_PLUGIN_REPO_SLUGS = [
    'sureforms'
];

const IBP_PLUGIN_ZIP_URLS = [
    'https://github.com/stingray82/WP-Tasks-After-Install/releases/latest/download/WP-Tasks-After-Install.zip',
];
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

-   Use **either** `IBP_THEME_REPO_SLUG` **or** `IBP_THEME_ZIP_URL` (not both).

-   If using a ZIP for the theme, set `IBP_THEME_SLUG_FOR_ZIP` so the theme can
    be activated. i.e for bricks builder it would be bricks

### 2. Install the Plugin

-   Zip the `/blueprint` folder

-   Upload it to your WordPress site via Plugins → Add New → Upload Plugin

-   Activate it

### 3. Let It Run

-   The plugin installs all configured themes and plugins.

-   It then **deactivates and deletes itself** — leaving behind a clean install.

-    

🧱 Example Use Cases
-------------------

-   🔨 Local dev environments (e.g., with Bricks + SureForms pre-installed)

-   🧪 QA/staging setups

-   📦 Theme or SaaS-like onboarding stacks

-    

🔐 Notes & Requirements
----------------------

-   Plugin deletion requires file system write permissions (`FS_METHOD` should
    allow direct access).

-   Tested on standard WordPress setups (5.8+).

-   ZIP URLs must be publicly accessible but can be secured for example using my
    downloader gateway or other such secure file links

 

📄 License
---------

**GPL-2.0 or later**

This plugin is free software: you can redistribute it and/or modify it under the
terms of the GNU General Public License as published by the Free Software
Foundation — either version 2 of the License, or (at your option) any later
version.

This plugin is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.

See the [LICENSE](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html) file
or the [GPL-2.0 license
text](https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt) for more details.

🙌 Credits
---------

Built by [@stingray82](https://github.com/stingray82)

Inspired by real-world needs for rapid WordPress blueprint deployment.
