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
    "revision": "cfda96222f54e52442678c70e2275a55"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "e1dc44b42b74d5cbf89e7bb5715a08d9"
  },
  {
    "url": "03-trials.html",
    "revision": "89a59f973ad88b205fa9db95bc3f8791"
  },
  {
    "url": "04-charges.html",
    "revision": "487f4f2dfc46deb1ef8f947cef68cc25"
  },
  {
    "url": "05-metered.html",
    "revision": "3b9d7b39828424811ba75b25c5927314"
  },
  {
    "url": "06-customer.html",
    "revision": "5f5a1dea6895451e370dbad7a34c2de5"
  },
  {
    "url": "07-invoices.html",
    "revision": "70e4593d13f3e9d62a262f9b8c39e97e"
  },
  {
    "url": "08-refunds.html",
    "revision": "ee7b22c2a8f29113a744fdb15643d2c0"
  },
  {
    "url": "09-events.html",
    "revision": "15bd1aa8648cdd2dac16183dfd39a616"
  },
  {
    "url": "10-webhook.html",
    "revision": "fbc9862f0f759872ee84e70d13fe6a6c"
  },
  {
    "url": "11-testing.html",
    "revision": "f0aa0ff6fc0a4ea04f7744e80cbe7cd3"
  },
  {
    "url": "12-faq.html",
    "revision": "64b8a64b835f0417d8335e9caf613967"
  },
  {
    "url": "13-upgrade.html",
    "revision": "23c231a77b0a2ea03a64366d7f462566"
  },
  {
    "url": "14-retry.html",
    "revision": "def636836de1e8c1c6a882e1a949db22"
  },
  {
    "url": "15-localization.html",
    "revision": "e20d6b62164e8a58c47d952081f2cf58"
  },
  {
    "url": "16-configuration.html",
    "revision": "121f83ca2aa5cddff1c360c77187651f"
  },
  {
    "url": "404.html",
    "revision": "4306280f77e5eae3d6908c8af12c0dde"
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
    "url": "assets/css/0.styles.359cd878.css",
    "revision": "d2acd007420eb02d9daa36f53e706e3e"
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
    "url": "assets/js/1.443a19a3.js",
    "revision": "6f9ef8c119dbf5c7c883338654108270"
  },
  {
    "url": "assets/js/10.e3f316a5.js",
    "revision": "f7e6c742439b2d227d392d3ad8781f8f"
  },
  {
    "url": "assets/js/11.5a916e61.js",
    "revision": "dbd9447d3e2152bff7776aaef2ca8652"
  },
  {
    "url": "assets/js/12.8c3cca42.js",
    "revision": "8576f39f0cf287a8ab2ce50136b13a87"
  },
  {
    "url": "assets/js/13.5d49f23d.js",
    "revision": "33a40dce55137fe55a6ae3c922d12307"
  },
  {
    "url": "assets/js/14.f48e17ec.js",
    "revision": "7669f9856738044b1fbe7f68c9d17a81"
  },
  {
    "url": "assets/js/15.da79bb24.js",
    "revision": "df892b7d8fef45347b7d439744f28898"
  },
  {
    "url": "assets/js/16.627e77af.js",
    "revision": "8dfbed7ff5f127f014e89f3e9c79509b"
  },
  {
    "url": "assets/js/17.7abefb57.js",
    "revision": "6850326a3d053a128c6e8bfb55315eea"
  },
  {
    "url": "assets/js/18.263c2ca2.js",
    "revision": "7ebe8acf90452f2d745bf34fc08b68e9"
  },
  {
    "url": "assets/js/19.0c5f190e.js",
    "revision": "f7d0a510466dce8afef22987ee6fe631"
  },
  {
    "url": "assets/js/2.6e6fd555.js",
    "revision": "273124a951551f3fd8c8f5ae870b76b6"
  },
  {
    "url": "assets/js/20.ce6af2be.js",
    "revision": "54cb4315992d9f0acaa69c007cdfefb4"
  },
  {
    "url": "assets/js/21.5250a4d9.js",
    "revision": "464a26413d56e8abe1b241cb6347d200"
  },
  {
    "url": "assets/js/22.c681cd67.js",
    "revision": "e89d2017fc568067d1a73ee4fd21c87e"
  },
  {
    "url": "assets/js/23.1ac997ee.js",
    "revision": "820129f0d275810f2fdeccbe757eaecd"
  },
  {
    "url": "assets/js/24.480dd6d7.js",
    "revision": "a3d87195e4a7317f6af6d72f7996685d"
  },
  {
    "url": "assets/js/25.b15f47fa.js",
    "revision": "5bfa4466e33c081d2b9dc175b8d5aa68"
  },
  {
    "url": "assets/js/26.68f1afba.js",
    "revision": "c7837af1e8d76a37fc4de38402c915e1"
  },
  {
    "url": "assets/js/27.afb4f463.js",
    "revision": "32e8981985d3e73a81c58fb04f593245"
  },
  {
    "url": "assets/js/28.32b6af89.js",
    "revision": "627b9a78dffd92bdcf678267d2ac0fe6"
  },
  {
    "url": "assets/js/29.6e0f850a.js",
    "revision": "edb4b5a0e0a532a61368000a97b24a8c"
  },
  {
    "url": "assets/js/3.94398cba.js",
    "revision": "c73b4cdbe51f1208099cd29f8d128c7b"
  },
  {
    "url": "assets/js/30.057064b8.js",
    "revision": "a15307619e322f0c4292e58567df249d"
  },
  {
    "url": "assets/js/31.9414b0c9.js",
    "revision": "ca7d4e857c0cd441103487b87018f2bf"
  },
  {
    "url": "assets/js/32.0c814cc3.js",
    "revision": "fd42d559f18365552e8179f8205b2b28"
  },
  {
    "url": "assets/js/33.2c8f4400.js",
    "revision": "315d3b51ef9f3d75261cd588fb714e39"
  },
  {
    "url": "assets/js/34.2cb36d69.js",
    "revision": "5a4eb447e2b312e26ce0aebd2a985d7a"
  },
  {
    "url": "assets/js/35.2d5047a3.js",
    "revision": "efa3f0d4c845434b027d9c2a84bf4b5e"
  },
  {
    "url": "assets/js/36.fcdb2838.js",
    "revision": "e08c8c0d40ebd9196ec6268b13dee4e7"
  },
  {
    "url": "assets/js/37.f17cb91e.js",
    "revision": "b0136f959f6d6d41088c1e4fd5aee0b5"
  },
  {
    "url": "assets/js/38.ff925058.js",
    "revision": "9de056e851cfd0691f28bec6b44b6376"
  },
  {
    "url": "assets/js/39.f79daa81.js",
    "revision": "a297ddfc640d467f5eb6c61a9d9533ca"
  },
  {
    "url": "assets/js/4.96859a46.js",
    "revision": "110eeb4f648ba8dd894c2544cb48b2c7"
  },
  {
    "url": "assets/js/40.6a649407.js",
    "revision": "9da5ef73f15d2be65d4e17d07ccc5fda"
  },
  {
    "url": "assets/js/5.224ca9d9.js",
    "revision": "d478b6dedd994937987901af4857b075"
  },
  {
    "url": "assets/js/6.cb02add9.js",
    "revision": "fa33c717162387b5ab7b5149b3e5a989"
  },
  {
    "url": "assets/js/7.40e0f0b3.js",
    "revision": "9fba35f05bdad10a0f7c74346d83e3e7"
  },
  {
    "url": "assets/js/app.ca0760c0.js",
    "revision": "7a6dcfb53cdee7278002ea962c1507bd"
  },
  {
    "url": "assets/js/vendors~docsearch.c4e8fdb6.js",
    "revision": "e1b08506b923019cbab0207abdc4e52c"
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
    "revision": "df5acf994f7071c37fc4839bf2f6a82d"
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
