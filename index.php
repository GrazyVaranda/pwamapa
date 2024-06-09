<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="imgs/myicon.png">
  <title>Mapa Localização</title>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <link rel="manifest" href="./manifest.json">
  <link rel="stylesheet" href="./style/style.css">
  
</head>

<body>
  <nav class="navbar">
    <button id="btnToggleDrawer" class="navbar-toggler">☰</button>
    </div>
  </nav>

  <div id="drawerMenu" class="drawer">
    <h2>MENU PRINCIPAL</h2>
    <button onclick="geoFindMe()">Procurar sua localização</button>
    <div class="drawer-footer">
      <button id="btnCloseDrawer" class="btn btn-primary">Fechar</button>
    </div>
  </div>

  <div class="container">
    <div id="map"></div>
    <button id="find-me" class="btn btn-primary mt-3">Procurar sua localização</button>
    <p id="status"></p>
    <a id="map-link" target="_blank"></a>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const drawer = document.getElementById('drawerMenu');
      const btnToggleDrawer = document.getElementById('btnToggleDrawer');
      const findMeButton = document.getElementById('find-me');
      const status = document.getElementById('status');
      const mapLink = document.getElementById('map-link');
      const installPwaButton = document.getElementById('installPwaWebAPP');

      let deferredPrompt;
      btnToggleDrawer.addEventListener('click', () => {
        drawer.classList.toggle('active');
      });

      btnCloseDrawer.addEventListener('click', () => {
        drawer.classList.remove('active');
      });
      
     document.addEventListener('click', (event) => {
      const isClickInsideDrawer = drawer.contains(event.target);
      const isClickOnToggle = btnToggleDrawer.contains(event.target);

      if (!isClickInsideDrawer && !isClickOnToggle) {
      drawer.classList.remove('active');
        }
      });
    
      function geoFindMe() {
        mapLink.href = "";
        mapLink.textContent = "";

        function success(position) {
          const latitude = position.coords.latitude;
          const longitude = position.coords.longitude;

          status.textContent = "";
          mapLink.href = `https://www.openstreetmap.org/#map=18/${latitude}/${longitude}`;
          mapLink.textContent = `Latitude: ${latitude} ° , Longitude: ${longitude} °`;

          var map = L.map('map').setView([latitude, longitude], 16);
          L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
          }).addTo(map);

          L.marker([latitude, longitude]).addTo(map);
        }

        function error() {
          status.textContent = "Não foi possível acessar sua localização";
        }

        if (!navigator.geolocation) {
          status.textContent = "Geolocalização não é suportada em seu navegador";
        } else {
          status.textContent = "Localizando...";
          navigator.geolocation.getCurrentPosition(success, error);
        }
      }

      findMeButton.addEventListener('click', geoFindMe);

        /* Não estou conseguindo abrir meu projeto no vercel pois com a aplicação de baixar o webapp ele somente baixa no celular, mas não é suportado,então irei mandar as duas versões */
      
        //window.addEventListener('beforeinstallprompt', (e) => {
        //e.preventDefault();
        //deferredPrompt = e;
        //installPwaButton.style.display = 'block';
     // });

     // installPwaButton.addEventListener('click', () => {
        //installPwaButton.style.display = 'none';
        //deferredPrompt.prompt();
        //deferredPrompt.userChoice.then((choiceResult) => {
          //if (choiceResult.outcome === 'accepted') {
            //console.log('Usuário aceitou instalar o PWA');
          //} else {
            //console.log('Usuário rejeitou instalar o PWA');
          //}
          //deferredPrompt = null;
        //});
      ///});

    });

    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('./serviceWorker.js')
          .then(registration => {
            console.log('ServiceWorker registrado com sucesso:', registration.scope);
          })
          .catch(error => {
            console.log('Falha ao registrar o ServiceWorker:', error);
          });
      });
    }
  </script>
</body>

</html>
