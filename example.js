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
function makeid(length) { // thanks to https://stackoverflow.com/a/1349426
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}
searchImage('GitHub').then((res) => {
    res.forEach(function (img) {
        var file = fs.createWriteStream(makeid(5) + '.jpg');
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
