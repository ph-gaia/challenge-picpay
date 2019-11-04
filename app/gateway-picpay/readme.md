### API Gateway PicPay - Documentação de Rotas

[Retornar para o início](../../README.md)

### Uso padrão
Em todas as rotas de listagem de registros o padrão de resposta poder ser notado abaixo:

##### GET /api/users

```javascript
{
  "status": "success",
  "message": "",
  "data": [
    {
      "id": 1,
      "full_name": "Paulo Henrique Gaia",
      "cpf": "01134669275",
      "phone_number": "91992854548",
      "email": "phcgaia11@yahoo.com.br",
      "account_type": "CONSUMER",
      "created_at": "2019-11-01 23:42:45",
      "updated_at": "2019-11-01 23:42:45"
    },
    {
      "id": 2,
      "full_name": "Paulo Victor Coelho Gaia",
      "cpf": "01134694202",
      "phone_number": "91989939010",
      "email": "pvcgaia@yahoo.com.br",
      "account_type": "CONSUMER",
      "created_at": "2019-11-01 23:43:52",
      "updated_at": "2019-11-01 23:43:52"
    }
  ]
}
```

#### Rotas para users

#### Informações base

- Rota Base `/api/< ENTIDADE >`
- Exemplo Rota Base `/v1/users`

#### Rotas disponívels

- [GET /api/users](#get-api-users)
- [GET /api/users/:id](#get-api-users-_id)
- [POST /api/users](#post-api-users)
- [PUT /api/users/:id](#put-api-users-_id)
- [DELETE /api/users/:id](#delete-api-users-_id)

---

##### <a name="get-api-users"></a> GET /api/users
- HTTP Method: `GET`
- Action: Retorna todos os registros de `users` (Usuários)
- Params: `none`
- Patterns: `none`
- Success HTTP code: 200
- Error HTTP code: `none`
- Success HTTP body:

```javascript
{
  "status": "success",
  "message": "",
  "data": [
    {
      "id": 1,
      "full_name": "Paulo Henrique Gaia",
      "cpf": "01134669275",
      "phone_number": "91992854548",
      "email": "phcgaia11@yahoo.com.br",
      "account_type": "CONSUMER",
      "created_at": "2019-11-01 23:42:45",
      "updated_at": "2019-11-01 23:42:45"
    },
    {
      "id": 2,
      "full_name": "Paulo Victor Coelho Gaia",
      "cpf": "01134694202",
      "phone_number": "91989939010",
      "email": "pvcgaia@yahoo.com.br",
      "account_type": "CONSUMER",
      "created_at": "2019-11-01 23:43:52",
      "updated_at": "2019-11-01 23:43:52"
    }
  ]
}
```

---

##### <a name="get-api-users-_id"></a> GET /api/users/:id
- HTTP Method: `GET`
- Action: Retorna um registro pelo ID informado
- Params:
    1. :id : Identificação do registro
- Patterns:
    1. `{id:[0-9]+}`
- Success HTTP code: 200
- Error HTTP code: 404
- Success HTTP body:

```javascript
{
  "status": "success",
  "message": "",
  "data": {
    "id": 1,
    "full_name": "Paulo Henrique Gaia",
    "cpf": "01134669275",
    "phone_number": "91992854548",
    "email": "phcgaia11@yahoo.com.br",
    "account_type": "CONSUMER",
    "created_at": "2019-11-01 23:42:45",
    "updated_at": "2019-11-01 23:42:45"
  }
}
```

- Error HTTP body:

```javascript
{
    "message": "User not found",
    "status": "error",
    "data": []
}
```
---

##### <a name="post-api-users"></a> POST /api/users
- HTTP Method: `POST`
- Action: Adiciona um registro ao Banco de Dados
- Params: `none`
- Patterns: `none`
- Success HTTP code: 201
- Error HTTP code: 400
- Success HTTP body:

```javascript
{
	"name": "Juan Leonardo Hugo Baptista",
	"cpf" : "75436525792",
	"phone" : "91313766289",
	"email" : "jjuanleonardohugobaptista@tcotecnologia.com.br",
	"typeAccount" : "CUSTOMER",
	"username" : "juan.batista",
	"password" : "MYjnwNJCt6"
}
```

- Error HTTP body:

```javascript
{
    "message": "There are wrong fields in submission",
    "status": "error",
    "error": [
        {
            "property": "name",
            "pointer": "/name",
            "message": "The property name is required",
            "constraint": "required",
            "context": 1
        }
    ]
}
```

---

##### <a name="put-api-users-_id"></a> PUT /api/users/:id
- HTTP Method: `PUT`
- Action: Altera um registro no Banco de Dados
- Params:
    1. :id : Identificação do registro
- Patterns:
    1. `{id:[0-9]+}`
- Success HTTP code: 200
- Error HTTP code: 400
- Success HTTP body:

```javascript
{
    "message": "Registry updated successfully",
    "status": "success",
    "data": {
        "id": 2,
        "name": "Paulo Henrique",
        "cpf": "01134669275",
        "phone_number": "91992854548",
        "email": "phcgaia11@yahoo.com.br",
        "account_type": "CONSUMER",
        "created_at": "2019-11-01 23:42:45",
        "updated_at": "2019-11-01 23:42:45"
    }
}
```

- Error HTTP body (400):

```javascript
{
    "message": "User not found",
    "status": "error"
}
```
---

##### <a name="delete-api-users-_id"></a> DELETE /api/users/:id
- HTTP Method: `DELETE`
- Action: Remove um registro pelo ID informado
- Params:
    1. :id : Identificação do registro
- Patterns:
    1. `{id:[0-9]+}`
- Success HTTP code: 204
- Error HTTP code: 400
- Success HTTP body:
- Error HTTP body:

```javascript
{
    "message": "User not found",
    "status": "error"
}
```
