// Issues with twig so need to change start and end tag to {[]}
var refis = angular.module('refis', ['google-maps', 'xeditable', 'highcharts-ng', 'ui.slider', 'angular-loading-bar']).config(function($interpolateProvider){
    $interpolateProvider.startSymbol('{[').endSymbol(']}');
  }
);

refis.factory('refiCache', function ($cacheFactory) {
  return $cacheFactory('myCache');
});

// Prospect Service
refis.factory('list__service', function($rootScope) {
  var list = {name: "DistrictList", maindata: []};

  list.maindata = [];
  list.prospectCount = 0;

  list.prepForBroadcast = function(value) {
    list.maindata = [];
    list.maindata.push(value);
    this.broadcastItem();
    //console.log("maindata contains: "+_.keys(list.maindata).length);
  };

  list.broadcastItem = function() {
    $rootScope.$broadcast('maindataBroadcast');
  };

  return list;
});


// Check for update on district
refis.factory('map__service', function($rootScope) {
  var map = {};

  // Default
  map.mapDOMElement = document.getElementById('heatmap');
  map.control_state = true;
  map.iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
  map.mapOptions = {
    zoom: 13,
    draggable: map.control_state,
    panControl: map.control_state,
    zoomControl: map.control_state,
    mapTypeControl: map.control_state,
    scaleControl: map.control_state,
    streetViewControl: map.control_state,
    overviewMapControl: map.control_state,
    scrollwheel: false,
    keyboardShortcuts: map.control_state,
    clickableLabels: map.control_state,
    disableDoubleClickZoom: false,
    center: new google.maps.LatLng(1.32008, 103.81984),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
    }
  };


  map.styles = [
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
  map.styledMap = new google.maps.StyledMapType(map.styles,
    {name: "Styled Map"});

  map.google = new google.maps.Map(map.mapDOMElement, map.mapOptions);

  map.prepForBroadcast = function() {
    this.broadcastItem();
  };

  map.broadcastItem = function() {
    $rootScope.$broadcast('mapBroadcast');
  };

  return map;

});


// Check for update on district
refis.factory('district__service', function($rootScope) {
  var origin = {};

  // Add this to scope incase value changes
  //Default
  origin.latitude = 1.30208;
  origin.longitude = 103.81984;
  origin.fullAddress = '123 test street';

  origin.prepForBroadcast = function(latitude_value, longitude_value, address_value) {
    origin.latitude = latitude_value;
    origin.longitude = longitude_value;
    origin.fullAddress =  address_value;
  };

  origin.broadcastItem = function() {
    $rootScope.$broadcast('originBroadcast');
  };

  return origin;
});



