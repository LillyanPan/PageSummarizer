var API_KEY = "9d9a22b3a21a5d476f252f7bf8d49ab04273c530";
var summary;
var url;
var req;
var application_id = 'fb5a37c1';
var application_key = '5f3a8a94152314bd2baa9c06905b7030';
var id;
var userID;

/*
TODO:
Delete functionality
Unique user identification
Restructure firebase layout
Encapsulate firebase add/delete into functions
Comment code?
*/ 

// Create random token
// Inspiration: http://stackoverflow.com/questions/23822170/getting-unique-clientid-from-chrome-extension
function getRandomToken() {
    var randSelection = new Uint8Array(32); // creates 32 length array of 8-bit unsigned ints; init to 0
    crypto.getRandomValues(randSelection);
    var hex = randSelection.join('');
    return hex;
}

// Run only on first installation - gets unique user ID
var first_run = false;
if (!localStorage['ran_before']) {
  first_run = true;
  localStorage['ran_before'] = '1';
}
if (first_run) {
    userID = getRandomToken();
    chrome.storage.sync.set({ "userID": userID }, function() {
        if (chrome.runtime.error) {
            console.log("Runtime error");
        }
    });
}


// Run only on first installation/new version installed - gets unique user ID
// LOOK AT - should be better solution
// chrome.runtime.onInstalled.addListener(function (details) {
//     userID = getRandomToken();
//     console.log(userID);
//     if (details.reason == "install") { // first instal;
//         userID = getRandomToken();
//         console.log(userID);
//     }
//     else if (details.reason == "update") { // update
//         userID = getRandomToken();
//         console.log(userID);
//     }
// })

// Create new tab when article title clicked
// No new window if toggling between saved and article summary popup
window.addEventListener('click', function(e) {
    if (e.target.href == "chrome-extension://" + id + "/popup.html#saved" || e.target.href == "chrome-extension://" + id + "/popup.html#summary") {
        return true;
    } else if (e.target.href !== undefined) {
        chrome.tabs.create({
            url: e.target.href
        })
    }

});

