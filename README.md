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
- Webserver with PHP installed (tested with PHP 7.4 and 8.0)<br>
- The following PHP extensions (enabled in php.ini): <br>
  extension=gd // image conversion<br>
  extension=mysqli // mysql and mariadb connections<br>
  extension=mbstring // mb_substr => multibyte safe substring (for example ü,ö,ä are two chars in regular substr or $string[ID] char<br>
  <br>
- MySQL or MariaDB Database<br>
<br>
###################################<br>
Installation<br>
###################################<br>
<br>
- Copy everything except the sql folder in the webspace directory<br>
- Edit config.php.example and rename it to config.php<br>
- Execute the sql scripts to install the base db structure and the base templates:<br>
  sql/create_database.sql<br>
  sql/template_de_0_base.sql // german template<br>
  <br>
- Create your own template by editing and executing:
  sql/new_template.sql<br>
  <br>
- Use the existing templates by executing these sql query files:<br>
  sql/template_de_1_rezeptverwaltung.sql // german template - recipe management<br>
  sql/template_de_2_kreativ.sql // german template - creative projects<br>
  sql/template_de_3_projektverwaltung.sql // german template - general projects<br>
  <br>
- Create the folder "images" (or rename it and change the name in the config.php)
- Enable write access for the webserver to this folder