// Heatmap (based from main data)
refis.factory('heatmap__service', function($rootScope, map__service) {
  var heatmap = { name:"HeatmapLocations", locations: [], results: []};
  heatmap.locations = [];
  heatmap.results = [];

  var filterableLocations = [];

  heatmap.gradient2 = [
    'rgba(0, 213, 195, 0)',
    'rgba(0, 213, 195, 0.35)',
    'rgba(0, 213, 195, 0.45)',
    'rgba(0, 213, 195, 0.55)',
    'rgba(243, 237, 123,0.1)',
    'rgba(243, 237, 123,0.35)',
    'rgba(243, 237, 123,0.45)',
    'rgba(243, 237, 123,0.55)',
    'rgba(238, 67, 99, 0.1)',
    'rgba(238, 67, 99, 0.25)',
    'rgba(238, 67, 99, 0.35)',
    'rgba(238, 67, 99, 0.45)',
    'rgba(238, 67, 99, 0.55)'
  ];
  heatmap.gradient = [
    'rgba(0, 213, 195, 0)',
    'rgba(0, 213, 195, 0.5)',
    'rgba(55, 219, 173, 0.25)',
    'rgba(55, 219, 173, 0.55)',

    'rgba(97, 224, 159, 0.2)',
    'rgba(97, 224, 159, 0.55)',

    'rgba(143, 230, 145, 0.2)',
    'rgba(143, 230, 145, 0.55)',

    'rgba(195, 235, 130, 0.2)',
    'rgba(195, 235, 130, 0.55)',

    'rgba(243, 237, 123, 0.2)',
    'rgba(243, 237, 123, 0.55)',

    'rgba(240, 203, 112, 0.2)',
    'rgba(240, 203, 112, 0.55)',

    'rgba(238, 164, 105, 0.2)',
    'rgba(238, 164, 105, 0.55)',

    'rgba(236, 127, 100, 0.2)',
    'rgba(236, 127, 100, 0.55)',

    'rgba(235, 90, 96, 0.2)',
    'rgba(235, 90, 96, 0.55)',

    'rgba(238, 67, 99, 0.4)',
    'rgba(238, 67, 99, 0.65)',
    'rgba(238, 67, 99, 0.85)'
  ];
  heatmap.radius = 35;
  // heatmap.dataArray = new google.maps.MVCArray(heatmap.locations);

  // heatmap.heatmapObj = new google.maps.visualization.HeatmapLayer({
  //   data: heatmap.dataArray,
  //   radius: 50,
  //   gradient: heatmap.gradient2,
  //   dissipating: true,
  //   maxIntensity: 100,
  //   opacity: 0.5
  // });

  heatmap.createHeatmap = function(){
    console.log('creating heatmap');

    // heatmap.heatmapObj = new google.maps.visualization.HeatmapLayer({
    //   data: heatmap.locations,
    //   radius: 50,
    //   gradient: heatmap.gradient,
    //   dissipating: true,
    //   maxIntensity: 100,
    //   opacity: 0.5
    // });

    //heatmap.heatmapObj.data = heatmap.locations;

    //heatmap.heatmapObj.setMap(map__service.google);
  };


  heatmap.prepForBroadcast = function(value) {
    heatmap.locations = value;
    filterableLocations = value;

    // for(var i = 0; i < heatmap.locations.length; i++ ){
    //    heatmap.dataArray.push(heatmap.locations[i]);
    // }
    //console.log(value);
    //heatmap.locations.push({latitude: latitude_value, longitude: longitude_value, weight: score_value });
    //filterableLocations.push({latitude: latitude_value, longitude: longitude_value, weight: score_value });
    this.broadcastItem();
  };
  heatmap.filterScore = function(value){
    heatmap.results  = [];
    for(var i = 0; i < filterableLocations.length; i++ ){
      if(value <= filterableLocations[i].weight){
        heatmap.results.push(filterableLocations[i]);
        console.log("pushing: "+filterableLocations[i]);
      }
    }
    console.log(heatmap.results.length);
  };

  heatmap.clear = function() {
    heatmap.locations = [];
  };

  heatmap.broadcastItem = function() {
    $rootScope.$broadcast('heatmapBroadcast');
  };

  return heatmap;

});



// Heatmap (based from main data)
refis.factory('api__service', function($rootScope, $http, list__service) {
  var api = { };

  api.filterBroadcast = function(values) {
    var responsePromise = $http.get("/api/filter/property", { params: values  });
    responsePromise.success(function(data, status, headers, config) {
      list__service.prepForBroadcast(data);
      $( ".filter__slider" ).slider({ disabled: false });
      api.broadcastItem();
    });
    responsePromise.error(function(data, status, headers, config) {
      alert("Could not fetch prospects, contact FortyTu");
      $( ".filter__slider" ).slider({ disabled: false });
      api.broadcastItem();
    });
  };

  api.broadcastItem = function() {
    $rootScope.$broadcast('apiBroadcast');
  };

  return api;

});

