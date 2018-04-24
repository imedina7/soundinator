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
  const App = new Vue({
    el: "#app",
    data: {
      loggedIn: loggedIn,
      loginform: false,
      title: "Welcome!",
      soundList: []
    },
    methods: {
      onSubmit: function(event){
        var self = this;
        var fdata = new FormData(document.getElementById("loginForm"));
    
        var req = new Request("/login.php",{ method: "POST", body: fdata});
        fetch(req).then(function (response){
          return response.json();
          
        }).then(function (json_response) {
            
            if (typeof json_response.session_id === "undefined") {
              console.log(json_response.error);
            } else {
              localStorage.setItem("session_id", json_response.session_id);
              console.log("session_id = " + json_response.session_id);
              self.loggedIn = true;
              loadSounds();
            }
        }).catch(function(error){
            console.log("no funcionó: "+ error);
        });
      },
      logout: function () {
        var self = this;
        if (localStorage.getItem("session_id") != null){
          var fdata = new FormData();
          fdata.append("SESSION_ID",localStorage.getItem("session_id"));
      
          var req = new Request("/login.php?logout",{ method: "POST", body: fdata});
          fetch(req).then(function (response){
            return response.json();
            
          }).then(function (json_response) {
            if (typeof json_response.status === "undefined") {
              console.log(json_response.error);
            } else {
              localStorage.removeItem("session_id");
              console.log("status = " + json_response.status);
              self.loggedIn = false;
              self.soundList = [];
            }
          }).catch(function(error){
              console.log("no funcionó: "+ error);
          });
        }
      },
      loadSounds: function (){
        ctx = this;
        if (localStorage.getItem("session_id") != null){
          ctx.loggedIn = true;
          var fdata = new FormData();
          fdata.append("SESSION_ID",localStorage.getItem("session_id"));
    
          var req = new Request("/api.php",{ method: "POST", body: fdata});
          fetch(req).then(function (response){
            return response.json();
            
          }).then(function (json_response) {
              var soundList = json_response.soundList;
              soundList.forEach(function (element) {
                var currentSound = new Sound(element.name);
                currentSound.id = element.id;
                currentSound.blob = b64toBlob(element.data,element.contentType,1024);
                currentSound.blobUrl = URL.createObjectURL(currentSound.blob);
                ctx.soundList.push(currentSound);
              })
              
          }).catch(function(error){
              console.log("no funcionó: "+ error);
          });
        }
      }
    },
    mounted(){
      this.loadSounds();
    }
  });
})();