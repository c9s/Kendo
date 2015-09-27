Kendo
======
[![Build Status](https://travis-ci.org/c9s/Kendo.svg?branch=master)](https://travis-ci.org/c9s/Kendo)

[![Latest Stable Version](https://poser.pugx.org/corneltek/kendo/v/stable)](https://packagist.org/packages/corneltek/kendo) 
[![Total Downloads](https://poser.pugx.org/corneltek/kendo/downloads)](https://packagist.org/packages/corneltek/kendo)
[![Latest Unstable Version](https://poser.pugx.org/corneltek/kendo/v/unstable)](https://packagist.org/packages/corneltek/kendo) 
[![License](https://poser.pugx.org/corneltek/kendo/license)](https://packagist.org/packages/corneltek/kendo)

[![Monthly Downloads](https://poser.pugx.org/corneltek/kendo/d/monthly)](https://packagist.org/packages/corneltek/kendo)
[![Daily Downloads](https://poser.pugx.org/corneltek/kendo/d/daily)](https://packagist.org/packages/corneltek/kendo)

## Install

```json
{
    "require": { 
        "corneltek/kendo": "*"
    }
}
```

## Development Environment Setup

    composer install --dev
    lazy build-conf db/config/database.yml
    lazy schema build src
    lazy sql --rebuild --basedata src
    phpunit

## Debugging

Query Permissions

```sql
SELECT ar.resource, ac.role, ar.operation, ac.allow FROM access_controls ac LEFT JOIN access_rules ar ON (ac.rule_id = ar.id);
```

```sql
SELECT ar.id as rule_id, ar.rules_class as rule_class, ac.role,
  if(ac.allow,'can', 'can not') as modal_verb, ar.operation, ar.resource 
FROM
  access_controls ac 
LEFT JOIN access_rules ar ON (ac.rule_id = ar.id);
```

