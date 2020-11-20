# Bücherverwaltung

---
###Einrichten:
Nach dem herunterladen aus dem Git Repositorie
den folgenden Befehl ausführen:
```
composer update
```
---
###Die Datenbankverbindung einrichten
In der Datei:

/.env

Unter:

doctrine/doctrine-bundle

Die zeile für die Datenbankverbindung anpassen
```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
```

###Die Datenbank "installieren" über die Befehle
Um die Datenbank zu erstellen:
```
php bin/console doctrine:database:create
```

Um die Tabellen anzulegen:
```
php bin/console doctrine:migrations:migrate
```

---
###Demo

###https://books.anyp.de
