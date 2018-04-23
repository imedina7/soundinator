var Sound = function (name){
  this.name = name || "Sound 1";
  this.id = undefined;
  this.contentType = "audio/mpeg"
  this.blob = null;
  this.blobUrl = null;
  this.playing = false;
}

Sound.prototype.isLoaded = function () {
  return this.blob != null;
}

Sound.prototype.getBlobUrl = function () {
  if (this.isLoaded()){
    return this.blobUrl;
  }
}

Sound.prototype.play = function () {
  var snd = this;
  var player = new Audio();
  player.src = this.getBlobUrl();
  player.play().then(function(){
    snd.playing = true;
  });
  player.onended = function () {
    snd.playing = false;
    player = null;
  }
}

function b64toBlob(b64Data, contentType, sliceSize) {
  contentType = contentType || '';
  sliceSize = sliceSize || 512;

  var byteCharacters = atob(b64Data);
  var byteArrays = [];

  for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
    var slice = byteCharacters.slice(offset, offset + sliceSize);

    var byteNumbers = new Array(slice.length);
    for (var i = 0; i < slice.length; i++) {
      byteNumbers[i] = slice.charCodeAt(i);
    }

    var byteArray = new Uint8Array(byteNumbers);

    byteArrays.push(byteArray);
  }
      
  var blob = new Blob(byteArrays, {type: contentType});
  return blob;
}


( function () {
  
  var sounds = new Array();
  var req = new Request.Request("/api.php",{ method: "GET", body: { SESSION_ID: localStorage.SESSION_ID, action: "getSounds" }});
  fetch(req).then(function (response){
    return response.json();
    
  }).then(function (json_response) {
      var soundList = json_response.soundList;
      soundList.forEach(function (element) {
        var currentSound = new Sound(element.name);
        currentSound.id = element.id;
        currentSound.blob = b64toBlob(element.data,element.contentType,1024);
        currentSound.blobUrl = URL.createObjectURL(currentSound.blob);
        sounds.push(currentSound);
      })
      
  }).catch(function(error){
      console.log("no funcionó: "+ error);
  });
  
  const App = new Vue({
    el: "#app",
    data: {
      title: "Welcome!",
      soundList: sounds
    }
  });
})();