// main.js

let map;
let userLocation;
let professionalLocation;

function initMap() {
  // Initialize the map
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: { lat: 0, lng: 0 },
    styles: [
      {
        featureType: "poi",
        elementType: "labels",
        stylers: [{ visibility: "off" }]
      }
    ]
  });

  const geocoder = new google.maps.Geocoder();
  // Use a global variable or a data attribute to set the professional address
  const professionalAddress = window.professionalAddress || "Default Address";

  geocoder.geocode({ address: professionalAddress }, (results, status) => {
    if (status === 'OK') {
      professionalLocation = results[0].geometry.location;

      new google.maps.Marker({
        map: map,
        position: professionalLocation,
        title: window.professionalName || "Professional",
        icon: {
          url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
        }
      });

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            userLocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            new google.maps.Marker({
              map: map,
              position: userLocation,
              title: "Your Location",
              icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
              }
            });

            const bounds = new google.maps.LatLngBounds();
            bounds.extend(professionalLocation);
            bounds.extend(userLocation);
            map.fitBounds(bounds);

            calculateDistance(userLocation, professionalLocation);
          },
          (error) => {
            console.error("Error getting user location:", error);
            map.setCenter(professionalLocation);
          }
        );
      } else {
        map.setCenter(professionalLocation);
      }
    } else {
      console.error('Geocode was not successful:', status);
    }
  });
}

function calculateDistance(from, to) {
  const service = new google.maps.DistanceMatrixService();
  service.getDistanceMatrix(
    {
      origins: [from],
      destinations: [to],
      travelMode: google.maps.TravelMode.DRIVING,
      unitSystem: google.maps.UnitSystem.METRIC
    },
    (response, status) => {
      if (status === 'OK') {
        const distance = response.rows[0].elements[0].distance.text;
        const duration = response.rows[0].elements[0].duration.text;
        const distanceElement = document.getElementById('distance');
        if (distanceElement) {
          distanceElement.innerHTML = `${distance} away (${duration} by car)`;
        }
      }
    }
  );
}

window.addEventListener("load", initMap);
