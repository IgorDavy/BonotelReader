### D�pendances
Version de php utilis�e 7.2+
Installer composer https://getcomposer.org/download/

# Installer les d�pendances php :
Pour Ubuntu :
sudo apt-get install php-xml
sudo apt-get install php7.2-mbstring

# Installer les d�pendances vendors :
composer install

### Tests
Ex�cuter les tests sous Windows :
vendor\bin\phpunit.bat --bootstrap vendor/autoload.php --testdox tests

Ex�cuter les test sous Ubuntu
./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests/

### Lancement du programme
php index.php

### Propri�t�s attendues
Concernant les propri�t�s attendues dans recreations ou facilities, trois diff�rentes techniques ont �t� utilis� :
- La recherche de l'occurence dans le texte, dans le cas o� il n'y a pas de balise normalis�e : 
Par exemple pour la pr�sence d'un golf on trouve une multitude de balises diff�rentes comme :
<li>Golf Course</li>
<li>Walter's Golf</li>
<li>The Naples Grande Golf Club</li>
...
On cherche la pr�sence de l'occurence "golf" dans le texte (insensible � la casse).

- La recherche du mot exact � l'aide d'une expression r�guli�re, pour le spa par exemple qui peut faire partie d'un autre mot (comme space):
On recherche donc le mot exact spa, ou spas (insensible � la casse). Cette technique est plus co�teuse en terme de temps c'est pour �a qu'elle n'est pas utilis�e dans le cas ci-dessus.

- Lorsqu'il y a une balise normalis�e telle que "Mobility accessible rooms", on parcours chaque balise <li> et on compare le texte contenu � la cha�ne de caract�re recherch�e (insensible � la casse)

### Propri�t� introduction_media
La fonction php getimagesize permet de r�cup�rer la taille de l'image afin de renseigner les champs width et height, mais le temps d'ex�cution du programme devient vraiment long.
J'ai donc d�sactiv� cette fonctionnalit� par d�faut dans l'index.php : $GET_IMAGE_SIZE = false;
On peut la r�activer en passant la valeur de la constante � true.
Pour tester rapidement cette fonctionnalit�, on peut utiliser le fichier tests/test.xml qui est une version r�duite du fichier fournit par BONOTEL, en commentant la ligne 12 et en d�commantant la ligne 13 de index.php.

### Choix technique
J'ai choisis de cr�er le tableau associatif php dans la classe XmlParser et de g�rer ensuite la cr�ation des fichiers dans la classe FileCreator, afin de d�coupler les deux fonctionnalit�s et ainsi d'avoir un code plus lisible, maintenable et �volutif.
On aurait pu faire la cr�ation de fichier directement dans XmlParser et ainsi effectuer une seule boucle mais le gain de temps est n�gligeable.

### Evolutions
Une piste d'�volution pour ce programme est de r�cup�rer le fichier � l'url indiqu� � l'aide d'un Stream, et d'effectuer en parall�le dans un autre thread le parsing Xml et la cr�ation du fichier.
Ainsi au fur et � mesure que le programme lit le fichier sur internet, il cr�� les fichiers en m�me temps. On gagnerait probablement en rapidit� d'ex�cution.
Si le programme est amen� � �voluer il faudrait �galement ajouter la gestion d'�ventuelles erreurs.
