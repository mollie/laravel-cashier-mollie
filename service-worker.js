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
    "revision": "d7ce946c519bcc3e0e68458264ef8d89"
  },
  {
    "url": "01-installation.html",
    "revision": "84e426bd0b7278051652e514e2e9a83c"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "78fe8a30a7978aa8df949d7045a67f65"
  },
  {
    "url": "03-trials.html",
    "revision": "f60b59412d56580c824a4da2f3a5ace2"
  },
  {
    "url": "04-charges.html",
    "revision": "90bb081b531b50a11a4085f23375b46e"
  },
  {
    "url": "05-metered.html",
    "revision": "f55955dc881c1a7cc78107eb1cede920"
  },
  {
    "url": "06-customer.html",
    "revision": "3a28c37ec98dcbae15753b8b110ffe66"
  },
  {
    "url": "07-invoices.html",
    "revision": "47152571e6df246620233a4e14a258db"
  },
  {
    "url": "08-events.html",
    "revision": "59a3e594d45a7eb395d377bedc1a12c1"
  },
  {
    "url": "09-webhook.html",
    "revision": "9be63b002ab5c45f631dc3f437345cdc"
  },
  {
    "url": "10-testing.html",
    "revision": "47b7dac4f62f5a61e9f8c9d7553a9a7a"
  },
  {
    "url": "11-faq.html",
    "revision": "7ed43a9130d91855c056fbaa56a7d76f"
  },
  {
    "url": "12-upgrade.html",
    "revision": "7f5b5bc60057bd8f6ec5dbcab6eb31ee"
  },
  {
    "url": "404.html",
    "revision": "8aa8cc18d48964a38256e591ff938a51"
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
    "url": "assets/js/10.5a6f9cdd.js",
    "revision": "d9768efe36a30f5813b88a6e7adbf105"
  },
  {
    "url": "assets/js/11.52d87ccc.js",
    "revision": "96b9d779ef62563341f36e0c5a9303ff"
  },
  {
    "url": "assets/js/12.c0536f74.js",
    "revision": "d0548da7fcd33915194b75a834e7d458"
  },
  {
    "url": "assets/js/13.ad87a78a.js",
    "revision": "6467c351aba7fa1489fe075d0315c781"
  },
  {
    "url": "assets/js/14.457604d0.js",
    "revision": "566836eb550160a3c4fd733540c73184"
  },
  {
    "url": "assets/js/15.2d00ac2b.js",
    "revision": "4bd315fd1570b246d7f1a7418c3a29ee"
  },
  {
    "url": "assets/js/16.704f3e6d.js",
    "revision": "8e07b1bc86150556f152d528858d13f1"
  },
  {
    "url": "assets/js/17.ead6c08d.js",
    "revision": "613bca080a3b5aca72b4f78a4991110c"
  },
  {
    "url": "assets/js/18.6abaf1fe.js",
    "revision": "25ea220bf253ff3a50a9c86ea24c6c85"
  },
  {
    "url": "assets/js/19.662dd142.js",
    "revision": "5b7265fd6e4ac33c48f58ae903043d49"
  },
  {
    "url": "assets/js/2.6c1fba75.js",
    "revision": "d9a6de867ac9d0affe1e0c1bd682d8be"
  },
  {
    "url": "assets/js/20.6ce90479.js",
    "revision": "3644841b3a89cc4050293b46db061684"
  },
  {
    "url": "assets/js/21.8419b434.js",
    "revision": "43744ac8cbc429d841ffd1c7decb1623"
  },
  {
    "url": "assets/js/22.b9f6c026.js",
    "revision": "bbe73036f1f62a74f6a649f2a5c4d47e"
  },
  {
    "url": "assets/js/3.446f0839.js",
    "revision": "93ab226d8a7f40cc63ec1508df074a98"
  },
  {
    "url": "assets/js/4.2fdb705e.js",
    "revision": "150f44337a771e1e3568d2e815315f2f"
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
    "url": "assets/js/7.2df0f702.js",
    "revision": "6ac5f98b92bf8c0839ef567c581b5551"
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
    "url": "assets/js/app.628643d1.js",
    "revision": "2710d5bfb72847ffaa000bd1b642a416"
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
    "revision": "e1ddc65bcdf265e77c40930af4445def"
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
