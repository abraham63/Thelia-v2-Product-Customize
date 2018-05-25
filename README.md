# Module de personnalisation de produit basic pour Thelia v2

Ce module permet de personnaliser des produits sur Thelia version 2 via un champs texte que le client peut renseigner. Utile pour les produits personnalisés au nom du client.

## Pré-requis

Thelia version 2.3.3 ou 2.3.4 - Module non testé sur les autres versions

## Installation

* Copier le module dans ```<thelia_root>/local/modules/PersoProduit``` et assurer vous que le dossier soit bien nommé PersoProduit
* Aller dans votre panneau d'administration des modules et activer le module
* Ajouter votre formulaire et l'affichage des champs dans vos template

## Utilisation


### Formulaire

Ce module ajoute un champs dans le formulaire d'ajout au panier à rajouter dans le template de la page produit
```
  {form_field field='champs_perso'}
      <input id="{$name}" class="{$name}" type="text" name="{$name}" value="{$VALUE}" {$attr} >
  {/form_field}
```

### Loop

#### Loop panier

Cette boucle permet de récuperer le champs de personnalisation au niveau du panier

##### Input arguments

|Argument |Description 
|---      |---        
|**item_id** | Id de l'élément dans le panier (cart item)


##### Output values

|Argument |Description 
|---      |---        
|**personnalisation** | Valeur du champs de personnalisation

```
{loop type="personnalisation.produit" name="personnalisation_du_produit" item_id="$ITEM_ID"}
   <span>{$personnalisation}</span>
{/loop}
```

#### Loop commande

Cette boucle permet de récuperer le champs de personnalisation au niveau de la commande

##### Input arguments

|Argument |Description 
|---      |---        
|**order_prod_id** | Id de l'élément dans la commande


##### Output values

|Argument |Description 
|---      |---        
|**personnalisation** | Valeur du champs de personnalisation

```
  {loop type="order.personnalisation.produit" name="qsifoqds" order_prod_id=$ID}
   <span>{$personnalisation}</span>
{/loop}
```
