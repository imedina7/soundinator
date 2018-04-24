<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Soundinator - The Official Botonera for The πbes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/normalize.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/simplegrid.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
  <?php if (getenv('CURRENT_ENV') == 'production') { ?>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
  <?php } else { ?>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
  <?php } ?>

</head>
<body>
  <div id="app" class="principal">
    <div class="navigation">
      <h1>{{ title }}</h1>
    </div>
    <div class="columnas">
      <div v-if="! loggedIn">
        <p style="display: inline-block">Looks like you are not logged in! <a href="#" @click="loginform = !loginform">Log in</a> first to see your sounds and add new ones.</p>
        <form v-if="loginform" id="loginForm" v-on:submit.prevent="onSubmit"/>
          <input type="text" name="username" id="username" placeholder="Username..."/>
          <input type="password" name="password" id="password" placeholder="Password..."/>
          <input type="submit" name="submit" id="submit" value="Login">
        </form>
      </div>
      <p v-if="loggedIn">So you are logged in, you can always <a href="#" @click="logout()">log out</a>, or <a href="#" @click="uploadDialog = !uploadDialog">upload</a> a file.</p>
      
      <div v-if="uploadDialog && loggedIn">
        <form enctype="multipart/form-data" v-on:submit.prevent="uploadFiles" id="uploadForm">
          <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
          <input name="userfile" type="file[]" accept="audio/*" multiple/>
          <input type="submit" value="Upload" />
        </form>
      </div>
      
      <ul class="soundList" v-if="loggedIn">
        <li v-for="sound in soundList">
          <a href="#" class="soundButton" :data-sound-id="sound.id" v-bind:class="{ 'playing' : sound.isplaying }" v-on:click="sound.play()">{{sound.name}}</a>
        </li>
      </ul>
    </div>
  </div>
  <script src="assets/js/main.js"></script>
</body>
</html>