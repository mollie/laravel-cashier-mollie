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
    "revision": "e115272d1a0b2a0d4ed3ddb2ddb62236"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "34534828e06b25b43c9b98d80c93a8aa"
  },
  {
    "url": "03-trials.html",
    "revision": "75584b28dbc759e4db2fabd6e94b74b1"
  },
  {
    "url": "04-charges.html",
    "revision": "d091d846e00b091cc72766b07e3aae93"
  },
  {
    "url": "05-metered.html",
    "revision": "23292c04c997ffe592272141ee450fd9"
  },
  {
    "url": "06-customer.html",
    "revision": "79b9995220b76c03279ab5357459acd9"
  },
  {
    "url": "07-invoices.html",
    "revision": "0933506c28330dc4b597570c6698b532"
  },
  {
    "url": "08-refunds.html",
    "revision": "824abe8164e7bbe7f37e902643a333dd"
  },
  {
    "url": "09-events.html",
    "revision": "7770c5b70c476604fcaa38de7e6a4a26"
  },
  {
    "url": "10-webhook.html",
    "revision": "86e666bd5bb5a75398b802a9e136b94e"
  },
  {
    "url": "11-testing.html",
    "revision": "baac3252bac4fcb7adefb7076bd3176d"
  },
  {
    "url": "12-faq.html",
    "revision": "2695d30a572d02bfaf556de91c3bc6e1"
  },
  {
    "url": "13-upgrade.html",
    "revision": "2daeaaa2268a62101e103862a4716eaf"
  },
  {
    "url": "14-retry.html",
    "revision": "fd7f42075fb4675170084434af2a82ce"
  },
  {
    "url": "15-localization.html",
    "revision": "612bfbe518f3781515606dddb7179708"
  },
  {
    "url": "16-configuration.html",
    "revision": "a39c65889b057b4ff033bc03a1690afe"
  },
  {
    "url": "404.html",
    "revision": "169331ed568d9c3f31fe7afb8a3f08aa"
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
    "url": "assets/js/23.a80aaaaf.js",
    "revision": "d0a7362bbeb8f022c3b4db0f9b0197d0"
  },
  {
    "url": "assets/js/24.5564df5a.js",
    "revision": "92eac712f03488364c0ebe410f3266b4"
  },
  {
    "url": "assets/js/25.fce0db21.js",
    "revision": "f828a077a5a3e874aa0069a698f199b4"
  },
  {
    "url": "assets/js/26.8e47b26b.js",
    "revision": "32206b08cbc670bd00c08dc176a550c9"
  },
  {
    "url": "assets/js/27.09e2c9e4.js",
    "revision": "71dc276e47184d6591da126ddf42e6ed"
  },
  {
    "url": "assets/js/28.32777cd2.js",
    "revision": "00de99a6970ada41b1f8f48e5a79fc0b"
  },
  {
    "url": "assets/js/29.1e2c0676.js",
    "revision": "10d4b3c2c7d9719c144621ceb7c60e10"
  },
  {
    "url": "assets/js/3.b11f09c4.js",
    "revision": "fa4c6c059a57f37b2d2b9ab023a34091"
  },
  {
    "url": "assets/js/30.d6d98a47.js",
    "revision": "38f3317c36cae787d5ababadb8862841"
  },
  {
    "url": "assets/js/31.5daabc3a.js",
    "revision": "b6ef50cb91b5509b8d9388765eb6aeb0"
  },
  {
    "url": "assets/js/32.23fa1041.js",
    "revision": "e5711148325521a5383746440ee5c47b"
  },
  {
    "url": "assets/js/33.70daf56c.js",
    "revision": "eb8136cad63ffeec2e48d9013de930be"
  },
  {
    "url": "assets/js/34.60612968.js",
    "revision": "5ceadcb340d32f1926af4882af960098"
  },
  {
    "url": "assets/js/35.16f3d37b.js",
    "revision": "6991b2b595803c24e4ed74712b43966b"
  },
  {
    "url": "assets/js/36.d5cfe1fe.js",
    "revision": "269e1b65608a256b2fe87bdc14410c41"
  },
  {
    "url": "assets/js/37.e9d0b5f2.js",
    "revision": "5579f6757258dc0f2d0010fba4b1ce28"
  },
  {
    "url": "assets/js/38.d97231ff.js",
    "revision": "6abc74abf9a79ecf5cdcd09fe408106e"
  },
  {
    "url": "assets/js/39.1290114d.js",
    "revision": "fdc1c9b4866c7e49bddcb98fbbb60cca"
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
    "url": "assets/js/app.6114c989.js",
    "revision": "3a5473cde21125cc56749eaa2ba4c6b4"
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
    "revision": "c8cc81ad377d778d9dbda330398bcf25"
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
