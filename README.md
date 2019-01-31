Indorse

This is Friend finder API for Indorse evaluation by Benjamin

Usage
1. Clone the repository, copy .env.example to .env file and supply app_key, jwt_key and database details OR

2. Fire postman or your favorite rest client and use https://indorse.ibenjaminke.com/

Functionality
1. Signup (/api/auth/register) - expects: first_name, last_name, username, email, password. Returns (201): message and token.
2. Login (/api/auth/login) - expects email and password. Returns (200): message and token

NB: For the below endpoints, token must be included as a parameter in the query string e.g 
https://indorse.ibenjaminke.com/api/profile/jdeepay?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJJbmRvcnNlIGJ5IEJlbmphbWluIiwic3ViIjoiamRlZXBheUB0ZXN0LmNvbSIsImlhdCI6MTU0ODk2MjY1MywiZXhwIjoxNTQ5MTc4NjUzfQ.Lixto700aOPMPQGqzDT6abU1Cn0ZQ4bAXAD8b5nusII

3. Create Profile (/api/profile/create) - expects: about, school, phone, status. Returns (201): message and profile (json)
4. Update Profile (/api/profile/update) - expects similar payload as create. Returns the same as create
5. Get profile (/api/profile/{username}) - No payload expected. Returns a profile. Note: User can only get their own profile else 403
6. List users (/api/users) - No payload. Returns all users
7. Update user account (/api/users/update) - Updates the user account of the user bearing the token. Expects: first_name, last_name, username, email. Returns (201), user (json)
8. Get one user (/api/users/{username}) - no payload. Returns (200) with user (json)
9. Delete User account (/api/users/{username}/delete) - no payload. Deletes account of the user bearing the token. Returns (200) and message
10. Befriend user (/api/friends/befriend/{username}) - befriends the user whose username is provided. Returns (201) and message
11. Unfriend user (/api/friends/unfriend/{username}) - unfriends user whose username is provided. Returns (201) and message
