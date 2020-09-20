# agora-php-server-sample 

Agora sample server implemented by PHP.

# Premise

Please install:

- heroku
- php: 7.4
- composer

# Setup

1. [Setup Heroku](https://devcenter.heroku.com/articles/getting-started-with-php)
1. copy `.env` file from `.env.local`

    ```
    cp .env.local .env
    ```

1. Edit `.env`

    - `AGORA_APP_CERTIFICATE`
      - APP Certificate value in your Agora project.
 
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
curl https://your_server/token?appId=myappid&channelName=mychannelname&userId=1234
```

```
{"token":"yourprojecttoken"}
```

# Development

## How to test in local?

```
heroku local -f ./Procfile.local
```

and, see: http://localhost:8888
