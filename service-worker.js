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
    "revision": "c91a8f1354e72e8c22681f8634cfddfd"
  },
  {
    "url": "01-installation.html",
    "revision": "be7abfb785782a5a143be61f79a23626"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "ac0e1f4777f26eb437194888e5f7fb15"
  },
  {
    "url": "03-trials.html",
    "revision": "5d23e4f526d6626e2a4fe7bd26505a8b"
  },
  {
    "url": "04-charges.html",
    "revision": "d365d0fd606722c7095766df61e60200"
  },
  {
    "url": "05-metered.html",
    "revision": "e2ff40ab60717c8a29b2fa793ac64816"
  },
  {
    "url": "06-customer.html",
    "revision": "fcee0cb283cd5e44da0e102f96528dd0"
  },
  {
    "url": "07-invoices.html",
    "revision": "b86d642486e6bdcbb9fb741b785e60b0"
  },
  {
    "url": "08-refunds.html",
    "revision": "78f932f7e7d2124472d3db3af7353c0d"
  },
  {
    "url": "09-events.html",
    "revision": "2fdcb0ff1346c08f2b9450d1c437e077"
  },
  {
    "url": "10-webhook.html",
    "revision": "486d639bad0b6737858f0ab92c15d996"
  },
  {
    "url": "11-testing.html",
    "revision": "c81392a8ba2a36e16a19ef3a36c457fd"
  },
  {
    "url": "12-faq.html",
    "revision": "dc2cb871ef0d54e17e0fde1e40ecd61d"
  },
  {
    "url": "13-upgrade.html",
    "revision": "39a8a5ae97e4576e58b1850d459887cc"
  },
  {
    "url": "14-retry.html",
    "revision": "e9dbb587adb389081c90b6b2b1e9c4b0"
  },
  {
    "url": "404.html",
    "revision": "004dcee919dccab7050e648403ee8bb1"
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
    "url": "assets/js/app.5b7095e9.js",
    "revision": "5bcf3bf17d5c6d6d8eb59b0b88c580f2"
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
    "revision": "8255cbfb0f085eecf546f77345a6b5aa"
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
