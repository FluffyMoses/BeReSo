# BeReSo
BEst REcipe SOftware<br><br>
###################################<br>
README.md<br>
###################################<br>

The purpose of this software is to organize and categorise photos of articles or recipes, etc.<br>
The addittional templates make it easy to rename the software and use it for something else, but the main focus will be on the photo recipe management.<br>
<br>
###################################<br>
Requirements<br>
###################################<br>
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

###################################<br>
Installation<br>
###################################<br>
<br>
<ul>
  <li>Copy everything except the sql folder in the webspace directory</li>
  <li>Edit config.php.example and rename it to config.php</li>
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
