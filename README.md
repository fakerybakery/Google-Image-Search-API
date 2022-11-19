# Free Google Image Search Crawler

| I'm working on a crawler for Web results, News results, and Shopping results. Stay posted :eyes:!
| ---

## NodeJS and PHP

This software was originally written in NodeJS, however for large amounts of objects, I would get an error about there being too many files open at a given time. That's why I ported it to PHP, which runs each one non-async.

Although the PHP port is slower, it is better overall.

I do not reccomend running the PHP port in the browser, as you will have to re-run it every ~30 seconds due to a timeout or memory error.

Instead, you can run it through Terminal/CLI:

```
php -e <file_name>.php
```

## Disclaimer

I am not liable for any damage/harm caused through this product. Use at your own risk. I am not liable if you get banned from Google. Use a VPN.

## License

Closed Source License. You must first get written permission by creating an `Issue`. Please follow me @fakerybakery and star this repo. No use is allowed, even for personal use, without prior written permission.

I am planning to change the license in the future.

## Requirements (for NodeJS)
JSDOM
```
npm i jsdom
```

## Examples

See the `example` files. More coming soon!

## Changelog

###### November 18, 2022

Initial commit - NodeJS only

###### November 18, 2022

Improved examples

Search for more than 20 images

###### November 18, 2022

Port to PHP

Remove CORS proxy

###### November 19, 2022

Added more examples
