
# Motus

Ce projet a été réalisé dans le cadre de mon admission à l'école La Plateforme. Il s'agit d'une version personnalisée du jeu Motus, développée en utilisant les langages HTML, CSS, PHP et JavaScript. L'objectif du jeu est de deviner un mot choisi aléatoirement parmi une liste dans le nombre de tentatives imparties.



## Fonctionnalités

- Affichage du jeu : Une page contenant l'interface du jeu où le mot secret est sélectionné aléatoirement depuis une base de données.
- Sélection des lettres : Les joueurs peuvent saisir leurs propositions en utilisant le clavier, sans avoir à recharger la page.
- Gestion des erreurs et de la victoire : Les propositions des joueurs sont vérifiées et affichées dans une grille avec un visuel pour aider le joueur. Les lettres sont marquées comme étant bien placées, mal placées ou absentes dans le mot secret.
- Page d'inscription et de connexion : Une interface permettant aux utilisateurs de créer un compte et de se connecter pour accéder au jeu. Les données des joueurs sont stockées de manière sécurisée. Les formulaires sont vérifiés sans rechargement de la page.
- Wall of Fame : Une fonctionnalité qui affiche les meilleurs scores des joueurs pour motiver les participants.
## Configuration et installation

1. Assurez-vous d'avoir installé PHP et MySQL sur votre ordinateur.
2. Clonez ce dépôt Git sur votre machine locale.
3. Ouvrez le fichier `config.php` et modifiez les valeurs des variables pour correspondre à votre environnement MySQL.

   ```php
   $host = 'localhost';
   $username = 'votre_nom_utilisateur_mysql';
   $password = 'votre_mot_de_passe_mysql';
   $database = 'motus';

4. Importez la base de données fournie ('motus.sql) dans votre serveur MySQL.
5. Démarrez votre serveur
6. Accédez au jeu en ouvrant le fichier 'index.php' dans votre naviguateur.
## Motus en quelques images:

![App Screenshot](https://github.com/souleimane-ifqirne/Motus/assets/75176152/8e59dfb9-075d-4d01-a941-11173159bd34)

![App Screenshot](https://github.com/souleimane-ifqirne/Motus/assets/75176152/dc2a33e3-95a3-400d-be70-305ac2e621b1)

![App Screenshot](https://github.com/souleimane-ifqirne/Motus/assets/75176152/8c25179f-bfb1-4bbb-a72a-aa67f45dcaab)

![App Screenshot](https://github.com/souleimane-ifqirne/Motus/assets/75176152/39438c4e-516b-4076-8eb9-17d8a3ce36e3)

![App Screenshot](https://github.com/souleimane-ifqirne/Motus/assets/75176152/c74e205a-055a-4d0a-a063-590eef23f9da)

![App Screenshot](https://github.com/souleimane-ifqirne/Motus/assets/75176152/c9951971-78c6-4e86-b5cb-e0ecb61a9d1d)

![App Screenshot](https://github.com/souleimane-ifqirne/Motus/assets/75176152/b47852f1-25e5-494d-b8da-477feb7580ed)
