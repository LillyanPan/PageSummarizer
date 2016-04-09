var express           = require('express'),
    app               = express(),
    meetupsController = require('./server/controllers/meetups-controller');


app.get('/', function (req, res) {
  res.sendfile(__dirname + '/client/views/index.html');
});

app.use('/js', express.static(__dirname + '/client/js'));

// in server controller
app.post('/api/meetups', meetupsController.create)

app.listen(3000, function() {
  console.log('I\'m Listening...');
})