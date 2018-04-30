<template>
<button class="soundButton" 
             :data-sound-id="sound.id" 
             v-bind:class="{ 'playing' : sound.isplaying }" 
             v-on:click="sound.play()">{{sound.name}}</button>
</template>

<script>
module.exports = {
  props: [sound],
  data: function () {
    return {
      sound: sound
    }
  }
}
</script>

<style scoped>

button {
    display: inline-block;
    border: 0;
    position:relative;
    width: 100%;
    font-size: 1.4em;
    text-align: center;
    background-color: #555;
    color: #AAA;
    padding: 20px 0;
    margin-right: 10px;
    margin-bottom: 10px;
}

button::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 20%;
    content:'';
    display: none;
}

button.playing::before {
    display: block;
    background-color: rgba(162, 255, 162, 0.363);
}
</style>
