## Installation

* Clone git: `git clone https://github.com/Snezhig/at-category-api.git`
* Move to folder: `cd at-category-api`
* Create .env: 'cp .env.template .env'
* Build: `docker-compose up --build -d`
* Open container shell: `docker-compose exec -u 1000 php bash`
* Generate key: `php artisan key:generate`
* Run migration and seeders: `php artisan migrate:fresh --seed`
* It's ready
* **Optional** import [postman collection](./docs/Collection.postman_collection)

## Task

* Create a model with the following fields:

```yaml
id: int [auto]
slug: string // Unique name for category [required]
name: string // Category's name [required]
description: string // Description of category [optional]
created_at: date // [auto]
active: boolean // [required]
```

* Realise api methods:
    * [Create](#Create a category) a category
    * [Update](#Update a category) a category (partial)
    * [Delete](#Delete a category) a category
    * [Get](#Get a category) a category by id or slug
    * [Get list](#List of categories) of categories

## Examples

### Create a category

```shell
curl --location --request POST 'localhost/api/v1/categories/' \
--header 'Content-Type: application/json' \
--data-raw '{
    "slug": "honey",
    "description": "",
    "active": true,
    "name": "Мёд"
}'
```

### Get a category

```shell
curl --location --request GET 'localhost/api/v1/categories/honey/' \
--header 'Content-Type: application/json' \
--data-raw '{
    "slug": "fdfdf",
    "description": "test-desription",
    "active": "0",
    "name": "test_name",
    "anot": 3
}'
```

### Update a category

```shell
curl --location --request PATCH 'localhost/api/v1/categories/honey/' \
--header 'Content-Type: application/json' \
--data-raw '{
    "description": "Some description about honey"
}'
```

### Delete a category

```shell
curl --location --request DELETE 'localhost/api/v1/categories/honey/'
```

### List of categories

* Filters (in query):
    * name
    * description
    * search (ignore `name` and `description` if exist)
    * active (accept 0,1,true,false)
* Sort (?sort=#field#):
    * id, slug, name, description, active (set `-` before field to do DESC order)
* Pagination (in query):
    * page: 1 by default
    * pageSize: 2 by default

```shell
curl --location --request GET 'localhost/api/v1/categories/'
```

```shell
# DESC sort by name
curl --location --request GET 'localhost/api/v1/categories/?sort=-name'
```

```shell
# Filter by active
curl --location --request GET 'localhost/api/v1/categories/?active=true'
```

```shell
# Get fifth page with up to ten elements on the page
curl --location --request GET 'localhost/api/v1/categories/?page=5&pageSize=10'
```
