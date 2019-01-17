#Recuperer : sudo docker pull camilobar/ubuntu_apache_php:xsl

#Lancer : sudo docker run -d -p 100:80 -v $PWD/html:/var/www/html/ camilobar/ubuntu_apache_php:xsl

#Acceder : localhost:100

#Il peu y avoir un probleme de droits sur le fichier meteo.xml:

*$ sudo docker ps --> noter id du container
*$ sudo docker exec -it <id_container> /bin/bash
*$ cd /var/www/html
*$ chmod 777 meteo.xml
 *$ exit

# A défaut vous pouvez voir ce que ça donne sur : http://ciasiexml.000webhostapp.com/
