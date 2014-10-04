(function(){
  var app = angular.module("modPerson", []);
  app.controller("PersonController", function PersonController($scope){
    $scope.persons = [{
      name: 'John Doe'
    }, {
      name: 'Jane Doe'
    }];

    $scope.checkAll = function(){
      if($scope.selectedAll){
        $scope.selectedAll = false;
      }else{
        $scope.selectedAll = true;
      }

      angular.forEach($scope.persons, function(person){
        person.Selected = $scope.selectedAll;

        console.log( person.Selected );
      });


    };//checkAll()


  });//PersonController
})();
