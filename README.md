# agora-php-server-sample 

Agora sample server implemented by PHP.

# Premise

Please install:

- Heroku
- php: 7.4
- composer

# Depends libraries

This project depends these libraries or product:

- [Heroku](https://dashboard.heroku.com/)
- [Slim Framework](http://www.slimframework.com/)

# Setup

1. [Setup Heroku](https://devcenter.heroku.com/articles/getting-started-with-php)
1. copy `.env` file from `.env.local`

    ```
    cp .env.local .env
    ```

1. Edit `.env`

    - `AGORA_APP_CERTIFICATE`
      - APP Certificate value in your Agora project.

1. Install composer

    ```
    composer install
    ```
 
1. Run locally

    ```
    heroku local -f ./Procfile.local
    ```
    and, see: http://localhost:8888

1. Or, deploy to heroku server.

    ```
    git push heroku master
    ```

# Features

- [ ] [Generate secure token](https://docs.agora.io/en/Agora%20Platform/token)
- [ ] [Recording](https://docs.agora.io/en/cloud-recording/cloud_recording_rest?platform=All%20Platforms)

## API

### GET /token

Get a secure token for the Agora project.

see: https://docs.agora.io/en/Agora%20Platform/token/#a-name--tokenause-a-token-for-authentication

#### Request

| Key | Required | Type | Description |
| --- | --- | --- | --- |
| appId       | ✔ | string | The App ID of your Agora Project.  |
| channelName | ✔ | string | Unique channel name for the Agora RTC session. |
| userId      | ✔ | int    | The user id in the Agora application. |

#### Response

| Key | Optional | Type | Description |
| --- | --- | --- | --- |
| token |   | string | The token for your Agora Project. |

#### Example

```
$ curl "http://localhost:8888/token?appId=myappid&channelName=mychannelname&userId=1234"
```

```
{"token":"005AQAoAEUyRjU0NUIyMzY1MjIyNjhBNUE2MEE1NzgyNEIzNzRENjBDQzJDMzAAAG1TZ1\/MKqESzadnXwAA"}
```

# Development

## How to test in local?

```
heroku local -f ./Procfile.local
```

and, see: http://localhost:8888
