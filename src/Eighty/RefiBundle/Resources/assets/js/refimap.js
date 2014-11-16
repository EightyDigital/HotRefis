// Issues with twig so need to change start and end tag to {[]}
var refis = angular.module('refis', ['google-maps', 'xeditable', 'highcharts-ng', 'ui.slider']).config(function($interpolateProvider){
    $interpolateProvider.startSymbol('{[').endSymbol(']}');
  }
);

// Radius distance
refis.factory('prospect__service', function($rootScope) {
  var prospects = {};

  //prospects.heatmap_score = '';

  prospects.prepForBroadcast = function(value) {
    prospects = value;
    this.broadcastItem();
  };

  prospects.broadcastItem = function() {
    $rootScope.$broadcast('prospectsBroadcast');
  };

  return prospects;
});


// Shared variable for origin point
refis.factory('origin__service', function($rootScope) {
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


var report__crud = refis.controller('heatmap__slider', function($scope, prospect__service) {
  $scope.slider = $( ".heatmap__slider" ).slider({
    //orientation: "vertical",
    range: "max",
    min: 0,
    max: 100,
    step: 5,
    value: 0,
    slide: function( event, ui ) {
      //console.log( (ui.value) );
      var tempVal = ui.value*500;
      var newVal = 100000 - tempVal
      $('.heatmap__control--results .value').text( accounting.formatNumber( newVal ) );
      if(ui.value == 0){
        $('.heatmap__control--results .value').text( accounting.formatNumber(100000) );
      }
    },
    // State change we must update step value - more of an inbetween
    change: function( event, ui ) {
      //distance__service.prepForBroadcast(-(ui.value));
    },
    create: function( event, ui ) {
      $('.heatmap__control--results .value').text( accounting.formatNumber(100000) );
    }
  });

  /* BROADCAST! MAKE THE CHANGES! */
  // Distance Changed, do something!
  // $scope.$on('distanceBroadcast', function() {
  //   console.log("slider cont: "+distance__service.meters);
  //   //$scope.meters = 'SLIDER CONTROLLER: ' + distance__service.meters;
  // });
});

var filter_controller = refis.controller('filter__controller', function($scope, $log) {


  // this.tab = 1;
  // this.selectTab = function (setTab){
  //   this.tab = setTab;
  // };
  // this.isSelected = function(checkTab) {
  //   return this.tab === checkTab;
  // };

  // PROPERTY SLIDERS
  // Property Value
  $scope.slider = $( ".property__value" ).slider({
    range: true,
    min: 0,
    max: 10000000,
    step: 10000,
    values: [ 0, 10000000 ],
    slide: function( event, ui ) {

      $( ".property__value .min__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[0], { symbol: "$",  format: "%s%v" })+"</span>");
      $( ".property__value .max__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[1], { symbol: "$",  format: "%s%v" })+"</span>");
      //console.log( (ui.value) );
    },
    // State change we must update step value - more of an inbetween
    // change: function( event, ui ) {
    //   $( ".property__value .min__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[0], { symbol: "$",  format: "%s%v" })+"</span>");
    //   $( ".property__value .max__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[1], { symbol: "$",  format: "%s%v" })+"</span>");
    //   //distance__service.prepForBroadcast(-(ui.value));
    // },
    create: function( event, ui ) {
      $( ".property__value .ui-slider-handle:nth-child(2)" ).addClass( "min__slider" ).html("<span class='val'>"+accounting.formatMoney(0, { symbol: "$",  format: "%s%v" })+"</span>");
      $( ".property__value .ui-slider-handle:nth-child(3)" ).addClass( "max__slider" ).html("<span class='val'>"+accounting.formatMoney(10000000, { symbol: "$",  format: "%s%v" })+"</span>");
    }
  });

  // Property LTV
  $scope.slider = $( ".property__ltv" ).slider({
    range: true,
    min: 0,
    max: 100,
    step: 1,
    values: [ 0, 100 ],
    slide: function( event, ui ) {

      $( ".property__ltv .min__slider" ).html("<span class='val'>"+ui.values[0]+"%</span>");
      $( ".property__ltv .max__slider" ).html("<span class='val'>"+ui.values[1]+"%</span>");
      //console.log( (ui.value) );
    },
    // State change we must update step value - more of an inbetween
    // change: function( event, ui ) {
    //   $( ".property__ltv .min__slider" ).html("<span class='val'>"+ui.values[0]+"%</span>");
    //   $( ".property__ltv .max__slider" ).html("<span class='val'>"+ui.values[1]+"%</span>");
    //   //distance__service.prepForBroadcast(-(ui.value));
    // },
    create: function( event, ui ) {
      $( ".property__ltv .ui-slider-handle:nth-child(2)" ).addClass( "min__slider" ).html("<span class='val'>0%</span>");
      $( ".property__ltv .ui-slider-handle:nth-child(3)" ).addClass( "max__slider" ).html("<span class='val'>100%</span>");
    }
  });


  // Property Loan Age
  $scope.slider = $( ".property__loanAge" ).slider({
    range: true,
    min: 0,
    max: 10,
    step: 1,
    values: [ 0, 10 ],
    slide: function( event, ui ) {

      $( ".property__loanAge .min__slider" ).html("<span class='val'>"+ui.values[0]+"</span>");
      $( ".property__loanAge .max__slider" ).html("<span class='val'>"+ui.values[1]+"</span>");
      //console.log( (ui.value) );
    },
    // State change we must update step value - more of an inbetween
    // change: function( event, ui ) {
    //   $( ".property__loanAge .min__slider" ).html("<span class='val'>"+ui.values[0]+"</span>");
    //   $( ".property__loanAge .max__slider" ).html("<span class='val'>"+ui.values[1]+"</span>");
    //   //distance__service.prepForBroadcast(-(ui.value));
    // },
    create: function( event, ui ) {
      $( ".property__loanAge .ui-slider-handle:nth-child(2)" ).addClass( "min__slider" ).html("<span class='val'>0</span>");
      $( ".property__loanAge .ui-slider-handle:nth-child(3)" ).addClass( "max__slider" ).html("<span class='val'>10+</span>");
    }
  });



  // FINANCIAL SLIDERS
  // Income
  $scope.slider = $( ".financials__income" ).slider({
    range: true,
    min: 0,
    max: 5000000,
    step: 10000,
    values: [ 0, 5000000 ],
    slide: function( event, ui ) {

      $( ".financials__income .min__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[0], { symbol: "$",  format: "%s%v" })+"</span>");
      $( ".financials__income .max__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[1], { symbol: "$",  format: "%s%v" })+"</span>");
      //console.log( (ui.value) );
    },
    // State change we must update step value - more of an inbetween
    // change: function( event, ui ) {
    //   $( ".financials__income .min__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[0], { symbol: "$",  format: "%s%v" })+"</span>");
    //   $( ".financials__income .max__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[1], { symbol: "$",  format: "%s%v" })+"</span>");
    //   //distance__service.prepForBroadcast(-(ui.value));
    // },
    create: function( event, ui ) {
      $( ".financials__income .ui-slider-handle:nth-child(2)" ).addClass( "min__slider" ).html("<span class='val'>"+accounting.formatMoney(0, { symbol: "$",  format: "%s%v" })+"</span>");
      $( ".financials__income .ui-slider-handle:nth-child(3)" ).addClass( "max__slider" ).html("<span class='val'>"+accounting.formatMoney(5000000, { symbol: "$",  format: "%s%v" })+"</span>");
    }
  });

  // Property Owned
  $scope.slider = $( ".financials__property" ).slider({
    range: true,
    min: 0,
    max: 10,
    step: 1,
    values: [ 0, 10 ],
    slide: function( event, ui ) {

      $( ".financials__property .min__slider" ).html("<span class='val'>"+ui.values[0]+"</span>");
      $( ".financials__property .max__slider" ).html("<span class='val'>"+ui.values[1]+"</span>");
      //console.log( (ui.value) );
    },
    // State change we must update step value - more of an inbetween
    // change: function( event, ui ) {
    //   $( ".financials__property .min__slider" ).html("<span class='val'>"+ui.values[0]+"</span>");
    //   $( ".financials__property .max__slider" ).html("<span class='val'>"+ui.values[1]+"</span>");
    //   //distance__service.prepForBroadcast(-(ui.value));
    // },
    create: function( event, ui ) {
      $( ".financials__property .ui-slider-handle:nth-child(2)" ).addClass( "min__slider" ).html("<span class='val'>0</span>");
      $( ".financials__property .ui-slider-handle:nth-child(3)" ).addClass( "max__slider" ).html("<span class='val'>10</span>");
    }
  });

  // Age of loan
  $scope.slider = $( ".financials__age" ).slider({
    range: true,
    min: 18,
    max: 70,
    step: 1,
    values: [ 18, 70 ],
    slide: function( event, ui ) {

      $( ".financials__age .min__slider" ).html("<span class='val'>"+ui.values[0]+"</span>");
      $( ".financials__age .max__slider" ).html("<span class='val'>"+ui.values[1]+"</span>");
      //console.log( (ui.value) );
    },
    // State change we must update step value - more of an inbetween
    // change: function( event, ui ) {
    //   $( ".financials__age .min__slider" ).html("<span class='val'>"+ui.values[0]+" years</span>");
    //   $( ".financials__age .max__slider" ).html("<span class='val'>"+ui.values[1]+" years</span>");
    //   //distance__service.prepForBroadcast(-(ui.value));
    // },
    create: function( event, ui ) {
      $( ".financials__age .ui-slider-handle:nth-child(2)" ).addClass( "min__slider" ).html("<span class='val'>18 years</span>");
      $( ".financials__age .ui-slider-handle:nth-child(3)" ).addClass( "max__slider" ).html("<span class='val'>70 years+</span>");
    }
  });

  // Assets
  $scope.slider = $( ".financials__assets" ).slider({
    range: true,
    min: 0,
    max: 10000000,
    step: 10000,
    values: [ 0, 10000000 ],
    slide: function( event, ui ) {

      $( ".financials__assets .min__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[0], { symbol: "$",  format: "%s%v" })+"</span>");
      $( ".financials__assets .max__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[1], { symbol: "$",  format: "%s%v" })+"</span>");
      //console.log( (ui.value) );
    },
    // State change we must update step value - more of an inbetween
    // change: function( event, ui ) {
    //   $( ".financials__assets .min__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[0], { symbol: "$",  format: "%s%v" })+"</span>");
    //   $( ".financials__assets .max__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[1], { symbol: "$",  format: "%s%v" })+"</span>");
    //   //distance__service.prepForBroadcast(-(ui.value));
    // },
    create: function( event, ui ) {
      $( ".financials__assets .ui-slider-handle:nth-child(2)" ).addClass( "min__slider" ).html("<span class='val'>"+accounting.formatMoney(0, { symbol: "$",  format: "%s%v" })+"</span>");
      $( ".financials__assets .ui-slider-handle:nth-child(3)" ).addClass( "max__slider" ).html("<span class='val'>"+accounting.formatMoney(10000000, { symbol: "$",  format: "%s%v" })+"</span>");
    }
  });


  // Debt
  $scope.slider = $( ".financials__debt" ).slider({
    range: true,
    min: 0,
    max: 5000000,
    step: 10000,
    values: [ 0, 5000000 ],
    slide: function( event, ui ) {

      $( ".financials__debt .min__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[0], { symbol: "$",  format: "%s%v" })+"</span>");
      $( ".financials__debt .max__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[1], { symbol: "$",  format: "%s%v" })+"</span>");
      //console.log( (ui.value) );
    },
    // State change we must update step value - more of an inbetween
    // change: function( event, ui ) {
    //   $( ".financials__debt .min__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[0], { symbol: "$",  format: "%s%v" })+"</span>");
    //   $( ".financials__debt .max__slider" ).html("<span class='val'>"+accounting.formatMoney(ui.values[1], { symbol: "$",  format: "%s%v" })+"</span>");
    //   //distance__service.prepForBroadcast(-(ui.value));
    // },
    create: function( event, ui ) {
      $( ".financials__debt .ui-slider-handle:nth-child(2)" ).addClass( "min__slider" ).html("<span class='val'>"+accounting.formatMoney(0, { symbol: "$",  format: "%s%v" })+"</span>");
      $( ".financials__debt .ui-slider-handle:nth-child(3)" ).addClass( "max__slider" ).html("<span class='val'>"+accounting.formatMoney(5000000, { symbol: "$",  format: "%s%v" })+"</span>");
    }
  });

  // Price selection
  $scope.duration = '10,000';

});

var map_controller = refis.controller('map__controller', function($scope, $http, prospect__service, origin__service) {

  $scope.originMarker = {};

  // Map: Create an array of styles.
  // var styles = [
  //   {
  //     stylers: [
  //       { saturation: -20 }
  //     ]
  //   },{
  //     featureType: "road",
  //     elementType: "geometry",
  //     stylers: [
  //       //{ lightness: 100 },
  //       { visibility: "simplified" },
  //       { "saturation": -100 },
  //       { "lightness": -8 },
  //       { "gamma": 1.18 }
  //     ]
  //   },{
  //     featureType: "road",
  //     elementType: "labels"
  //   }
  // ];

  var styles = [
    {
        "featureType": "road.highway",
        "elementType": "geometry",
        "stylers": [
          { "saturation": 0 },
          { "lightness": 0 },
          { "gamma": 0 }
        ]
    }, {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
          { "saturation": -100 },
          { "gamma": 1 },
          { "lightness": -24 }
        ]
    }, {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
          { "saturation": -100 }
        ]
    }, {
        "featureType": "administrative",
        "stylers": [
          { "saturation": -100 }
        ]
    }, {
        "featureType": "transit",
        "stylers": [
          { "saturation": -100 }
        ]
    }, {
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [
          { "saturation": -50 }
        ]
    }, {
        "featureType": "road",
        "stylers": [
          { "saturation": -100 }
        ]
    }, {
        "featureType": "administrative",
        "stylers": [
          { "saturation": -100 }
        ]
    }, {
        "featureType": "landscape",
        "stylers": [
          { "saturation": -100 }
        ]
    }, {
        "featureType": "poi",
        "stylers": [
          { "saturation": -100 }
        ]
    }, {
    }
  ];

  // Create a new StyledMapType object, passing it the array of styles,
  // as well as the name to be displayed on the map type control.
  var styledMap = new google.maps.StyledMapType(styles,
    {name: "Styled Map"});


  var responsePromise = $http.get("/api/filter/property");
  $scope.prospects = {};
  responsePromise.success(function(data, status, headers, config) {
      $scope.prospects = data;
  });
  responsePromise.error(function(data, status, headers, config) {
      alert("Could not fetch prospects, contact FortyTu");
  });
  console.log('gogogo');
  console.log($scope.prospects);
  $scope.heatMapData = [
    { location: new google.maps.LatLng(1.33632523, 103.8506676), weight: 0.5 },
    { location: new google.maps.LatLng(1.314019, 103.884676), weight: 1 },
    { location: new google.maps.LatLng(1.379752459, 103.8810601), weight: 0.23 },
    { location: new google.maps.LatLng(1.300445, 103.90106), weight: 0.5 },
    { location: new google.maps.LatLng(1.300445, 103.90106), weight: 1 },
    { location: new google.maps.LatLng(1.3377863, 103.9303141), weight: 1 },



    { location: new google.maps.LatLng(1.375914403, 103.8877047), weight: 0.45 },
    { location: new google.maps.LatLng(1.279328, 103.850506), weight: 1 },
    { location: new google.maps.LatLng(1.300445, 103.90106), weight: 1 },
    { location: new google.maps.LatLng(1.313923, 103.843079), weight: 0.3 },
    { location: new google.maps.LatLng(1.303889, 103.840872), weight: 0.5 },


    { location: new google.maps.LatLng(1.344826, 103.788188), weight: 0.65 },
    { location: new google.maps.LatLng(1.304251, 103.822467), weight: 0.12 },
    { location: new google.maps.LatLng(1.295672, 103.832227), weight: 0.55 },
    { location: new google.maps.LatLng(1.302848, 103.904104), weight: 1 },
    { location: new google.maps.LatLng(1.306538, 103.839412), weight: 0.9 },

    { location: new google.maps.LatLng(1.302799, 103.850434), weight: 0.2 },
    { location: new google.maps.LatLng(1.29682, 103.836822), weight: 0.54 },
    { location: new google.maps.LatLng(1.314426, 103.84484), weight: 0.23 }
  ];

  $scope.geoLocations = [];
  // Get latitudes/longitudes
  // $http({
  //   url: '/'+"web/listing.json",
  //   method: "GET"
  // }).success(function (data) {
  //   $.each(data.listings, function(i, item) {
  //     $scope.geoLocations.push(item.location);
  //     createMarker(item.location)
  //   });
  // });
  //console.log($scope.geoLocations);
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

  $scope.mapCenter = $scope.map.getCenter();

  // Generic Constructor for markers
  var createMarker = function (location){
    var marker = new google.maps.Marker({
        map: $scope.map,
        position: new google.maps.LatLng(location.latitude, location.longitude),
        title: location.fullAddress,
        animation: google.maps.Animation.DROP
    });

    marker.content = '<div class="infoWindowContent">Kerrisdale</div>';

    google.maps.event.addListener(marker, 'click', function(){
        $scope.infoWindow.setContent('<h2>' + marker.title + '</h2><a href="/add">Add to ShortList</a>');
        $scope.infoWindow.open($scope.map, marker);
    });
    //console.log(marker);
    //marker.setMap(map);
    $scope.markers.push(marker);
  }

  // Push Json Markers
  var pushMarkers = function(geolocations){
    //console.log('derp');
    //console.log($scope.geoLocations.length);
    for (i = 0; i < $scope.geoLocations.length; i++){
      createMarker($scope.geoLocations[i]);
    }
  }

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
        $scope.geoLocations.push(item.location);
      });
      clearMarkers();
      pushMarkers($scope.geoLocations);
    }
  }

  google.maps.event.addDomListener(window, "resize", function() {
    // Here you set the center of the map based on your "mapCenter" variable
    $scope.map.setCenter($scope.mapCenter);
  });

  // var hmap = $scope.map.getMap(); // add getMap() here to get the map instance
  //   console.log($scope.map);
  var heatmap = new google.maps.visualization.HeatmapLayer({
    data: $scope.heatMapData,
    radius: 125,
    dissipating: true
  });
  heatmap.setMap($scope.map);
  pushMarkers($scope.geoLocations);


});

refis.directive('preventDefault', function() {
    return function(scope, element, attrs) {
        $(element).click(function(event) {
            event.preventDefault();
        });
    }
})
