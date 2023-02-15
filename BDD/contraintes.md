Contraintes BD SAE 3 :

Utilisateur :

  - PK -> not null

  - nom -> not null

  - prenom -> not null

  - mot de passe -> not null

  - adresse mail -> not null

  - date de naissance -> pas supérieur a CURDATE


Genre :

  - PK -> not null

  - Nom -> not null

(liste genre : homme, femme, non binaire, autres, (ne se prononce pas -> choix pas défaut))


Humeur : 

  - PK -> not null

  - Libellé -> not null

  - Emoji -> not null

(liste libellé : cf fin cachier des charges)

Historique :

  - PK -> not null
 
  - DATE_ajout -> = CURDATETIME

  - DATE_hum -> pas supérieur a DATE_ajout, pas inférieur a DATE_ajout - 24h
