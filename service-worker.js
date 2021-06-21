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
    "revision": "dcb23b828ea3eb4271af9b2f55834a83"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "a728c981d8a7973005cb858905330a3c"
  },
  {
    "url": "03-trials.html",
    "revision": "693c4f98ecac178f035cbafb4feffceb"
  },
  {
    "url": "04-charges.html",
    "revision": "281669b39f98b781da49dcae72c547e0"
  },
  {
    "url": "05-metered.html",
    "revision": "fce9ed992104f87a03d302bb262b467d"
  },
  {
    "url": "06-customer.html",
    "revision": "bdb913b9eb89b5390f079752f6be1ef1"
  },
  {
    "url": "07-invoices.html",
    "revision": "0142bdb79e0676d7d8a30c2bb29772ee"
  },
  {
    "url": "08-events.html",
    "revision": "97113694fae3364582ea74f226ed4dca"
  },
  {
    "url": "09-webhook.html",
    "revision": "67ee7f2a23813f6339cd41a11b281f1a"
  },
  {
    "url": "10-testing.html",
    "revision": "4e1e84345a149dc34bac3687fbe15068"
  },
  {
    "url": "11-faq.html",
    "revision": "61e2933d678ffef6c3b9043808e76e78"
  },
  {
    "url": "12-upgrade.html",
    "revision": "b8e1bdbbc16ac9f6f80b815111ccd8ad"
  },
  {
    "url": "404.html",
    "revision": "9f04ce21cf5c46a3f3945d2b0c12c40a"
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
    "url": "assets/css/0.styles.0d1aa54b.css",
    "revision": "e35051108bd72f5a2c1034cc8dabcd61"
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
    "url": "assets/js/10.e158d347.js",
    "revision": "11af7e7c70b6c7733d87f5250df87b1a"
  },
  {
    "url": "assets/js/11.37b8f219.js",
    "revision": "e9312cfd78ce7d24fe71cea3c0ae1d80"
  },
  {
    "url": "assets/js/12.a17facb9.js",
    "revision": "b40d569f1cc778e58092f50af6f634dc"
  },
  {
    "url": "assets/js/13.0b610b10.js",
    "revision": "f0ead69a00ca0fa9d0bc46463aaf87d0"
  },
  {
    "url": "assets/js/14.be1a0186.js",
    "revision": "b853424f31da24af7b59fedda662b10a"
  },
  {
    "url": "assets/js/15.253cfccf.js",
    "revision": "29e2566c6d99ee4c90005f96558020ce"
  },
  {
    "url": "assets/js/16.28c33df7.js",
    "revision": "8ab39581729c054ed477161be4b7926d"
  },
  {
    "url": "assets/js/17.ef89ea09.js",
    "revision": "5d23563ec01d1b33d627ee9e55ad2e3e"
  },
  {
    "url": "assets/js/18.309d5c53.js",
    "revision": "2e6fa12e55bf7bdf3b59369f8ce9fa05"
  },
  {
    "url": "assets/js/19.1cd295ce.js",
    "revision": "9e9fe329d7fd6ce3a7ed96e96bc7a633"
  },
  {
    "url": "assets/js/2.71747a20.js",
    "revision": "bdec522a5510810905db47d8694a8533"
  },
  {
    "url": "assets/js/20.58394086.js",
    "revision": "c052d7682479b9d11dc0f74f04b2d053"
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
    "url": "assets/js/9.36250ecd.js",
    "revision": "7a4a8b12018a8f1debdd5271ba0ca1a7"
  },
  {
    "url": "assets/js/app.33737e93.js",
    "revision": "9a383dd708252abd2531a7dbd22c9c44"
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
    "revision": "23e33c7d237311989caf6932b2a900ed"
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
