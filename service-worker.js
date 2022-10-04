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
    "revision": "af4aeaddc2589740d200416bff913761"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "1477bbd7b1b82ebb3570296717fab3fc"
  },
  {
    "url": "03-trials.html",
    "revision": "bc599ba46dd1f71363bc680656d41ab4"
  },
  {
    "url": "04-charges.html",
    "revision": "e082209d7769e24047bd7a2c1749cf0c"
  },
  {
    "url": "05-metered.html",
    "revision": "358d520646ff42c987274950b54e882e"
  },
  {
    "url": "06-customer.html",
    "revision": "eb67885f373573407470858bc65af425"
  },
  {
    "url": "07-invoices.html",
    "revision": "ec9852d9c54cc726bd253f288a0a4144"
  },
  {
    "url": "08-refunds.html",
    "revision": "bbffbb5111911e3d2f72fc312eb2c5e9"
  },
  {
    "url": "09-events.html",
    "revision": "a9fcdb5a3890255d9c14ca022ca5ee97"
  },
  {
    "url": "10-webhook.html",
    "revision": "42e4f5a5c94626aeb30f67a82ae80c21"
  },
  {
    "url": "11-testing.html",
    "revision": "6902c4860a411f65b8e8c8cdb50e1afb"
  },
  {
    "url": "12-faq.html",
    "revision": "7e7559e21e2099391643990149846da1"
  },
  {
    "url": "13-upgrade.html",
    "revision": "a37ebb1da6656b0b0ace56f1bf7ad6a4"
  },
  {
    "url": "14-retry.html",
    "revision": "25e6fdd542db5b771de782bd26492b07"
  },
  {
    "url": "15-localization.html",
    "revision": "8803b2e55bedcfaf682614c74ac39f96"
  },
  {
    "url": "16-configuration.html",
    "revision": "ab74e813ea1ab3d85c986fd93052effc"
  },
  {
    "url": "404.html",
    "revision": "8adfcf740f0dc82aa67dfc04ec9ab768"
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
    "url": "assets/css/0.styles.ce263dea.css",
    "revision": "94003a900c58188582215d92ada1316f"
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
    "url": "assets/js/10.8612861b.js",
    "revision": "5d3e5d1a386e154dc953297696c2025c"
  },
  {
    "url": "assets/js/11.38df5dcf.js",
    "revision": "4ff72fc1ff0205bed8b54ea56f7432a2"
  },
  {
    "url": "assets/js/12.cffc4c84.js",
    "revision": "c55cca7738262faf9c25c83296a818f0"
  },
  {
    "url": "assets/js/13.2473e2df.js",
    "revision": "a00034179a3578e7480f70be1112e99c"
  },
  {
    "url": "assets/js/14.1d2f9a32.js",
    "revision": "16ae6591abf17b926b198ea01e1a832f"
  },
  {
    "url": "assets/js/15.ef203a0f.js",
    "revision": "99626727ebf49058f9f55e496023ee6c"
  },
  {
    "url": "assets/js/16.10f032f7.js",
    "revision": "50794c9b38d443548238bb8e2a8b0e23"
  },
  {
    "url": "assets/js/17.e7494da9.js",
    "revision": "7dec2d23cae7a7a641bf419d0e494c51"
  },
  {
    "url": "assets/js/18.72d8a6a0.js",
    "revision": "f4890a9c5a06d18c05fb042fb55a226d"
  },
  {
    "url": "assets/js/19.5f8d2de8.js",
    "revision": "c596c8beca50c012d3b7cde7c781db47"
  },
  {
    "url": "assets/js/2.a7657729.js",
    "revision": "153d2e019608d653e0a04af814eb7e52"
  },
  {
    "url": "assets/js/20.d31dd05f.js",
    "revision": "6b67d5e12ca113a739c2a9a6af3c7637"
  },
  {
    "url": "assets/js/21.56aba49f.js",
    "revision": "e61c55990d632711789282a8e7833452"
  },
  {
    "url": "assets/js/22.c4cd0136.js",
    "revision": "0aca239d4fc98fd8053b77a925d0bce4"
  },
  {
    "url": "assets/js/23.2c649c08.js",
    "revision": "41d22ea0f6770d5e053f912565d84acd"
  },
  {
    "url": "assets/js/24.f571be51.js",
    "revision": "8035ae9ebef65a86ed636578cf3d52a1"
  },
  {
    "url": "assets/js/25.e9d4803b.js",
    "revision": "64efe396c29a1006fd21955bb473c02e"
  },
  {
    "url": "assets/js/3.f73f2a2b.js",
    "revision": "333abd8cb378d979755796fa9b34beae"
  },
  {
    "url": "assets/js/4.ec288f54.js",
    "revision": "e338088bee2d7c1f3a76fd8727b79f2e"
  },
  {
    "url": "assets/js/5.fdc1d316.js",
    "revision": "a0d09cd8ce48c03b8081676b8cfc854a"
  },
  {
    "url": "assets/js/6.540f62c1.js",
    "revision": "f87c98839cad77561ea83f8745d18587"
  },
  {
    "url": "assets/js/7.cf46d5fd.js",
    "revision": "ce2fc06256991f6b8fd56cc89bc7daa7"
  },
  {
    "url": "assets/js/8.ada048f8.js",
    "revision": "ac456e070b81316510e7331e802be828"
  },
  {
    "url": "assets/js/9.bf664e33.js",
    "revision": "59e0360e84176a4130d028485bb1be1b"
  },
  {
    "url": "assets/js/app.4fc9ed0c.js",
    "revision": "458d8151d868f03490bf03752ed0d3be"
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
    "revision": "23d186b749c484c95796e617375b8a56"
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
