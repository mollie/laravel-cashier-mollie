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
    "revision": "38fb5ffa52192f78bc4e54dcef1baac9"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "47c6d2b7d0d39160746219c75718334d"
  },
  {
    "url": "03-trials.html",
    "revision": "efe5f12ea5af0f6a8f33369996fdf555"
  },
  {
    "url": "04-charges.html",
    "revision": "abca9b40b5e1285d13cdb410b404bb5e"
  },
  {
    "url": "05-metered.html",
    "revision": "432c21be21849c19681407fb035421a2"
  },
  {
    "url": "06-customer.html",
    "revision": "946de10b2133f7c60df4d4231a590476"
  },
  {
    "url": "07-invoices.html",
    "revision": "a18fd1feed33db501e62ce9d5f452822"
  },
  {
    "url": "08-refunds.html",
    "revision": "4af931aff89fad9fc69ea27b7e16e9b1"
  },
  {
    "url": "09-events.html",
    "revision": "8be23990544e3ad177bb255e9afb4c31"
  },
  {
    "url": "10-webhook.html",
    "revision": "cb3b3f1669de857ea0c44620c5440a7d"
  },
  {
    "url": "11-testing.html",
    "revision": "41501566f83945ea9ab98acecbd650c8"
  },
  {
    "url": "12-faq.html",
    "revision": "53fa598abecd9defe5cc84cd4fc1f9a6"
  },
  {
    "url": "13-upgrade.html",
    "revision": "10a02b784b68b7025d0a67e7323afe26"
  },
  {
    "url": "14-retry.html",
    "revision": "8a9edab8d552ff041a0af1f7c4c50b9f"
  },
  {
    "url": "15-localization.html",
    "revision": "3bacaa27989c141173efa6fccaa47594"
  },
  {
    "url": "16-configuration.html",
    "revision": "40a97202f04b45c53d6d1bc34b694153"
  },
  {
    "url": "404.html",
    "revision": "e1b4c664193729202d8b753cdee3b688"
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
    "url": "assets/js/1.59beea3b.js",
    "revision": "002d766fd13807b7295c57aebde3b9d8"
  },
  {
    "url": "assets/js/10.6b754ae1.js",
    "revision": "62505f16269f91edf082adc61f8c5e97"
  },
  {
    "url": "assets/js/11.d9989b9f.js",
    "revision": "ca6714b329f56a5edbbe03bd96298169"
  },
  {
    "url": "assets/js/12.b1fabc43.js",
    "revision": "70075e88ec78504a8c35abe306941cc8"
  },
  {
    "url": "assets/js/13.1cb5ee61.js",
    "revision": "6c59fc597f7a231aef70ecbfc7de2425"
  },
  {
    "url": "assets/js/14.fe7a12cb.js",
    "revision": "40fc4654a1d419a661923605f3c9226c"
  },
  {
    "url": "assets/js/15.54cfc835.js",
    "revision": "7c1f51c75e2106597bc706ce1b5c99be"
  },
  {
    "url": "assets/js/16.73fba09f.js",
    "revision": "8dd5523775cde7514f8ec6893f45168f"
  },
  {
    "url": "assets/js/17.919259a1.js",
    "revision": "4dd9a6646bfdecc65134817c0ae0c26e"
  },
  {
    "url": "assets/js/18.dbf08a99.js",
    "revision": "8d0d25194b9f6ce0954dfa28086c7d0a"
  },
  {
    "url": "assets/js/19.ab717a18.js",
    "revision": "78c485c08c94abd8b1c05d159e6df521"
  },
  {
    "url": "assets/js/2.38511147.js",
    "revision": "8216a6452046e888e2e7c9e02e21d60e"
  },
  {
    "url": "assets/js/20.69ba80bc.js",
    "revision": "fd5300cf1d7e09306e369e80e934e40b"
  },
  {
    "url": "assets/js/21.d9e3d872.js",
    "revision": "568791f9af3ccee4ea3a0e6a152fabe2"
  },
  {
    "url": "assets/js/22.3d79b29e.js",
    "revision": "a9be0563b791d28b92492cbfba524873"
  },
  {
    "url": "assets/js/23.74238d94.js",
    "revision": "4ae6cdcc3ff7e676f75da3dd755a4e84"
  },
  {
    "url": "assets/js/24.5564df5a.js",
    "revision": "92eac712f03488364c0ebe410f3266b4"
  },
  {
    "url": "assets/js/25.c23a5a76.js",
    "revision": "c9747a8fc11e62724d7ae18ffc517481"
  },
  {
    "url": "assets/js/26.9935303a.js",
    "revision": "2778d52583dfc8ca9884f9842aea28fb"
  },
  {
    "url": "assets/js/27.92665c9f.js",
    "revision": "9f011f1c538fca1eac23e74f46b98c64"
  },
  {
    "url": "assets/js/28.cb4145aa.js",
    "revision": "344a0bce1e3147e6b28ab22f1ecc6d44"
  },
  {
    "url": "assets/js/29.6d54f8e6.js",
    "revision": "4a970220328e02898d29ec6123acdf41"
  },
  {
    "url": "assets/js/3.b11f09c4.js",
    "revision": "fa4c6c059a57f37b2d2b9ab023a34091"
  },
  {
    "url": "assets/js/30.e2e7dd28.js",
    "revision": "0a6ed02f7d51e0b460c708246179271a"
  },
  {
    "url": "assets/js/31.bd331708.js",
    "revision": "b0bc6e414afef5f189418ea591ed874e"
  },
  {
    "url": "assets/js/32.d5de8d06.js",
    "revision": "818ea89e33fbfcb701a5881d63efbbc7"
  },
  {
    "url": "assets/js/33.4169f70b.js",
    "revision": "7865acdf6503e0225d2cf87ed33886e6"
  },
  {
    "url": "assets/js/34.75e8e6aa.js",
    "revision": "5cb9c81733a9c94ca75df35d3f12f2a0"
  },
  {
    "url": "assets/js/35.293feb40.js",
    "revision": "d3fee9891c11925d7c2d07e6ec767e8b"
  },
  {
    "url": "assets/js/36.2d44eaad.js",
    "revision": "fa0bb5244de4d4f8ae79a78b0d8498f5"
  },
  {
    "url": "assets/js/37.88ef5c50.js",
    "revision": "279e5e645bed0330eea449387f9d1917"
  },
  {
    "url": "assets/js/38.9d5e7a05.js",
    "revision": "860aaa7ce5b521c66ddbd995a6f01426"
  },
  {
    "url": "assets/js/39.3d1f895b.js",
    "revision": "291217b213df5d15cb45293e33e7c82e"
  },
  {
    "url": "assets/js/4.b16263cd.js",
    "revision": "954076a7dc180ac488edf7d046af8822"
  },
  {
    "url": "assets/js/40.5c0a2311.js",
    "revision": "def0eac529d0f016a27baf49ad9eca31"
  },
  {
    "url": "assets/js/5.280719b2.js",
    "revision": "1fd46ae8fc153e65f1b1ddebaf14a435"
  },
  {
    "url": "assets/js/6.b85cedd4.js",
    "revision": "40d1f76ba8da15400db16428ab2e2266"
  },
  {
    "url": "assets/js/7.b1184ded.js",
    "revision": "1845424c0a1f91db461a3565b5f214cd"
  },
  {
    "url": "assets/js/app.d55c6a2d.js",
    "revision": "9250bf6c2650d2c5bfcbcc8a21720a0f"
  },
  {
    "url": "assets/js/vendors~docsearch.38ba84f9.js",
    "revision": "e4157406331de5fb593dcbd94fca7647"
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
    "revision": "79a7148ade7313a19e74cfa82486fea8"
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
