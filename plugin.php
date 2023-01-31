// Import the Jellyfin API
var JellyfinAPI = require("jellyfin-apiclient");

// Create a new instance of the Jellyfin API client
var apiClient = new JellyfinAPI();

// Define the plugin's name, description, and id
var pluginName = "Movie Request";
var pluginDescription = "Allows users to submit movie requests to server administrators";
var pluginId = "com.jellyfin.movierequest";

// Define the plugin's endpoint for submitting requests
var submitRequestEndpoint = "/Plugins/" + pluginId + "/SubmitRequest";

// Define the endpoint for viewing requests
var viewRequestsEndpoint = "/Plugins/" + pluginId + "/ViewRequests";

// Define the plugin's routes
var routes = [
    {
        path: submitRequestEndpoint,
        method: "POST",
        handler: function (req, res) {
            // Get the request data from the body of the POST request
            var requestData = req.body;
            
            // Add the request to the database
            apiClient.postJSON(submitRequestEndpoint, requestData).then(function () {
                // Return a success response
                res.send({
                    result: true,
                    message: "Your request has been submitted successfully!"
                });
            }).catch(function (error) {
                // Return an error response
                res.send({
                    result: false,
                    message: "An error occurred while submitting your request: " + error
                });
            });
        }
    },
    {
        path: viewRequestsEndpoint,
        method: "GET",
        handler: function (req, res) {
            // Get the list of requests from the database
            apiClient.getJSON(viewRequestsEndpoint).then(function (requests) {
                // Return the requests
                res.send({
                    result: true,
                    data: requests
                });
            }).catch(function (error) {
                // Return an error response
                res.send({
                    result: false,
                    message: "An error occurred while retrieving the requests: " + error
                });
            });
        }
    }
];

// Register the plugin with Jellyfin
apiClient.registerPlugin({
    name: pluginName,
    description: pluginDescription,
    id: pluginId,
    routes: routes
});
