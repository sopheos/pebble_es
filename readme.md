# Pebble/ES

Ce document est un guide d'utilisation du query builder Elasticsearch de Pebble.

## Exemple simple

### Préparer une recherche

Pour effectuer une recherche, on prépare une requète `\Pebble\ES\Query` à laquelle on ajoute un composant `\Pebble\ES\BoolQuery`.

````php
$bool = \Pebble\ES\BoolQuery::make()->minimumSouldMatch(1);
$query =  \Pebble\ES\Query::make()->query($bool);
````
La requète booléene permet de grouper des filtres de recherches dans des directives (`must`, `must_not`, `filter`, `should`) :

````php
\Pebble\ES\Term::make('pays', 'FR')->filter($bool);
\Pebble\ES\Match::make('recherche', $search)->boost(16)->should($bool);
\Pebble\ES\Match::make('nom', $search)->boost(8)->should($bool);
````

Voir la documentation de élasticsearch : https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-bool-query.html
        
### Envoyer la requète

Le query builder utilise `\GuzzleHttp\Client` pour effectuer les requetes HTTP vers le serveur.

````php
$options = ['base_uri' => 'http://127.0.0.1:9200'];
$guzzle  = new \GuzzleHttp\Client($options);
$request = \Pebble\ES\Request::make($guzzle);
$res = $request->search('loc_villes');
````

Voir la documentation de Guzzle : https://github.com/guzzle/guzzle

### Récupérer les résultats

La méthode `search` renvoit un objet `\Pebble\ES\Result`.

````php
echo json_encode($res, JSON_PRETTY_PRINT);
````

## Le composant `ES\Request`

### Constructeur

Le constructeur ou la méthode statique `make` prend comme argument un objet `\GuzzleHttp\Client`.

### Methodes

* `request(string $method, string $url, $data = []) : \stdClass` : Envoi une requète.
* `get($url) : \stdClass` : Raccourcis pour envoyer une requète GET. 
* `post($url, $data = []) : \stdClass` : Raccourcis pour envoyer une requète POST.
* `put($url, $data = []) : \stdClass` : Raccourcis pour envoyer une requète PUT.
* `delete($url) : \stdClass` : Raccourcis pour envoyer une requète DELETE.
* `search(string $url = '', $data = []) : \Pebble\ES\Result` : Raccourcis pour faire une recherche.
* `search(string $url = '', $data = []) : \Pebble\ES\Result` : Raccourcis pour faire une recherche.
* `scroll(string $url = '', $data = [], string $time = '1m') : \Pebble\ES\Result` : Raccourcis pour faire une recherche très longue.
* `clearScroll(string $id) : \Pebble\ES\Result` : suppression d'une recherche longue
* `bulk(string $url, array $data) : \stdClass` : Envoi de données en masse.
* `lastRequest() : array` : Récupère la dernière requète.

### Les recherches longues

Une recherche standard est limité à 10 000 éléments. Pour récupérer tous les résultats, d'une recherche longue, il faut utiliser des requètes préparées.

Première recherche : on utilise la méthode `scroll` comme avec la méthode `search`.
Un paramètre additionnel permet de déterminer combien de temps cette requète va être conservée.

Le résultat renverra un paramètre additionnel : `scroll_id`.
Pour récupérer les résultats suivants, il faudra passer cette valeur directement
au paramètre `$data`.

Lorsque la recherche est terminée il est préférable de supprimer la requète préparée
avec la méthode `clearScroll`

Exemple :

````php
// Première recherche
$query =  \Pebble\ES\Query::make();
// ...
$res = $request->scroll('loc_villes', $query);
$scroll_id = $res1->scrollID();

// Seconde recherche
$res2 = $request->scroll('loc_villes', $scroll_id);

// Fin
$request->clearScroll($scroll_id);
````

## Le composant `ES\Result`

### took()

Retourne le temps d'execution de la requète en millisecondes

### total()

Retourne le nombre de résultats totaux correspondant à une requète.

### maxScore()

Retourne le meilleur score de recherche correspondant à une requète.

### hits()

Retourne le jeu de résultats d'une requète

### aggs($name0, $name1, ... , $nameN)

Retourne le jeu de résultat d'un aggrégat.
La liste de paramètre correspond au chemin de l'aggrégat

### raw()

Renvoit le résultat brute de la recherche

### jsonSerialize()