var heatmap_slider = refis.controller('heatmap__slider', function($scope, heatmap__service) {
  $scope.slider = $( ".heatmap__slider" ).slider({
    range: "max",
    min: 0,
    max: 100,
    step: 5,
    value: 0,
    slide: function( event, ui ) {
      var tempVal = ui.value*500;
      var newVal = 100000 - tempVal
      $('.heatmap__control--results .value').text( accounting.formatNumber( newVal ) );
      if(ui.value == 0){
        $('.heatmap__control--results .value').text( accounting.formatNumber(100000) );
      }
    },
    // State change we must update step value - more of an inbetween
    change: function( event, ui ) {
      heatmap__service.filterScore((ui.value));
    },
    create: function( event, ui ) {
      $('.heatmap__control--results .value').text( accounting.formatNumber(100000) );
    }
  });
});



var filter_controller = refis.controller('filter__controller', function($scope, $log, $http, list__service, api__service) {
  // PROPERTY SLIDERS
  $scope.property_value_min = 0;
  $scope.property_value_max = 10000000;

  $scope.ltv_min = 0;
  $scope.ltv_max = 100;

  $scope.loan_age_min = 0;
  $scope.loan_age_max = 10;

  $scope.income_min = 0;
  $scope.income_max = 5000000;

  $scope.property_owned_min = 0;
  $scope.property_owned_max = 10;

  $scope.age_min = 18;
  $scope.age_max = 70;

  $scope.assets_min = 0;
  $scope.assets_max = 10000000;

  $scope.debt_min = 0;
  $scope.debt_max = 5000000;


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
    //State change we must update step value - more of an inbetween
    change: function( event, ui ) {
      $scope.property_value_min = ui.values[0];
      $scope.property_value_max = ui.values[1];

      //disable all sliders
      //$( ".filter__slider" ).slider({ disabled: true });
      //fetching
      //api__service.filterBroadcast( { property_value_min: ui.values[0], property_value_max: ui.values[1], ltv_min: $scope.ltv_min, ltv_max: $scope.ltv_max, loan_age_min: $scope.loan_age_min, loan_age_max: $scope.loan_age_max, income_min: $scope.income_min, income_max: $scope.income_max, property_owned_min: $scope.property_owned_min, property_owned_max: $scope.property_owned_max, age_min: $scope.age_min, age_max: $scope.age_max, assets_min: $scope.assets_min, assets_max: $scope.assets_max, debt_min: $scope.debt_min, debt_max: $scope.debt_max } );

    },
    create: function( event, ui ) {
      $( ".property__value .ui-slider-handle:nth-child(2)" ).addClass( "min__slider" ).html("<span class='val'>"+accounting.formatMoney(0, { symbol: "$",  format: "%s%v" })+"</span>");
      $( ".property__value .ui-slider-handle:nth-child(3)" ).addClass( "max__slider" ).html("<span class='val'>"+accounting.formatMoney(10000000, { symbol: "$",  format: "%s%v" })+"</span>");
    },
    fetch: function(min, max) {
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
    //State change we must update step value - more of an inbetween
    change: function( event, ui ) {
      $scope.ltv_min = ui.values[0];
      $scope.ltv_max = ui.values[1];

      //$( ".filter__slider" ).slider({ disabled: true });
      //fetching
      //api__service.filterBroadcast( { property_value_min: $scope.property_value_min, property_value_max: $scope.property_value_max, ltv_min: $scope.ltv_min, ltv_max: $scope.ltv_max, loan_age_min: $scope.loan_age_min, loan_age_max: $scope.loan_age_max, income_min: $scope.income_min, income_max: $scope.income_max, property_owned_min: $scope.property_owned_min, property_owned_max: $scope.property_owned_max, age_min: $scope.age_min, age_max: $scope.age_max, assets_min: $scope.assets_min, assets_max: $scope.assets_max, debt_min: $scope.debt_min, debt_max: $scope.debt_max } );

    },
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
    //State change we must update step value - more of an inbetween
    change: function( event, ui ) {

      $scope.loan_age_min = ui.values[0];
      $scope.loan_age_max = ui.values[1];

      //$( ".filter__slider" ).slider({ disabled: true });
      //fetching
      //api__service.filterBroadcast( { property_value_min: $scope.property_value_min, property_value_max: $scope.property_value_max, ltv_min: $scope.ltv_min, ltv_max: $scope.ltv_max, loan_age_min: $scope.loan_age_min, loan_age_max: $scope.loan_age_max, income_min: $scope.income_min, income_max: $scope.income_max, property_owned_min: $scope.property_owned_min, property_owned_max: $scope.property_owned_max, age_min: $scope.age_min, age_max: $scope.age_max, assets_min: $scope.assets_min, assets_max: $scope.assets_max, debt_min: $scope.debt_min, debt_max: $scope.debt_max } );

    },
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
    //State change we must update step value - more of an inbetween
    change: function( event, ui ) {

      $scope.income_min = ui.values[0];
      $scope.income_max = ui.values[1];

      //$( ".filter__slider" ).slider({ disabled: true });
      //fetching
      //api__service.filterBroadcast( { property_value_min: $scope.property_value_min, property_value_max: $scope.property_value_max, ltv_min: $scope.ltv_min, ltv_max: $scope.ltv_max, loan_age_min: $scope.loan_age_min, loan_age_max: $scope.loan_age_max, income_min: $scope.income_min, income_max: $scope.income_max, property_owned_min: $scope.property_owned_min, property_owned_max: $scope.property_owned_max, age_min: $scope.age_min, age_max: $scope.age_max, assets_min: $scope.assets_min, assets_max: $scope.assets_max, debt_min: $scope.debt_min, debt_max: $scope.debt_max } );

    },
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
    //State change we must update step value - more of an inbetween
    change: function( event, ui ) {
      $scope.property_owned_min = ui.values[0];
      $scope.property_owned_max = ui.values[1];

      //$( ".filter__slider" ).slider({ disabled: true });
      //fetching
      //api__service.filterBroadcast( { property_value_min: $scope.property_value_min, property_value_max: $scope.property_value_max, ltv_min: $scope.ltv_min, ltv_max: $scope.ltv_max, loan_age_min: $scope.loan_age_min, loan_age_max: $scope.loan_age_max, income_min: $scope.income_min, income_max: $scope.income_max, property_owned_min: $scope.property_owned_min, property_owned_max: $scope.property_owned_max, age_min: $scope.age_min, age_max: $scope.age_max, assets_min: $scope.assets_min, assets_max: $scope.assets_max, debt_min: $scope.debt_min, debt_max: $scope.debt_max } );

    },
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

      $scope.age_min = ui.values[0];
      $scope.age_max = ui.values[1];

      $( ".financials__age .min__slider" ).html("<span class='val'>"+ui.values[0]+"</span>");
      $( ".financials__age .max__slider" ).html("<span class='val'>"+ui.values[1]+"</span>");
      //console.log( (ui.value) );
    },
    //State change we must update step value - more of an inbetween
    change: function( event, ui ) {
      //$( ".filter__slider" ).slider({ disabled: true });
      //fetching
      //api__service.filterBroadcast( { property_value_min: $scope.property_value_min, property_value_max: $scope.property_value_max, ltv_min: $scope.ltv_min, ltv_max: $scope.ltv_max, loan_age_min: $scope.loan_age_min, loan_age_max: $scope.loan_age_max, income_min: $scope.income_min, income_max: $scope.income_max, property_owned_min: $scope.property_owned_min, property_owned_max: $scope.property_owned_max, age_min: $scope.age_min, age_max: $scope.age_max, assets_min: $scope.assets_min, assets_max: $scope.assets_max, debt_min: $scope.debt_min, debt_max: $scope.debt_max } );

    },
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
    //State change we must update step value - more of an inbetween
    change: function( event, ui ) {
      $scope.assets_min = ui.values[0];
      $scope.assets_max = ui.values[1];

      //$( ".filter__slider" ).slider({ disabled: true });
      //fetching
      //api__service.filterBroadcast( { property_value_min: $scope.property_value_min, property_value_max: $scope.property_value_max, ltv_min: $scope.ltv_min, ltv_max: $scope.ltv_max, loan_age_min: $scope.loan_age_min, loan_age_max: $scope.loan_age_max, income_min: $scope.income_min, income_max: $scope.income_max, property_owned_min: $scope.property_owned_min, property_owned_max: $scope.property_owned_max, age_min: $scope.age_min, age_max: $scope.age_max, assets_min: $scope.assets_min, assets_max: $scope.assets_max, debt_min: $scope.debt_min, debt_max: $scope.debt_max } );

    },
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
    //State change we must update step value - more of an inbetween
    change: function( event, ui ) {
      $scope.debt_min = ui.values[0];
      $scope.debt_max = ui.values[1];

      //$( ".filter__slider" ).slider({ disabled: true });
      //fetching
      //api__service.filterBroadcast( { property_value_min: $scope.property_value_min, property_value_max: $scope.property_value_max, ltv_min: $scope.ltv_min, ltv_max: $scope.ltv_max, loan_age_min: $scope.loan_age_min, loan_age_max: $scope.loan_age_max, income_min: $scope.income_min, income_max: $scope.income_max, property_owned_min: $scope.property_owned_min, property_owned_max: $scope.property_owned_max, age_min: $scope.age_min, age_max: $scope.age_max, assets_min: $scope.assets_min, assets_max: $scope.assets_max, debt_min: $scope.debt_min, debt_max: $scope.debt_max } );

    },
    create: function( event, ui ) {
      $( ".financials__debt .ui-slider-handle:nth-child(2)" ).addClass( "min__slider" ).html("<span class='val'>"+accounting.formatMoney(0, { symbol: "$",  format: "%s%v" })+"</span>");
      $( ".financials__debt .ui-slider-handle:nth-child(3)" ).addClass( "max__slider" ).html("<span class='val'>"+accounting.formatMoney(5000000, { symbol: "$",  format: "%s%v" })+"</span>");
    }
  });

  // Price selection
  $scope.duration = '10,000';
  $scope.applyFilters = function () {
    api__service.filterBroadcast( { property_value_min: $scope.property_value_min, property_value_max: $scope.property_value_max, ltv_min: $scope.ltv_min, ltv_max: $scope.ltv_max, loan_age_min: $scope.loan_age_min, loan_age_max: $scope.loan_age_max, income_min: $scope.income_min, income_max: $scope.income_max, property_owned_min: $scope.property_owned_min, property_owned_max: $scope.property_owned_max, age_min: $scope.age_min, age_max: $scope.age_max, assets_min: $scope.assets_min, assets_max: $scope.assets_max, debt_min: $scope.debt_min, debt_max: $scope.debt_max } );
  }
});

