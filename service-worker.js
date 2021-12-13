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
    "revision": "64981b9bc788f1f57779e9dde419b916"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "ee35f67dede25d38279c9420c22e243a"
  },
  {
    "url": "03-trials.html",
    "revision": "5ffad30e54d2bf6f46a4dad2c9522371"
  },
  {
    "url": "04-charges.html",
    "revision": "23f77b953b9956870e0cb5c6d49b29ef"
  },
  {
    "url": "05-metered.html",
    "revision": "99fa3d111d444ebcc9b9e14895be0656"
  },
  {
    "url": "06-customer.html",
    "revision": "84fdbacb8926c24b16e134715d3d8e05"
  },
  {
    "url": "07-invoices.html",
    "revision": "6d36267e4c71156e578752a6359ff014"
  },
  {
    "url": "08-refunds.html",
    "revision": "5f3c552215a280c428092a4412ad14b8"
  },
  {
    "url": "09-events.html",
    "revision": "68af6ca0710498fcd8fc6b8038383f06"
  },
  {
    "url": "10-webhook.html",
    "revision": "39a8e5a4458edb81150020a24ac170f5"
  },
  {
    "url": "11-testing.html",
    "revision": "21d086ffa242c132b2e1aa6a4ba687a1"
  },
  {
    "url": "12-faq.html",
    "revision": "f67822d922161bba41625e3269dc67f6"
  },
  {
    "url": "13-upgrade.html",
    "revision": "4e6613f1df1866888815597d7d2021bf"
  },
  {
    "url": "14-retry.html",
    "revision": "fe1dd7435f22bf6d8fddf0f91e2f7e44"
  },
  {
    "url": "15-localization.html",
    "revision": "2f8055ad074bfff976db7b14be02a9cc"
  },
  {
    "url": "404.html",
    "revision": "91b9e3f16c2f57bf3ea08983b8ae0954"
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
    "url": "assets/css/0.styles.8d363c57.css",
    "revision": "86b0fd77cc538defc1d3f70fc50d22c8"
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
    "url": "assets/js/10.10907cbf.js",
    "revision": "88118981a832fae0c1e6dbae10ee9bc7"
  },
  {
    "url": "assets/js/11.06d48f1a.js",
    "revision": "56edffcebfa5b6b861f2f0f7f4431377"
  },
  {
    "url": "assets/js/12.b1b4f26a.js",
    "revision": "dd1e4763d4339d246784a8b2346fb870"
  },
  {
    "url": "assets/js/13.a368191a.js",
    "revision": "42dec482bda19de54456c3447d52f6e3"
  },
  {
    "url": "assets/js/14.cc8e3b38.js",
    "revision": "fb651838ab400c4c58b10d5512fc1872"
  },
  {
    "url": "assets/js/15.e5b7484e.js",
    "revision": "40aed53ce60cbae8f13430164339cc42"
  },
  {
    "url": "assets/js/16.1699dcd5.js",
    "revision": "411fa84db7a07aa1f211e601f5a286c8"
  },
  {
    "url": "assets/js/17.0203c377.js",
    "revision": "8714daf39855b8f03ace53c78f5b84f5"
  },
  {
    "url": "assets/js/18.fca08ff7.js",
    "revision": "d27d0f7be67fb52b371c541b54bf926c"
  },
  {
    "url": "assets/js/19.b40fc194.js",
    "revision": "8f47743734698feb5da6f5547629e510"
  },
  {
    "url": "assets/js/2.9a92ed29.js",
    "revision": "d6bb4eabe15de3cb24ed399fd18623ae"
  },
  {
    "url": "assets/js/20.21920152.js",
    "revision": "a117e57a0be77a21836101be0de5840a"
  },
  {
    "url": "assets/js/21.29c4e7cb.js",
    "revision": "8981c4a1a822736fe32befe897573b07"
  },
  {
    "url": "assets/js/22.4dbaffa7.js",
    "revision": "056a9bde8189edde4d00d2b80f3b95f8"
  },
  {
    "url": "assets/js/23.d9c71a34.js",
    "revision": "ebd1a815b2925c8792e7ae523e7c0451"
  },
  {
    "url": "assets/js/24.e4940cdf.js",
    "revision": "1af3a2e2a12c6a066cf4bf3baa87649c"
  },
  {
    "url": "assets/js/3.ef87c80f.js",
    "revision": "79a7f9f0eca783e7b2b7bdf6a1179e6e"
  },
  {
    "url": "assets/js/4.c942773d.js",
    "revision": "7a8fd493b7b3dec709ccc7ff1a017202"
  },
  {
    "url": "assets/js/5.d52db440.js",
    "revision": "020bc69a323754abf041eeca903594f8"
  },
  {
    "url": "assets/js/6.d94c8d48.js",
    "revision": "86025134757914bca6638500df08cbb9"
  },
  {
    "url": "assets/js/7.1c1e2dca.js",
    "revision": "1e0b27f6d80cb4bb09417ca379df3dcd"
  },
  {
    "url": "assets/js/8.3bbd489b.js",
    "revision": "a64d15335a4f22a11b703358ca59369e"
  },
  {
    "url": "assets/js/9.e116eba4.js",
    "revision": "1ba6c11c8dd55e5d36870774065d5c42"
  },
  {
    "url": "assets/js/app.8adb4762.js",
    "revision": "28662d2687505c2c03cecdf6f0ae4699"
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
    "revision": "2344b951d73492cc619b5fb1b47d7173"
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
