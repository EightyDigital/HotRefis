// Issues with twig so need to change start and end tag to {[]}
var pgApp = angular.module('refis', ['google-maps', 'xeditable', 'highcharts-ng']).config(function($interpolateProvider){
       $interpolateProvider.startSymbol('{[').endSymbol(']}');
   }
);

// Radius distance
pgApp.factory('distance__service', function($rootScope) {
  var distance = {};

  distance.meters = '';

  distance.prepForBroadcast = function(value) {
    this.meters = value;
    this.broadcastItem();
  };

  distance.broadcastItem = function() {
    $rootScope.$broadcast('distanceBroadcast');
  };

  return distance;
});


// Shared variable for origin point
pgApp.factory('origin__service', function($rootScope) {
  var origin = {};

  // Add this to scope incase value changes
  //Default
  origin.latitude = 1.30208;
  origin.longitude = 103.81984;
  origin.fullAddress = '123 test street';

  origin.prepForBroadcast = function(latitude_value, longtitude_value, address_value) {
    origin.latitude = latitude_value;
    origin.longitude = longtitude_value;
    origin.fullAddress =  address_value;
  };

  origin.broadcastItem = function() {
    $rootScope.$broadcast('originBroadcast');
  };

  return origin;

});

var map_controller = pgApp.controller('map__controller', function($scope, $http, distance__service, origin__service) {

  $scope.originMarker = {};

  // Map: Create an array of styles.
  var styles = [
    {
      stylers: [
        { saturation: -20 }
      ]
    },{
      featureType: "road",
      elementType: "geometry",
      stylers: [
        { lightness: 100 },
        { visibility: "simplified" }
      ]
    },{
      featureType: "road",
      elementType: "labels"
    }
  ];

  var heatMapData = [
    { location: new google.maps.LatLng(1.372312, 103.764122), weight: 100},
                new google.maps.LatLng(1.32561, 103.837525),

    {location: new google.maps.LatLng(1.3186506, 103.7812757), weight: 100},
    {location: new google.maps.LatLng(1.325835114, 103.9316646), weight: 100},
    {location: new google.maps.LatLng(1.32561, 103.837525), weight: 90},
    new google.maps.LatLng(1.2856911948692, 103.82977982275),
    {location: new google.maps.LatLng(1.32561, 103.837525), weight: 100},

    {location: new google.maps.LatLng(1.2857484727176, 103.82975128499), weight: 90},
    {location: new google.maps.LatLng(1.2857199408323, 103.82975128237), weight: 100},
    new google.maps.LatLng(1.2857197267548, 103.82975128499),
    {location: new google.maps.LatLng(1.3161729386751, 103.83087515831), weight: 100},
    new google.maps.LatLng(1.349282, 103.8669025898),
    {location: new google.maps.LatLng(1.3909770297858, 103.91250733174), weight: 100},
    {location: new google.maps.LatLng(1.2708745, 103.8133722), weight: 90}
  ];

  // Create a new StyledMapType object, passing it the array of styles,
  // as well as the name to be displayed on the map type control.
  var styledMap = new google.maps.StyledMapType(styles,
    {name: "Styled Map"});

  var mapDOMElement = document.getElementById('heatmap'),
  control_state = true,
  iconBase = 'https://maps.google.com/mapfiles/kml/shapes/',
  mapOptions = {
    zoom: 12,
    draggable: control_state,
    panControl: control_state,
    zoomControl: control_state,
    mapTypeControl: control_state,
    scaleControl: control_state,
    streetViewControl: control_state,
    overviewMapControl: control_state,
    scrollwheel: control_state,
    keyboardShortcuts: control_state,
    clickableLabels: control_state,
    disableDoubleClickZoom: false,
    center: new google.maps.LatLng(origin__service.latitude, origin__service.longitude),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
    }
  };

  // Create a new 'Map' instance
  $scope.map = new google.maps.Map(mapDOMElement, mapOptions);
  $scope.map.mapTypes.set('map_style', styledMap);
  $scope.map.setMapTypeId('map_style');

  // Create my marker bin!
  $scope.markers = [];

  // Create my listings bin!
  $scope.listings = [];

  // On click we show this info window
  $scope.infoWindow = new google.maps.InfoWindow();

  // get markers was here


  $scope.originMarker = new google.maps.Marker({
      map: $scope.map,
      position: new google.maps.LatLng(origin__service.latitude, origin__service.longitude),
      title: origin__service.fullAddress,
      icon: iconBase + 'schools_maps.png',
      animation: google.maps.Animation.DROP

  });

  $scope.mapCenter = $scope.map.getCenter();

  // Lets create my origin position
  var createOrigin = function (originMarker){
    originMarker.content = '<div class="infoWindowContent">Test Content</div>';

    google.maps.event.addListener(originMarker, 'click', function(){
        $scope.infoWindow.setContent('<h2>' + originMarker.title + '</h2>');
        $scope.infoWindow.open($scope.map, originMarker);
    });

    //$scope.markers.push(originMarker);
    $scope.originMarker = originMarker;
    $scope.circle = new google.maps.Circle({
      map: $scope.map,
      radius: 3000,    // km in metres
      strokeColor: '#333',
      strokeOpacity: 0.4,
      strokeWeight: 2,
      fillColor: '#333',
      fillOpacity: 0.35
    });

    $scope.circle.bindTo('center', $scope.originMarker, 'position');
  }

  var createRadius = function (marker, meters){
      // Add circle overlay and bind to originMarker
      $scope.circle.setMap(null);

      switch(meters) {
        case 3000:
          $scope.map.setZoom(13);
          break;
        case 2750:
          $scope.map.setZoom(14);
          break;
        case 2500:
          $scope.map.setZoom(14);
          break;
        case 2250:
          $scope.map.setZoom(14);
          break;
        case 2000:
          $scope.map.setZoom(14);
          break;
        case 1750:
          $scope.map.setZoom(15);
          break;
        case 1500:
          $scope.map.setZoom(15);
          break;
        case 1250:
          $scope.map.setZoom(15);
          break;
        case 1000:
          $scope.map.setZoom(15);
          break;
        case 750:
          $scope.map.setZoom(16);
          break;
        case 500:
          $scope.map.setZoom(16);
          break;
        case 250:
          $scope.map.setZoom(17);
          break;
        default:
          $scope.map.setZoom(14);
          break;
      }
      $scope.circle = new google.maps.Circle({
        map: $scope.map,
        radius: meters,    // km in metres
        strokeColor: '#333',
        strokeOpacity: 0.4,
        strokeWeight: 2,
        fillColor: '#333',
        fillOpacity: 0.35
      });
      $scope.circle.bindTo('center', marker, 'position');
      $scope.map.setCenter($scope.mapCenter);
  }

  // Generic Constructor for markers
  var createMarker = function (location){
    var marker = new google.maps.Marker({
        map: $scope.map,
        position: new google.maps.LatLng(location.latitude, location.longitude),
        title: location.fullAddress,
        animation: google.maps.Animation.DROP
    });

    marker.content = '<div class="infoWindowContent">Test Content</div>';

    google.maps.event.addListener(marker, 'click', function(){
        $scope.infoWindow.setContent('<h2>' + marker.title + '</h2>');
        $scope.infoWindow.open($scope.map, marker);
    });

    $scope.markers.push(marker);
  }

  // Push Json Markers
  var pushMarkers = function(geolocations){
    for (i = 0; i < $scope.geolocations.length; i++){
      createMarker($scope.geolocations[i]);
    }
  }

  // Time to setup by calling the required functions
  // Get em!
  //getMarkers();

  // Create the origin
  createOrigin($scope.originMarker);

  /* BROADCAST! MAKE THE CHANGES! */
  // Distance Changed, do something!
  $scope.$on('distanceBroadcast', function() {

    //$scope.markers = [];
    console.log("Change in distance detected meters: "+distance__service.meters);

    clearMarkers();

    //createOrigin($scope.originMarker);

    createRadius($scope.originMarker,distance__service.meters);

    // Get New markers
    getMarkers(distance__service.meters);
    $scope.map.setCenter($scope.mapCenter);
  });

  // Generic Constructor for markers
  var clearMarkers = function (){
    //console.log('attempting to clean');
    for (var i = 0; i < $scope.markers.length; i++) {

      //console.log('cleaning marker: '+i+' | title: '+$scope.markers[i].title);
      $scope.markers[i].setMap(null);
    }
  }
  $scope.openInfoWindow = function(e, selectedMarker){
    e.preventDefault();
    google.maps.event.trigger(selectedMarker, 'click');
  }
  $scope.deleteListing = function (id) {

    var listing = $scope.listings[id];
    if (listing != -1) {
      $scope.geolocations = [];
      $scope.listings.splice(id, 1);
      $.each($scope.listings, function(i, item) {
        $scope.geolocations.push(item.location);
      });
      clearMarkers();
      pushMarkers($scope.geolocations);
    }
  }

  //google.maps.event.addDomListener(window, 'load', initialize);

  google.maps.event.addDomListener(window, "resize", function() {
    // Here you set the center of the map based on your "mapCenter" variable
    $scope.map.setCenter($scope.mapCenter);
  });

  // var hmap = $scope.map.getMap(); // add getMap() here to get the map instance
  //   console.log($scope.map);
  var heatmap = new google.maps.visualization.HeatmapLayer({
    data: heatMapData
  });
  heatmap.setMap($scope.map);
});
