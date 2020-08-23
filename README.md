## Prerequisite
- Docker
- mailtrap.io (Verification email will send to this)

## Getting started
- Prepare the .env file, make sure you have the following variables:

```
// Must follow
DB_CONNECTION=mysql
// MySQL container name 
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel  
DB_USERNAME=root  
DB_PASSWORD=test123  

// Put your mailtrap.io credentail
MAIL_MAILER=smtp  
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=example@david.com
MAIL_FROM_NAME="${APP_NAME}"
```
- Then please run the following commands:
```
// Start the container
docker-compose up -d  

// Generate app key so that laravel can run
docker-compose exec app php artisan key:generate  

// Create tables and put in some dummy data
docker-compose exec app php artisan migrate:fresh --seed  

// Create oauth clients 
docker-compose exec app php artisan passport:install
```

## Notes
- This app has no UI at all, apis only
- Using password grant type for laravel passport
- I built the apis based on the technical test requirements, this app is not a complete app
- Tested in centos linux

## APIs Documentation
- Register api
```
POST /api/register
Header:
- Content-Type: application-json
- Accept: application-json

Example payload:
{
	"data": {
	    "name":  "test",
	    "email":  "test@david.com",
		"password":  "abcd1234",
		"password_confirmation":  "abcd1234"
	}
}

Example response:
- Http status 200 (Registered)
{
	"data":  {
		"name":  "test",
		"email":  "test@david.com",
		"updated_at":  "2020-08-23T10:19:46.000000Z",
		"created_at":  "2020-08-23T10:19:46.000000Z",
		"id":  12
	}
}
- Http status 422 (Validation)
{
	"errors":  {
		"data.email":  [
			"The data.email has already been taken."
		]
	}
}
```

- Email verify api (From link in the verification email)
```
GET /api/verify/{id}/{hash}
Params:
- email 
- expires
- signature
Header:
- Accept: application-json

Example response:
- Http status 204 (Verified)
- Http status 401 (Invalid)
{
	"errors": {
		"title": "Unauthorized"
	}
}
```

- Resend email verification api 
```
POST /api/resend
Params:
- email
Header:
- Accept: application-json

Example response:
- Http status 204 (verified before)
- Http status 202 (Sent)
- Http status 401 (Invalid)
{
	"errors": {
		"title": "Unauthorized"
	}
}
```

- Request tokens api (IMPORTANT to use for other apis)
```
POST /api/oauth/token
Header:
- Accept: application-json

Example payload:
{
    "grant_type":  "password",
    "client_id": 2 // Can get after passport:install
	"client_secret":  "secret", // Can get after passport:install
	"username":  "test@david.com"
	"password": "password"
	"scope":  "*"
}

Example response:
- Http status 200 (Valid)
{
	"token_type":  "Bearer",
	"expires_in":  31536000,
	"access_token":  "access token",
	"refresh_token":  "refresh token"
}
- Http status 400 (Invalid grant)
```

