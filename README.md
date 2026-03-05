# BlogSecure - Plateforme de Blog Vulnérable

## Description
**BlogSecure** est une application web PHP volontairement vulnérable créée à des fins pédagogiques. Elle contient plusieurs failles de sécurité courantes que les étudiants doivent identifier et corriger.

## Installation

### Prérequis
- PHP 7.0 ou supérieur
- MySQL/MariaDB
- Un serveur web (Apache, Nginx, etc.)

### Étapes d'installation

1. **Cloner ou télécharger le projet**
   ```
   git clone <url-du-repo>
   cd blogsecure
   ```

2. **Créer la base de données**
   - Ouvrir phpMyAdmin ou MySQL CLI
   - Créer une nouvelle base de données nommée `blogsecure`
   ```sql
   CREATE DATABASE blogsecure;
   ```

3. **Importer le fichier SQL**
   ```sql
   mysql -u root -p blogsecure < db.sql
   ```

4. **Configurer la connexion**
   - Éditer le fichier `config.php`
   - Adapter `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME` selon votre configuration

5. **Placer les fichiers sur le serveur**
   - Copier tous les fichiers PHP dans le dossier web root de votre serveur (htdocs pour Apache, www pour Nginx, etc.)

6. **Accéder à l'application**
   - Ouvrir `http://localhost/blogsecure/index.php` (ou votre URL locale)

## Utilisateurs de Test

Après l'importation de la base de données, vous pouvez vous connecter avec :

| Username | Password | Email |
|----------|----------|-------|
| admin | password123 | admin@blogsecure.com |
| user1 | password456 | user1@blogsecure.com |
| user2 | password789 | user2@blogsecure.com |

## Structure du Projet

```
blogsecure/
├── index.php           # Page d'accueil et listing des articles
├── login.php           # Page de connexion
├── register.php        # Page d'inscription
├── article.php         # Affichage d'un article avec commentaires
├── add_comment.php     # Traitement de l'ajout de commentaire
├── create_article.php  # Création d'un nouvel article
├── delete_article.php  # Suppression d'un article
├── auth.php            # Gestion de l'authentification
├── config.php          # Configuration de la base de données
├── db.sql              # Structure et données de la base de données
├── ENONCE.md           # Énoncé du TP
└── README.md           # Ce fichier
```

## Failles de Sécurité à Identifier

L'application contient intentionnellement les failles suivantes :

### Injection SQL
- Pas d'utilisation de prepared statements
- Concaténation directe des entrées utilisateur dans les requêtes

### XSS (Cross-Site Scripting)
- Affichage non échappé du contenu utilisateur
- Affichage des titres et contenus d'articles sans sanitization

### CSRF (Cross-Site Request Forgery)
- Absence de tokens CSRF sur les formulaires
- Suppression d'articles par simple GET request

### Autres Failles
- Mots de passe stockés en texte brut
- Pas de validation des entrées
- Pas de gestion des erreurs sécurisée
- Tentatives de login illimitées (pas de rate limiting)

## Avertissements Importants

⚠️ **ATTENTION** ⚠️

- Cette application est **volontairement vulnérable**
- À **UTILISER UNIQUEMENT** dans un environnement d'apprentissage contrôlé
- **NE JAMAIS** déployer en production
- **NE JAMAIS** copier ces failles dans des applications réelles
- L'utilisation malveillante de ces techniques est **illégale**

## Objectifs du TP

1. Identifier les failles de sécurité dans le code
2. Comprendre comment exploiter ces failles
3. Apprendre à corriger les vulnérabilités
4. Appliquer les bonnes pratiques de sécurité web

## Ressources

- [OWASP Top 10](https://owasp.org/Top10/)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
- [OWASP SQL Injection](https://owasp.org/www-community/attacks/SQL_Injection)
- [OWASP XSS](https://owasp.org/www-community/attacks/xss/)
- [OWASP CSRF](https://owasp.org/www-community/attacks/csrf)

## Support

Pour toute question sur le TP, contactez votre formateur ou enseignant.

---

*Projet créé à des fins pédagogiques - Septembre 2025*
