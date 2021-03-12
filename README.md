# BeReSo
BEst REcipe SOftware

### README.md
The purpose of this software is to organize and categorise photos of articles or recipes, etc.<br>
The addittional templates make it easy to rename the software and use it for something else, but the main focus will be on the photo recipe management.<br>

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
 
  <li>MySQL or MariaDB Database</li>
</ul>

### Installation
<ul>
  <li>Copy everything except the sql folder in the webspace directory</li>
  <li>Edit config.example.php and rename it to config.php</li>
  <li>Execute the sql scripts to install the base db structure and the base templates:
    <ul>
      <li>sql/create_database.sql</li>
      <li>sql/template_de_0_base.sql // german template</li>
    </ul>
  </li>
  <li>Create your own template by editing and executing:
    <ul>
      <li>sql/new_template.sql</li>
    </ul>
  </li>  
  <li>Use the existing templates by executing these sql query files:
    <ul>
      <li>sql/template_de_1_rezeptverwaltung.sql // german template - recipe management</li>
      <li>sql/template_de_2_kreativ.sql // german template - creative projects</li>
      <li>sql/template_de_3_projektverwaltung.sql // german template - general projects</li>
    </ul>
  </li>
  <li>Create the folder "images" (or rename it and change the name in the config.php)</li>
  <li>Enable write access for the webserver to this folder</li>
 </ul>
 
### New User
Open this url and change the following settings:<br>
<br>
<a href="index.php?module=login&action=generate_user_sqlinsert&generate_user=USERNAME&generate_password=PASSWORD&generate_template=TEMPLATEID" target="_BLANK">index.php?module=login&action=generate_user_sqlinsert&generate_user=USERNAME&generate_password=PASSWORD&generate_template=TEMPLATEID</a><br>
<br>
<ul>
    <li>USERNAME = username of the new user, Letters a-z, A-Z and - allowed</li>
    <li>PASSWORD = the password of the new user that will be hashed</li>
    <li>TEMPLATEID = the template id of the template the user should use, see the "Installation" step above for the id</li>
</ul>
Run the SQL command you get as an result and the user is created and ready to log in!<br>
For example, the SQL INSERT for the user USERNAME with the hashed password PASSWORD and the template 1:<br>

```sql
INSERT INTO bereso_user (user_name,user_pwhash,user_template) VALUES ('USERNAME','$2y$10$R46bmwUUxbnmiBE0S3JR4uKuQJA3sbP8aMz7Dgzovyp.f1g91nZuO','1');
```

Make a folder with the name of the new created user id under the path $bereso['images'].<br>
For example: images/3/ for the new user with the user id 3
