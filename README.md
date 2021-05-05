# Teste PHP DeliveryIT
Para executar o projeto basta seguir os passos:
- Clone o repositório
- Baixe o composer e instale as dependências
- Configure o banco de dados local em .env (Não foi possível subir via docker)
- Rode a aplicação `php artisan serve`


## Endpoints
- `[POST] => /api/corredores` - Inclusão de corredores para uma corrida

```json
{
	"name": "Corredor Teste 14523",
	"cpf": "123.123.123-44",
	"birthdate": "1988-03-26"
}
```

- `[POST] => /api/provas` - Inclusão de provas

```json
{
	"race_date": "2021-05-20",
	"type_of_races_id": 3
}
```

- `[POST] => /api/competicao` - Inclusão de corredores em provas

```json
{
	"runners_id": 5,
	"races_id": 2
}
```

- `[POST] => /api/competicao/novo` - Inclusão de resultados dos corredores

```json
{
    "runners_id": 1,
    "races_id": 1,
    "race_start_time": "09:00:00",
    "race_end_time": "10:00:00"
}
```

- `[GET] => /api/classificacao-geral` - Listagem de classificação das provas gerais

- `[GET] => /api/classificacao-por-idade` - Listagem de classificação das provas por idade


