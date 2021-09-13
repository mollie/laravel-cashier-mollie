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
    "revision": "e8209dfa1f45db30225b12eefc9dbc1c"
  },
  {
    "url": "01-installation.html",
    "revision": "e5ec900bacb8384f0f820470f7438186"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "aaf992d7e3ea18990575dccd905314f0"
  },
  {
    "url": "03-trials.html",
    "revision": "ad15c74624f2310c966ed920468eef76"
  },
  {
    "url": "04-charges.html",
    "revision": "b8d27662220add2f01683f790419baf6"
  },
  {
    "url": "05-metered.html",
    "revision": "74324895ceb8675d13f2e20f8b06b90f"
  },
  {
    "url": "06-customer.html",
    "revision": "dfee9b51dc4ef6b2c08fa2e0c687a159"
  },
  {
    "url": "07-invoices.html",
    "revision": "45138f74ff51e829d3eaf0674c801491"
  },
  {
    "url": "08-refunds.html",
    "revision": "1ed6bdb3cb8a4f81d16e5f09fb67300c"
  },
  {
    "url": "09-events.html",
    "revision": "599537cde6582ad69c73139fa2cc3f28"
  },
  {
    "url": "10-webhook.html",
    "revision": "cb8dd23d08565bf7dec14fb8887c64e8"
  },
  {
    "url": "11-testing.html",
    "revision": "c942626d10eaf195c2ae8aab59ec0efa"
  },
  {
    "url": "12-faq.html",
    "revision": "be329f5e98252aeeaa4e057941b33562"
  },
  {
    "url": "13-upgrade.html",
    "revision": "4fd82169bbf209e7a778beede6504c81"
  },
  {
    "url": "14-retry.html",
    "revision": "a730c9ba244132c166e41f55b69ca247"
  },
  {
    "url": "404.html",
    "revision": "614c05fc926a35c535b8ca6567cba565"
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
    "url": "assets/js/10.8905d9e9.js",
    "revision": "3406aa986fa71e48cfcb1a18f71e3fc8"
  },
  {
    "url": "assets/js/11.400a032b.js",
    "revision": "7819e70b9d6b53233626a1e92387c9f0"
  },
  {
    "url": "assets/js/12.199a7f12.js",
    "revision": "37c6f1b2e77df3f6765219c00b149a21"
  },
  {
    "url": "assets/js/13.330ecb83.js",
    "revision": "165529fcb422c53b346b74b2e3ceb9ae"
  },
  {
    "url": "assets/js/14.1bf3ff44.js",
    "revision": "1655fca9e3f8b85a8da150ab34a1c893"
  },
  {
    "url": "assets/js/15.381cc754.js",
    "revision": "7ba5da5bd1292fcb1db624b4a060619a"
  },
  {
    "url": "assets/js/16.aac1270d.js",
    "revision": "5e61b5245ed2cfe1f0a41f0760a64a6a"
  },
  {
    "url": "assets/js/17.102c2308.js",
    "revision": "d071ec3d6fd2c9cb1fcdcea69a68bacf"
  },
  {
    "url": "assets/js/18.886d7d4c.js",
    "revision": "13c581087ef37312117ba2147952af7e"
  },
  {
    "url": "assets/js/19.020abfed.js",
    "revision": "a3b99a416add017269f29e8a87e7d0bb"
  },
  {
    "url": "assets/js/2.9546977a.js",
    "revision": "d90e1592fdfe70f8173325fffe37dbb3"
  },
  {
    "url": "assets/js/20.13bdeb92.js",
    "revision": "3e9cd9eee0367e57cad51287ef3d28ea"
  },
  {
    "url": "assets/js/21.8c1448d8.js",
    "revision": "3a1722fa5b211246a73ceecf125395cf"
  },
  {
    "url": "assets/js/22.cd1acc54.js",
    "revision": "824e5770c3bf71f5643ebcb027f9a2c9"
  },
  {
    "url": "assets/js/23.dd88ba55.js",
    "revision": "7597138663d60fd45e502153f64b7a71"
  },
  {
    "url": "assets/js/24.631a59a4.js",
    "revision": "8709414e2ec8429ffe8d826eabcf6e87"
  },
  {
    "url": "assets/js/3.48b18616.js",
    "revision": "fed7e03efdb952beb2eb224bf228b4e2"
  },
  {
    "url": "assets/js/4.39f8c929.js",
    "revision": "66b79d7045e03ce66230c0f214636dfb"
  },
  {
    "url": "assets/js/5.0239c790.js",
    "revision": "6189e7924662cd647f27689c41387532"
  },
  {
    "url": "assets/js/6.7aeb4e0b.js",
    "revision": "8fdb7902c28c00bf7299c8a59e85ebd6"
  },
  {
    "url": "assets/js/7.ebdaec88.js",
    "revision": "10fec0460e5d578082c2dfb3ffbe0319"
  },
  {
    "url": "assets/js/8.87bb0e49.js",
    "revision": "5d59327542efb71269c8c2402b056391"
  },
  {
    "url": "assets/js/9.b1a9c499.js",
    "revision": "37f2b4c35f4f173a28068fcd4b6ae100"
  },
  {
    "url": "assets/js/app.fdaa0aa5.js",
    "revision": "f26398f6f4959f5f4f43a50a355c32a8"
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
    "revision": "25e7f097cdd1b19bf4700ae837bcf0c0"
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
