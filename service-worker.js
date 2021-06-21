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
    "revision": "f8240a75789a212d5bcbf148a3772420"
  },
  {
    "url": "01-installation.html",
    "revision": "ca9a7ddcd7ef43deeacacffc0432ff40"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "6e9167264bf804834887108bf4972519"
  },
  {
    "url": "03-trials.html",
    "revision": "9b4dfb334dfe41cf7058fab9fdbdb187"
  },
  {
    "url": "04-charges.html",
    "revision": "b0ade37ab94a68b8fad112d8bb7667f6"
  },
  {
    "url": "05-metered.html",
    "revision": "cd0a1d490d1d9f541bf510e120a4836f"
  },
  {
    "url": "06-customer.html",
    "revision": "fb14811145705a7fb1c7e0737c123b82"
  },
  {
    "url": "07-invoices.html",
    "revision": "5f5232c104c6acf9e4067fc0eb7dda7a"
  },
  {
    "url": "08-events.html",
    "revision": "4ab47445f98bd632000cf843b33cf619"
  },
  {
    "url": "09-webhook.html",
    "revision": "c55832b9355c5b8692defd356ba5f606"
  },
  {
    "url": "10-testing.html",
    "revision": "599dd5fdfe4d096c6bc03aa0996cdb0c"
  },
  {
    "url": "11-faq.html",
    "revision": "2518292c07e9f6319aaeab32512a39e8"
  },
  {
    "url": "12-upgrade.html",
    "revision": "8a0b34620dac6da1cf72164c4a36add9"
  },
  {
    "url": "404.html",
    "revision": "038f4e6f49166f74fcac23017fc68234"
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
    "url": "assets/js/11.7ee16fb1.js",
    "revision": "22e6dc967a1fc9731a627562bc3e3c2f"
  },
  {
    "url": "assets/js/12.e0eec008.js",
    "revision": "302fc23dee79c4a8219743a3246e4b38"
  },
  {
    "url": "assets/js/13.10f65fdd.js",
    "revision": "ab50bb696106879023b1e8a8675ddef1"
  },
  {
    "url": "assets/js/14.93dd1c1b.js",
    "revision": "a177333bb281bcbdcf654219a3f79bad"
  },
  {
    "url": "assets/js/15.2e4ef96c.js",
    "revision": "3e25582f57c576b13c92f6bc9b853d0d"
  },
  {
    "url": "assets/js/16.adb928c5.js",
    "revision": "b39606ebaa0bfcb2e0504bb6193a7f36"
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
    "url": "assets/js/2.71747a20.js",
    "revision": "bdec522a5510810905db47d8694a8533"
  },
  {
    "url": "assets/js/20.6a183dcd.js",
    "revision": "ff9e5f2912781d37b0cd3816388d74a1"
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
    "url": "assets/js/9.4179b4e4.js",
    "revision": "ef02f77d9d12ea503e748a1c3d9a08f0"
  },
  {
    "url": "assets/js/app.361af10e.js",
    "revision": "0a68b0f08d7660347dc5b20b7d027d4c"
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
    "revision": "4545cdb03ad39bf1d0c9b52eba0e3e9a"
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
