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
    "revision": "9d35930f2c10fc87300eae816474db36"
  },
  {
    "url": "01-installation.html",
    "revision": "036cd415bac5f660aa228a75b843f8cb"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "8ec7a71bb03e7b7b7eb7eac9fcb13010"
  },
  {
    "url": "03-trials.html",
    "revision": "329bccac80387101ffaac083a1021903"
  },
  {
    "url": "04-charges.html",
    "revision": "f9ddcf7a91e314f844373adf67accef1"
  },
  {
    "url": "05-metered.html",
    "revision": "1f9db410cbe90f7619478aa78998b977"
  },
  {
    "url": "06-customer.html",
    "revision": "0c64ac0f677d15475b91cde9812feb0b"
  },
  {
    "url": "07-invoices.html",
    "revision": "bfd60c504ec08bbb4922b34f024c9cbe"
  },
  {
    "url": "08-refunds.html",
    "revision": "97738c15b274ec209a3279a27a8ce328"
  },
  {
    "url": "09-events.html",
    "revision": "ff636055aed9079ddffb781e4bae5da7"
  },
  {
    "url": "10-webhook.html",
    "revision": "a288e3dfb8eb32e9502a32d6ddc74287"
  },
  {
    "url": "11-testing.html",
    "revision": "43c1cfe6f3e552f1157c7c6e291ef2a2"
  },
  {
    "url": "12-faq.html",
    "revision": "7b9abc890ed50a6f2f7db80ec67019df"
  },
  {
    "url": "13-upgrade.html",
    "revision": "e900e04ca30c262689100902de612841"
  },
  {
    "url": "404.html",
    "revision": "f92a0613c1132b17d8f0ee67a9960100"
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
    "url": "assets/js/16.8e613490.js",
    "revision": "56799884b3dc93601e63ea5767af5d8d"
  },
  {
    "url": "assets/js/17.85d17706.js",
    "revision": "021ce8a834233cb74396ec8204e2aa4a"
  },
  {
    "url": "assets/js/18.2c3b8911.js",
    "revision": "273cefa1239300f7f612dd85b28f5739"
  },
  {
    "url": "assets/js/19.cc608b67.js",
    "revision": "ebb63804489dcd44be48ce1769ce651e"
  },
  {
    "url": "assets/js/2.8b6200c7.js",
    "revision": "6fdec37b9ad6038ef08a27980162b0ac"
  },
  {
    "url": "assets/js/20.24ad2b54.js",
    "revision": "a0b8583a9380b71923a2c786bc7d1579"
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
    "url": "assets/js/app.543c9352.js",
    "revision": "d37b7be090481b0a70736069833ef314"
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
    "revision": "e67edb0544f4c84d3cb782d2fba6301c"
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
