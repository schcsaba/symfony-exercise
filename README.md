# devJobs

**devJobs** est un projet backend réalisé avec [Symfony](https://symfony.com/)
et [API Platform](https://api-platform.com/) pour que les entreprises puissent gérer leurs offres d'emploi et les rendre
disponibles via une
API web.

## Exigences techniques

- [PHP 7.4](https://www.php.net/downloads.php#v7.4.30)
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/)
- Un serveur de base de données [MySQL](https://www.mysql.com/), [MariaDB](https://mariadb.org/)
  , [PostgreSQL](https://www.postgresql.org/)
  , [SQLite](https://www.sqlite.org/index.html) ou [Oracle](https://www.oracle.com/index.html)

## Installation

1. Clonez le projet :

   `git clone https://gitlab.cefim-formation.org/CSchnitchen/ecf-back-end.git`

2. Entrez dans le répertoire du projet :

   `cd ecf-back-end`

3. Installez les packages PHP requis :

   `composer install`

4. Installez les packages JavaScript requis :

   `npm install`

5. Construisez les assets :

   `npm run build`

6. Configurez la base de données comme décrit dans
   la [documentation Symfony](https://symfony.com/doc/current/doctrine.html#configuring-the-database).

7. Créez la base de données :

   `symfony console doctrine:database:create`

8. Exécutez les migrations pour créer les tables de la base de données :

   `symfony console doctrine:migrations:migrate`

9. Chargez les données de test dans la base de données :

   `symfony console doctrine:fixtures:load`

10. Démarrer le serveur local :

    `symfony server:start`.

11. Ouvrez le site à l'URL affichée dans le terminal (généralement https://127.0.0.1:8000)
12. Cliquez sur le menu *Login*. Sur la page de connexion, vous pouvez vous connecter en tant qu'administrateur avec le
    nom d'utilisateur *admin* et le mot de passe *motdepasse*.
13. La documentation de l'API est disponible à l'URL suivante : https://127.0.0.1:8000/api

## Mode d'emploi

- En tant qu'administrateur, vous pouvez gérer le site dans le menu *Administration*.
- En tant que société, vous pouvez
    - créer un compte dans le menu *Inscription*,
    - modifier votre mot de passe dans le menu *Mon compte*,
    - gérer vos offres d'emploi dans le menu *Gérer vos offres d'emploi*. Pour cela, vous devez d'abord créer une
      société dans le menu *Mon entreprise*.
- En tant que candidat, vous pouvez postuler à une offre d'emploi sur la page de candidature de l'offre d'emploi. Le
  modèle de l'URL de la page de candidature est le suivant : https://127.0.0.1:8000/<locale>/offre/<slug>. Le *local*
  peut être *en* ou *fr*, le *slug* est le slug de l'offre d'emploi. Le slug est fourni dans l'API avec les autres
  données, afin que les pages de candidatures puissent être atteintes depuis un frontend.
- Le site est disponible en anglais et en français. La langue peut être définie dans le menu supérieur.

## Exécution des tests

1. Configurez la connexion à la base de données de test dans le fichier `.env.test`.

2. Créez la base de données de test :

   `symfony console doctrine:database:create --env=test`

3. Créez le schéma de la base de données de test :

   `symfony console doctrine:schema:create --env=test`

4. Exécutez les tests

   `symfony php bin/phpunit`

# devJobs

**devJobs** is a backend project created with [Symfony](https://symfony.com/)
and [API Platform](https://api-platform.com/) for companies to manage their job offers and make them available through a
web API.

## Technical requirements

- [PHP 7.4](https://www.php.net/downloads.php#v7.4.30)
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/)
- A [MySQL](https://www.mysql.com/), [MariaDB](https://mariadb.org/), [PostgreSQL](https://www.postgresql.org/)
  , [SQLite](https://www.sqlite.org/index.html) or [Oracle](https://www.oracle.com/index.html) database server

## Installation

1. Clone the project:

   `git clone https://gitlab.cefim-formation.org/CSchnitchen/ecf-back-end.git`

2. Enter into the directory of the project:

   `cd ecf-back-end`

3. Install the required PHP packages:

   `composer install`

4. Install the required JavaScript packages:

   `npm install`

5. Build the assets:

   `npm run build`

6. Configure the database as described in
   the [Symfony documentation](https://symfony.com/doc/current/doctrine.html#configuring-the-database).

7. Create the database:

   `symfony console doctrine:database:create`

8. Run the migrations to create the database tables:

   `symfony console doctrine:migrations:migrate`

9. Load test data into the database:

   `symfony console doctrine:fixtures:load`

10. Start the local server:

    `symfony server:start`

11. Open the site at the URL displayed in the terminal (usually https://127.0.0.1:8000)
12. Click on the *Login* menu. On the login page you can log in as administrator with the username *admin* and the
    password *motdepasse*.
13. The API documentation is available at the following URL: https://127.0.0.1:8000/api

## How to use

- As administrator, you can manage the site in the *Administration* menu.
- As company, you can
    - create an account in the Register menu,
    - change your password in the *My account* menu,
    - manage your job offers in the *Manage your job offers* menu. For that you should create a company in the *My
      Company* menu first.
- As a candidate, you can apply to a job offer on the application page of the job offer. The pattern of the application
  page URL is the following: https://127.0.0.1:8000/<locale>/offer/<slug>. The *locale* can be *en* or *fr*, the *slug*
  is the slug of the job offer. The slug is provided in the API together with the other data, so that the application
  pages can be reached from a frontend application.
- The site is available in English and French. The language can be set in the top menu.

## Running the tests

1. Configure the test database connection in the `.env.test` file.

2. Create the test database:

   `symfony console doctrine:database:create --env=test`

3. Create the test database schema:

   `symfony console doctrine:schema:create --env=test`

4. Run the tests

    `symfony php bin/phpunit`