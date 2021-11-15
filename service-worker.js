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
    "revision": "27b823500bde484aa5849e8ae50c91eb"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "d30bcadbdddd3d96db65612fd884bcd1"
  },
  {
    "url": "03-trials.html",
    "revision": "01ed65ffbba97b0cfa038709bf3d2e71"
  },
  {
    "url": "04-charges.html",
    "revision": "6276e5eb1c36e79d19a48a7b94e4c1ec"
  },
  {
    "url": "05-metered.html",
    "revision": "8fb9a7eff5a99d4dd1d89a375a1df716"
  },
  {
    "url": "06-customer.html",
    "revision": "166de07739806c137e14276401f2de93"
  },
  {
    "url": "07-invoices.html",
    "revision": "c87052d9666e62bae5b1dc7357f49199"
  },
  {
    "url": "08-refunds.html",
    "revision": "76fd0ad87b34b225ad3972ebef7512b5"
  },
  {
    "url": "09-events.html",
    "revision": "792b37ffd1f8539020853b8a04b65a50"
  },
  {
    "url": "10-webhook.html",
    "revision": "df4e104e5e3c26853171bb65d7efb7b5"
  },
  {
    "url": "11-testing.html",
    "revision": "fe8074918074b6e0a4784ef7daf05c78"
  },
  {
    "url": "12-faq.html",
    "revision": "9a1eab886defd052bd9e206173bfe868"
  },
  {
    "url": "13-upgrade.html",
    "revision": "881322c62bc47005c3f27253effa94f5"
  },
  {
    "url": "14-retry.html",
    "revision": "f26a1f67e3cc2e4261d1bc65fe7cd856"
  },
  {
    "url": "404.html",
    "revision": "482e3c5a4aa43c5c0b08a7bc3f28ec8c"
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
    "url": "assets/js/10.231f0486.js",
    "revision": "740eafa4f21d483b8b8622f44866c3b0"
  },
  {
    "url": "assets/js/11.2f475b9a.js",
    "revision": "fc9b6a6b31eebc105ee522fe794ba2d4"
  },
  {
    "url": "assets/js/12.ffe0a3c9.js",
    "revision": "e9506f71d3b06daba324facfef9ed00d"
  },
  {
    "url": "assets/js/13.618fbab6.js",
    "revision": "d006d14c8f13797bb0fef91c93d6c10c"
  },
  {
    "url": "assets/js/14.a64eb087.js",
    "revision": "0c1b2085d1c29d5610cd7b19b3485f0f"
  },
  {
    "url": "assets/js/15.75945fcf.js",
    "revision": "f3f466382d26a174dbdeb65346f8aa12"
  },
  {
    "url": "assets/js/16.d67aa4c0.js",
    "revision": "747730294b70ad42b5d8028d25764e2c"
  },
  {
    "url": "assets/js/17.9b921c9f.js",
    "revision": "9c967aa5833b108e98bca66470b9dd5f"
  },
  {
    "url": "assets/js/18.5c043a5e.js",
    "revision": "dc46333b5798963bd8f721d0a6ee47c8"
  },
  {
    "url": "assets/js/19.4f65b9be.js",
    "revision": "9c2d37ff58b57e3eab03769969916e56"
  },
  {
    "url": "assets/js/2.6952a2b9.js",
    "revision": "652e163d8c63cedffe86162734d234ec"
  },
  {
    "url": "assets/js/20.5c446241.js",
    "revision": "2fec0d9861777029d19ba3c3aaccf719"
  },
  {
    "url": "assets/js/21.3d7a01cb.js",
    "revision": "48307989822e98ae275f58972e3b8e96"
  },
  {
    "url": "assets/js/22.9904d625.js",
    "revision": "03010c434058296fa57eaf894cf7db83"
  },
  {
    "url": "assets/js/23.ef7d42a5.js",
    "revision": "d52ba350089bd6c80751311b5c975491"
  },
  {
    "url": "assets/js/3.fa274cbf.js",
    "revision": "daa4fa3dbcff43553926ec9e0117302e"
  },
  {
    "url": "assets/js/4.9631845c.js",
    "revision": "a873768cb6e945576742133544827dc5"
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
    "url": "assets/js/7.8aa5162d.js",
    "revision": "4cd15eb542b9517d7e798ecbc4c49866"
  },
  {
    "url": "assets/js/8.60b99c60.js",
    "revision": "9b3bb5635a52c2b1af2edd9335a51a6f"
  },
  {
    "url": "assets/js/9.2682c50d.js",
    "revision": "b5ae2fefe8bed690061cceca608c4c34"
  },
  {
    "url": "assets/js/app.a735620a.js",
    "revision": "f2e3912b335820acc34acc00283bd24f"
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
    "revision": "42dd56652bfa88d5c732d13326b646b4"
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
