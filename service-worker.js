/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js");

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [
  {
    "url": "01-installation.html",
    "revision": "4173818077c1671975111ce6a73db012"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "73373c90e9c7d5c66ba544acf52165a4"
  },
  {
    "url": "03-trials.html",
    "revision": "7153d4484de0049b01ceda29659761ac"
  },
  {
    "url": "04-charges.html",
    "revision": "32f5c817c282b4707e7f56b210093daa"
  },
  {
    "url": "05-metered.html",
    "revision": "3310102b5ace7b1a68d72fd5c34e63da"
  },
  {
    "url": "06-customer.html",
    "revision": "f41e43054bc27a0e64e267109ef69d69"
  },
  {
    "url": "07-invoices.html",
    "revision": "b0a1ae880f08eca2788cb7a524c59ee5"
  },
  {
    "url": "08-events.html",
    "revision": "85e4608ee35a97f54f03f3172d730824"
  },
  {
    "url": "09-webhook.html",
    "revision": "8c5c252c4d0bc6c9deb0d6ec6888994e"
  },
  {
    "url": "10-testing.html",
    "revision": "39ce7ff765ddd734ed03ae7a5b862a35"
  },
  {
    "url": "11-faq.html",
    "revision": "8f5421c46585a06930fffb425bc33d1b"
  },
  {
    "url": "12-upgrade.html",
    "revision": "e5185af749e4cfc499bf5032fa775c54"
  },
  {
    "url": "404.html",
    "revision": "68b98f8f99a541903de7bf9b81bc3975"
  },
  {
    "url": "android-chrome-192x192.png",
    "revision": "7f1890f254594de8c4b514dee90ed629"
  },
  {
    "url": "android-chrome-384x384.png",
    "revision": "bc31b03048d4a3ba4fe82ce2389b0b38"
  },
  {
    "url": "apple-touch-icon.png",
    "revision": "19f3e3722c9d450ecfa73e8a92aaa47a"
  },
  {
    "url": "assets/css/0.styles.bfbd43d6.css",
    "revision": "97628342bab5ebed9a0cd043d1614b93"
  },
  {
    "url": "assets/img/cashier-mollie.svg",
    "revision": "06f0ab467b31062098cffb5ff5d18bc6"
  },
  {
    "url": "assets/img/laravelcashiermollie.a7bde0e4.jpg",
    "revision": "a7bde0e4173f90acd2d72e0eb69d2764"
  },
  {
    "url": "assets/img/search.83621669.svg",
    "revision": "83621669651b9a3d4bf64d1a670ad856"
  },
  {
    "url": "assets/js/10.6a9a5f21.js",
    "revision": "62083beea4c4df76926617e793c8fac0"
  },
  {
    "url": "assets/js/11.9e896dca.js",
    "revision": "55f1964ccf4e11e134c65c6626af67d2"
  },
  {
    "url": "assets/js/12.b5d72388.js",
    "revision": "d5ab40eea502fe92493c286ec51db01a"
  },
  {
    "url": "assets/js/13.0b610b10.js",
    "revision": "f0ead69a00ca0fa9d0bc46463aaf87d0"
  },
  {
    "url": "assets/js/14.21bf4b0a.js",
    "revision": "9be40f3904284c2bc9c719a166b435d1"
  },
  {
    "url": "assets/js/15.0361c574.js",
    "revision": "84e51b651fa366edf3fd56aa2e848acb"
  },
  {
    "url": "assets/js/16.01c1b0ac.js",
    "revision": "30706e1748ceaa4d8cec7271c86007f2"
  },
  {
    "url": "assets/js/17.4cd2945c.js",
    "revision": "38db9af13ecc560e08c22edb5248ad08"
  },
  {
    "url": "assets/js/18.66b6979b.js",
    "revision": "c01368775552677a58295a99e2ca544f"
  },
  {
    "url": "assets/js/19.362c8227.js",
    "revision": "0a5ba3ec1a36e1fdbfb19c3e04246f31"
  },
  {
    "url": "assets/js/2.71747a20.js",
    "revision": "bdec522a5510810905db47d8694a8533"
  },
  {
    "url": "assets/js/20.17798d94.js",
    "revision": "64ba1e20ce227c709eceb15600421e8c"
  },
  {
    "url": "assets/js/21.06e62f82.js",
    "revision": "66b5daf4aac60d8306caa3328fa2f927"
  },
  {
    "url": "assets/js/3.446f0839.js",
    "revision": "93ab226d8a7f40cc63ec1508df074a98"
  },
  {
    "url": "assets/js/4.1df7a44a.js",
    "revision": "3bcd6269d0f732c118f06a19e7d7e9ea"
  },
  {
    "url": "assets/js/5.51dda37e.js",
    "revision": "0e8c7f0383a2c8977d95e8850bfea4e2"
  },
  {
    "url": "assets/js/6.48de4dd1.js",
    "revision": "56a9c8da60a5773477b1f43ee529a422"
  },
  {
    "url": "assets/js/7.e797ff8e.js",
    "revision": "d483d4fd4451cccda704ab6b7170bcf2"
  },
  {
    "url": "assets/js/8.a170632b.js",
    "revision": "f641a0e16501d19b3eb0d76258d83a37"
  },
  {
    "url": "assets/js/9.eb8671dc.js",
    "revision": "879834bc683a5f707392d6dc2d050114"
  },
  {
    "url": "assets/js/app.ae22ca69.js",
    "revision": "5178894dd06b3c6d404df772a400e2d3"
  },
  {
    "url": "assets/pages/laravelcashiermollie.jpg",
    "revision": "a7bde0e4173f90acd2d72e0eb69d2764"
  },
  {
    "url": "favicon-16x16.png",
    "revision": "e8cead60a31ba0059df44368227bba35"
  },
  {
    "url": "favicon-32x32.png",
    "revision": "2f21759d559a5e952851228adbb628ec"
  },
  {
    "url": "index.html",
    "revision": "54bb60ebe8f18a67697240d1b2199851"
  }
].concat(self.__precacheManifest || []);
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});
addEventListener('message', event => {
  const replyPort = event.ports[0]
  const message = event.data
  if (replyPort && message && message.type === 'skip-waiting') {
    event.waitUntil(
      self.skipWaiting().then(
        () => replyPort.postMessage({ error: null }),
        error => replyPort.postMessage({ error })
      )
    )
  }
})
