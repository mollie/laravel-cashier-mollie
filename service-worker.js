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
    "url": "00-installation.html",
    "revision": "0f097176bf3b1c13ee99e0eed5c7896a"
  },
  {
    "url": "01-installation.html",
    "revision": "a165f8c0abbe2ab421c44401a93305ab"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "b48289b40532e29c75a5e26de8ebff62"
  },
  {
    "url": "03-trials.html",
    "revision": "db23f82fd4b0b132b7fe4c0cb698172a"
  },
  {
    "url": "04-charges.html",
    "revision": "a4cb62b1ff89268034b09dfda0966969"
  },
  {
    "url": "05-metered.html",
    "revision": "528255aa05acbc40fb0464b7924f5017"
  },
  {
    "url": "06-customer.html",
    "revision": "b0bb0dca03d97055259b5a11a3648df7"
  },
  {
    "url": "07-invoices.html",
    "revision": "1552dd12166243df03554f9080a39672"
  },
  {
    "url": "08-refunds.html",
    "revision": "7811529507232c54fe182f95f58a2b10"
  },
  {
    "url": "09-events.html",
    "revision": "cae638c25f1bef373fc05bfa59fa4d3a"
  },
  {
    "url": "10-webhook.html",
    "revision": "01ec8a11843d1821e622d551f4fa382a"
  },
  {
    "url": "11-testing.html",
    "revision": "39f64536ccdadb48a1eac107c06b60d2"
  },
  {
    "url": "12-faq.html",
    "revision": "c0e09aea0f8007df31c81d062c4aa9e5"
  },
  {
    "url": "13-upgrade.html",
    "revision": "59ce9bc56d247282ed9e6c72d30677b9"
  },
  {
    "url": "14-retry.html",
    "revision": "a12acb9af948d8adb4d1dc9e7df0c5ca"
  },
  {
    "url": "404.html",
    "revision": "8d48d767c27644a487eac28d75cb8a28"
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
    "url": "assets/js/10.58668bc5.js",
    "revision": "3d18b7e15b07bd9fe5f5a989842c3dc6"
  },
  {
    "url": "assets/js/11.deab8466.js",
    "revision": "dd7de4934ac5fe4d56600393fa661add"
  },
  {
    "url": "assets/js/12.01dddea8.js",
    "revision": "235c47c6bac0e12143355a1daf4792d6"
  },
  {
    "url": "assets/js/13.9a7c1e29.js",
    "revision": "595e8c6e0b43d4d30e486d1bab221ee7"
  },
  {
    "url": "assets/js/14.7029b215.js",
    "revision": "b93c1f33650411810121d1cae46e80f5"
  },
  {
    "url": "assets/js/15.1894b764.js",
    "revision": "fc094d1b15db40622f2fbda4769c7640"
  },
  {
    "url": "assets/js/16.870df960.js",
    "revision": "50901af51500c57e0bfae6e5dbaa915f"
  },
  {
    "url": "assets/js/17.f326c0c6.js",
    "revision": "6d28b20036a346092f9c3b97da2cc656"
  },
  {
    "url": "assets/js/18.a97bec3a.js",
    "revision": "f9e387e594b9441efc96d77079bc92df"
  },
  {
    "url": "assets/js/19.1efb6fc9.js",
    "revision": "2fbd93a6afbc46c6e8df7f097e62e2d4"
  },
  {
    "url": "assets/js/2.6952a2b9.js",
    "revision": "652e163d8c63cedffe86162734d234ec"
  },
  {
    "url": "assets/js/20.c6acd820.js",
    "revision": "de8bed714e28bdb80931f42e95431798"
  },
  {
    "url": "assets/js/21.13c4549b.js",
    "revision": "8414c324f7865d2068c41df1dfbdb997"
  },
  {
    "url": "assets/js/22.2e691448.js",
    "revision": "1436643d9cb1b36959586b5d9580ac88"
  },
  {
    "url": "assets/js/23.ace833c4.js",
    "revision": "e57093c0d482318868d64647b298a385"
  },
  {
    "url": "assets/js/24.b1080bef.js",
    "revision": "18b8db0efe9646af39ea44fe82a1d7e5"
  },
  {
    "url": "assets/js/3.fa274cbf.js",
    "revision": "daa4fa3dbcff43553926ec9e0117302e"
  },
  {
    "url": "assets/js/4.70d5c80b.js",
    "revision": "2ade4dea3d9f9e9fd62c00cca0919e50"
  },
  {
    "url": "assets/js/5.1681f43e.js",
    "revision": "7921397f289b9662c76a5ab21fa299f3"
  },
  {
    "url": "assets/js/6.3795202e.js",
    "revision": "e74829f64c1be1aa20ff383591a43027"
  },
  {
    "url": "assets/js/7.f22e9687.js",
    "revision": "aa83f3f92db8144d41127dfe23acf1cb"
  },
  {
    "url": "assets/js/8.60b99c60.js",
    "revision": "9b3bb5635a52c2b1af2edd9335a51a6f"
  },
  {
    "url": "assets/js/9.2c12574d.js",
    "revision": "af0f327248a4eb4c4e3b02df7f28e43b"
  },
  {
    "url": "assets/js/app.ea1d408d.js",
    "revision": "02ff762fed1e8b904fa96011e64b8c7d"
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
    "revision": "86aedb2d2d84aa152cd7ba63432c7ad5"
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
