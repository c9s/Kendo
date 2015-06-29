Kendo
======

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

