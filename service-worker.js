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
    "revision": "eb5f1d2991b056a614a63d34a938158d"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "4f4e2d50d34f0bf0b2c83106fea7a294"
  },
  {
    "url": "03-trials.html",
    "revision": "ea338ac75bb6bca483f7e124a57c453a"
  },
  {
    "url": "04-charges.html",
    "revision": "5fa353da66d14e5ba83d20889c60a037"
  },
  {
    "url": "05-metered.html",
    "revision": "837295a67adcd25b56baf9e09949bbeb"
  },
  {
    "url": "06-customer.html",
    "revision": "49b6a419baa0d5c6c9a0e29d289cb904"
  },
  {
    "url": "07-invoices.html",
    "revision": "b8d7448c35ff6eadf7c75828b71b4b83"
  },
  {
    "url": "08-refunds.html",
    "revision": "c234d0b7859454db4e0d45ab6943d985"
  },
  {
    "url": "09-events.html",
    "revision": "b57fac52e0ecf871d883fdc63acb47d7"
  },
  {
    "url": "10-webhook.html",
    "revision": "f0ca1f2602da04b6192060084eff77b3"
  },
  {
    "url": "11-testing.html",
    "revision": "c80869d346d59cf3a2bf7e8e6743c02b"
  },
  {
    "url": "12-faq.html",
    "revision": "2f5a513cf3d62e8234f4e7d901572de6"
  },
  {
    "url": "13-upgrade.html",
    "revision": "5eaadbbba2075e611cb3297df3eea3ba"
  },
  {
    "url": "14-retry.html",
    "revision": "5cfbc7587bb4c9666c0b6766d80ee8bc"
  },
  {
    "url": "15-localization.html",
    "revision": "d760690789260ff5aeb56622bf6925db"
  },
  {
    "url": "16-configuration.html",
    "revision": "6a02a635e21a819c2baa1c1f278f3b68"
  },
  {
    "url": "404.html",
    "revision": "a0e14d962ce7690153902fe20da6035d"
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
    "url": "assets/css/0.styles.55b3e847.css",
    "revision": "0dc105425b8aff6d848214e9acbd76cb"
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
    "url": "assets/js/10.983d3872.js",
    "revision": "d4b9109d94c85d87ec0405ebcd2c43cc"
  },
  {
    "url": "assets/js/11.aa713818.js",
    "revision": "63aabea8f1fd12691657be718ad9840c"
  },
  {
    "url": "assets/js/12.186b776b.js",
    "revision": "c55cca7738262faf9c25c83296a818f0"
  },
  {
    "url": "assets/js/13.2b2134fd.js",
    "revision": "54c397c0de1d8dee43f2c625af59f64b"
  },
  {
    "url": "assets/js/14.24afae99.js",
    "revision": "5e1687f274cf91198501b3c5d5e9926c"
  },
  {
    "url": "assets/js/15.afa83413.js",
    "revision": "dd53f5e0a1d811b5a5e950e5cfafd631"
  },
  {
    "url": "assets/js/16.616ac1de.js",
    "revision": "fccfccce42daf647893947b350ddc5b8"
  },
  {
    "url": "assets/js/17.90e8fbe9.js",
    "revision": "7dec2d23cae7a7a641bf419d0e494c51"
  },
  {
    "url": "assets/js/18.f843dd05.js",
    "revision": "f4890a9c5a06d18c05fb042fb55a226d"
  },
  {
    "url": "assets/js/19.8b268723.js",
    "revision": "c596c8beca50c012d3b7cde7c781db47"
  },
  {
    "url": "assets/js/2.676c7395.js",
    "revision": "61ac83bed59d894594fd4ae767a74b9b"
  },
  {
    "url": "assets/js/20.23eb098f.js",
    "revision": "6b67d5e12ca113a739c2a9a6af3c7637"
  },
  {
    "url": "assets/js/21.6bf99894.js",
    "revision": "e61c55990d632711789282a8e7833452"
  },
  {
    "url": "assets/js/22.3ad5b201.js",
    "revision": "183ac5b7b238d889271ba465ff71f493"
  },
  {
    "url": "assets/js/23.f34bccbc.js",
    "revision": "e23c30fffef4cb938c451b1fe2b6cd7a"
  },
  {
    "url": "assets/js/24.5157772f.js",
    "revision": "6d8834d9c2117b558e668ecc42cdf005"
  },
  {
    "url": "assets/js/25.d4ae5cb2.js",
    "revision": "e88b913d2a168ef7caf67dace1008edf"
  },
  {
    "url": "assets/js/3.341cd230.js",
    "revision": "3708a8c71b3055459fb97cdd8772788a"
  },
  {
    "url": "assets/js/4.962f095d.js",
    "revision": "77e3c8b1e1961e7c106f1a46a469cbb6"
  },
  {
    "url": "assets/js/5.37372cb8.js",
    "revision": "4f792f9dbbb25bddc2dfcf2e1511696f"
  },
  {
    "url": "assets/js/6.6786ae0f.js",
    "revision": "d90384fd1fe9bfd3fa101caaa3c2809f"
  },
  {
    "url": "assets/js/7.d2c208b4.js",
    "revision": "6d0260a8716df10418e0fed9f6560a1f"
  },
  {
    "url": "assets/js/8.4d9a6b3b.js",
    "revision": "a04db9c69831e368f57ecec4a72d92d6"
  },
  {
    "url": "assets/js/9.6779aa27.js",
    "revision": "a1248f4b3669f58282afe3630b4480a0"
  },
  {
    "url": "assets/js/app.530bd18a.js",
    "revision": "5bc44bdc8e78b598d38c178eff870996"
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
    "revision": "6e7c0d0bce2fbacb832fcfff5cf87fb6"
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
