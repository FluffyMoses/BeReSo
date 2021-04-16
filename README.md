# BeReSo
BEst REcipe SOftware

### README.md
The purpose of this software is to organize photos of articles, recipes, or others.<br>
The additional templates make it easy to rename the software and use it for something else, but the main focus will be on the photo recipe management.<br>
BeReSo is still under developement, so a few nice-to-have features, like a webinterface for usermanagement, are still missing, but are planned for the future.<br>

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

![bereso_ios_addtohomescreen](https://user-images.githubusercontent.com/79584516/111751058-4fc85b00-8894-11eb-8879-3b9fd3b40faf.png) ![bereso_ios_homescreen](https://user-images.githubusercontent.com/79584516/111750869-109a0a00-8894-11eb-94ba-f86784980e02.jpg) ![bereso_ios_webapp](https://user-images.githubusercontent.com/79584516/111751163-6ff81a00-8894-11eb-9aef-e40ebc2f7ee5.jpg)

<ul>
  <li>Chrome webapp installation:
    <ul>
      <li>Open Chrome, open the settings "..." menu and "Install BeReSo...".</li>        
    </ul>
  </li>
</ul>


![bereso_chrome_install](https://user-images.githubusercontent.com/79584516/111752240-bbf78e80-8895-11eb-9c1f-95854acb0001.PNG)
![bereso_chrome_webapp](https://user-images.githubusercontent.com/79584516/111752421-f95c1c00-8895-11eb-9e74-6f878d4a171f.PNG)