- Refresh tokens api (For this app this api not really important, because the token doesn't expire so soon)
```
POST /api/oauth/token
Header:
- Accept: application-json

Example payload:
{
    "grant_type":  "refresh_token",
    "client_id": 2 // Can get after passport:install
	"client_secret":  "secret", // Can get after passport:install
	"refresh_token":  "refresh token"
	"scope":  "*"
}

Example response:
- Http status 200 (Valid)
{
	"token_type":  "Bearer",
	"expires_in":  31536000,
	"access_token":  "access token",
	"refresh_token":  "refresh token"
}
- Http status 400 (Invalid grant)
```

GET /api/users (For admin only)
```
Header:
- Accept: application-json
- Authorization: "Bearer access token"

Example response:
- Http status 200
{
	"data":  [
		{
			"id":  1,
			"name":  "Miss Lelia Brakus III",
			"email":  "magdalen.hermann@example.org",
			"email_verified_at":  "2020-08-22T15:50:19.000000Z",
			"type":  0,
			"created_at":  "2020-08-22T15:50:19.000000Z",
			"updated_at":  "2020-08-22T15:50:19.000000Z"
		},
		...
	]
}
- Http status 401 (Unauthorized)
{
	"errors":  {
		"title":  "Unauthorized"
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
	"title":  "Unauthenticated"
	}
}
```

GET /api/articles (For all users)
```
Header:
- Accept: application-json
- Authorization: "Bearer access token"

Example response:
- Http status 200 (response body change based role)
{
	"data":  [
		{
			"id":  1,
			"user_id":  4,
			"title":  "Nostrum porro aut molestiae a molestiae qui ut est enim voluptates omnis.",
			"description":  "Sint possimus qui dolorum assumenda. Eveniet numquam et aut commodi. Nostrum non iure magnam. Sed pariatur odio eum sint. Veritatis ut neque delectus vel aliquid eveniet explicabo. Quod debitis alias officia ea. Vitae sit fugit ipsa iste harum. Quam dignissimos facere suscipit recusandae consequatur mollitia. Tempora vel cumque molestiae atque est deserunt nihil. Aut magni consequatur quod magni et.",
			"created_at":  "2020-08-22T15:50:20.000000Z",
			"updated_at":  "2020-08-22T15:50:20.000000Z",
			"comments_count":  3 // Only appear when it's admin
		},
		...
	]
}
```

GET /api/articles/{id} (For all users)
```
Header:
- Accept: application-json
- Authorization: "Bearer access token"

Example response:
- Http status 200 (response body change based role)
{

	"data":  {
		"id":  1,
		"user_id":  4,
		"title":  "Nostrum porro aut molestiae a molestiae qui ut est enim voluptates omnis.",
		"description":  "Sint possimus qui dolorum assumenda. Eveniet numquam et aut commodi. Nostrum non iure magnam. Sed pariatur odio eum sint. Veritatis ut neque delectus vel aliquid eveniet explicabo. Quod debitis alias officia ea. Vitae sit fugit ipsa iste harum. Quam dignissimos facere suscipit recusandae consequatur mollitia. Tempora vel cumque molestiae atque est deserunt nihil. Aut magni consequatur quod magni et.",
		"created_at":  "2020-08-22T15:50:20.000000Z",
		"updated_at":  "2020-08-22T15:50:20.000000Z"
	}
}
```

DELETE /api/articles/{id} (For admin only)
```
Header:
- Accept: application-json
- Authorization: "Bearer access token"

Example response:
- Http status 200
{

	"data":  {
		"status":  false
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
		"title":  "Unauthorized"
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
	"title":  "Unauthenticated"
	}
}
```

POST /api/articles (For admin only)
```
Header:
- Accept: application-json
- Authorization: "Bearer access token"

Example payload:
{
	"data": {
		"title":  "test",
		"description":  "testing123"
	}
}

Example response:
- Http status 200
{
	"data":  {
		"user_id":  4,
		"title":  "test",
		"description":  "testing123",
		"updated_at":  "2020-08-23T11:14:38.000000Z",
		"created_at":  "2020-08-23T11:14:38.000000Z",
		"id":  16
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
		"title":  "Unauthorized"
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
	"title":  "Unauthenticated"
	}
}
```

PUT /api/articles/{id} (For admin only)
```
Header:
- Accept: application-json
- Authorization: "Bearer access token"

Example payload:
{
	"data": {
		"title":  "test",
		"description":  "testing123"
	}
}

Example response:
- Http status 200
{
	"status":  true,
	"data":  {
		"id":  16,
		"user_id":  4,
		"title":  "testing",
		"description":  "tseting12345",
		"created_at":  "2020-08-23T11:14:38.000000Z",
		"updated_at":  "2020-08-23T11:16:38.000000Z"
	}
}
- Http status 404 (article not found)
- Http status 401 (Unauthorized)
{
	"errors":  {
		"title":  "Unauthorized"
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
	"title":  "Unauthenticated"
	}
}
```

DELETE /api/comments/{id} (For admin and normal user only)
```
Header:
- Accept: application-json
- Authorization: "Bearer access token"

Example response:
- Http status 200 (If normal user only will be able to delete comments belong to the user)
{
	"status":  true
}
- Http status 401 (Unauthorized)
{
	"errors":  {
		"title":  "Unauthorized"
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
	"title":  "Unauthenticated"
	}
}
```

POST /api/comments (For admin and normal user only)
```
Header:
- Accept: application-json
- Authorization: "Bearer access token"

Example payload:
{
	"data": {
		"article_id":  1,
		"body": "content"
	}
}

Example response:
- Http status 200
{
	"data":  {
		"user_id":  4,
		"article_id":  16,
		"body":  "Hello world",
		"updated_at":  "2020-08-23T11:23:01.000000Z",
		"created_at":  "2020-08-23T11:23:01.000000Z",
		"id":  34
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
		"title":  "Unauthorized"
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
	"title":  "Unauthenticated"
	}
}
```

PUT /api/comments/{id} (For admin and normal user only)
```
Header:
- Accept: application-json
- Authorization: "Bearer access token"

Example payload:
{
	"data": {
		"body": "content"
	}
}

Example response:
- Http status 200
{
	"status":  true,
	"data":  {
		"id":  34,
		"user_id":  4,
		"article_id":  16,
		"body":  "testing1234",
		"created_at":  "2020-08-23T11:23:01.000000Z",
		"updated_at":  "2020-08-23T11:26:49.000000Z"
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
		"title":  "Unauthorized"
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
	"title":  "Unauthenticated"
	}
}
```

GET /api/blog/statistics (For admin only)
```
Header:
- Accept: application-json
- Authorization: "Bearer access token"

Example payload:
{
	"data": {
		"body": "content"
	}
}

Example response:
- Http status 200
{
	"data":  {
		"articles_count":  16,
		"comments_count":  34,
		"users_count":  12,
		"comments_count_per_user":  [
			{
			"id":  1,
			"name":  "Miss Lelia Brakus III",
			"email":  "magdalen.hermann@example.org",
			"email_verified_at":  "2020-08-22T15:50:19.000000Z",
			"type":  0,
			"created_at":  "2020-08-22T15:50:19.000000Z",
			"updated_at":  "2020-08-22T15:50:19.000000Z",
			"comments_count":  0
			},
			...
		]
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
		"title":  "Unauthorized"
	}
}
- Http status 401 (Unauthorized)
{
	"errors":  {
	"title":  "Unauthenticated"
	}
}
```
