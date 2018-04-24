<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Soundinator - The Official Botonera for The πbes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
  <?php if (getenv('CURRENT_ENV') == 'production') { ?>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
  <?php } else { ?>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
  <?php } ?>

</head>
<body>
  <div id="app">
    <nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#">Fixed navbar</a>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="#">Disabled</a>
          </li>
        </ul>
        <form class="form-inline mt-2 mt-md-0">
          <input class="form-control mr-sm-2" type="text" placeholder="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>
    </nav>

    <div class="container">
      <div class="jumbotron">
        <h1>{{ title }}</h1>
        <div v-if="! loggedIn">
          <p class="lead">Looks like you are not logged in! <a href="#" @click="loginform = !loginform">Log in</a> first to see your sounds and add new ones.</p>
          <form v-if="loginform" id="loginForm" v-on:submit.prevent="onSubmit">
            <input type="text" name="username" id="username" placeholder="Username...">
            <input type="password" name="password" id="password" placeholder="Password...">
            <input type="submit" name="submit" id="submit" value="Login">
          </form>
        </div>
        <div v-if="loggedIn">So you are logged in, you can always <a href="#" @click="logout()">log out</a></div>
        <ul>
          <li v-for="sound in soundList">
            <a href="#" class="soundButton" :data-sound-id="sound.id" v-bind:class="{ 'playing' : sound.isplaying }" v-on:click="sound.play()">{{sound.name}}</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <script src="assets/js/main.js"></script>
</body>
</html>