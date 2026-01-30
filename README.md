## Initialisation du projet :

Pour lancer le projet, il faut aller dans le répertoire toubilib_partie2.  
À partir de là, il faut faire la commande :
````
docker compose up 
````

**Remarque :**  
Il se peut que le service "mail-toubi" ne se lance pas. Pour cela, il suffit de le lancer seul. Après cela, il devrait tourner correctement.


## Requêtes possible :

**Praticien :**  
Liste des praticiens :  
http://localhost:8081/praticiens  
http://localhost:8081/praticiens?specialite=1&ville=Paris

Détail d'un praticien :  
http://localhost:8081/praticiens/4305f5e9-be5a-4ccf-8792-7e07d7017363

Les créneaux occupés sur une date :  
http://localhost:8081/praticiens/4305f5e9-be5a-4ccf-8792-7e07d7017363/rdvs?debut=2025-12-01&fin=2025-12-02
avec heures :  
http://localhost:8081/praticiens/4305f5e9-be5a-4ccf-8792-7e07d7017363/rdvs?debut=2025-12-01%2009:00:00&fin=2025-12-01%2018:30:00

**Rdv :**
Pour lancer les requêtes rdv, il faut être authentifié  
Consulter un RDV :  
http://localhost:8081/rdvs/737c4813-b4e4-3634-baee-0a8698625672


Annuler un RDV :  
http://localhost:8081/rdvs/737c4813-b4e4-3634-baee-0a8698625672/annuler

L'inscription est possible via PostMan et Bruno :  
http://localhost:8081/signin  
Avec le body :
```
{
    "email": "Marine.Paul@hotmail.fr",
    "password": "Marine.Paul"
}
```

Il faut ensuite prendre l'acces token donnée par l'API et le mettre dans les requêtes qui demandent une authentification.  
Pour les requêtes qui demandent une authentification, il suffit de mettre dans le header Authorization : `Bearer [access token]`