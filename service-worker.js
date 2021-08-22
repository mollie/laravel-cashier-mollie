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
    "revision": "529e8a2151fe52a41644dc3e8005081f"
  },
  {
    "url": "01-installation.html",
    "revision": "48c0b7ecd1351fc41fcb907e1ceac9f9"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "fe5248987cc4b3819840205861398db6"
  },
  {
    "url": "03-trials.html",
    "revision": "f2b65e70068be7e5ee75937fe31faca3"
  },
  {
    "url": "04-charges.html",
    "revision": "a1777c9458e9b7941364a7675a1853e1"
  },
  {
    "url": "05-metered.html",
    "revision": "71ef663d9a694bfa913350036ac1fe16"
  },
  {
    "url": "06-customer.html",
    "revision": "ca7711805c086c0d6c408580ba5c10ed"
  },
  {
    "url": "07-invoices.html",
    "revision": "bde9e589f7d1ba104c112c7d2f0d4e3b"
  },
  {
    "url": "08-refunds.html",
    "revision": "615f2c88b13117b3f2e35ed4a9a2f11a"
  },
  {
    "url": "09-events.html",
    "revision": "ea56887582aaf23a2231115aa698e2a4"
  },
  {
    "url": "10-webhook.html",
    "revision": "ba735da8865f86edec5587a9a4b36c77"
  },
  {
    "url": "11-testing.html",
    "revision": "9fd62eb6b89cb535f52b902dc6416e54"
  },
  {
    "url": "12-faq.html",
    "revision": "cc3a9c89e2a5cc38c05b32066c01ca1c"
  },
  {
    "url": "13-upgrade.html",
    "revision": "445dc2f3e2ed89181342fea5984b8392"
  },
  {
    "url": "404.html",
    "revision": "959536d899c553fd3bf30d1c5696021f"
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
    "url": "assets/js/10.8d46c5ee.js",
    "revision": "67df51de32cbd1a96360329d3d3fb222"
  },
  {
    "url": "assets/js/11.93e5f7fe.js",
    "revision": "895305c281a748e638b65a6752fdaa57"
  },
  {
    "url": "assets/js/12.a5c83118.js",
    "revision": "eed49c918f641e1bba65c2f6e2972c00"
  },
  {
    "url": "assets/js/13.f6974c27.js",
    "revision": "a1adb5899caefe2ba88542016b27d575"
  },
  {
    "url": "assets/js/14.154e0cc0.js",
    "revision": "c3550b6a3fc848369b3ff407d1c3319f"
  },
  {
    "url": "assets/js/15.26d6c284.js",
    "revision": "3a6bde468bcc988f862ffed4799f5952"
  },
  {
    "url": "assets/js/16.5aa57e8f.js",
    "revision": "ffcc4979d6471412712d1fbb89e33493"
  },
  {
    "url": "assets/js/17.871c60f3.js",
    "revision": "af36cf88182c9f5123e57371eb453d32"
  },
  {
    "url": "assets/js/18.ffc3ee5f.js",
    "revision": "93505d823fbc38a53b49437b4b2cefdf"
  },
  {
    "url": "assets/js/19.19b6408e.js",
    "revision": "f9af1c50bceba4e3ebc872a4199e1099"
  },
  {
    "url": "assets/js/2.d2159a5d.js",
    "revision": "7d9eaca7109518a91332afaca3ba0ccb"
  },
  {
    "url": "assets/js/20.4696e028.js",
    "revision": "cd669ca47f7834f26782bb44932777a5"
  },
  {
    "url": "assets/js/21.70b50cbc.js",
    "revision": "ed3b253eab9b73aef16cf98e45fee98b"
  },
  {
    "url": "assets/js/22.75ba4773.js",
    "revision": "e471ce11f6dcdbdda9207463c23cd5e3"
  },
  {
    "url": "assets/js/23.31801bca.js",
    "revision": "009509f89b41d2e640ebcf08ed8c5334"
  },
  {
    "url": "assets/js/3.479c439a.js",
    "revision": "bb5838a205640356bae48926a47adc89"
  },
  {
    "url": "assets/js/4.a4a1d908.js",
    "revision": "9fa757b321e6e8f5f2cb0684aea2c7fb"
  },
  {
    "url": "assets/js/5.1cd88ab0.js",
    "revision": "96ea102b2d1f7931188fd20b687dcb94"
  },
  {
    "url": "assets/js/6.f6279bce.js",
    "revision": "58e82b753f9395b06b77bf927b233c6d"
  },
  {
    "url": "assets/js/7.15ce5e6a.js",
    "revision": "680ffd1446fed49989ad4692c7a1f05d"
  },
  {
    "url": "assets/js/8.a8f690d8.js",
    "revision": "10802b152820bddaf1cbb00a12cc6366"
  },
  {
    "url": "assets/js/9.8665ac59.js",
    "revision": "499c843e24711d4be4db05f3860d9684"
  },
  {
    "url": "assets/js/app.580242de.js",
    "revision": "cd76f2b5bbc87234473aa13d785011b7"
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
    "revision": "b6215c24a0765c87c000bbb8d6824b6f"
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
