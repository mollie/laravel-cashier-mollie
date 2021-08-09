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
    "revision": "2bc7e4e984af2fe8e911a57e80533ae2"
  },
  {
    "url": "01-installation.html",
    "revision": "7bb1b518084957ae6f2b3e783fe3eac3"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "377186f5ba24c624033786481ebeae7c"
  },
  {
    "url": "03-trials.html",
    "revision": "cdba03d815e220e808ef80da7d9537dd"
  },
  {
    "url": "04-charges.html",
    "revision": "558569941e39490f858847aca6825fa7"
  },
  {
    "url": "05-metered.html",
    "revision": "949d3f84f6b7f3e2bf62ad63f95c2088"
  },
  {
    "url": "06-customer.html",
    "revision": "b16d677e7b36738327e064b227290287"
  },
  {
    "url": "07-invoices.html",
    "revision": "808a9c37896ab27846c0739626b818ef"
  },
  {
    "url": "08-refunds.html",
    "revision": "b4cf220bc403342a39a53e923dbe87d5"
  },
  {
    "url": "09-events.html",
    "revision": "1d1c86c0a4d1de7fcbbe0f3033816a09"
  },
  {
    "url": "10-webhook.html",
    "revision": "c1ca082fd8c948e283aaa8d4c829ebd1"
  },
  {
    "url": "11-testing.html",
    "revision": "a0688e875dbc3c498f79da1bf405f761"
  },
  {
    "url": "12-faq.html",
    "revision": "aaf4445961e471a3562a44ef27fa8129"
  },
  {
    "url": "13-upgrade.html",
    "revision": "36b732a4dbed2c8803ec7a5a5b110dcd"
  },
  {
    "url": "404.html",
    "revision": "f31bbf61732e6579197c942927ca54e7"
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
    "url": "assets/js/10.c85fbd1e.js",
    "revision": "b4f3fafdb1d81abfce55ffdb2fef8709"
  },
  {
    "url": "assets/js/11.1c4d91cd.js",
    "revision": "c59d1854b54f258173733c42be85bac6"
  },
  {
    "url": "assets/js/12.85b5c740.js",
    "revision": "4cbfb9aa38ee76c3f6b8c0cba5612ad3"
  },
  {
    "url": "assets/js/13.52149f3a.js",
    "revision": "567ab9ba33f86c000f072e641c6905c7"
  },
  {
    "url": "assets/js/14.1cecacf6.js",
    "revision": "f4252cc5cc55c6930723410c315dc8e6"
  },
  {
    "url": "assets/js/15.d45b4dfb.js",
    "revision": "32e279c14a4e3af74ba93dba14ddded1"
  },
  {
    "url": "assets/js/16.a6933dac.js",
    "revision": "619a0eb18d19ea7db6bc026527cd83d4"
  },
  {
    "url": "assets/js/17.8985bcc9.js",
    "revision": "e7bb61b77bc678c2a7f3686737d7e115"
  },
  {
    "url": "assets/js/18.5c693b4d.js",
    "revision": "28a2052385b1fbc339a2e42a71bd8887"
  },
  {
    "url": "assets/js/19.d8a7312f.js",
    "revision": "a87fca7b5daae1810a858c0e847ab898"
  },
  {
    "url": "assets/js/2.99509112.js",
    "revision": "3c77e1f986ea5f033bdec4d1f2cd6b28"
  },
  {
    "url": "assets/js/20.d9e20501.js",
    "revision": "6a683a6738409fd7ae4eede1916ad66a"
  },
  {
    "url": "assets/js/21.6aaa2f1c.js",
    "revision": "d1694ce853365853edbbb551412a2fa7"
  },
  {
    "url": "assets/js/22.dd403f57.js",
    "revision": "884ca61d6a9c6578122a2fc95ce6cb61"
  },
  {
    "url": "assets/js/23.31801bca.js",
    "revision": "009509f89b41d2e640ebcf08ed8c5334"
  },
  {
    "url": "assets/js/3.479c439a.js",
    "revision": "bb5838a205640356bae48926a47adc89"
  },
  {
    "url": "assets/js/4.a4a1d908.js",
    "revision": "9fa757b321e6e8f5f2cb0684aea2c7fb"
  },
  {
    "url": "assets/js/5.1cd88ab0.js",
    "revision": "96ea102b2d1f7931188fd20b687dcb94"
  },
  {
    "url": "assets/js/6.f6279bce.js",
    "revision": "58e82b753f9395b06b77bf927b233c6d"
  },
  {
    "url": "assets/js/7.c21b93af.js",
    "revision": "be27570548f7dbce70e4cc58b9712c4a"
  },
  {
    "url": "assets/js/8.a8f690d8.js",
    "revision": "10802b152820bddaf1cbb00a12cc6366"
  },
  {
    "url": "assets/js/9.9f1e91c1.js",
    "revision": "267ff42b77a5fae23dd127af7151c65a"
  },
  {
    "url": "assets/js/app.f654994f.js",
    "revision": "4e6e6b4f6beb4c39cd32210c4e4bda05"
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
    "revision": "569deec2c2ea2f97c5bc606e3546e624"
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
