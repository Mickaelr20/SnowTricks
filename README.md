[![LinkedIn][linkedin-shield]][linkedin-url]

<!-- PRESENTATION -->
<br />
<div align="center">
  <a href="https://github.com/Mickaelr20/SnowTricks">
    <img src="public/readme_images/logo.svg" alt="Logo" height="80">
  </a>

  <h3 align="center">SnowTricks - Annuaire de tricks de snowboard</h3>

  <p align="center">
    Site web réalisé en PHP avec le framework Symfony 6 par Rivière Mickaël
  </p>
</div>

<!-- TABLE OF CONTENTS -->
<details>
  <summary>Sommaire</summary>
  <ol>
    <li><a href="#a-propos">À propos</a></li>
    <li><a href="#installation">Installation</a></li>
    <li><a href="#demarrage">Démarrage</a></li>
    <li><a href="#license">Licence</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>

<!-- A PROPOS -->

## A propos

[![Header snowtricks][product-screenshot]](http://localhost:8000/)

Le site SnowTricks, un site communautaire qui présente une liste de tricks de snowboard

<!-- GETTING STARTED -->

## Installation

Clonez ce repo sur votre machine avec la commande suivante:
```sh
git clone https://github.com/Mickaelr20/SnowTricks.git
```

Une fois le projet récupéré, vous aurez besoins d'installer ses dépendences composer et npm

### Installer composer et npm :

#### Composer
Vous devez installer (ou avoir) composer sur votre machine.

Pour installer les dépendances composer, exécutez la commande suivante dans le répertoire du projet :
```sh
composer install
```

#### Npm
Vous devez installer (ou avoir) npm sur votre machine.

Pour installer les dépendances npm, exécutez la commande suivante dans le répertoire du projet :
```sh
npm install
```
ensuite, vous aurez besoins des assets du projet, pour les constuire faites:
```sh
npm run dev
# Ou pour un version optimisé pour la production:
npm run build
```

Par la suite, vous aurez besoins de relié l'application à une base de donnée:

### Fichier de configuration: .env.local

L'application nécéssite un accès a une base de données, ainsi qu'un service d'envoie d'email

vous devez créer un fichier .env.local à la racine du projet dans lequel figureront deux variables:
veillez à bien remplacer les <place_holder>
```sh
# L'url vers la base de données "DATABASE_URL"
DATABASE_URL="mysql://<db_user>:<db_password>@<bd_host>/<db_name>"
# L'url vers le serveur SMTP "MAILER_DSN": peut varier selon votre service et le mode de transfert
# Pour plus d'informations: (https://symfony.com/doc/current/mailer.html) ou le site de votre fournisseur
MAILER_DSN="gmail+smtp://<username>:<password>@default"
```

## Démarrage :

Une fois toutes les étapes précédentes effectuées, vous pouvez executer les commandes suivantes depuis le repertoire d'installation du projet:

```sh
# Créer la base de données:
bin/console doctrine:database:create

# Créer les tables:
bin/console doctrine:migration:migrate

# Créer le jeu de données initiale:
bin/console doctrine:fixtures:load
```
## Dependences

Javascript:
<ul>
    <li>Bootstrap 5 (https://getbootstrap.com/docs/5.0/getting-started/introduction/)</li>
    <li>Bootstrap icons 5 (https://icons.getbootstrap.com/)</li>
    <li>Jquery (https://jquery.com/)</li>
    <li>Owl carousel (https://owlcarousel2.github.io/OwlCarousel2/)</li>
</ul>

<!-- LICENSE -->

## License

Distribué sous GNU GENERAL PUBLIC LICENSE V2. Voir https://github.com/Mickaelr20/SnowTricks/blob/main/license pour plus d'informations.

<!-- CONTACT -->

## Contact

Rivière Mickael - mickaelr20@gmail.com - [Mon LinkedIn][linkedin-url]

Lien du projet : [https://github.com/Mickaelr20/SnowTricks](https://github.com/Mickaelr20/SnowTricks/)

<!-- MARKDOWN LINKS & IMAGES -->
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?logo=linkedin&colorB=555
[linkedin-url]: https://www.linkedin.com/in/mickael-riviere-s/
[product-screenshot]: public/readme_images/snowtricks.png
