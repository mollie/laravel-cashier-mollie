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
    "revision": "5a1998967f39eb833e0524a015a48fbb"
  },
  {
    "url": "01-installation.html",
    "revision": "dbb794ffc8434ab7488ed15767b6efc5"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "b780d8f029096bd7dded9670b1611b8f"
  },
  {
    "url": "03-trials.html",
    "revision": "c1d204f85e1a1f6ef3276f0ed932ff58"
  },
  {
    "url": "04-charges.html",
    "revision": "2fffd30103fd2e0896af52a059ab21b9"
  },
  {
    "url": "05-metered.html",
    "revision": "7aacf7eaae739db5c29f1de40e907fb9"
  },
  {
    "url": "06-customer.html",
    "revision": "c2583beddd28d6800162a8aa84e29f8c"
  },
  {
    "url": "07-invoices.html",
    "revision": "094320c98a9ba65951bb5839886ff99d"
  },
  {
    "url": "08-refunds.html",
    "revision": "ed63d4a1d24868332ead0faf6ae0a50d"
  },
  {
    "url": "09-events.html",
    "revision": "46a89828697b3449e49991cbd2c7f13d"
  },
  {
    "url": "10-webhook.html",
    "revision": "14f6ec276e122bb449ffc862fbab3fec"
  },
  {
    "url": "11-testing.html",
    "revision": "97e4d235e8cc3be92f9973bf704cd048"
  },
  {
    "url": "12-faq.html",
    "revision": "80a730667d6a9267d2b3a21640ecf733"
  },
  {
    "url": "13-upgrade.html",
    "revision": "6e0cf2914fdf1757952315e6945712dd"
  },
  {
    "url": "404.html",
    "revision": "3286c6c4b26b7f3bd6f926f780a4591a"
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
    "url": "assets/js/10.f74b7e28.js",
    "revision": "ae985ec4421a852c2fb0145d6d2b947d"
  },
  {
    "url": "assets/js/11.4ebd195c.js",
    "revision": "0a06a6a3ca24d2a5e5b94a7ff0b57958"
  },
  {
    "url": "assets/js/12.1b1155b2.js",
    "revision": "acbf54acbc3880336c2b0ffb0798745a"
  },
  {
    "url": "assets/js/13.7926b8c1.js",
    "revision": "216ec9f7bb25ef14a3dfd17c977bd4e8"
  },
  {
    "url": "assets/js/14.457604d0.js",
    "revision": "566836eb550160a3c4fd733540c73184"
  },
  {
    "url": "assets/js/15.ec567912.js",
    "revision": "33998fa9003a0615532c32cefbdf5f41"
  },
  {
    "url": "assets/js/16.704f3e6d.js",
    "revision": "8e07b1bc86150556f152d528858d13f1"
  },
  {
    "url": "assets/js/17.0e4a298d.js",
    "revision": "a05cd5ef2d2eb6356b921351f2a51133"
  },
  {
    "url": "assets/js/18.740267ab.js",
    "revision": "6a48b5da4e0f7e42e9d9edbb2ea58e33"
  },
  {
    "url": "assets/js/19.8b7a4edd.js",
    "revision": "9aae833530123f1371eeef83df30b075"
  },
  {
    "url": "assets/js/2.8b6200c7.js",
    "revision": "6fdec37b9ad6038ef08a27980162b0ac"
  },
  {
    "url": "assets/js/20.3e14ad54.js",
    "revision": "f821648ccc73f0102907f47c69e3ca7b"
  },
  {
    "url": "assets/js/21.0538dce3.js",
    "revision": "22ad4632f849d59cc928d2b43ac33af5"
  },
  {
    "url": "assets/js/22.5f94f3c1.js",
    "revision": "35c27bf27e08b58f00de6a846018a6c7"
  },
  {
    "url": "assets/js/23.1f26728f.js",
    "revision": "22794c2fb49dcb78ee22c7f846357423"
  },
  {
    "url": "assets/js/3.ff64d913.js",
    "revision": "584eb7d0f2d76393abe09a2aaae8eeca"
  },
  {
    "url": "assets/js/4.c7081b42.js",
    "revision": "cbc09aab26919db3d15ce9ac2f4074b5"
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
    "url": "assets/js/7.d6207137.js",
    "revision": "5388743fcca711ac9e4af0bc71d4384a"
  },
  {
    "url": "assets/js/8.a170632b.js",
    "revision": "f641a0e16501d19b3eb0d76258d83a37"
  },
  {
    "url": "assets/js/9.58dbd6b4.js",
    "revision": "8faef5f2d56905761b0e77154488493e"
  },
  {
    "url": "assets/js/app.75f6593e.js",
    "revision": "b147ea1ad6cd51227d4c11c70c6d0415"
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
    "revision": "b75b314e056283a64323338b3488ac77"
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
