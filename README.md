# Agora recording application

Agora recording sample server.

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
1. copy `.env` file from `.env.example`

    ```
    cp .env.example .env
    ```

1. Edit `.env`

   | Key | Description |
   | --- | ----------- |
   | `AGORA_APP_ID`                       | Agora Project AppId. |
   | `AGORA_APP_CERTIFICATE`              | Agora Project AppCertificate. |
   | `HEADER_ACCESS_CONTROL_ALLOW_ORIGIN` | `Access-Control-Allow-Origin` Header. |
   | `AGORA_RESTFUL_API_CUSTOMER_ID`      | Agora CustomerId for ResfulAPI. |
   | `AGORA_RESTFUL_API_CUSTOMER_SECRET`  | Agora CustomerSecret for RestfulAPI. |
   | `AGORA_RECORDING_AWS_ACCESS_TOKEN`   | AWS access key for uploading recording file to AWS S3. |
   | `AGORA_RECORDING_AWS_SECRET_TOKEN`   | AWS secret key for uploading recording file to AWS S3. |
   | `AGORA_RECORDING_AWS_S3_BUCKET_NAME` | AWS S3 Bucket name. |

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

### Basic information

|  | BaseURL |
|----------|-----------------------|
| Local | `http://localhost:8888/v1/...` |
| Prod  | `https://${heroku-domain}/v1/...` |

### GET /token

Get a secure token for the Agora project.

see: https://docs.agora.io/en/Agora%20Platform/token/#a-name--tokenause-a-token-for-authentication

#### Request

| Key | Required | Type | Description |
| --- | --- | --- | --- |
| channelName | ✔ | string | Unique channel name for the Agora RTC session. |
| userId      | ✔ | int    | The user id in the Agora application. |

#### Response

| Key | Optional | Type | Description |
| --- | --- | --- | --- |
| token |   | string | The token for your Agora Project. |

#### Example

```
$ curl "http://localhost:8888/v1/token?channelName=mychannelname&userId=1234"
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
| token       | ✔ | string | Token getting by `GET /token` API. |

#### Response

| Key | Optional | Type | Description |
| --- | --- | --- | --- |
| resourceId |   | string | The resource id for cloud recordings. |
| sid        |   | string | The recording id for the channel. |

#### Example

```
 curl -s -X POST "http://localhost:8888/v1/recording/start" -d '{
  "token": "005AQAoADk2RjhCQTI1QTNENzIyMUFGRDIxNEE3ODY0OTlCNUREOUIxMzBEQUQQAL3bq",
  "channelName": "test",
  "userId": 1234
}' | jq '.'
```

```json
{
  "resourceId": "Etkl6g-zSB7EpP-Da1zN65HXLQnA2s-23cPxAwEFqYaWWQ5AeOQlgEO4SGH5_hg263tLUldqQyZcq4VG4a_MN4-etbhDkfvmnZDDCwTsQI3JLpSBG_SZymZQ0YQ20eTHNbw0jcay8u4GJ9795u5cM-Qr4DiucM3dUF8Uj26P_56ocHtZN3te7gYDwTHqv9cXRam8hZNQbXghzpkxehccZ1cj6nrm7wtqPOdk__EfzYk-wWdSNBDKE1G3RYllF_syKQUZDrxcFumZothzIzJRnjSScypvXchheXfIcMXYo6E",
  "sid": "16fad08c8b47ce121c1483bec56e656e"
}
```
### POST /recording/stop

Stop recording.

see: https://docs.agora.io/en/cloud-recording/cloud_recording_rest?platform=All%20Platforms

#### Request

| Key | Required | Type | Description |
| --- | --- | --- | --- |
| resourceId  | ✔ | string | Resource id.  |
| sid         | ✔ | string | Recording id. |
| channelName | ✔ | string | Unique channel name for the Agora RTC session. |
| userId      | ✔ | int    | The user id in the Agora application. |

#### Response

| Key    | Optional | Type     | Description |
| ---    | ---      | ---      | --- |
| files  |          | object[] | The list of this file object |


#### Example

```
$ curl -s -X POST "http://localhost:8888/v1/recording/start" -d '{
  "resourceId": "Etkl6g-zSB7EpP-Da1zN65HXLQnA2s-23cPxAwEFqYaWWQ5AeOQlgEO4SGH5_hg263tLUldqQyZcq4VG4a_MN4-etbhDkfvmnZDDCwTsQI3JLpSBG_SZymZQ0YQ20eTHktAd43wJVHmjsZ2KZah1FgMbuFONuPcijEkJnYDSX6nqkSTdIfZmpRyBSmMTOpJmkkypDkxXmxhn2MlU4MntRbWghyht8dUHwa-54AveVTkplHMoZhsg9ePh54jRTM2FVAM-AdJg8v3F4EYaPNcigym7eLYK9rDybv8zIxLBFGA",
  "sid": "222f6d96464a234078ccf6b4712a63b8",
  "channelName": "test",
  "userId": 1234
}' | jq '.'
```

```json
{
  "files": [
    {
      "fileName": "aaa.m3u8"
    }
  ]
}
```
