### API Gateway PicPay - Documentação de Rotas

[Retornar para o início](../../README.md)

### Uso padrão
Em todas as rotas de listagem de registros o padrão de resposta poder ser notado abaixo:

##### GET /api/users

```javascript
{
    "message": "",
    "status": "success",
    "data": [
        {
            "id": 1,
            "name": "Paulo Henrique",
            "mail" : "teste@teste.com.br",
            "phone" : "91990012012",
            "type_account" : "CUSTOMER"
        },
        {
            "id": 2,
            "name": "Paulo Victor",
            "mail" : "teste@teste.com.br",
            "phone" : "91990012012",
            "type_account" : "CUSTOMER"
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
    "message": "",
    "status": "success",
    "data": [{
        "id": 1,
        "name": "Paulo Henrique Gaia"
    }]
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
    "message": "",
    "status": "success",
    "data": {
        "id": 1,
        "name": "Paulo Henrique Gaia"
    }
}
```

- Error HTTP body:

```javascript
{
    "message": "User not found",
    "status": "error"
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
    "message": "Registry created successfully",
    "status": "success",
    "data": {
        "id": 2,
        "name": "Paulo Henrique"
    }
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
    }
}
```

- Error HTTP body (400):

```javascript
{
    "message": "Register not found",
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
