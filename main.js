const jsdom = require("jsdom");

function searchImage(query) {
    query = encodeURIComponent(query);
    var options = {
        headers: new Headers({
            'X-Requested-With': 'XMLHttpRequest'
        }),
    }
    return new Promise(function(resolve, reject) {
        fetch("https://corsproxy.io/https://www.google.com/search?source=lnms&sa=X&gbv=1&tbm=isch&q=" + query, options)
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
