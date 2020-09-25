# agora-php-server-sample 

Agora sample server implemented by PHP.

# Prerequisites

Please install:

- Heroku
- php: 7.4
- composer

# Depends libraries

This project depends on these libraries or product:

- [Heroku](https://dashboard.heroku.com/)
- [Slim Framework](http://www.slimframework.com/)

# Setup

1. [Setup Heroku](https://devcenter.heroku.com/articles/getting-started-with-php)
1. copy `.env` file from `.env.sample`

    ```
    cp .env.sample .env
    ```

1. Edit `.env`

   | Key | Description |
   | --- | ----------- |
   | `AGORA_APP_ID`                       | Agora Project AppId. |
   | `AGORA_APP_CERTIFICATE`              | Agora Project AppCertificate |
   | `AGORA_RESTFUL_API_CUSTOMER_ID`      | Agora CustomerId for ResfulAPI |
   | `AGORA_RESTFUL_API_CUSTOMER_SECRET`  | Agora CustomerSecret for RestfulAPI |
   | `AGORA_RECORDING_AWS_ACCESS_TOKEN`   | AWS access key for uploading recording file to AWS S3 |
   | `AGORA_RECORDING_AWS_SECRET_TOKEN`   | AWS secret key for uploading recording file to AWS S3 |

1. Install composer

    ```
    composer install
    ```
 
1. Run locally

    ```
    heroku local -f ./Procfile.local
    ```
    then, see: http://localhost:8888

1. Or, deploy to heroku server.

    ```
    git push heroku master
    ```

# Features

- [x] [Generate secure token](https://docs.agora.io/en/Agora%20Platform/token)
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

### POST /recording/start

Start recording.

see: https://docs.agora.io/en/cloud-recording/cloud_recording_rest?platform=All%20Platforms

#### Request

| Key | Required | Type | Description |
| --- | --- | --- | --- |
| channelName | ✔ | string | Unique channel name for the Agora RTC session. |
| userId      | ✔ | int    | The user id in the Agora application. |

#### Response

| Key | Optional | Type | Description |
| --- | --- | --- | --- |
| recordingId |   | string | The recording id for the channel. |

#### Example

```
$ curl -X POST "http://localhost:8888/recording/start" -d '{
  "channelName": "test",
  "userId": 1234
}'
```

```
{"recordingId":"d54ab21c6548284312c3268f38901699"}

```
