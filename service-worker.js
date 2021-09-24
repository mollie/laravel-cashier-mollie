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
    "revision": "a2d4968d80f60a987082c29db2c59dc7"
  },
  {
    "url": "01-installation.html",
    "revision": "9d4b4dcfe1ce2eed6d3554d9db765fcd"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "a4e49fbae74bf924ed0bf472e48c8f91"
  },
  {
    "url": "03-trials.html",
    "revision": "0e54ef74bb22af89f90836c71cf8d878"
  },
  {
    "url": "04-charges.html",
    "revision": "91a11cf327953cce9d1683495b848eba"
  },
  {
    "url": "05-metered.html",
    "revision": "df102a8fe62a0bafd00a6d757d163e83"
  },
  {
    "url": "06-customer.html",
    "revision": "c712a9e4ccfee26c5d259c1e332f303c"
  },
  {
    "url": "07-invoices.html",
    "revision": "e276a8c817dddf70591357daf86f5807"
  },
  {
    "url": "08-refunds.html",
    "revision": "bea9be662c32bd35b84d642bd4608711"
  },
  {
    "url": "09-events.html",
    "revision": "f44334fa575dbfccabcb96ec322b5045"
  },
  {
    "url": "10-webhook.html",
    "revision": "257b3dba0dc5d53ab97b3251f75dc951"
  },
  {
    "url": "11-testing.html",
    "revision": "481c5c4688c3c0ddc0892e8ad97196ce"
  },
  {
    "url": "12-faq.html",
    "revision": "99c861436d74bdce0b3f10b2ba8c33dc"
  },
  {
    "url": "13-upgrade.html",
    "revision": "c934eaf2fcf865b5e2e9f6523d58c26b"
  },
  {
    "url": "14-retry.html",
    "revision": "f6299b354a84fe33b107317eb1ddded7"
  },
  {
    "url": "404.html",
    "revision": "a41367dbd973310c7fb96d99310d2fe2"
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
    "url": "assets/js/10.483417d5.js",
    "revision": "6505a407c430ec64b2f2d11207f524d0"
  },
  {
    "url": "assets/js/11.04a28e09.js",
    "revision": "038416706d3cdf6ed69181f38c69c9ef"
  },
  {
    "url": "assets/js/12.169b607d.js",
    "revision": "9031d712ab9e684c4d04f28ca2665dbd"
  },
  {
    "url": "assets/js/13.db74747c.js",
    "revision": "b72b0738427d99156a2c98e89603cbaf"
  },
  {
    "url": "assets/js/14.dc203d76.js",
    "revision": "eb7bcb0ed671468e5f73e9e0d605861b"
  },
  {
    "url": "assets/js/15.1c96c876.js",
    "revision": "1230719009fb17ec154f3331657667d7"
  },
  {
    "url": "assets/js/16.b5995f81.js",
    "revision": "94b14413d1fe2e50a9559dd2de9785c4"
  },
  {
    "url": "assets/js/17.5116b9e6.js",
    "revision": "1060ea1bcb10f5a1d897664dc45ce74b"
  },
  {
    "url": "assets/js/18.826287fc.js",
    "revision": "1fe42da1954799461e7be8bb40e798e9"
  },
  {
    "url": "assets/js/19.4d86ba95.js",
    "revision": "906f38438902a00e28aa4a76b3dc8b32"
  },
  {
    "url": "assets/js/2.97f321bb.js",
    "revision": "dcab558013827ad8d44e714fabfcdfc6"
  },
  {
    "url": "assets/js/20.ea560127.js",
    "revision": "40e8f26efaad1115b674fa08875e80ad"
  },
  {
    "url": "assets/js/21.c67ddad5.js",
    "revision": "3d9d3b033d51cda46c767fed6702fecc"
  },
  {
    "url": "assets/js/22.3b434446.js",
    "revision": "856a034db45a2b03012994ecd29f2408"
  },
  {
    "url": "assets/js/23.471b3834.js",
    "revision": "94f279ca498d5baaae63640c660b1272"
  },
  {
    "url": "assets/js/24.19e115b6.js",
    "revision": "6d6114782041eb3a595b8f50d6e4e212"
  },
  {
    "url": "assets/js/3.c56599a8.js",
    "revision": "cf9e20e49a20b4f9d5aa2878045db7c2"
  },
  {
    "url": "assets/js/4.2b5a4abe.js",
    "revision": "a6980ec8c988a5e6b98603fb54e31b23"
  },
  {
    "url": "assets/js/5.b0c981cd.js",
    "revision": "1e4f11cbc179fd5e36fa2d46bd30bea7"
  },
  {
    "url": "assets/js/6.e906b868.js",
    "revision": "f74bfdb5ef9762d236633b56f9f6c373"
  },
  {
    "url": "assets/js/7.618b6291.js",
    "revision": "f64b16fc8c140c1cfb60db9777600a52"
  },
  {
    "url": "assets/js/8.08d5f1a5.js",
    "revision": "59fcf1c2cc0118dd112c9fd6fbd14053"
  },
  {
    "url": "assets/js/9.3eb51084.js",
    "revision": "4306e7acf6620f10178c5ec6b11ed068"
  },
  {
    "url": "assets/js/app.0d2e30e2.js",
    "revision": "16fca1a924f5176974ea5d9edd530f32"
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
    "revision": "0fd8c01109b084653f9f090cc8aebe8e"
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
