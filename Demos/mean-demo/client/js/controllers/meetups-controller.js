app.controller('meetupsController', ['$scope', '$resource', function($scope, $resource) {
	// base url for base meetups
	var Meetup = $resource('/api/meetups');
		$scope.meetups = [
		{name: "MEAN"},
		{name: "MEANer"}
	]

	$scope.createMeetup = function () {
		var meetup = new Meetup();
		meetup.name = $scope.meetupName;
		meetup.$save();
	}
}]);
