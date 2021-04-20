# BeReSo

The purpose of this software is to organize photos of articles, recipes, or others.<br>
The additional templates make it easy to rename the software and use it for something else, but the main focus will be on the photo recipe management.<br>
BeReSo is still under developement, so a few nice-to-have features are still missing, but are planned for the future.<br>

### Features
<ul>
    <li>Manage recipe screenshots, scans, photos, etc. via mobilephone or browser.</li>
    <li>Adding hashtags to an recipe description automaticalle creates a category folder and adds the recipe to it.</li>    
    <li>Multiple hashtag categories can be moved inside a tag group that acts like a folder for tags.</li>
    <li>Webinterface admincenter to change configurations and manage users.</li>
    <li>OCR support via additional BeReSo OCR Agent, based on Tesseract. <a href="https://github.com/FluffyMoses/BeReSo-Agent-OCR-Docker" target="_BLANK">Available as Docker image.</a></li>
    <li>Multi users with different language and style templates supported.</li>
    <li>Webinterface for users to change some settings like keep display always on while using BeReSo, recipes per page, etc.</li>
    <li>Share recipes via link.</li>
    <li>Create checkboxes that automatically save their statis inside the recipe description. (to check if ingredient is added, bought etc.)</li>
    <li>Import shared recipes into your own BeReSo account.</li>
    <li>Generate printable view of each recipe.</li>
    <li>Mark recipes as favorite.</li>
    <li>Can be installed as WebApp.</li>
    <li>etc.</li>
</ul>


### Requirements
<ul>
  <li>Webserver with PHP installed (tested with PHP 7.4 and 8.0)</li>
  <li>The following PHP extensions (enabled in php.ini):
    <ul>
      <li>extension=gd // image conversion</li>
      <li>extension=mysqli // mysql and mariadb connections</li>
      <li>extension=mbstring // mb_substr => multibyte safe substring and needed by exif<br></li>
      <li>extension=exif // needed for image exif informations
    </ul>
  </li>
  <li>Set the upload parameters high engough in the php.ini. For example 60 Mb:
    <ul>
      <li>post_max_size = 60M</li>
      <li>upload_max_filesize = 60M</li>
    </ul>
  </li>
  <li>MySQL or MariaDB Database</li>
  <li>A ssl certificate for secure HTTPS connections is recomended in general and also needed by the service worker. The installation as an app, by Chrome or on Android, only works when a secure connection is established. The IOS "add to homescreen" webapp installation works with or without certificate.</li>
 </ul>

### Installation
<ul>
  <li>Get the latest release: <a href="https://github.com/FluffyMoses/BeReSo/releases">https://github.com/FluffyMoses/BeReSo/releases</a></li>
  <li>Copy everything in the webspace directory</li>
  <li>Edit config.example.php and rename it to config.php</li>
  <li>Create the folder "images" (or rename it and change the name in the config.php)</li>
  <li>Enable write access for the webserver to this folder</li>
  <li>Open install.php in your webbrowser and follow the instructions:
    <ul>
        <li>Check php extensions</li>
        <li>Create tables in database and insert templates</li>
        <li>Create first user account</li>
        <li>Delete install.php and the sql folder after the script ran successfully</li>
    </ul>
  </li>    
  <li>Log into BeReSo with your new created account!</li>
  <li>Open the Admincenter and change the configuration to your needs</li>
 </ul>
 

### Clients

<ul>
  <li>Webbrowser: BeReSo should work with all modern webbrowsers, just open the url with index.php.</li>
  
  <li>IOS webapp installation:
    <ul>
      <li>Open Safari on IOS (iPhone or iPad), open the share menu and add to homescreen. An icon is created on your homescreen and the app launches in fullscreen mode.</li>          
    </ul>
  </li>
</ul>

<img src="https://gnadl.com/images/bereso/on_homescreen.jpg" height="447" width="250"> <img src="https://gnadl.com/images/bereso/webapp.png" height="447" width="250"> 

<ul>
  <li>Chrome webapp installation:
    <ul>
      <li>Open Chrome, open the settings "..." menu and "Install BeReSo...".</li>        
    </ul>
  </li>
</ul>

<img src="https://gnadl.com/images/bereso/install_chrome.png" height="351" width="200"> <img src="https://gnadl.com/images/bereso/recipes_list.png" height="420" width="400">
