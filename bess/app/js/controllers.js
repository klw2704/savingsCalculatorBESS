'use strict';

/* Controllers */

var batterySavingsApp = angular.module('batterySavingsApp', ['filters']);

batterySavingsApp.controller('BatterySavingsCtrl', function($scope, $http) {


	$scope.generation = 3600;
	$scope.bill = 2800;
	$scope.selfConsumption = 0.2;
	$scope.selfConsumptionPC = $scope.selfConsumption*100;
	$scope.price = 17;
	$scope.FiTs = 20;
	$scope.efficiencyPC = 95;
	$scope.cost = 3200;

	$scope.calcTrueBill = function(){
	    // calc true bill for entered self consumption,  generation and bill
	    $scope.selfConsumption = parseInt($scope.selfConsumptionPC)/100;
	    if ($scope.selfConsumption < 0) $scope.selfConsumption = 0;
	    if ($scope.selfConsumption > 100) $scope.selfConsumption = 10;
	    $scope.selfConsumptionPC = ($scope.selfConsumption*100);
	    $scope.trueBill = $scope.selfConsumption * $scope.generation + $scope.bill;
	    $scope.calc();
	}
	$scope.calcSelfConsumption = function(){
	    // calc self consumption from entered bill and true bill
	    $scope.selfConsumption = ($scope.trueBill - $scope.bill)/$scope.generation;
	    $scope.selfConsumptionPC = ($scope.selfConsumption*100);
	    $scope.calc();
	}
	$scope.calc = function() {

	    $scope.summerGeneration = $scope.generation*0.67;
	    $scope.winterGeneration = $scope.generation*0.33;
	    $scope.summerUsage = $scope.trueBill/2;
	    $scope.winterUsage = $scope.trueBill/2;

	    $scope.maxSummerSavings = Math.min($scope.summerGeneration, $scope.summerUsage);
	    $scope.maxWinterSavings = Math.min($scope.winterGeneration, $scope.winterUsage);
	    $scope.totalSavings = $scope.maxSummerSavings + $scope.maxWinterSavings;
	    
	    $scope.currentSavings = $scope.generation*$scope.selfConsumption;
	    $scope.netSavings = $scope.totalSavings - $scope.currentSavings;
	    $scope.netSavingsGBP = $scope.netSavings * $scope.price/100;
	    $scope.finalSelfConsumption = $scope.totalSavings / $scope.generation;

	    if ($scope.efficiencyPC < 60)  $scope.efficiencyPC = 60;
	    if ($scope.efficiencyPC > 100)  $scope.efficiencyPC = 100;
	    $scope.efficiency = $scope.efficiencyPC/100;
	    $scope.FiTsLoss = $scope.FiTs * (1-$scope.efficiency);
	    $scope.FiTsFactor = ($scope.price - $scope.FiTsLoss)/$scope.price;
	    $scope.FinalSavingsGBP = $scope.netSavingsGBP * $scope.FiTsFactor;

	    $scope.paybackTime = $scope.cost / $scope.FinalSavingsGBP;
	}
	$scope.calcTrueBill();
    });



angular.module('filters', []).
    filter('GBP', ['$filter', function ($filter) {
		return function (input) {
		    if (isNaN(input)) {
			return input;
		    } else {
			return "£"+input.toFixed(0);
		    };
		};
	    }]).
    filter('PC', ['$filter', function ($filter) {
		return function (input) {
		    if (isNaN(input)) {
			return input;
		    } else {
			return (input*100).toFixed(0)+'%';
		    };
		};
	    }]);