var API_KEY = "9d9a22b3a21a5d476f252f7bf8d49ab04273c530";
var jsonRelConcURL, jsonKeyRank, jsonTitle, jsonAuthor, summary = {};
var url;
var req;
var application_id = 'fb5a37c1';
var application_key = '5f3a8a94152314bd2baa9c06905b7030';

window.addEventListener('click',function(e){
  if(e.target.href !== undefined){
    chrome.tabs.create({url: e.target.href})
  }
});

$(document).ready(function() {
  var save = document.getElementById('save');

	chrome.tabs.query({currentWindow: true, active: true}, function(tabs){
	    url = tabs[0].url;
	    //Get Title
	    var urlTitle = "http://gateway-a.watsonplatform.net/calls/url/URLGetTitle?apikey=" + API_KEY + "&url=" + url + "&linkedData=1&outputMode=json";
	    //Get Author
	    var urlAuthor = "http://gateway-a.watsonplatform.net/calls/url/URLGetAuthor?apikey=" + API_KEY + "&url=" + url + "&linkedData=1&outputMode=json";
	    //Gets overall sentiment - not using
	    var jsonSentiment = "http://gateway-a.watsonplatform.net/calls/url/URLGetTextSentiment?apikey=" + API_KEY + "&url=" + url + "&outputMode=json&return=enriched.url.title,enriched.url.author,enriched.url.keywords.keyword.sentiment.score,enriched.url.keywords.enriched.url.keywords.keyword.sentiment.type";
	    //URL: Gets Key words with rank and sentiment
	    var urlKeyRank = "http://gateway-a.watsonplatform.net/calls/url/URLGetRankedKeywords?apikey=" + API_KEY + "&url=" + url + "&sentiment=1&linkedData=1&outputMode=json&start=now-1d&end=now&count=10";
	    //Gets relavent links to key concepts
	    var urlRelConcURL = "http://gateway-a.watsonplatform.net/calls/url/URLGetRankedConcepts?apikey=" + API_KEY + "&url=" + url + "&sentiment=1&outputMode=json";
	    // Gets full URL from wikipedia
	    var urlWikiFullURL = "https://en.wikipedia.org/w/api.php?action=query&titles=";

	    // HTTP GET Requests
	    var req = new XMLHttpRequest();

		$.ajax({
		  url: "https://api.aylien.com/api/v1/summarize",
			data: { 
				sentences_number: 3,
				url: url
			},
			type: "GET",
			beforeSend: function(req){
				req.setRequestHeader("X-AYLIEN-TextAPI-Application-Key",application_key);
				req.setRequestHeader("X-AYLIEN-TextAPI-Application-ID",application_id);
				req.setRequestHeader("sentences_number", 3);
			},
			cache: true
		}).done(function( json ) {
			summary = '';
			var i = 0;	
			while (i < 3) {
				summary += " " + json.sentences[i];
				i++;
			}
			$("#summary").append("<p>"+summary+"</p>");
			console.log(summary);
		});
		$.ajax({
		  url: urlTitle,
		  cache: false
		}).done(function( json ) {
			title = json.title;
			$("#title").append("<p><a href='" + json.url + "'onclick='chrome.tabs.create({url:this.href})'>" + title + "</a></p>");
		});
		$.ajax({
		  url: urlAuthor,
		  cache: false
		}).done(function( json ) {
			author = json.author;
			if (author == "undefined") {$("#author").append("<p>Author Unknown</p>");}
			else { $("#author").append("<p>"+author+"</p>"); }
		});
		$.ajax({
		  url: urlKeyRank,
		  cache: false
		}).done(function( json ) {
			jsonKeyRank = json;
			console.log(jsonKeyRank);
			var text = [];
			for (var i = 0; i < 5; i++) {
				$.each(json.keywords[i], function (key, data) {
					if (key == "text") { text.push(data); };
				});
			}
			$("#keywords").append("<p>"+text.join(", ")+"</p>");

			var height = 600;
			var width = 800;
			var padding = 50;
 
			var svg = d3.select("#sentiment").append("svg")
				.attr("width", width)
				.attr("height", height);
			var jsonArr = [];
			$.each(json.keywords, function (key,data) {
				if (isNaN(data.sentiment.score)) {
					return true;
				}
				else {
					jsonArr.push({
						text: data.text,
						relevance: Number(data.relevance),
						score: Number(data.sentiment.score),
						sentiment: data.sentiment.type
					});
				}
			});
			var sentRange = d3.extent(jsonArr, function(d){return d.relevance});
			var xScale = d3.scale.linear()
  			.domain([-1, 1]).range([padding, width - padding - 25])
  			var fontScale = d3.scale.linear()
  			.domain([.1,1]).range([5, 20])
  			var yScale = d3.scale.linear()
 			.domain([sentRange[0]-.05, sentRange[1]+.05]).range([height - padding, padding + 50]);

 			var colorScale = d3.scale.linear().domain([-1, 0, 1]).range(["#ff1919", "#d3d3d3", "#198c19"]);
 			var xAxis = d3.svg.axis().scale(xScale);
			svg.append("g")
			  .attr("class", "axis")
			  .attr("transform", "translate(0, " + yScale(sentRange[0]-.05) + ")")
			  .call(xAxis);

			var yAxis = d3.svg.axis().scale(yScale).orient("left");
			svg.append("g")
				.attr("class", "axis")
				.attr("transform", "translate(" + padding + ", 0)")
				.call(yAxis);
			svg.append("text")
				.attr("class", "axisLabel")
		        .attr("x", width / 2 - 45)
		        .attr("y",  height + padding / 4)
		        .text("Sentiment");
			
			var yLabel = svg.append("g")
				.attr("transform", "translate(-60,650)");

			yLabel.append("text")
				.attr("class", "axisLabel")
		        .attr("x", height / 2)
		        .attr("y", padding)
		        .attr("transform", "rotate(-90)")
		        .text("Relavence")

			var wordGroup = svg.append("g");
			jsonArr.forEach(function (d, i) {
				wordGroup.append("text")
					.attr("class", "graphText")
					.attr("x", xScale(d.score))
					.attr("y", yScale(d.relevance))
					.text(d.text)
					.attr("fill", function() {
						return colorScale(d.score);
					})
					.attr("font-size", function() {
						return fontScale(d.relevance);
					})
			});
			var legend = svg.append("g")
					.attr("transform", "translate(60,0)");

				legend.append('rect')
					.attr("class", "keyBox")
					.attr("x", 530)
					.attr("y", 0)
					.attr("width", 200)
					.attr("height", 90)
					.attr("rx", 10)
					.attr("ry", 10)
					.attr("fill", "#f6f6f6")
					.attr("opacity", .7);

				legend.append("g")
				  .attr("class", "legendLinear")
				  .attr("transform", "translate(550,30)");

				var legendLinear = d3.legend.color()
				  .shapeWidth(30)
				  .orient('horizontal')
				  .scale(colorScale);

				legend.select(".legendLinear")
				  .call(legendLinear);

				legend.append("text")
					.attr("class", "keyLabel")
					.attr("x", 550)
					.attr("y", 20)
					.text("Sentiment Score")
					.attr("font-size", "10px");
				legend.append("text")
					.attr("class", "keyLabel")
					.attr("x", 680)
					.attr("y", 80)
					.text("Positive")
					.attr("font-size", "8px");
				legend.append("text")
					.attr("class", "keyLabel")
					.attr("x", 550)
					.attr("y", 80)
					.text("Negative")
					.attr("font-size", "8px");
		});
		$.ajax({
		  url: urlRelConcURL,
		  cache: false
		}).done(function( json ) {
			jsonRelConcURL = json;
			var text;
			var relavence = [];
			var website = [];
			for (var i = 0; i < 5; i++) {
				var links = '';
				$.each(json.concepts[i], function (key, data) {
					if (key == "text") { text = data; }
					else if (key != "relevance") {
						links += "<a href='" + data + "'>" + key + "</a><br>";
					}
				});
				$("#links").append("<tr id=" + String(i) + "><td>"+ text +"</td>" + "<td>"+ links +"</td></tr>");
			}
			for (var i = 0; i < 5; i++) {
				var fullLink;
				var index = 0;
				$.each(json.concepts[i], function (key, data) {
					if (key == "text") {
						$.ajax({
							url: urlWikiFullURL + data + "&prop=info&inprop=url&format=json",
							cahce: false
						}).done(function ( wikiJson ) {
							// One loop for-loop because wikiJson.query.pages returns a distinct object
							for (var link in wikiJson.query.pages) {
								fullLink = wikiJson.query.pages[link].fullurl;
							}

							console.log("#"+String(i));
							$("#"+String(index)).append("<td><a href='" + fullLink + "'>Wikipedia</a></td>");
							index++;
						})
					};
				});
			};
		});
	});
	save.addEventListener('click', function() {
    var root = new Firebase('https://capital-one-news.firebaseio.com/');
		var newEntryRef = root.push();
    newEntryRef.set({
      title: title,
      author: author,
      url: url,
      summary: summary
    });
	})
});







