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
    "revision": "056cb08d362b2193ca9f9108cf53e975"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "d55ac7e26e64c4f63a3f2572dab0ece6"
  },
  {
    "url": "03-trials.html",
    "revision": "588fc0691ae95233d32538315b403cf3"
  },
  {
    "url": "04-charges.html",
    "revision": "2988f72e0842e280d77c6e69f9e355f3"
  },
  {
    "url": "05-metered.html",
    "revision": "1517f3c5f5c06c4fdf41dd7eb3a7dfcf"
  },
  {
    "url": "06-customer.html",
    "revision": "3c1431c694fcc00522f572966be6ce18"
  },
  {
    "url": "07-invoices.html",
    "revision": "1cca67bdb37f491b1d1cf2c63de6ae78"
  },
  {
    "url": "08-refunds.html",
    "revision": "de657661b5e3317b593fdb99420f158c"
  },
  {
    "url": "09-events.html",
    "revision": "04ae17fe3c43e9db6216c3c04f641388"
  },
  {
    "url": "10-webhook.html",
    "revision": "d5c2d165394bac64cf6b73e4706668fd"
  },
  {
    "url": "11-testing.html",
    "revision": "272cee5b1e27c7548cf8135f7626c78e"
  },
  {
    "url": "12-faq.html",
    "revision": "05af860167ae77f6677525d42383a58f"
  },
  {
    "url": "13-upgrade.html",
    "revision": "5fadf50ea62a9b4e9fe1643c6a2de0c2"
  },
  {
    "url": "14-retry.html",
    "revision": "b7f97b7198d41dff74a18b45cfeedd32"
  },
  {
    "url": "15-localization.html",
    "revision": "d6faae94aa7eb30f14bf668ac58b8848"
  },
  {
    "url": "16-configuration.html",
    "revision": "d791f4823a5bf7b73b70ff3ccf22a234"
  },
  {
    "url": "404.html",
    "revision": "4ffd08a06f09c98c4558bd90f2e2b19a"
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
    "url": "assets/css/0.styles.9f506865.css",
    "revision": "21c16233e71f280df3f6fc760fab2a43"
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
    "url": "assets/js/10.b9ca5af9.js",
    "revision": "67d14c2b5a05151dd619a1569e473ba5"
  },
  {
    "url": "assets/js/11.3b41cf32.js",
    "revision": "85992250cd8c8017f0baf7f79764b71f"
  },
  {
    "url": "assets/js/12.7095e60c.js",
    "revision": "4789920957294b44cfd384709827baf6"
  },
  {
    "url": "assets/js/13.6165fdbf.js",
    "revision": "555fe1fa21daab734bd7d94e342fdbed"
  },
  {
    "url": "assets/js/14.6b75a6bc.js",
    "revision": "1f886cce8f6467b4968912c121196692"
  },
  {
    "url": "assets/js/15.23899618.js",
    "revision": "a5eb2500bb8e8249a6792fe389ff9eeb"
  },
  {
    "url": "assets/js/16.bfc8cde9.js",
    "revision": "0d9a49d6e93a4c37660d1e5e8b42ec19"
  },
  {
    "url": "assets/js/17.9d22f6c2.js",
    "revision": "78d6af3e60293d3ac1719e49cbfaeeca"
  },
  {
    "url": "assets/js/18.2eb6bd77.js",
    "revision": "b1d7ffd206dd0c9afcc7f753a80f06f7"
  },
  {
    "url": "assets/js/19.ddc34cfe.js",
    "revision": "8c753c1443f2ac8c89e5544fe8dc0065"
  },
  {
    "url": "assets/js/2.f39e6992.js",
    "revision": "30175955d754a5d6d44b591d27cd978e"
  },
  {
    "url": "assets/js/20.6f52643a.js",
    "revision": "86a40107d5cd3a4c473085a3a3cecc73"
  },
  {
    "url": "assets/js/21.90b92104.js",
    "revision": "1756f966206260a0e415c0844c51a9aa"
  },
  {
    "url": "assets/js/22.950cf0e8.js",
    "revision": "98c5a594aa0b8819196c5d5fe2695465"
  },
  {
    "url": "assets/js/23.3109801d.js",
    "revision": "5adbe2c3ddaf152bc76be191832dd089"
  },
  {
    "url": "assets/js/24.e51f476b.js",
    "revision": "4beee31d2f87b6cc1ab1c5f498cda316"
  },
  {
    "url": "assets/js/25.ab3e10e4.js",
    "revision": "c173bcb3aa31bf27403520379498c0b1"
  },
  {
    "url": "assets/js/3.12889470.js",
    "revision": "f00cd76fafc2c90509e164821379231f"
  },
  {
    "url": "assets/js/4.004c63b6.js",
    "revision": "53b1f0e33162bd6417232e26453378c8"
  },
  {
    "url": "assets/js/5.c2e0fb4d.js",
    "revision": "0ba7bce76cd717ae4927ac8704c55383"
  },
  {
    "url": "assets/js/6.e9754ac6.js",
    "revision": "af0337aa358f957874d4c0a89dacf110"
  },
  {
    "url": "assets/js/7.e7e6374e.js",
    "revision": "76a735511ce8c6f5b42ffb4a9e9b8a32"
  },
  {
    "url": "assets/js/8.cf96bfee.js",
    "revision": "f31baeae3f6066ec0bf55e422bf6a9f2"
  },
  {
    "url": "assets/js/9.187f617c.js",
    "revision": "aa71167de93958a1f845f6e6138cf56d"
  },
  {
    "url": "assets/js/app.92d58846.js",
    "revision": "23fd3f9e6dedb5231217796ce18e12a5"
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
    "revision": "aa0e8e979ea6c7cbd11ee23b1196395e"
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
