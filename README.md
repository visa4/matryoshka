# Matryoshka [![Build Status](https://travis-ci.org/ripaclub/matryoshka.svg)](https://travis-ci.org/ripaclub/matryoshka)

[m&#592;'tr<sup>j</sup>&#629;&#642;k&#601;]

Matryoshka is a Model Service Layer that normalize and standardize your model's interface use,
whether you are using Zend\Db, Mongo, Doctrine or anything else.


## Theory of operation

Matryoshka provides an handful API based on **criteria** interfaces. Think about criterias as a set of small objects, that's the responsibility of the developer to implement them: each criteria encapsulating a small piece of business logic and exposes a small interface. Criterias use the datagateway, instead Matryoshka's components do not use datagateway directly, so any kind of datagateway can be used.

Layers:
* ModelManager: a dedicated service locator for your model service classes (aka Model)
* Model: a service class repressenting a collection of entities that provides common features in a centralized way: CRUD, result set, paginating, hydrating, input filtering and more.
* Criteria: an "user query intefarce" from an API point of view, acting as mediator between model and datagateway.
* Datagateway: any kind of datagateway, like Zend\Db\TableGateway or MongoCollection. 


## Installation

Since a Matryoshka stable version has not been released yet you have to put
the Matryoshka repository in your composer.json

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/ripaclub/matryoshka.git"
        }
    ]
```

And of course you have to add it to your dependencies.

```
"ripaclub/matryoshka" : "dev-develop"
```

[![Analytics](https://ga-beacon.appspot.com/UA-49655829-1/ripaclub/matryoshka)](https://github.com/igrigorik/ga-beacon)

