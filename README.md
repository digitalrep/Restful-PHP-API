# Simple PHP RESTful API
Thought it might be fun to try to make a simpler version of a Laravel RESTful api

## How to use

### Tokens
- When you access bill or biller, add Authorization Header with value Bearer: token_goes_here
- It will provide you with a refreshed token after each request
- If you don't use the refreshed token the original token will run out after 9 minutes

### Set up
- This was built for apache2
- Point your DocumentRoot in conf file at the 'public' directory
- Make sure the database folder is owned by www-data

## Problems I encountered building it (so far):
1. The base64 encoding adds forward slashes which are then escaped so when you bring a token in via a URL you get \\/ but when you try to verify it, you're verifying against / so you have to cleanse the token and remove instances of / first.
2. The database folder itself (as well as the sqlite database file) must be owned by www-data or the database will open but you won't be able to write to it. 
3. When sending a PUT request using POSTMAN, check the x-www-form-urlencoded option rather than the form-data option otherwise the PUT request vars won't be intercepted. 
