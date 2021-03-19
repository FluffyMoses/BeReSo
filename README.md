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
      <li>extension=mbstring // mb_substr => multibyte safe substring (for example ü,ö,ä are two chars in regular substr or $string[ID] char<br></li>
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
 </ul>
 
### Add another user
Open the following url and change the settings as listed below:<br>
<br>
<a href="index.php?module=login&action=generate_user_sqlinsert&generate_user=USERNAME&generate_password=PASSWORD&generate_template=TEMPLATEID" target="_BLANK">index.php?module=login&action=generate_user_sqlinsert&generate_user=USERNAME&generate_password=PASSWORD&generate_template=TEMPLATEID</a><br>
<br>
<ul>
    <li>USERNAME = username of the new user, letters a-z, A-Z and - allowed</li>
    <li>PASSWORD = the password of the new user that will be hashed</li>
    <li>TEMPLATEID = the template id of the template the user should use
      <ul>
        <li>1 = German - DE Rezeptmanagement</li>
        <li>2 = German - DE Kreativ</li>
        <li>3 = German - DE Projektverwaltung</li>
        <li>4 = English - EN Recipe management</li>
      </ul>
    </li>
</ul>
Run the SQL command you get as a result and the user is created and ready to log in!<br>
As an example, the sql INSERT for the user USERNAME with the hashed password PASSWORD and the template id 4 (recipe management english):<br>

```sql
INSERT INTO bereso_user (user_name,user_pwhash,user_template) VALUES ('USERNAME','$2y$10$R46bmwUUxbnmiBE0S3JR4uKuQJA3sbP8aMz7Dgzovyp.f1g91nZuO','4');
```

Create a folder with the name of the new created user id under the path $bereso['images'].<br>
For example: images/3/ for the new user with the user id 3

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