Méthode identique à `raw()`. Cette méthode est necessaire pour la transformation
de l'objet en objet `json`.

## Query builder

Les reqètes élasticsearch sont très verbeuses. Le Query builder permet d'aider à construire les requètes les plus communes.

Tous les éléments du query builder doivent étendre `\Pebble\ES\AbstractRaw`

* `abstract raw() : array` : Renvoi les données du composant de la requète sour forme de tableau.

Un composant personnalisé peut être créer avec le helper `\Pebble\ES\Raw`

````php
Raw::make(["prefix" => ["user" => "ki"]]);
````

### Le composant `Pebble\ES\Query`

Conteneur principal pour le Query builder.

* `static make() : Query` : Alternative au constructeur.
* `set(string $key, $value)` : Ajoute un ensemble clé, valeur à la requète
* `size(int $value = 10)` : Limite le nombre de résultat.
* `from(int $value = 0)` : Décalage (offset) du jeu de résultat voulu.
* `searchAfter(array $value)` : Pour ne retourner les résultats qu'au-delà des valeurs passées par rapport au valeurs de tri(généralement utilisé en se basant sur les dernières valeurs de tri d'une requête précédente)
* `query(AbstractRaw $query)` : Ajoute un champ de recherche.
* `conflictsProceed()` : Gère les conflits lors de modification / suppression par requète.
* `aggs(AbstractRaw $agg)` : Ajoute un aggrégat.
* `sort(AbstractRaw $sort)` : Ajoute un tri.
* `highlight(AbstractRaw $highlight)` : Ajoute un highlight.

### Requètes booléenes `ES\BoolQuery`

Tri les requètes dans des directives.

* `static make() : Query` : Alternative au constructeur.
* `must(AbstractFilter $filter)` : Ajoute un filtre à la directive must.
* `should(AbstractFilter $filter)` : Ajoute un filtre à la directive should.
* `filter(AbstractFilter $filter)` : Ajoute un filtre à la directive filter.
* `must_not(AbstractFilter $filter)` : Ajoute un filtre à la directive must_not.
* `minimumSouldMatch(int $value)` : Nombre minimal de résultat pour la directive should.
* `boost(float $value)` : Renforce le score de la recherche.

### Filtres

Tous les filtres du moteurs n'ont pas encore été ajouté à Pebble.
Merci de les rajouter au fur et à mesure des besoins.

De même des options facultatives peut être ajoutées à certains filtres
(voir la doc de ElasticSearch) Ils peuvent être ajoutés avec la méthode :

````php
$filter->set('key', $value);
````

Des raccourcis peuvent être ajoutés au fur et à mesure sur les filtres.

Remarque : Certains filtres on un contexte de recherche (FullText). Le score sera impacté.
D'autres filtres auront un contexte de filtre. Le score ne sera pas impacté.

#### Match

Recherche FullText la valeur d'un champ analysée (full text)

````php
Match::make('titre', 'meuble')->fuzzy('auto')->boost(16);
````

#### MatchAll

Recherche FullText sur tous les documents

````php
MatchAll::make();
````

#### Range

Recherche des valeurs bornées par un interval.

````php
Range::make('prix')->gte(10)->lte(15);
Range::make('borne')->gte('01/01/2012')->lte('2013')->format('dd/MM/yyyy||yyyy');
````

* `gte($value)` : Plus grand ou égal.
* `gt($value)` : Plus grand.
* `lte($value)` : Plus petit ou égal.
* `lt($value)` : Plus petit.
* `format(string $value)` : Format pour les dates.
* `time_zone(string $value)` : Conversion des dates dans un autre fuseau horaire.

#### Exists

Recherche les document dont le champ existe et a une valeur non nulle.

````php
Exists::make('petname');
````

#### Term

Recherche une valeur particulière d'un champ.

````php
Term::make('pays', 'FR');
````

#### Terms

Recherche les champs qui correspondent à une certaine valeur.

````php
Terms::make('pays', 'FR', 'CH', 'BE');
````

#### Prefix

Permet de rechercher sur un champ non analysé qui commence par une valeur.
Étend `Term`.

````php
Prefix('user', 'ki');
````

#### Wildcard

Permet de rechercher la valeur d'un champ non analysé avec des jokers.
Étend `Term`.

````php
Wildcard::make('user', 'ki*y');
````

#### Regex

