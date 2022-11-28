// This is an example for AI.
// Train AI on Google's images!
const jsdom = require("jsdom");
const fs = require('fs');
const http = require('https');


function searchImage(query) {
    query = encodeURIComponent(query);
    var options = {
        headers: new Headers({
            'X-Requested-With': 'XMLHttpRequest'
        }),
    }
    return new Promise(function (resolve, reject) {
        fetch("https://www.google.com/search?source=lnms&sa=X&gbv=1&tbm=isch&q=" + query, options)
            .then((res) => res.text())
            .then((res) => {
                var p = new jsdom.JSDOM(res);
                var parser = p.window.document;
                var table = parser.querySelectorAll('table')[2];
                var imgs = table.querySelectorAll('img');
                var imageList = [];
                imgs.forEach((img) => {
                    imageList.push(img.src);
                });
                resolve(imageList);
            })
            .catch((err) => console.log(err));
    });
}

function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}
//searchImage('Test').then((res) => {
//    console.log(res);
//    var car = 'Test';
//    res.forEach(function (img) {
//        var file = fs.createWriteStream('img/' + car.trim() + '/' + makeid(5) + '.jpg');
//        var request = http.get(img, function (response) {
//            response.pipe(file);
//            console.log('Starting a download');
//            file.on("finish", () => {
//                file.close();
//                console.log('Completed a download.');
//            });
//        });
//    });
//});
var file = fs.readFileSync('objects.txt', 'utf8');
var objs = file.split("\n");
objs.forEach(function (obj) {
    try {
        fs.mkdirSync('img/' + obj.trim());
    } catch(e) {
        
    }
    console.log(obj);
    searchImage(obj.trim()).then((res) => {
        res.forEach(function (img) {
            var file = fs.createWriteStream('img/' + obj.trim() + '/' + makeid(5) + '.jpg');
            var request = http.get(img, function (response) {
                response.pipe(file);
                console.log('Starting a download');
                file.on("finish", () => {
                    file.close();
                    console.log('Completed a download.');
                });
            });
        });
    });
});
