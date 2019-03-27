### Dépendances
Version de php utilisée 7.2+
Installer composer https://getcomposer.org/download/

# Installer les dépendances php :
Pour Ubuntu :
sudo apt-get install php-xml
sudo apt-get install php7.2-mbstring

# Installer les dépendances vendors :
composer install

### Tests
Exécuter les tests sous Windows :
vendor\bin\phpunit.bat --bootstrap vendor/autoload.php --testdox tests

Exécuter les test sous Ubuntu
./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests/

### Lancement du programme
php index.php

### Propriétés attendues
Concernant les propriétés attendues dans recreations ou facilities, trois différentes techniques ont été utilisé :
- La recherche de l'occurence dans le texte, dans le cas où il n'y a pas de balise normalisée : 
Par exemple pour la présence d'un golf on trouve une multitude de balises différentes comme :
<li>Golf Course</li>
<li>Walter's Golf</li>
<li>The Naples Grande Golf Club</li>
...
On cherche la présence de l'occurence "golf" dans le texte (insensible à la casse).

- La recherche du mot exact à l'aide d'une expression régulière, pour le spa par exemple qui peut faire partie d'un autre mot (comme space):
On recherche donc le mot exact spa, ou spas (insensible à la casse). Cette technique est plus coûteuse en terme de temps c'est pour ça qu'elle n'est pas utilisée dans le cas ci-dessus.

- Lorsqu'il y a une balise normalisée telle que "Mobility accessible rooms", on parcours chaque balise <li> et on compare le texte contenu à la chaîne de caractère recherchée (insensible à la casse)

### Propriété introduction_media
La fonction php getimagesize permet de récupérer la taille de l'image afin de renseigner les champs width et height, mais le temps d'exécution du programme devient vraiment long.
J'ai donc désactivé cette fonctionnalité par défaut dans l'index.php : $GET_IMAGE_SIZE = false;
On peut la réactiver en passant la valeur de la constante à true.
Pour tester rapidement cette fonctionnalité, on peut utiliser le fichier tests/test.xml qui est une version réduite du fichier fournit par BONOTEL, en commentant la ligne 12 et en décommantant la ligne 13 de index.php.

### Choix technique
J'ai choisis de créer le tableau associatif php dans la classe XmlParser et de gérer ensuite la création des fichiers dans la classe FileCreator, afin de découpler les deux fonctionnalités et ainsi d'avoir un code plus lisible, maintenable et évolutif.
On aurait pu faire la création de fichier directement dans XmlParser et ainsi effectuer une seule boucle mais le gain de temps est négligeable.

### Evolutions
Une piste d'évolution pour ce programme est de récupérer le fichier à l'url indiqué à l'aide d'un Stream, et d'effectuer en parallèle dans un autre thread le parsing Xml et la création du fichier.
Ainsi au fur et à mesure que le programme lit le fichier sur internet, il créé les fichiers en même temps. On gagnerait probablement en rapidité d'exécution.
Si le programme est amené à évoluer il faudrait également ajouter la gestion d'éventuelles erreurs.