Permet de rechercher la valeur d'un champ non analysé avec des expression régulières.
Étend `Term`.

````php
Regex::make('user', 'k.*m');
````

#### Fuzzy

Permet de rechercher la valeur approximative d'un champ non analysé.
Étend `Term`.

````php
Fuzzy::make('ville', 'milhouse')
    ->fuzziness(1)
    ->prefixLength(0)
    ->maxExpansions(20);
````

#### Ids

Filtre les documents dont l'identifiant appartient à une liste.

````php
Ids::make(['jsgrvdoi', 'zldns']);
````

#### GeoDistance

Filtre les documents dans un rayon autour d'un point.
Le champ doit être du type `geo_point`.

````php
GeoDistance::make('loc', 47.75, 7.31, '100m'); // field, lat, lon, distance
````

#### GeoBound

Filtre les documents entre des bornes.
Le champ doit être du type `geo_point`.

````php
GeoDistance::make(field, latMin, latMax, lonMin, lonMax)
GeoDistance::fromDistance(field, lat, lon, $distance)
````

### Tris

#### Sort

Tri par valeur

````php
Sort::make()->add('_score')->desc('type')->asc('pays_alias');
````

#### SortGeo

Tri par distance.

````php
SortGeo::make('loc', 47.75, 7.31); // // field, lat, lon
````

### Agrégations

Rassemble l'ensemble des données sélectionnées par la requête de recherche.

Reprenons l'exemple précédent et cherchons la liste des régions

````php
$aggs = \Pebble\ES\Aggregation::make('admin_list', 'terms')->field('admin_nom')->size(1000)->addTo($request);
````

Remarque : Si l'on est intéressé que par les aggrégat, on peut demander à la requete de ne pas renvoyé de résultats de recherche

````php
$request->size(0);
````

Pour récupérer cette liste, il suffit de chercher son nom dans les aggrégats :

````php
$list = $res->aggs('admin_list');
````

Il est possible de faire des recherches boolennes sur un aggrégat :

````php
$boolQuery->addToAggs($aggs)
````

ou 

````php
$aggs->filter($boolQuery);
````

### Highlight

Il est possible de mettre en avant les matches par rapport à la recherche avec Highlight et de définir les tags entourant les matches.

````php
$highlight = ES\Highlight::make('title')->preTags('<br>')->postTags('</br>');
$query->highlight($highlight);
````

Les tags sont facultatifs et ES mettra par défaut les matches entre tags `<em>`.

Il est possible de passer un string ou un tableau de string pour highlight plusieurs fields dans le cas où l'on recherche dans plusieurs fields.

````php
$highlight = ES\Highlight::make(['title', 'description']);
$query->highlight($highlight);
````

Le résultat est à récupérer dans les hits->highlight.

## Inserer/Remplacer un document

### Identifiant non fournit

````php
$res = Request::make($guzzle)->post("tests/_doc", [
    "name" => "Mathieu",
    "age" => 2,
]);
$success = isset($res->result) && ($res->result === 'created' || $res->result === 'updated');
````

### Identifiant fournit

````php
$res = Request::make($guzzle)->put("tests/_doc/1/_update", [
    "name" => "Mathieu",
    "age" => 2,
]);
$success = isset($res->result) && ($res->result === 'created' || $res->result === 'updated');
````

## Mettre à jour un document

````php
$res = Request::make($guzzle)->post("tests/_doc/1/_update", [
    "doc" => ["age" => 3],
]);
````

## Mise à jour atomique

````php
$res = Request::make($guzzle)->post("tests/_doc/1/_update", [
    "script" => "ctx._source.age += 5"
]);
$success = isset($res->result) && $res->result === 'updated';
````

## Supprimer un document

````php
$res = Request::make($guzzle)->delete("tests/_doc/1");
$success = isset($res->result) && $res->result === 'deleted';
````

## Insertion de masse

````php
$data = [];
$data[] = ['index' => ["_id" : "1"]];
$data[] = ['name' => 'Pierre'];
$data[] = ['index' => ["_id" : "2"]];
$data[] = ['name' => 'Chloé'];

Request::make($guzzle)->bulk('tests/_doc', $data);
````

## Suppression de masse

````php
// Delete
$bool = BoolQuery::make();
// ...
$query = Query::make()->query($bool)->conflictsProceed();
Request::make($guzzle)->post('tests/_delete_by_query', $query);
````