var map_controller = refis.controller('map__controller', function($scope, $http, list__service, heatmap__service, district__service, map__service, api__service) {

  $scope.maindata = {};
  $scope.heatMapData = [];
  $scope.geoLocations = [];
  $scope.prospectCount = 0;
  $scope.pointArray = [];
  api__service.filterBroadcast();

  $scope.heatmap = new google.maps.visualization.HeatmapLayer({
    data: $scope.heatMapData,
    radius: heatmap__service.radius,
    gradient: heatmap__service.gradient,
    dissipating: true,
    maxIntensity: 50,
    opacity: 0.5
  });

  // Create a new 'Map' instance
  map__service.google.mapTypes.set('map_style', map__service.styledMap);
  map__service.google.setMapTypeId('map_style');

  // Create my marker bin!
  $scope.markers = [];

  // Create my listings bin!
  $scope.listings = [];

  // On click we show this info window
  $scope.infoWindow = new google.maps.InfoWindow();

  // get markers was here

  $scope.mapCenter = map__service.google.getCenter();



  // Generic Constructor for markers
  var createMarker = function (location){
    var marker = new google.maps.Marker({
        map: map__service.google,
        position: new google.maps.LatLng(location.latitude, location.longitude),
        title: location.sector_code,
        icon: map__service.iconBase + 'placemark_circle.png',
        animation: google.maps.Animation.DROP
    });
    //console.log(location);
    marker.content = '<div class="address"><div class="streetname"><span>'+location.name+'</span></div>&nbsp;<div class="score">Potential: <span>'+location.sector_score+'%</span></div><div class="prospects">Prospects: <span>'+location.total_sector_prospects+'</span></div></div>';

    google.maps.event.addListener(marker, 'click', function(){
        $scope.infoWindow.setContent('<h2>Postal Sector: ' + marker.title + '</h2>'+ marker.content +'<br/><div class="addShort"><a href="/add">Add to ShortList</a></div>');
        $scope.infoWindow.open(map__service.google, marker);
    });
    //console.log(marker);
    //marker.setMap(map);
    $scope.markers.push(marker);
  }

  // Push Json Markers
  var pushMarkers = function(){
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

  // Get markers
  var getMarkers = function (data){
    //console.log('attempting to clean');
    for (var i = 0; i < $scope.data.length; i++) {
      //console.log('cleaning marker: '+i+' | title: '+$scope.markers[i].title);
      $scope.markers[i].setMap(null);
    }
  }


  // Push Json Markers
  var setMapData = function(){
    $scope.geolocations = list__service.maindata;
    $scope.heatdata = [];
    clearMarkers();
    // All Sectors
    // console.log($scope.geolocations);
    $.each($scope.geolocations, function(a, sectorList) {
      // Sector by Sector
      // console.log(sectorList);
      $.each(sectorList, function(b, condoList) {
        createMarker(condoList);

        // Individual Condo Info
        // console.log(condoList.properties);
        $.each(condoList.properties, function(b, condo) {
          //console.log('condo propscore: '+condo.property_score);
          $scope.heatdata.push({latitude: condo.latitude, longitude: condo.longitude, weight: condo.property_score});
        });
      });
    });
    heatmap__service.prepForBroadcast($scope.heatdata);
  }

  $scope.openInfoWindow = function(e, selectedMarker){
    e.preventDefault();
    google.maps.event.trigger(selectedMarker, 'click');
  }

  // remove heatmaps
  var refreshHeatMap = function(){
    // heatmap__service.heatmapObj.setMap(null);

    // heatmap__service.createHeatmap();
    // console.log(heatmap__service.heatmapObj);

    // heatmap__service.heatmapObj.setMap(map__service.google);

    $scope.heatMapData = [];
    $.each(heatmap__service.locations, function(a, condoLocation) {
      $scope.heatMapData.push({ location: new google.maps.LatLng(condoLocation.latitude, condoLocation.longitude), weight: condoLocation.weight} );
    });

    //$scope.pointArray = [];
    //$scope.pointArray = new google.maps.MVCArray($scope.heatMapData);

    $scope.heatmap.data = $scope.heatMapData;
    console.log($scope.heatmap.data);

    $scope.heatmap.setMap(null);
    $scope.heatmap.setMap(map__service.google);
  }

  // Create initial Heatmap
  //heatmap__service.createHeatmap($scope.map);

  google.maps.event.addDomListener(window, "resize", function() {
    // Here you set the center of the map based on your "mapCenter" variable
    $scope.map.setCenter($scope.mapCenter);
  });


  /* BROADCAST! MAKE THE CHANGES! */
  // List Changed, do something!
  $scope.$on('maindataBroadcast', function() {
    //$scope.map = map__service.google;
    console.log("Change in main data");
    //console.log("geoLocs: "+$scope.geolocations);
    setMapData();
    //refreshHeatMap();
  });

  // heatmap Changed, do something!
  $scope.$on('apiBroadcast', function() {
    //$scope.map = map__service.google;
    refreshHeatMap();
    console.log("Change in heatmap detected");

  });
  // google maps Changed, do something!
  $scope.$on('mapBroadcast', function() {
    //$scope.map = map__service.google;
    map__service.google.mapTypes.set('map_style', map__service.styledMap);
    map__service.google.setMapTypeId('map_style');
    console.log("Change in map detected");
  });

});

refis.directive('preventDefault', function() {
  return function(scope, element, attrs) {
    $(element).click(function(event) {
      event.preventDefault();
    });
  }
})


