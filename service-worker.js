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
    "revision": "da57db14983e4c9314c0b652090d0165"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "98ee6fb17fc104ee032e15f86ec275ee"
  },
  {
    "url": "03-trials.html",
    "revision": "d545c3408e81ae9a08c36cea8c1c90ed"
  },
  {
    "url": "04-charges.html",
    "revision": "afae48a62ef8399d7cb652b00ee2b222"
  },
  {
    "url": "05-metered.html",
    "revision": "2eb43ecd032d53164a9050bb6a79232f"
  },
  {
    "url": "06-customer.html",
    "revision": "fc9444d63c4187dcfcd4e8b5a4a704ee"
  },
  {
    "url": "07-invoices.html",
    "revision": "e8cb300c8aff4f89a7c39aa8470ae9a0"
  },
  {
    "url": "08-refunds.html",
    "revision": "9623ea8d3bcff59bdef3c5cf63e9e849"
  },
  {
    "url": "09-events.html",
    "revision": "7f7653c2044e531c6d903378c42b9db1"
  },
  {
    "url": "10-webhook.html",
    "revision": "3648b1034ff76a8c54fb6e21f5f22937"
  },
  {
    "url": "11-testing.html",
    "revision": "8fc2f44d4b71b762de5d57780d8faf47"
  },
  {
    "url": "12-faq.html",
    "revision": "de30be8bb9a2cdf88d5c4d18d20fe401"
  },
  {
    "url": "13-upgrade.html",
    "revision": "fd7bf0ea92270c4e76cc5539cd380600"
  },
  {
    "url": "14-retry.html",
    "revision": "c9f4be4d874caa94fa15a2343a4f0f47"
  },
  {
    "url": "15-localization.html",
    "revision": "1a4eb9800c4a428f84874abb9d694574"
  },
  {
    "url": "16-configuration.html",
    "revision": "501409a118cd3b2bb0cb132ccb1e541e"
  },
  {
    "url": "404.html",
    "revision": "69f4236efa4acaf01c277cc0f7d8f0b7"
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
    "url": "assets/js/24.8d246edb.js",
    "revision": "6d7153968aaee2aebcd689b9a77ba71d"
  },
  {
    "url": "assets/js/25.a6ba3d22.js",
    "revision": "becc5b858702a98827c81967f6cfe4a2"
  },
  {
    "url": "assets/js/26.cb82d887.js",
    "revision": "c647b62cea010843015147be6f7a3bef"
  },
  {
    "url": "assets/js/27.86329ce0.js",
    "revision": "567bb678f86dd4deb0123b90688c663a"
  },
  {
    "url": "assets/js/28.07e79d43.js",
    "revision": "8999f7407d6c4f7a1f0f3d160cc88d7b"
  },
  {
    "url": "assets/js/29.2e552cce.js",
    "revision": "f36e21d79447ffed9bd6de0245572a83"
  },
  {
    "url": "assets/js/3.b11f09c4.js",
    "revision": "fa4c6c059a57f37b2d2b9ab023a34091"
  },
  {
    "url": "assets/js/30.361376e2.js",
    "revision": "7a7a164b123537cde0ec4944ee937917"
  },
  {
    "url": "assets/js/31.90df113b.js",
    "revision": "91616d8d53d0201d490610c0662db4f9"
  },
  {
    "url": "assets/js/32.23fa1041.js",
    "revision": "e5711148325521a5383746440ee5c47b"
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
    "url": "assets/js/35.7a946bbe.js",
    "revision": "cb2c6aa57f511f38bbb973f4bdda1076"
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
    "url": "assets/js/app.fcf18d50.js",
    "revision": "d17f3eca9bcb819907e43d72850a343b"
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
    "revision": "751d6f2162e5e55187432bb63a635b45"
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
