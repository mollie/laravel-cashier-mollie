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
    "revision": "01453925cf1306ee8e2271f4b26a00a6"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "d2a6123ed1b74468580b348a19d1663e"
  },
  {
    "url": "03-trials.html",
    "revision": "6a8d16906b718ff2be7c15b80012c11d"
  },
  {
    "url": "04-charges.html",
    "revision": "53a020e96ea27ee2cea37219f0abb9a1"
  },
  {
    "url": "05-metered.html",
    "revision": "73c82b06f952d23b1e9a7418a639dd7b"
  },
  {
    "url": "06-customer.html",
    "revision": "9aaec05154f06edfbf9fc7d1f18cbf42"
  },
  {
    "url": "07-invoices.html",
    "revision": "c108143eaccd70a7dbbd9bf2f217fcba"
  },
  {
    "url": "08-refunds.html",
    "revision": "cd1d4773398a78377f5492517eb2772b"
  },
  {
    "url": "09-events.html",
    "revision": "d8f1a4c98713d9f263448f3110568104"
  },
  {
    "url": "10-webhook.html",
    "revision": "a1d9a860e8cc6ce2f2d6bf2bb381b2fa"
  },
  {
    "url": "11-testing.html",
    "revision": "a97133a2c63fce9a249c4d28a330b208"
  },
  {
    "url": "12-faq.html",
    "revision": "e1b9834bde2a783d8af1c3514760dfa2"
  },
  {
    "url": "13-upgrade.html",
    "revision": "6f2721bc5405b91a4701f6c860734407"
  },
  {
    "url": "14-retry.html",
    "revision": "c4c7d3d0d9a647b257027ea31ec6e453"
  },
  {
    "url": "15-localization.html",
    "revision": "5adac3f24fe6259d6e92b32cec3f7fe1"
  },
  {
    "url": "16-configuration.html",
    "revision": "fcbf38f68e8ef90ff3fc7776b50bc252"
  },
  {
    "url": "404.html",
    "revision": "387a3af7ef73a2077eb9ba65944d0aa5"
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
    "url": "assets/js/10.cc0d10ec.js",
    "revision": "5278e4e57daf8f9d298c6e7aefc13b01"
  },
  {
    "url": "assets/js/11.21c46678.js",
    "revision": "c6ea78d6ecc1fe44f8c266bdd7837eee"
  },
  {
    "url": "assets/js/12.04982678.js",
    "revision": "778d52ab21929a3b723f3ae4f7f75330"
  },
  {
    "url": "assets/js/13.40c19714.js",
    "revision": "ce55a019ae07e68b9354986b22da483a"
  },
  {
    "url": "assets/js/14.cbb36970.js",
    "revision": "518432dac4bb252e01d36adef0709e45"
  },
  {
    "url": "assets/js/15.659cac24.js",
    "revision": "682e9694369ad0cb210131c916d1501d"
  },
  {
    "url": "assets/js/16.f0760b69.js",
    "revision": "e595f20b2146c3514f6cbcfb459aaaa4"
  },
  {
    "url": "assets/js/17.6c004d50.js",
    "revision": "847aec78119027205d5d31a228eb0c7a"
  },
  {
    "url": "assets/js/18.484e1911.js",
    "revision": "944811d5180dabdf6c742eee8822802f"
  },
  {
    "url": "assets/js/19.9f5c0a19.js",
    "revision": "c860f11e6b5e6cef8d80a70f4cfc3239"
  },
  {
    "url": "assets/js/2.4b5cd536.js",
    "revision": "be5246a7630b9e6bbdc161db28f4df92"
  },
  {
    "url": "assets/js/20.ff981d6e.js",
    "revision": "817d843159402f2cd81cc4ebdb8c3224"
  },
  {
    "url": "assets/js/21.29b8599d.js",
    "revision": "7f04858e65133f769d4ef4b2f02da0cd"
  },
  {
    "url": "assets/js/22.697ac29f.js",
    "revision": "1cfee5588498ecd30fe2765a28d826ab"
  },
  {
    "url": "assets/js/23.79add072.js",
    "revision": "8162fe8122a3b8bf9f997b8dad20b34d"
  },
  {
    "url": "assets/js/24.ff61ea41.js",
    "revision": "227be1867f9a1070bca3028cdfab1976"
  },
  {
    "url": "assets/js/25.3c3d4bce.js",
    "revision": "b7706ba60a7e24a0b81cb7e15c5f8aed"
  },
  {
    "url": "assets/js/3.ea8bd253.js",
    "revision": "9cc0da53a730fddbc0e52c5098d45a66"
  },
  {
    "url": "assets/js/4.b4959cfc.js",
    "revision": "c0500db6573d4d89976e785df21687af"
  },
  {
    "url": "assets/js/5.eefa5c0f.js",
    "revision": "86b7994ed41dd70a850e8ce92dd71a1c"
  },
  {
    "url": "assets/js/6.68f2172a.js",
    "revision": "12cd29e698f98a0f4ff1bf98244e6b99"
  },
  {
    "url": "assets/js/7.2c91c857.js",
    "revision": "1c8bada4e552d0375b01f5e9d8e463b4"
  },
  {
    "url": "assets/js/8.6eee3747.js",
    "revision": "583f03de769c83677d077e89c6f40e1e"
  },
  {
    "url": "assets/js/9.ec6f0dc1.js",
    "revision": "8ef207422ad18b1b11b8c43d2ed0d3aa"
  },
  {
    "url": "assets/js/app.9473e60d.js",
    "revision": "91e3f74773b3478842bb045ef2f76093"
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
    "revision": "e7eba4d583ec61f7d35c494acb97a64d"
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
