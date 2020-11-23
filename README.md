## Intro
#### This service introduces simple implementation of DDD, CQRS and ARR in Symfony. 
##### DDD - https://martinfowler.com/tags/domain%20driven%20design.html
##### CQRS - https://martinfowler.com/bliki/CQRS.html
##### ARR - https://docs.microsoft.com/en-us/azure/architecture/patterns/async-request-reply
#####

# Installation
<ol>
    <li>Make sure You have docker and docker-compose installed (3.8v file support)</li>
    <li>Create local image registry and specify it in Makefile and docker-compose (or use existing one if You have any)</li>
    <li>Run <b>make build</b></li>
    <li>If build completes run <b>make up</b></li>
    <li>Run <b>make console</b> to enter application console</li>
    <li>In application console run <b>composer install</b></li>
</ol>


# JSON API - prefixed with /api
<h2>Posts</h2>
<h4>GET /posts</h4>
<ul>
    <li>get list of posts</li>
    <li>paginated by default</li>
    <li>optional query params: <b>int|string page</b> and <b>int|string limit</b></li>
</ul>
<h4>GET /posts/{id}</h4>
<ul>
    <li>get specific post</li>
</ul>   
<h4>POST /posts</h4>
<ul>
    <li>accepts request and returns http 202</li>
    <li>returns json with redirect link</li>
    <li>returns Location header set to redirect link</li>
    <li>required fields: <b>string postTitle</b> and <b>string postContent</b></li>
</ul>
<h4>POST /posts/{id}</h4>
<ul>
    <li>update specific post</li>
    <li>returns json with redirect link </li>
    <li>required fields: <b>string postId</b>
</ul>
<h4>DELETE /posts/{id}</h4>
<ul>
    <li>delete specific post</li>
    <li>paginated by default</li>
    <li>query params: <b>int|string postId</b>
</ul>

<h2>Comments</h2>
<h4>GET /comments</h4>
<ul>
    <li>get list of comments</li>
    <li>paginated by default</li>
    <li>optional query params: <b>int|string page</b> and <b>int|string limit</b></li>
</ul>
<h4>GET /posts/{postId}/comments/{commentId}</h4>
<ul>
    <li>get specific comment</li>
    <li>if not found -> returns http 404</li>
    <li>query params: <b>int|string page</b> and <b>int|string limit</b></li>
</ul>   
<h4>POST /posts/{postId}/comments</h4>
<ul>
    <li>accepts request and returns http 202</li>
    <li>returns json with redirect link</li>
    <li>returns Location header set to redirect link</li>
    <li>required fields: <b>int|string postId</b> and <b>string commentContent</b></li>
</ul>
<h4>POST /posts/{postId}/comments/{commentId}</h4>
<ul>
    <li>update specific comment</li>
    <li>Authorization API key, using query param e.g: <b></b></li>
    <li>returns json with redirect link </li>
    <li>required fields: <b>int|string postId</b> <b>int|string commentId</b>
</ul>
<h4>DELETE /posts/{postId}/comments/{commentId}</h4>
<ul>
    <li>delete specific comment</li>
    <li>Authorization API key, using query param e.g: <b></b></li>
    <li>query params: <b>int|string postI</b> and <b>int|string commentId</b>
</ul>

# Flow
#### Basic flow follows ARR pattern: 
<ol>
    <li>send post</li>
    <li>accept</li>
    <li>redirect client to status endpoint</li>
    <li>keep returning 202 till the job is done</li>
    <li>after job is done, return 302 redirect to resource</li>
</ol>