$(document).ready(function() {

    // Originally hide article saved alert
    $("#saved-article-alert").hide();

	id = chrome.runtime.id;

    var save = document.getElementById('save');

    chrome.tabs.query({
        currentWindow: true,
        active: true
    }, function(tabs) {
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
        req = new XMLHttpRequest();


        /***************** 
              SUMMARY
        ******************/

        $.ajax({
            url: "https://api.aylien.com/api/v1/summarize",
            data: {
                sentences_number: 3,
                url: url
            },
            type: "GET",
            beforeSend: function(req) {
                req.setRequestHeader("X-AYLIEN-TextAPI-Application-Key", application_key);
                req.setRequestHeader("X-AYLIEN-TextAPI-Application-ID", application_id);
                req.setRequestHeader("sentences_number", 3);
            },
            cache: true
        }).done(function(json) {
            summary = '';
            var i = 0;
            while (i < 3) {
                summary += " " + json.sentences[i];
                i++;
            }
            $("#summaryNews").append("<p>" + summary + "</p>");
        });

        /***************** 
          ARTICLE TITLE
        ******************/

        $.ajax({
            url: urlTitle,
            cache: false
        }).done(function(json) {
            title = json.title;
            $("#title").append("<p><a href='" + json.url + "'onclick='chrome.tabs.create({url:this.href})'>" + title + "</a></p>");
        });

        /***************** 
              AUTHOR
        ******************/

        $.ajax({
            url: urlAuthor,
            cache: false
        }).done(function(json) {
            author = json.author;
            if (author == undefined) {
                $("#author").append("<p>Author Unknown</p>");
            } else {
                $("#author").append("<p>" + author + "</p>");
            }
        });

        /***************** 
            KEY WORDS
        ******************/

        $.ajax({
            url: urlKeyRank,
            cache: false
        }).done(function(json) {
            var text = [];
            for (var i = 0; i < 5; i++) {
                $.each(json.keywords[i], function(key, data) {
                    if (key == "text") {
                        text.push(data);
                    };
                });
            }
            $("#keywords").append("<p>" + text.join(", ") + "</p>");

            /***************** 
                D3 ANALYSIS
            ******************/

            var height = 700;
            var width = 850;
            var padding = 50;

            var svg = d3.select("#sentiment").append("svg")
                .attr("width", width)
                .attr("height", height);
            var jsonArr = [];
            $.each(json.keywords, function(key, data) {
                if (isNaN(data.sentiment.score)) {
                    return true;
                } else {
                    jsonArr.push({
                        text: data.text,
                        relevance: Number(data.relevance),
                        score: Number(data.sentiment.score),
                        sentiment: data.sentiment.type
                    });
                }
            });
            var sentRange = d3.extent(jsonArr, function(d) {
                return d.relevance
            });
            var min = Math.max(sentRange[0] - .01, 0);
            var max = Math.min(sentRange[1] + .01, 1);
            var xScale = d3.scale.linear()
                .domain([-1, 1]).range([padding, width - padding/2])
            var fontScale = d3.scale.linear()
                .domain([.1, 1]).range([12, 22])
            var yScale = d3.scale.linear()
                .domain([min, max]).range([height - padding, padding + 50]);

            var colorScale = d3.scale.linear().domain([-1, 0, 1]).range(["#ff1919", "#d3d3d3", "#198c19"]);
            var xAxis = d3.svg.axis().scale(xScale).orient("bottom");
            var graph = svg.append("g");
            graph.attr("transform", "translate(-10, -30) scale(0.8)");
            graph.append("g")
                .attr("class", "axis")
                .attr("transform", "translate(0, " + yScale(min) + ")")
                .call(xAxis);

            var yAxis = d3.svg.axis().scale(yScale).orient("left");
            graph.append("g")
                .attr("class", "axis")
                .attr("transform", "translate(" + padding + ", 0)")
                .call(yAxis);
            graph.append("text")
                .attr("class", "axisLabel")
                .attr("x", width / 2 - 25)
                .attr("y", height + padding / 4)
                .text("Sentiment");

            var yLabel = graph.append("g")
                .attr("transform", "translate(-60,755)");

            yLabel.append("text")
                .attr("class", "axisLabel")
                .attr("x", height / 2)
                .attr("y", padding)
                .attr("transform", "rotate(-90)")
                .text("Relavence")

            var wordGroup = graph.append("g");
            jsonArr.forEach(function(d, i) {
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
            var legend = graph.append("g")
                .attr("transform", "translate(100,0)");

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
                .attr("font-size", "11px");
            legend.append("text")
                .attr("class", "keyLabel")
                .attr("x", 680)
                .attr("y", 80)
                .text("Positive")
                .attr("font-size", "9px");
            legend.append("text")
                .attr("class", "keyLabel")
                .attr("x", 550)
                .attr("y", 80)
                .text("Negative")
                .attr("font-size", "9px");
            graph.append("text")
                .attr("class", "graphTitle")
                .attr("x", 0)
                .attr("y", 20)
                .text("Analysis of Key Words")
                .attr("transform", "translate(-35,0)");
        });

        /***************** 
         KEY CONCEPTS/WIKI
        ******************/

        var loop = [0, 1, 2, 3, 4];
        var keyData = [];
        $.ajax({
            url: urlRelConcURL,
            cache: false
        }).done(function(json) {
            var text;
            var block = 0;
            $.each(loop, function(i, val) {
                var links1 = '';
                var links2 = '';
                $.each(json.concepts[i], function(key, data) {
                    if (key == "text") {
                        text = data;
                        keyData.push(data);
                    } else if (key != "relevance" && block < 3) {
                        links1 += "<a href='" + data + "'>" + key + "</a><br>";
                        block++;
                    } else if (key != "relevance") {
                        links2 += "<a href='" + data + "'>" + key + "</a><br>";
                    }
                });
                $("tbody").append("<tr id=" + String(i) + "><td>" + text + "</td>" + "<td>" + links1 + "</td><td>" + links2 + "</td></tr>");
                block = 0;
            });
	        var index = 0;
	        //Thank you: http://stackoverflow.com/questions/21819905/jquery-ajax-calls-in-for-loop
            for (var k in keyData) {
            	(function(k) {
            		$.ajax({
		                url: urlWikiFullURL + keyData[k] + "&prop=info&inprop=url&format=json",
		                cahce: false
                	}).done(function(wikiJson) {
	                    // One loop for-loop as wikiJson.query.pages returns a distinct object
	                    for (var link in wikiJson.query.pages) {
	                        fullLink = wikiJson.query.pages[link].fullurl;
	                    }
	                    $("#" + String(k)).append("<td><a href='" + fullLink + "'>Wikipedia</a></td>");
	                    index++;
                	})
            	})(k);
            }
        });
    });
});


/***************** 
     FIREBASE
******************/

// Saves article to Firebase on click with banner indication
save.addEventListener('click', function() {
    chrome.storage.sync.get("userID", function(val) {
        if (val.userID != undefined) { // userID defined
            var root = new Firebase('https://capital-one-news.firebaseio.com/users/' + val.userID);
            var newEntryRef = root.push();
            newEntryRef.set({
                title: title,
                author: author,
                url: url,
                summary: summary
            });
        }
    });
    $("#saved-article-alert").fadeIn(300);
    $("#saved-article-alert").delay(300).fadeOut(300);
});

// Delete articles
$(document).on("click", ".delete", function() {
    var parentDiv = $(this).parents('div:first').parents('div:first');
    var title = parentDiv.children(".articleTitle").text();
    chrome.storage.sync.get("userID", function(val) {
        if (val.userID != undefined) { // userID defined
            var ref = new Firebase('https://capital-one-news.firebaseio.com/users/' + val.userID);
            var selectedArticle = ref.orderByChild('title').equalTo(title).on("child_added", function(snapshot) {
                var articleRef = new Firebase('https://capital-one-news.firebaseio.com/users/' + val.userID + "/" + snapshot.key());
                articleRef.remove();
            });
        }
    });
});

// Displays user's saved articles
// Get userID from storage; userID only avaliable in callback
chrome.storage.sync.get("userID", function(val) {
    if (val.userID == undefined) {
        $("#saved").html("");
        $("#saved").append("<div id='savedArticles'></div>");
        $("#savedArticles").append("<div class='col-md-12'><div class='articleTitle'>Not a valid user! Are you using icognito?</div><br>");
    }
    else {
        var root = new Firebase('https://capital-one-news.firebaseio.com/users/' + val.userID);
        root.on("value", function(items) {
            $("#saved").html("");
            for (var item in items.val()) {
                item = items.val()[item];
                $("#saved").append("<div id='savedArticles'></div>");
                $("#savedArticles").append("<div class='col-md-12'><div class='row'><div class='articleTitle col-sm-11'><a href='" + item.url + "'>" + item.title + "</a></div><div class='col-sm-1'><button type='button' class='btn btn-danger btn-circle btn-xs delete'><i class='glyphicon glyphicon-remove'></i></button></div></div><div class='articleAuthor'>" + item.author + "</div><div class='articleSummary'>" + item.summary + "</div></div><div class='col-md-12'><hr></div>");
            };
        }
        , function(errorObject) {
        console.log("The read failed: " + errorObject.code);
        });
    }
});