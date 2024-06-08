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
    <a href="#" class="navbar-brand">Mapa</a>
    <button id="installPwaWebAPP">Instalar Aplicativo</button>
    <div id="user_identification_div">
      <span>👤</span>
      <a href="#">User Name</a>
    </div>
  </nav>

  <div id="drawerMenu" class="drawer">
    <h2>MENU PRINCIPAL</h2>
    <button onclick="geoFindMe()">Procurar sua localização</button>
  </div>

  <div class="container">
    <div id="map"></div>
    <button id="find-me" class="btn btn-primary mt-3">Procurar sua localização</button>
    <p id="status"></p>
    <a id="map-link" target="_blank"></a>
  </div>

  <script>
    if ('serviceWorker' in navigator )=> {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('./ServiceWorker.js').then(registration => {
          console.log('ServiceWorker registrou: ', registration.scope);
        }, err => {
          console.log('ServiceWorker falhou : ', err);
        });
      });
    }

    let deferredPrompt;

    window.addEventListener('beforeinstall', (e) => {
      e.preventDefault();
      deferredPrompt = e;
      document.getElementById('installPwa').style.display = 'block';
    });

    document.getElementById('installPwa').addEventListener('click', (e) => {
      document.getElementById('installPwa').style.display = 'none';
      deferredPrompt.prompt();
      deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
          console.log('Usuário aceitou instalar o PWA');
        } else {
          console.log('Usuário rejeitou instalar o PWA');
        }
        deferredPrompt = null;
      });
    });

    document.getElementById('btnToggleDrawer').addEventListener('click', function () {
      var drawer = document.getElementById('drawerMenu');
      if (drawer.classList.contains('active')) {
        drawer.classList.remove('active');
      } else {
        drawer.classList.add('active');
      }
    });

    function geoFindMe() {
      const status = document.querySelector("#status");
      const mapLink = document.querySelector("#map-link");

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
        var marker = L.marker([latitude, longitude]).addTo(map);
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

    document.querySelector("#find-me").addEventListener("click", geoFindMe);
  </script>
</body>

</html>
