<html>
    <head>
        <title>BlindTest - Anim</title>
        <meta charset="UTF-8">
        <link href="../assets/css/general.css" rel="stylesheet">
        <link rel="icon" href="../assets/images/logo.ico">
    </head>
    <body>
        <div id="chrono">40</div>
        <div id="end-screen"></div>
        <div id="reponse-screen"></div>
        <div id="answer-zone">
            <input type="text" name="answer" id="answer" onkeyup="checkPause()"><br>
            <button id="answer-button" onclick="checkAnswer()">Tenter le coup</button><br>
            <button id="skip-button" onclick="skip()">skip</button>
        </div>
        <div id="infos">
            Points : <a id="points">0</a><br>
            Round : <a id="round">1</a>
        </div>
        <div id="player"></div>
    </body>
</html>

<script type="text/javascript" src="../assets/javascript/music.js"></script>
<script type="text/javascript" src="../assets/javascript/parametres.js"></script>
<script>
    // Variables de base
    chrono = document.getElementById("chrono");
    time = temps_par_round;
    var interval = setInterval(changeTime, 1000);
    pause = false;
    randomMusic = Math.floor(Math.random() * musicList.length);
    dejaJoue = [randomMusic];
    idVid = musicList[randomMusic][1];
    rightAnswer = musicList[randomMusic][0];
    round = 1;
    maxRound = nombre_total_de_round;
    document.getElementById("round").innerHTML = round + "/" + maxRound;
    score = 0;
    end = false;
    
    // YOUOTUBE API
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          height: '0',
          width: '0',
          videoId: ''+idVid,
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }
      function onPlayerReady(event) {
        event.target.playVideo();
      }

      var done = false;
      function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
          done = true;
        }
      }

      function pauseVideo() {
        player.pauseVideo()
      }

      function playVideo() {
        player.playVideo()
      }

      function stopVideo() {
        player.stopVideo()
      }


    // Mes fonctions
    function checkPause(){
        if(document.getElementById("answer").value != ""){
            pause = true;
            pauseVideo();
        }
        else{
            pause = false;
            playVideo();
        }
    }

    function changeTime(){
        if(pause==false){
            if(time>0){
                time = time - 1;
                chrono.innerHTML = time;
            }
            else{
                if((round+1)>10){
                    afficheFin();
                    pause = true;
                }
                else{
                    round+=1;
                    score-=15;
                    stopVideo();
                    afficheReponse()
                    document.getElementById("points").innerHTML = score;
                    document.getElementById("round").innerHTML = round + "/"+ maxRound;
                }
            }
        }
    }

    function checkAnswer(){
        rep = document.getElementById("answer");
        if(rep.value.toLowerCase() == rightAnswer){
            score+=time;
            if((round+1)>10){
                afficheFin();
                pause = true;
            }
            else{
                round+=1;
                rep.value = "";
                afficheReponse()
            }
        }
        else{
            score-=5;
        }
        document.getElementById("points").innerHTML = score;
        document.getElementById("round").innerHTML = round + "/"+ maxRound;
    }

    function nextMusic(){
        document.getElementById("reponse-screen").innerHTML = "";
        document.getElementById("answer-zone").innerHTML = '<input type="text" name="answer" id="answer" onkeyup="checkPause()"><br><button id="answer-button" onclick="checkAnswer()">Tenter le coup</button><br><button id="skip-button" onclick="skip()">skip</button>';
        pause = false;
        time = temps_par_round;
        randomMusic = Math.floor(Math.random() * musicList.length);
        isNew = false;
        while(isNew==false){
            isNew = true;
            dejaJoue.forEach(function(item, index, array){
                if(randomMusic == item){
                    isNew = false;
                }
            });
            if(isNew == false){
                randomMusic = Math.floor(Math.random() * musicList.length);
            }
        }
        add = dejaJoue.push(randomMusic);
        idVid = musicList[randomMusic][1];
        rightAnswer = musicList[randomMusic][0];
        player.loadVideoById(""+idVid);
    }

    function afficheFin(){
        document.getElementById("end-screen").innerHTML = '<h1 id="titre-fin">FIN</h1><h2 id="titre-score">Votre score est '+score+'</h3><a href="accueil"><button class="future-button fin">Rejouer</button></a>';
        document.getElementById("answer").remove();
        document.getElementById("answer-button").remove();
        end = true;
        afficheReponse();
    }

    function afficheReponse(){
        pause = true;
        if(end==false){
            document.getElementById("reponse-screen").innerHTML = '<h1>REPONSE</h1><h2 id="nom-reponse">'+rightAnswer+'</h2><button class="future-button fin" onclick="nextMusic()">Suivant</button>';
        }
        else{
            document.getElementById("reponse-screen").innerHTML = '<h1>REPONSE</h1><h2 id="nom-reponse">'+rightAnswer+'</h2>';
        }
        document.getElementById("answer-zone").innerHTML = "";
    }

    function skip(){
        time = 0;
        pause = 0;
        player.playVideo();
    }

    playVideo();
</script>