<h2>laravel-chat-app</h2>
<p>
	A realtime chat app built with Laravel 5.7, vue.js , redise and socket-io. We can send  text messages through this app.
   
</p>
 
<h5>common use</h5>
<ol>
<li>pbulic chat</li>
<li>room chat</li>
<li>private chat</li>
<li>notifications</li>
</ol>
    
<p align="center">
    <img src="https://i.imgur.com/3NTws6n.png">
     <img src="https://i.imgur.com/RYtQff1.png">
     <img src="https://i.imgur.com/sWfu7Z7.png">
</p>
<p><g-emoji alias="speech_balloon" fallback-src="https://assets-cdn.github.com/images/icons/emoji/unicode/1f4ac.png" ios-version="6.0">
<h3>Project Installation</h3>
<ol>
<li>Clone repo / download project.</li>
<li>Open cmd and go to your project directory folder.</li>
<li>Install composer dependencies <code>composer install</code>.</li>
<li>Install node dependencies <code>npm install</code>.</li>
<li>Create <code>.env</code> file with environment variables,or you can rename <code>.env.example</code> file to <code>.env</code>.</li>
<li>Set database connection in <code>.env</code> file.</li>
    <li>almost install with => composer install  and npm install 
    <ul>
        <li> install Redis</li>
<li> install Laravel Echo </li>
<li> install Socket.IO </li>
        </ul>
    </li>

<li>
Set following things in <code>.env</code> file
	<ul>
		<li><code>BROADCAST_DRIVER=redis</code></li>
		<li><code>CACHE_DRIVER=redis</code></li>
		<li><code>QUEUE_CONNECTION=redis</code></li>
		<li><code>SESSION_DRIVER=redis</code></li>
        <li><code>SESSION_LIFETIME=120</code></li>
        <li><code>SESSION_DOMAIN=127.0.0.1</code></li>
        <li><code>REDIS_HOST=127.0.0.1</code></li>
        <li><code>REDIS_PASSWORD=null</code></li>
        <li><code>REDIS_PORT=6379</code></li>
	</ul>

</li>

<li>Run migrations to generate tables in your database <code>php artisan migrate</code>.</li>
<li>
	Open cmd and go to your project directory folder and run <code>php artisan storage:link</code> to access to image.
</li>
<li>
	Open cmd and go to your project directory folder and run <code>npm run watch</code>.
</li>
</ol>

<p>Enjoy chatting. Would be grateful for your feedback.</p>
