<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Soundinator - The Real Botonera for The πbes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet">
  <link href="assets/css/fontawesome-all.min.css" rel="stylesheet" />
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
      <ul class="links">
        <li v-if="loginform && !loggedIn">
          <form v-if="loginform" id="loginForm" v-on:submit.prevent="onSubmit"/>
            <input type="text" name="username" id="username" placeholder="Username..."/>
            <input type="password" name="password" id="password" placeholder="Password..."/>
            <input type="submit" name="submit" id="submit" value="Login">
          </form>
        </li>
        <li>
          <form enctype="multipart/form-data" v-on:submit.prevent="uploadFiles" id="uploadForm">
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
            <input name="userfile[]" type="file" accept="audio/*, application/ogg" id="file-upload" multiple/>
            
            <input v-if="uploadDialog && loggedIn" type="submit" value="Upload" />
          </form>
        </li>
        <li v-if="! loggedIn">
          <a href="#" @click="loginform = !loginform"><i class="fas fa-sign-in-alt"></i></a>
        </li>
        <li v-if="loggedIn"><a href="#" @click="uploadDialog = !uploadDialog"><label for="file-upload"><i class="fas fa-upload"></i></label></a></li>
        <li v-if="loggedIn"><a href="#" @click="logout()"><i class="fas fa-sign-out-alt"></i></a></li>
      </ul>
    </div>
    <div class="columnas main-view">
      <ul class="soundList" v-if="loggedIn">
        <li v-for="sound in soundList">
          <button class="soundButton" 
             :id="'sound-'+sound.id" 
             v-bind:class="{ 'playing' : sound.playing }" 
             @click="playSound(sound)"><span v-bind:style="{ 'width': sound.progress + '%'}"></span>{{sound.name}}<canvas :id="'soundCanvas-'+sound.id"></canvas></button>
        </li>
      </ul>
    </div>
  </div>
  <script src="assets/js/main.js"></script>
</body>
</html>